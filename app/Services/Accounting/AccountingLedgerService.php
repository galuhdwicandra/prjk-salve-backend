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
        $dateTo = (string) $filters['date_to'];

        $openingBalance = $this->calculateOpeningBalance($account, $branchId, $dateFrom);

        $lines = $this->ledgerLinesQuery($account, $branchId, $dateFrom, $dateTo)
            ->get();

        $runningBalance = $openingBalance;
        $totalDebit = 0.0;
        $totalCredit = 0.0;

        $rows = $lines->map(function (AccountingJournalLine $line) use ($account, &$runningBalance, &$totalDebit, &$totalCredit) {
            $debit = (float) $line->debit;
            $credit = (float) $line->credit;

            $totalDebit += $debit;
            $totalCredit += $credit;

            $runningBalance = $this->applyMovement($account, $runningBalance, $debit, $credit);

            return [
                'id' => (string) $line->id,
                'journal_entry_id' => (string) $line->journal_entry_id,
                'journal_date' => optional($line->journalEntry?->journal_date)->format('Y-m-d'),
                'journal_no' => $line->journalEntry?->journal_no,
                'source_type' => $line->journalEntry?->source_type,
                'source_no' => $line->journalEntry?->source_no,
                'branch' => $line->journalEntry?->branch ? [
                    'id' => (string) $line->journalEntry->branch->id,
                    'name' => $line->journalEntry->branch->name,
                    'code' => $line->journalEntry->branch->code,
                ] : null,
                'description' => $line->description ?: $line->journalEntry?->description,
                'debit' => round($debit, 2),
                'credit' => round($credit, 2),
                'balance' => round($runningBalance, 2),
            ];
        });

        $page = max((int) ($filters['page'] ?? 1), 1);
        $perPage = max((int) ($filters['per_page'] ?? 50), 1);

        $paginatedRows = $this->paginate($rows, $page, $perPage);

        return [
            'data' => $paginatedRows->items(),
            'meta' => [
                'current_page' => $paginatedRows->currentPage(),
                'per_page' => $paginatedRows->perPage(),
                'total' => $paginatedRows->total(),
                'last_page' => $paginatedRows->lastPage(),
                'account' => [
                    'id' => (string) $account->id,
                    'code' => $account->code,
                    'name' => $account->name,
                    'type' => $account->type,
                    'normal_balance' => $account->normal_balance,
                    'branch_id' => $account->branch_id,
                ],
                'branch_id' => $branchId,
                'date_from' => $dateFrom,
                'date_to' => $dateTo,
                'opening_balance' => round($openingBalance, 2),
                'total_debit' => round($totalDebit, 2),
                'total_credit' => round($totalCredit, 2),
                'ending_balance' => round($runningBalance, 2),
            ],
        ];
    }

    private function resolveAccount(string $accountId, $user): AccountingAccount
    {
        $query = AccountingAccount::query()
            ->where('id', $accountId)
            ->where('is_active', true);

        if (! $user->hasRole('Superadmin')) {
            $query->where(function ($q) use ($user) {
                $q->whereNull('branch_id')
                    ->orWhere('branch_id', $user->branch_id);
            });
        }

        $account = $query->first();

        if (! $account) {
            throw ValidationException::withMessages([
                'account_id' => ['Akun tidak aktif atau tidak sesuai cabang user.'],
            ]);
        }

        return $account;
    }

    private function resolveBranchId(array $filters, AccountingAccount $account, $user): ?string
    {
        if (! $user->hasRole('Superadmin')) {
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
                'journalEntry:id,branch_id,journal_no,journal_date,source_type,source_no,description,status',
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
                'path' => request()->url(),
                'query' => request()->query(),
            ]
        );
    }
}
