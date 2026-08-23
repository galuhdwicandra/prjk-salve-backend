<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CashTransactions\CashTransactionStoreRequest;
use App\Http\Requests\CashTransactions\CashTransferStoreRequest;
use App\Models\Branch;
use App\Models\CashTransaction;
use App\Models\TransactionCategory;
use App\Services\Accounting\AccountingPostingService;
use App\Services\DocumentNumberService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class CashTransactionController extends Controller
{
    private const RELATIONS = [
        'branch:id,name,code',
        'cashAccount:id,code,name',
        'toAccount:id,code,name',
        'contact:id,code,name,phone',
        'lines.category:id,name',
    ];

    public function __construct(
        private DocumentNumberService $numbers,
        private AccountingPostingService $posting,
    ) {}

    public function index(Request $request)
    {
        $this->authorize('viewAny', CashTransaction::class);

        $user = $request->user();
        $kind = (string) $request->query('kind', 'IN');

        $query = CashTransaction::query()
            ->with(self::RELATIONS)
            ->orderByDesc('trx_date')
            ->orderByDesc('created_at');

        if ($kind === 'OUT') {
            $query->where(function ($q) {
                $q->where('kind', 'OUT')
                    ->orWhere(fn ($sub) => $sub->where('kind', 'TRANSFER')->where('fee_amount', '>', 0));
            });
        } else {
            $query->where('kind', $kind === 'TRANSFER' ? 'TRANSFER' : 'IN');
        }

        $branchIds = $user->branchScopeIds($request->query('branch_id'));

        if ($branchIds !== null) {
            $query->whereIn('branch_id', $branchIds);
        }

        if ($from = $request->query('date_from')) {
            $query->whereDate('trx_date', '>=', $from);
        }

        if ($to = $request->query('date_to')) {
            $query->whereDate('trx_date', '<=', $to);
        }

        if ($search = trim((string) $request->query('q', ''))) {
            $query->where(function ($q) use ($search) {
                $q->where('no', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhereHas('contact', fn ($sub) => $sub->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('cashAccount', fn ($sub) => $sub->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('lines.category', fn ($sub) => $sub->where('name', 'like', "%{$search}%"));
            });
        }

        $items = $query->paginate(min((int) $request->query('per_page', 25), 100));

        return $this->ok($items->items(), [
            'current_page' => $items->currentPage(),
            'per_page' => $items->perPage(),
            'total' => $items->total(),
            'last_page' => $items->lastPage(),
        ]);
    }

    public function show(CashTransaction $cashTransaction)
    {
        $this->authorize('view', $cashTransaction);

        return $this->ok($cashTransaction->load(self::RELATIONS));
    }

    public function store(CashTransactionStoreRequest $request)
    {
        $this->authorize('create', CashTransaction::class);

        $payload = $request->validated();
        $branch = $this->resolveBranch($request, $payload['branch_id'] ?? null);
        $docKey = $payload['kind'] === 'IN' ? DocumentNumberService::CASH_IN : DocumentNumberService::CASH_OUT;

        $transaction = DB::transaction(function () use ($request, $payload, $branch, $docKey) {
            $transaction = CashTransaction::create([
                'branch_id' => $branch->id,
                'kind' => $payload['kind'],
                'no' => $this->numbers->next($branch, $docKey),
                'trx_date' => $payload['trx_date'],
                'cash_account_id' => $payload['cash_account_id'],
                'contact_id' => $payload['contact_id'] ?? null,
                'amount' => $this->sumLines($payload['lines']),
                'description' => $payload['description'] ?? null,
                'attachment_path' => $this->storeAttachment($request),
                'created_by' => $request->user()?->id,
            ]);

            $this->syncLines($transaction, $payload['lines']);
            $this->posting->postCashTransaction($transaction->fresh('lines'), $request->user());

            return $transaction;
        });

        return $this->ok($transaction->load(self::RELATIONS), [], 'Created', 201);
    }

    public function update(CashTransactionStoreRequest $request, CashTransaction $cashTransaction)
    {
        $this->authorize('update', $cashTransaction);

        if ($cashTransaction->kind === 'TRANSFER') {
            throw ValidationException::withMessages([
                'kind' => ['Pindah dana tidak bisa diedit. Hapus lalu buat ulang.'],
            ]);
        }

        $payload = $request->validated();

        DB::transaction(function () use ($request, $payload, $cashTransaction) {
            $attachment = $this->storeAttachment($request);

            if ($attachment && $cashTransaction->attachment_path) {
                $this->deleteAttachment($cashTransaction->attachment_path);
            }

            $cashTransaction->fill([
                'trx_date' => $payload['trx_date'],
                'cash_account_id' => $payload['cash_account_id'],
                'contact_id' => $payload['contact_id'] ?? null,
                'amount' => $this->sumLines($payload['lines']),
                'description' => $payload['description'] ?? null,
                'attachment_path' => $attachment ?: $cashTransaction->attachment_path,
            ])->save();

            $cashTransaction->lines()->delete();
            $this->syncLines($cashTransaction, $payload['lines']);
            $this->posting->postCashTransaction($cashTransaction->fresh('lines'), $request->user());
        });

        return $this->ok($cashTransaction->fresh()->load(self::RELATIONS), [], 'Updated');
    }

    public function transfer(CashTransferStoreRequest $request)
    {
        $this->authorize('create', CashTransaction::class);

        $payload = $request->validated();
        $branch = $this->resolveBranch($request, $payload['branch_id'] ?? null);
        $fee = round((float) ($payload['fee_amount'] ?? 0), 2);

        $transaction = DB::transaction(function () use ($request, $payload, $branch, $fee) {
            $transaction = CashTransaction::create([
                'branch_id' => $branch->id,
                'kind' => 'TRANSFER',
                'no' => $this->numbers->next($branch, DocumentNumberService::TRANSFER),
                'trx_date' => $payload['trx_date'],
                'cash_account_id' => $payload['from_account_id'],
                'to_account_id' => $payload['to_account_id'],
                'amount' => $payload['amount'],
                'fee_amount' => $fee,
                'fee_bearer' => $fee > 0 ? ($payload['fee_bearer'] ?? 'SENDER') : null,
                'description' => $payload['description'] ?? null,
                'created_by' => $request->user()?->id,
            ]);

            if ($fee > 0) {
                $transaction->lines()->create([
                    'transaction_category_id' => $this->feeCategoryId($payload['fee_category_id'] ?? null),
                    'description' => 'Biaya admin transfer',
                    'amount' => $fee,
                    'line_order' => 1,
                ]);
            }

            $this->posting->postCashTransaction($transaction->fresh('lines'), $request->user());

            return $transaction;
        });

        return $this->ok($transaction->load(self::RELATIONS), [], 'Created', 201);
    }

    public function destroy(Request $request, CashTransaction $cashTransaction)
    {
        $this->authorize('delete', $cashTransaction);

        DB::transaction(function () use ($request, $cashTransaction) {
            $this->posting->voidCashTransaction($cashTransaction, $request->user());

            if ($cashTransaction->attachment_path) {
                $this->deleteAttachment($cashTransaction->attachment_path);
            }

            $cashTransaction->delete();
        });

        return $this->ok(null, [], 'Deleted');
    }

    private function resolveBranch(Request $request, ?string $branchId): Branch
    {
        $user = $request->user();
        $resolved = $user->hasAnyRole(['Superadmin', 'Akuntansi']) ? $branchId : $user->branch_id;

        if (! $resolved) {
            throw ValidationException::withMessages([
                'branch_id' => ['Cabang wajib dipilih untuk membuat transaksi.'],
            ]);
        }

        if (! $user->canManageBranch((string) $resolved)) {
            abort(403);
        }

        return Branch::query()->findOrFail($resolved);
    }

    private function feeCategoryId(?string $requested): string
    {
        $category = $requested
            ? TransactionCategory::query()->find($requested)
            : TransactionCategory::query()->where('is_default', true)->where('cash_out', true)->first();

        if (! $category) {
            throw ValidationException::withMessages([
                'fee_category_id' => ['Kategori biaya admin belum tersedia.'],
            ]);
        }

        return (string) $category->id;
    }

    private function sumLines(array $lines): float
    {
        return round(array_sum(array_map(fn (array $line) => (float) $line['amount'], $lines)), 2);
    }

    private function syncLines(CashTransaction $transaction, array $lines): void
    {
        foreach (array_values($lines) as $index => $line) {
            $transaction->lines()->create([
                'transaction_category_id' => $line['transaction_category_id'],
                'description' => $line['description'] ?? null,
                'amount' => $line['amount'],
                'line_order' => $index + 1,
            ]);
        }
    }

    private function storeAttachment(Request $request): ?string
    {
        if (! $request->hasFile('attachment')) {
            return null;
        }

        return 'storage/' . $request->file('attachment')->store('uploads/cash-transactions', 'public');
    }

    private function deleteAttachment(string $storedPath): void
    {
        Storage::disk('public')->delete(preg_replace('#^storage/#', '', $storedPath));
    }
}
