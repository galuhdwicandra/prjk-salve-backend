<?php
namespace App\Services\Accounting;

use App\Models\AccountingAccount;
use App\Models\AccountingJournalLine;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;

class AccountingLedgerService
{
    public function build(array $filters, $user): array
    {
        $account = $this->resolveAccount((string) $filters['account_id'], $user);

        $branchId = $this->resolveBranchId($filters, $account, $user);
        $dateFrom = (string) $filters['date_from'];
        $dateTo   = (string) $filters['date_to'];

        $openingBalance = $this->calculateOpeningBalance($account, $branchId, $dateFrom);

        $lines = $this->ledgerLinesQuery($account, $branchId, $dateFrom, $dateTo)
            ->get();

        $runningBalance = $openingBalance;
        $totalDebit     = 0.0;
        $totalCredit    = 0.0;

        $rows = $lines->map(function (AccountingJournalLine $line) use ($account, &$runningBalance, &$totalDebit, &$totalCredit) {
            $debit  = (float) $line->debit;
            $credit = (float) $line->credit;

            $totalDebit  += $debit;
            $totalCredit += $credit;

            $runningBalance = $this->applyMovement($account, $runningBalance, $debit, $credit);

            return [
                'id'               => (string) $line->id,
                'journal_entry_id' => (string) $line->journal_entry_id,
                'journal_date'     => optional($line->journalEntry?->journal_date)->format('Y-m-d'),
                'journal_no'       => $line->journalEntry?->journal_no,
                'source_type'      => $line->journalEntry?->source_type,
                'source_id'        => $line->journalEntry?->source_id,
                'source_no'        => $line->journalEntry?->source_no,
                'branch'           => $line->journalEntry?->branch ? [
                    'id'   => (string) $line->journalEntry->branch->id,
                    'name' => $line->journalEntry->branch->name,
                    'code' => $line->journalEntry->branch->code,
                ] : null,
                'description'      => $line->description ?: $line->journalEntry?->description,
                'debit'            => round($debit, 2),
                'credit'           => round($credit, 2),
                'balance'          => round($runningBalance, 2),
            ];
        });

        $page    = max((int) ($filters['page'] ?? 1), 1);
        $perPage = max((int) ($filters['per_page'] ?? 50), 1);

        $paginatedRows = $this->paginate($rows, $page, $perPage);

        return [
            'data' => $paginatedRows->items(),
            'meta' => [
                'current_page'    => $paginatedRows->currentPage(),
                'per_page'        => $paginatedRows->perPage(),
                'total'           => $paginatedRows->total(),
                'last_page'       => $paginatedRows->lastPage(),
                'account'         => [
                    'id'             => (string) $account->id,
                    'code'           => $account->code,
                    'name'           => $account->name,
                    'type'           => $account->type,
                    'normal_balance' => $account->normal_balance,
                    'branch_id'      => $account->branch_id,
                ],
                'branch_id'       => $branchId,
                'date_from'       => $dateFrom,
                'date_to'         => $dateTo,
                'opening_balance' => round($openingBalance, 2),
                'total_debit'     => round($totalDebit, 2),
                'total_credit'    => round($totalCredit, 2),
                'ending_balance'  => round($runningBalance, 2),
            ],
        ];
    }

