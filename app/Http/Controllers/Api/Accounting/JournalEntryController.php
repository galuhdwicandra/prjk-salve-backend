<?php
namespace App\Http\Controllers\Api\Accounting;

use App\Http\Controllers\Controller;
use App\Http\Requests\Accounting\FundTransferRequest;
use App\Http\Requests\Accounting\JournalEntryStoreRequest;
use App\Http\Requests\Accounting\JournalEntryUpdateRequest;
use App\Models\AccountingAccount;
use App\Models\AccountingJournalEntry;
use App\Models\AccountingJournalLine;
use App\Services\Accounting\AccountingJournalNumberService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class JournalEntryController extends Controller
{
    public function __construct(
        private AccountingJournalNumberService $numberService,
    ) {}

    public function index(Request $request)
    {
        $this->authorize('viewAny', AccountingJournalEntry::class);

        $user = $request->user();

        $query = AccountingJournalEntry::query()
            ->with([
                'branch:id,name,code',
                'creator:id,name',
                'poster:id,name',
                'voider:id,name',
            ])
            ->withCount('lines')
            ->orderByDesc('journal_date')
            ->orderByDesc('created_at');

        if ($user->hasAnyRole(['Superadmin', 'Akuntansi'])) {
            if ($branchId = $request->query('branch_id')) {
                $query->where('branch_id', $branchId);
            }
        } else {
            $query->where('branch_id', $user->branch_id);
        }

        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }

        if ($sourceType = $request->query('source_type')) {
            $query->where('source_type', $sourceType);
        }

        if ($from = $request->query('date_from')) {
            $query->whereDate('journal_date', '>=', $from);
        }

        if ($to = $request->query('date_to')) {
            $query->whereDate('journal_date', '<=', $to);
        }

        if ($search = trim((string) $request->query('q', ''))) {
            $query->where(function ($q) use ($search) {
                $q->where('journal_no', 'like', "%{$search}%")
                    ->orWhere('source_no', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $items = $query->paginate((int) $request->query('per_page', 20));

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

    public function show(AccountingJournalEntry $journal)
    {
        $this->authorize('view', $journal);

        return response()->json([
            'data'    => $journal->load([
                'branch:id,name,code',
                'mapping:id,event_key,payment_method,expense_category',
                'creator:id,name',
                'poster:id,name',
                'voider:id,name',
                'lines' => fn($q) => $q->orderBy('line_order'),
                'lines.account:id,code,name,type,normal_balance,branch_id,is_active',
            ]),
            'meta'    => [],
            'message' => 'OK',
            'errors'  => null,
        ]);
    }

    public function store(JournalEntryStoreRequest $request)
    {
        $this->authorize('create', AccountingJournalEntry::class);

        $payload  = $request->validated();
        $branchId = $this->resolveBranchId($request, $payload);
        $lines    = $this->normalizeLines($payload['lines'], $branchId);

        $journal = DB::transaction(function () use ($request, $payload, $branchId, $lines) {
            $journal = new AccountingJournalEntry([
                'branch_id'    => $branchId,
                'mapping_id'   => null,
                'journal_no'   => $this->numberService->next($payload['journal_date']),
                'journal_date' => Carbon::parse($payload['journal_date'])->toDateString(),
                'source_type'  => 'manual',
                'source_id'    => null,
                'source_no'    => null,
                'status'       => 'DRAFT',
                'description'  => $payload['description'] ?? null,
                'total_debit'  => $lines['total_debit'],
                'total_credit' => $lines['total_credit'],
                'created_by'   => $request->user()?->id,
                'posted_by'    => null,
                'posted_at'    => null,
                'voided_by'    => null,
                'voided_at'    => null,
                'void_reason'  => null,
            ]);

            $journal->id = (string) Str::uuid();
            $journal->save();

            foreach ($lines['items'] as $index => $line) {
                $detail = new AccountingJournalLine([
                    'journal_entry_id' => $journal->id,
                    'account_id'       => $line['account_id'],
                    'description'      => $line['description'],
                    'debit'            => $line['debit'],
                    'credit'           => $line['credit'],
                    'line_order'       => $index + 1,
                ]);

                $detail->id = (string) Str::uuid();
                $detail->save();
            }

            return $journal;
        });

        return response()->json([
            'data'    => $journal->load([
                'branch:id,name,code',
                'lines' => fn($q) => $q->orderBy('line_order'),
                'lines.account:id,code,name,type,normal_balance,branch_id,is_active',
            ]),
            'meta'    => [],
            'message' => 'Created',
            'errors'  => null,
        ], 201);
    }

    public function transfer(FundTransferRequest $request)
    {
        $this->authorize('create', AccountingJournalEntry::class);

        $payload  = $request->validated();
        $branchId = $this->resolveBranchId($request, $payload);

        if (! $request->user()->canManageBranch($branchId)) {
            abort(403);
        }

        $amount = round((float) $payload['amount'], 2);

        $lines = $this->normalizeLines([
            [
                'account_id'  => $payload['from_account_id'],
                'description' => $payload['description'] ?? null,
                'credit'      => $amount,
            ],
            [
                'account_id'  => $payload['to_account_id'],
                'description' => $payload['description'] ?? null,
                'debit'       => $amount,
            ],
        ], $branchId);

        $journal = DB::transaction(function () use ($request, $payload, $branchId, $lines) {
            $journal = new AccountingJournalEntry([
                'branch_id'    => $branchId,
                'mapping_id'   => null,
                'journal_no'   => $this->numberService->next($payload['journal_date']),
                'journal_date' => Carbon::parse($payload['journal_date'])->toDateString(),
                'source_type'  => 'transfer',
                'source_id'    => null,
                'source_no'    => null,
                'status'       => 'POSTED',
                'description'  => $payload['description'] ?? null,
                'total_debit'  => $lines['total_debit'],
                'total_credit' => $lines['total_credit'],
                'created_by'   => $request->user()?->id,
                'posted_by'    => $request->user()?->id,
                'posted_at'    => now(),
                'voided_by'    => null,
                'voided_at'    => null,
                'void_reason'  => null,
            ]);

            $journal->id = (string) Str::uuid();
            $journal->save();

            foreach ($lines['items'] as $index => $line) {
                $detail = new AccountingJournalLine([
                    'journal_entry_id' => $journal->id,
                    'account_id'       => $line['account_id'],
                    'description'      => $line['description'],
                    'debit'            => $line['debit'],
                    'credit'           => $line['credit'],
                    'line_order'       => $index + 1,
                ]);

                $detail->id = (string) Str::uuid();
                $detail->save();
            }

            return $journal;
        });

        return response()->json([
            'data'    => $journal->load([
                'branch:id,name,code',
                'lines' => fn($q) => $q->orderBy('line_order'),
                'lines.account:id,code,name,type,normal_balance,branch_id,is_active',
            ]),
            'meta'    => [],
            'message' => 'Transfer berhasil',
            'errors'  => null,
        ], 201);
    }

    public function update(JournalEntryUpdateRequest $request, AccountingJournalEntry $journal)
    {
        $this->authorize('update', $journal);

        if ((string) $journal->source_type !== 'manual') {
            return response()->json([
                'data'    => null,
                'meta'    => [],
                'message' => 'Jurnal otomatis tidak boleh diedit manual.',
                'errors'  => [
                    'journal' => ['Jurnal otomatis tidak boleh diedit manual. Gunakan jurnal koreksi.'],
                ],
            ], 422);
        }

        $payload  = $request->validated();
        $branchId = $this->resolveBranchId($request, $payload);
        $lines    = $this->normalizeLines($payload['lines'], $branchId);

        DB::transaction(function () use ($journal, $payload, $branchId, $lines) {
            $journal->fill([
                'branch_id'    => $branchId,
                'journal_date' => Carbon::parse($payload['journal_date'])->toDateString(),
                'description'  => $payload['description'] ?? null,
                'total_debit'  => $lines['total_debit'],
                'total_credit' => $lines['total_credit'],
            ])->save();

            AccountingJournalLine::query()
                ->where('journal_entry_id', $journal->id)
                ->delete();

            foreach ($lines['items'] as $index => $line) {
                $detail = new AccountingJournalLine([
                    'journal_entry_id' => $journal->id,
                    'account_id'       => $line['account_id'],
                    'description'      => $line['description'],
                    'debit'            => $line['debit'],
                    'credit'           => $line['credit'],
                    'line_order'       => $index + 1,
                ]);

                $detail->id = (string) Str::uuid();
                $detail->save();
            }
        });

        return response()->json([
            'data'    => $journal->refresh()->load([
                'branch:id,name,code',
                'lines' => fn($q) => $q->orderBy('line_order'),
                'lines.account:id,code,name,type,normal_balance,branch_id,is_active',
            ]),
            'meta'    => [],
            'message' => 'Updated',
            'errors'  => null,
        ]);
    }

    public function post(Request $request, AccountingJournalEntry $journal)
    {
        $this->authorize('post', $journal);

        $journal->loadMissing('lines');

        if ($journal->lines->count() < 2) {
            return response()->json([
                'data'    => null,
                'meta'    => [],
                'message' => 'Jurnal minimal memiliki dua baris.',
                'errors'  => [
                    'lines' => ['Jurnal minimal memiliki dua baris.'],
                ],
            ], 422);
        }

        if (bccomp((string) $journal->total_debit, (string) $journal->total_credit, 2) !== 0) {
            return response()->json([
                'data'    => null,
                'meta'    => [],
                'message' => 'Jurnal belum balance.',
                'errors'  => [
                    'total' => ['Total debit harus sama dengan total kredit.'],
                ],
            ], 422);
        }

        DB::transaction(function () use ($request, $journal) {
            $journal->fill([
                'status'      => 'POSTED',
                'posted_by'   => $request->user()?->id,
                'posted_at'   => now(),
                'voided_by'   => null,
                'voided_at'   => null,
                'void_reason' => null,
            ])->save();
        });

        return response()->json([
            'data'    => $journal->refresh()->load([
                'branch:id,name,code',
                'poster:id,name',
                'lines' => fn($q) => $q->orderBy('line_order'),
                'lines.account:id,code,name,type,normal_balance,branch_id,is_active',
            ]),
            'meta'    => [],
            'message' => 'Posted',
            'errors'  => null,
        ]);
    }

    public function void(Request $request, AccountingJournalEntry $journal)
    {
        $this->authorize('void', $journal);

        $request->validate([
            'void_reason' => ['required', 'string', 'max:1000'],
        ], [
            'void_reason.required' => 'Alasan void wajib diisi.',
        ]);

        DB::transaction(function () use ($request, $journal) {
            $journal->fill([
                'status'      => 'VOID',
                'voided_by'   => $request->user()?->id,
                'voided_at'   => now(),
                'void_reason' => $request->input('void_reason'),
            ])->save();
        });

        return response()->json([
            'data'    => $journal->refresh()->load([
                'branch:id,name,code',
                'voider:id,name',
                'lines' => fn($q) => $q->orderBy('line_order'),
                'lines.account:id,code,name,type,normal_balance,branch_id,is_active',
            ]),
            'meta'    => [],
            'message' => 'Voided',
            'errors'  => null,
        ]);
    }

    private function resolveBranchId(Request $request, array $payload): string
    {
        $user = $request->user();

        if (! $user->hasAnyRole(['Superadmin', 'Akuntansi'])) {
            if (! $user->branch_id) {
                throw ValidationException::withMessages([
                    'branch_id' => ['User belum terikat ke cabang.'],
                ]);
            }

            return (string) $user->branch_id;
        }

        $branchId = $payload['branch_id'] ?? null;

        if (! $branchId) {
            throw ValidationException::withMessages([
                'branch_id' => ['Cabang wajib dipilih untuk membuat jurnal.'],
            ]);
        }

        return (string) $branchId;
    }

    private function normalizeLines(array $rows, string $branchId): array
    {
        $items       = [];
        $totalDebit  = 0.0;
        $totalCredit = 0.0;

        foreach ($rows as $index => $row) {
            $debit  = round((float) ($row['debit'] ?? 0), 2);
            $credit = round((float) ($row['credit'] ?? 0), 2);

            if ($debit <= 0 && $credit <= 0) {
                throw ValidationException::withMessages([
                    "lines.{$index}.debit" => ['Debit atau kredit harus diisi.'],
                ]);
            }

            if ($debit > 0 && $credit > 0) {
                throw ValidationException::withMessages([
                    "lines.{$index}.debit" => ['Satu baris tidak boleh memiliki debit dan kredit sekaligus.'],
                ]);
            }

            $account = AccountingAccount::query()
                ->where('id', $row['account_id'])
                ->where('is_active', true)
                ->where(function ($q) use ($branchId) {
                    $q->whereNull('branch_id')
                        ->orWhere('branch_id', $branchId);
                })
                ->first();

            if (! $account) {
                throw ValidationException::withMessages([
                    "lines.{$index}.account_id" => ['Akun tidak aktif atau tidak sesuai cabang jurnal.'],
                ]);
            }

            $items[] = [
                'account_id'  => (string) $row['account_id'],
                'description' => $row['description'] ?? null,
                'debit'       => $debit,
                'credit'      => $credit,
            ];

            $totalDebit  += $debit;
            $totalCredit += $credit;
        }

        $totalDebit  = round($totalDebit, 2);
        $totalCredit = round($totalCredit, 2);

        if ($totalDebit <= 0 || $totalCredit <= 0) {
            throw ValidationException::withMessages([
                'lines' => ['Total debit dan kredit harus lebih dari nol.'],
            ]);
        }

        if (bccomp((string) $totalDebit, (string) $totalCredit, 2) !== 0) {
            throw ValidationException::withMessages([
                'total' => ['Total debit harus sama dengan total kredit.'],
            ]);
        }

        return [
            'items'        => $items,
            'total_debit'  => $totalDebit,
            'total_credit' => $totalCredit,
        ];
    }
}
