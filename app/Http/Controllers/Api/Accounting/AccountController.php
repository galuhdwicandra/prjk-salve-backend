<?php
namespace App\Http\Controllers\Api\Accounting;

use App\Http\Controllers\Controller;
use App\Http\Requests\Accounting\AccountStoreRequest;
use App\Http\Requests\Accounting\AccountUpdateRequest;
use App\Models\AccountingAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AccountController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', AccountingAccount::class);

        $user = $request->user();

        $query = AccountingAccount::query()
            ->with(['branch:id,name,code', 'parent:id,code,name'])
            ->orderBy('sort_order')
            ->orderBy('code');

        if ($user->hasAnyRole(['Superadmin', 'Akuntansi'])) {
            if ($branchId = $request->query('branch_id')) {
                $query->where(function ($q) use ($branchId) {
                    $q->whereNull('branch_id')
                        ->orWhere('branch_id', $branchId);
                });
            }
        } else {
            $query->where(function ($q) use ($user) {
                $q->whereNull('branch_id')
                    ->orWhere('branch_id', $user->branch_id);
            });
        }

        if ($type = $request->query('type')) {
            $query->where('type', $type);
        }

        if (($active = $request->query('is_active')) !== null) {
            $query->where('is_active', filter_var($active, FILTER_VALIDATE_BOOLEAN));
        }

        if (($isCash = $request->query('is_cash_account')) !== null) {
            $query->where('is_cash_account', filter_var($isCash, FILTER_VALIDATE_BOOLEAN));
        }

        if ($search = trim((string) $request->query('q', ''))) {
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $items = $query->paginate((int) $request->query('per_page', 20));

        if ($user->show_balance) {
            $balanceBranchId = $user->hasAnyRole(['Superadmin', 'Akuntansi'])
                ? $request->query('branch_id')
                : $user->branch_id;

            $balances = $this->currentBalances(
                $items->pluck('id')->all(),
                $balanceBranchId ? (string) $balanceBranchId : null
            );

            foreach ($items as $account) {
                $account->setAttribute('balance', $balances[$account->id] ?? 0.0);
            }
        }

        return response()->json([
            'data'    => $items->items(),

            'meta'    => [
                'current_page' => $items->currentPage(),
                'per_page'     => $items->perPage(),
                'total'        => $items->total(),
                'last_page'    => $items->lastPage(),
            ],
            'message' => 'OK',
            'errors'  => null,
        ]);
    }

    public function show(AccountingAccount $account)
    {
        $this->authorize('view', $account);

        return response()->json([
            'data'    => $account->load(['branch:id,name,code', 'parent:id,code,name']),
            'meta'    => [],
            'message' => 'OK',
            'errors'  => null,
        ]);
    }

    public function store(AccountStoreRequest $request)
    {
        $this->authorize('create', AccountingAccount::class);

        $payload = $this->payloadWithBranchScope($request->validated(), $request, true);

        $account = DB::transaction(function () use ($payload) {
            $account     = new AccountingAccount($payload);
            $account->id = (string) Str::uuid();
            $account->save();

            return $account;
        });

        return response()->json([
            'data'    => $account->load(['branch:id,name,code', 'parent:id,code,name']),
            'meta'    => [],
            'message' => 'Created',
            'errors'  => null,
        ], 201);
    }

    public function update(AccountUpdateRequest $request, AccountingAccount $account)
    {
        $this->authorize('update', $account);

        $payload = $this->payloadWithBranchScope($request->validated(), $request, false);

        DB::transaction(function () use ($account, $payload) {
            $account->fill($payload)->save();
        });

        return response()->json([
            'data'    => $account->refresh()->load(['branch:id,name,code', 'parent:id,code,name']),
            'meta'    => [],
            'message' => 'Updated',
            'errors'  => null,
        ]);
    }

    public function destroy(AccountingAccount $account)
    {
        $this->authorize('delete', $account);

        if ($reason = $this->usageReason($account)) {
            return response()->json([
                'data'    => null,
                'meta'    => [],
                'message' => "Akun tidak dapat dihapus. {$reason}",
                'errors' => [
                    'account' => [$reason],
                ],
            ], 422);
        }

        $account->delete();

        return response()->json([
            'data'    => null,
            'meta'    => [],
            'message' => 'Deleted',
            'errors'  => null,
        ]);
    }

    public function bulkDestroy(Request $request)
    {
        $ids = array_values(array_filter((array) $request->input('ids', [])));

        if (empty($ids)) {
            return response()->json([
                'data'    => null,
                'meta'    => [],
                'message' => 'Tidak ada akun yang dipilih.',
                'errors'  => [
                    'ids' => ['Pilih minimal satu akun.'],
                ],
            ], 422);
        }

        $deleted    = 0;
        $skippedIds = [];

        foreach (AccountingAccount::query()->whereIn('id', $ids)->get() as $account) {
            if ($request->user()->cannot('delete', $account) || $this->usageReason($account) !== null) {
                $skippedIds[] = (string) $account->id;
                continue;
            }

            $account->delete();
            $deleted++;
        }

        return response()->json([
            'data'    => null,
            'meta'    => [
                'deleted'     => $deleted,
                'skipped'     => count($skippedIds),
                'skipped_ids' => $skippedIds,
            ],
            'message' => 'OK',
            'errors'  => null,
        ]);
    }

    private function usageReason(AccountingAccount $account): ?string
    {
        if ($account->children()->exists()) {
            return 'Akun masih memiliki akun turunan.';
        }

        if ($account->debitMappings()->exists() || $account->creditMappings()->exists()) {
            return 'Akun masih digunakan pada mapping akun.';
        }

        if ($account->journalLines()->exists()) {
            return 'Akun sudah dipakai pada transaksi.';
        }

        if ($account->paymentMethodAccounts()->exists()) {
            return 'Akun masih terhubung ke metode pembayaran.';
        }

        return null;
    }

    private function payloadWithBranchScope(array $payload, Request $request, bool $creating): array
    {
        $user = $request->user();

        if ($user->hasAnyRole(['Superadmin', 'Akuntansi'])) {
            if ($creating) {
                $payload['branch_id'] = $payload['branch_id'] ?? null;
            }
            return $payload;
        }

        if ($creating || array_key_exists('branch_id', $payload)) {
            $payload['branch_id'] = $user->branch_id;
        }

        return $payload;
    }

    private function currentBalances(array $accountIds, ?string $branchId = null): array
    {
        if (empty($accountIds)) {
            return [];
        }

        $rows = DB::table('accounting_journal_lines as lines')
            ->join('accounting_journal_entries as entries', 'entries.id', '=', 'lines.journal_entry_id')
            ->join('accounting_accounts as accounts', 'accounts.id', '=', 'lines.account_id')
            ->whereIn('lines.account_id', $accountIds)
            ->where('entries.status', 'POSTED')
            ->when($branchId, fn($query) => $query->where('entries.branch_id', $branchId))
            ->select([
                'lines.account_id',
                'accounts.normal_balance',
                DB::raw('SUM(lines.debit) as total_debit'),
                DB::raw('SUM(lines.credit) as total_credit'),
            ])
            ->groupBy('lines.account_id', 'accounts.normal_balance')
            ->get();

        $balances = [];

        foreach ($rows as $row) {
            $debit  = (float) $row->total_debit;
            $credit = (float) $row->total_credit;

            $balances[$row->account_id] = round(
                $row->normal_balance === 'DEBIT' ? $debit - $credit : $credit - $debit,
                2
            );
        }

        return $balances;
    }
}