        public function buildAll(array $filters, $user): array
    {
        $branchId = $this->resolveScopeBranchId($filters, $user);
        $dateFrom = (string) $filters['date_from'];
        $dateTo   = (string) $filters['date_to'];

        $accounts = $this->visibleAccountsQuery($user)->get()->keyBy(fn ($account) => (string) $account->id);
        $openings = $this->openingBalancesByAccount($branchId, $dateFrom);

        $groups   = [];
        $rowCount = 0;

        foreach ($this->groupedLinesQuery($branchId, $dateFrom, $dateTo)->cursor() as $line) {
            $key     = (string) $line->account_id;
            $account = $accounts->get($key);

            if (! $account) {
                continue;
            }

            if (! isset($groups[$key])) {
                $opening = $this->balanceFromTotals(
                    $account,
                    (float) ($openings[$key]['debit'] ?? 0),
                    (float) ($openings[$key]['credit'] ?? 0)
                );

                $groups[$key] = [
                    'account'         => [
                        'id'             => (string) $account->id,
                        'code'           => $account->code,
                        'name'           => $account->name,
                        'type'           => $account->type,
                        'normal_balance' => $account->normal_balance,
                    ],
                    'opening_balance' => round($opening, 2),
                    'total_debit'     => 0.0,
                    'total_credit'    => 0.0,
                    'ending_balance'  => $opening,
                    'rows'            => [],
                ];
            }

            $debit  = (float) $line->debit;
            $credit = (float) $line->credit;

            $groups[$key]['total_debit']  += $debit;
            $groups[$key]['total_credit'] += $credit;
            $groups[$key]['ending_balance'] = $this->applyMovement(
                $account,
                $groups[$key]['ending_balance'],
                $debit,
                $credit
            );

            $groups[$key]['rows'][] = [
                'id'               => (string) $line->id,
                'journal_entry_id' => (string) $line->journal_entry_id,
                'journal_date'     => $line->journal_date,
                'journal_no'       => $line->journal_no,
                'source_type'      => $line->source_type,
                'source_no'        => $line->source_no,
                'cash_kind'        => $line->cash_kind,
                'description'      => $line->line_description ?: $line->entry_description,
                'debit'            => round($debit, 2),
                'credit'           => round($credit, 2),
                'balance'          => round($groups[$key]['ending_balance'], 2),
            ];

            $rowCount++;
        }

        $data = array_map(function (array $group) {
            $group['total_debit']    = round($group['total_debit'], 2);
            $group['total_credit']   = round($group['total_credit'], 2);
            $group['ending_balance'] = round($group['ending_balance'], 2);

            return $group;
        }, array_values($groups));

        return [
            'data' => $data,
            'meta' => [
                'grouped'        => true,
                'branch_id'      => $branchId,
                'date_from'      => $dateFrom,
                'date_to'        => $dateTo,
                'total_rows'     => $rowCount,
                'total_accounts' => count($data),
            ],
        ];
    }

    private function visibleAccountsQuery($user)
    {
        $query = AccountingAccount::query();

        if (! $user->hasAnyRole(['Superadmin', 'Akuntansi'])) {
            $query->where(function ($q) use ($user) {
                $q->whereNull('branch_id')
                    ->orWhere('branch_id', $user->branch_id);
            });
        }

        return $query;
    }

    private function resolveScopeBranchId(array $filters, $user): ?string
    {
        if (! $user->hasAnyRole(['Superadmin', 'Akuntansi'])) {
            if (! $user->branch_id) {
                throw ValidationException::withMessages([
                    'branch_id' => ['User belum terikat ke cabang.'],
                ]);
            }

            return (string) $user->branch_id;
        }

        return ($filters['branch_id'] ?? null) ? (string) $filters['branch_id'] : null;
    }

    private function openingBalancesByAccount(?string $branchId, string $dateFrom): array
    {
        $rows = AccountingJournalLine::query()
            ->join('accounting_journal_entries as e', 'e.id', '=', 'accounting_journal_lines.journal_entry_id')
            ->where('e.status', 'POSTED')
            ->whereDate('e.journal_date', '<', $dateFrom)
            ->when($branchId, fn ($q) => $q->where('e.branch_id', $branchId))
            ->groupBy('accounting_journal_lines.account_id')
            ->selectRaw('accounting_journal_lines.account_id as account_id, COALESCE(SUM(accounting_journal_lines.debit), 0) as debit_total, COALESCE(SUM(accounting_journal_lines.credit), 0) as credit_total')
            ->get();

        $totals = [];

        foreach ($rows as $row) {
            $totals[(string) $row->account_id] = [
                'debit'  => (float) $row->debit_total,
                'credit' => (float) $row->credit_total,
            ];
        }

        return $totals;
    }

    private function groupedLinesQuery(?string $branchId, string $dateFrom, string $dateTo)
    {
        return AccountingJournalLine::query()
            ->join('accounting_journal_entries as e', 'e.id', '=', 'accounting_journal_lines.journal_entry_id')
            ->join('accounting_accounts as a', 'a.id', '=', 'accounting_journal_lines.account_id')
            ->leftJoin('cash_transactions as ct', function ($join) {
                $join->on('ct.id', '=', 'e.source_id')
                    ->where('e.source_type', '=', 'cash_transaction');
            })
            ->where('e.status', 'POSTED')
            ->whereDate('e.journal_date', '>=', $dateFrom)
            ->whereDate('e.journal_date', '<=', $dateTo)
            ->when($branchId, fn ($q) => $q->where('e.branch_id', $branchId))
            ->orderBy('a.sort_order')
            ->orderBy('a.code')
            ->orderBy('e.journal_date')
            ->orderBy('e.journal_no')
            ->orderBy('accounting_journal_lines.line_order')
            ->select([
                'accounting_journal_lines.id',
                'accounting_journal_lines.journal_entry_id',
                'accounting_journal_lines.account_id',
                'accounting_journal_lines.debit',
                'accounting_journal_lines.credit',
                'accounting_journal_lines.description as line_description',
                'e.journal_no',
                'e.journal_date',
                'e.source_type',
                'e.source_no',
                'e.description as entry_description',
                'ct.kind as cash_kind',
            ]);
    }

    private function resolveAccount(string $accountId, $user): AccountingAccount
    {
        $account = $this->visibleAccountsQuery($user)->where('id', $accountId)->first();

        if (! $account) {
            throw ValidationException::withMessages([
                'account_id' => ['Akun tidak ditemukan atau tidak sesuai cabang user.'],
            ]);
        }

        return $account;
    }

    private function resolveBranchId(array $filters, AccountingAccount $account, $user): ?string
    {
        if (! $user->hasAnyRole(['Superadmin', 'Akuntansi'])) {
            if (! $user->branch_id) {
                throw ValidationException::withMessages([
                    'branch_id' => ['User belum terikat ke cabang.'],
                ]);
            }

            return (string) $user->branch_id;
        }

        $requestedBranchId = $filters['branch_id'] ?? null;

        if ($account->branch_id) {
            if ($requestedBranchId && (string) $requestedBranchId !== (string) $account->branch_id) {
                throw ValidationException::withMessages([
                    'branch_id' => ['Cabang filter tidak sesuai dengan cabang akun.'],
                ]);
            }

            return (string) $account->branch_id;
        }

        return $requestedBranchId ? (string) $requestedBranchId : null;
    }

    private function calculateOpeningBalance(AccountingAccount $account, ?string $branchId, string $dateFrom): float
    {
        $totals = AccountingJournalLine::query()
            ->where('account_id', $account->id)
            ->whereHas('journalEntry', function ($q) use ($branchId, $dateFrom) {
                $q->where('status', 'POSTED')
                    ->whereDate('journal_date', '<', $dateFrom);

                if ($branchId) {
                    $q->where('branch_id', $branchId);
                }
            })
            ->selectRaw('COALESCE(SUM(debit), 0) as debit_total, COALESCE(SUM(credit), 0) as credit_total')
            ->first();

        return $this->balanceFromTotals(
            $account,
            (float) ($totals->debit_total ?? 0),
            (float) ($totals->credit_total ?? 0)
        );
    }

    private function ledgerLinesQuery(AccountingAccount $account, ?string $branchId, string $dateFrom, string $dateTo)
    {
        return AccountingJournalLine::query()
            ->with([
                'journalEntry:id,branch_id,journal_no,journal_date,source_type,source_id,source_no,description,status',
                'journalEntry.branch:id,name,code',
            ])
            ->where('account_id', $account->id)
            ->whereHas('journalEntry', function ($q) use ($branchId, $dateFrom, $dateTo) {
                $q->where('status', 'POSTED')
                    ->whereDate('journal_date', '>=', $dateFrom)
                    ->whereDate('journal_date', '<=', $dateTo);

                if ($branchId) {
                    $q->where('branch_id', $branchId);
                }
            })
            ->join('accounting_journal_entries', 'accounting_journal_entries.id', '=', 'accounting_journal_lines.journal_entry_id')
            ->orderBy('accounting_journal_entries.journal_date')
            ->orderBy('accounting_journal_entries.journal_no')
            ->orderBy('accounting_journal_lines.line_order')
            ->select('accounting_journal_lines.*');
    }

    private function applyMovement(AccountingAccount $account, float $currentBalance, float $debit, float $credit): float
    {
        if ((string) $account->normal_balance === 'DEBIT') {
            return $currentBalance + $debit - $credit;
        }

        return $currentBalance + $credit - $debit;
    }

    private function balanceFromTotals(AccountingAccount $account, float $debit, float $credit): float
    {
        if ((string) $account->normal_balance === 'DEBIT') {
            return $debit - $credit;
        }

        return $credit - $debit;
    }

    private function paginate(Collection $rows, int $page, int $perPage): LengthAwarePaginator
    {
        return new LengthAwarePaginator(
            $rows->forPage($page, $perPage)->values()->all(),
            $rows->count(),
            $perPage,
            $page,
            [
                'path'  => request()->url(),
                'query' => request()->query(),
            ]
        );
    }
}
