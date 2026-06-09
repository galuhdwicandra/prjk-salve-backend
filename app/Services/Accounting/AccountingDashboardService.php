<?php
namespace App\Services\Accounting;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AccountingDashboardService
{
    public function build(array $filters, User $user): array
    {
        $dateFrom = (string) $filters['date_from'];
        $dateTo   = (string) $filters['date_to'];
        $branchId = $this->resolveBranchId($filters, $user);

        $summary  = $this->buildSummary($dateFrom, $dateTo, $branchId);
        $charts   = $this->buildCharts($dateFrom, $dateTo, $branchId, $user);
        $warnings = $this->buildWarnings($dateTo, $branchId);

        return [
            'data' => [
                'summary'  => $summary,
                'charts'   => $charts,
                'warnings' => $warnings,
            ],
            'meta' => [
                'date_from' => $dateFrom,
                'date_to'   => $dateTo,
                'branch_id' => $branchId,
                'basis'     => 'posted',
            ],
        ];
    }

    private function buildSummary(string $dateFrom, string $dateTo, ?string $branchId): array
    {
        $cashTotal = $this->accountBalanceAsOf(
            $dateTo,
            $branchId,
            fn($query) => $query->where('accounts.is_cash_account', true)
        );

        $receivableTotal = $this->accountBalanceAsOf(
            $dateTo,
            $branchId,
            fn($query) => $query
                ->where('accounts.type', 'ASSET')
                ->where(function ($q) {
                    $q->where('accounts.code', '1100')
                        ->orWhere('accounts.name', 'like', '%Piutang%');
                })
        );

        $revenueTotal = $this->periodAccountBalance(
            $dateFrom,
            $dateTo,
            $branchId,
            fn($query) => $query->where('accounts.type', 'REVENUE')
        );

        $expenseTotal = $this->periodAccountBalance(
            $dateFrom,
            $dateTo,
            $branchId,
            fn($query) => $query->where('accounts.type', 'EXPENSE')
        );

        $netProfit = round($revenueTotal - $expenseTotal, 2);

        $assetTotal = $this->accountBalanceAsOf(
            $dateTo,
            $branchId,
            fn($query) => $query->where('accounts.type', 'ASSET')
        );

        $liabilityTotal = $this->accountBalanceAsOf(
            $dateTo,
            $branchId,
            fn($query) => $query->where('accounts.type', 'LIABILITY')
        );

        $equityBalance = $this->accountBalanceAsOf(
            $dateTo,
            $branchId,
            fn($query) => $query->where('accounts.type', 'EQUITY')
        );

        $profitUntilDate         = $this->profitUntilDate($dateTo, $branchId);
        $equityTotal             = round($equityBalance + $profitUntilDate, 2);
        $liabilityAndEquityTotal = round($liabilityTotal + $equityTotal, 2);
        $difference              = round($assetTotal - $liabilityAndEquityTotal, 2);

        return [
            'total_cash'                     => round($cashTotal, 2),
            'total_receivables'              => round($receivableTotal, 2),
            'total_revenue'                  => round($revenueTotal, 2),
            'total_expense'                  => round($expenseTotal, 2),
            'net_profit'                     => $netProfit,
            'total_assets'                   => round($assetTotal, 2),
            'total_liabilities'              => round($liabilityTotal, 2),
            'total_equities'                 => round($equityTotal, 2),
            'total_liabilities_and_equities' => $liabilityAndEquityTotal,
            'balance_difference'             => $difference,
            'is_balance_sheet_balanced'      => abs($difference) < 0.01,
            'balance_status'                 => abs($difference) < 0.01 ? 'BALANCED' : 'NOT_BALANCED',
        ];
    }

    private function buildCharts(string $dateFrom, string $dateTo, ?string $branchId, User $user): array
    {
        return [
            'revenue_vs_expense'   => $this->revenueExpenseTrend($dateFrom, $dateTo, $branchId),
            'cash_in_vs_cash_out'  => $this->cashFlowTrend($dateFrom, $dateTo, $branchId),
            'net_profit_by_branch' => $user->hasRole('Superadmin')
                ? $this->netProfitByBranch($dateFrom, $dateTo, $branchId)
                : [],
            'receivables_trend'    => $this->receivablesTrend($dateFrom, $dateTo, $branchId),
        ];
    }

    private function buildWarnings(string $dateTo, ?string $branchId): array
    {
        $mappingIssueCount      = $this->mappingIssueCount($branchId);
        $unbalancedJournalCount = $this->unbalancedJournalCount($branchId);
        $draftJournalCount      = $this->draftJournalCount($branchId);

        $assetTotal = $this->accountBalanceAsOf(
            $dateTo,
            $branchId,
            fn($query) => $query->where('accounts.type', 'ASSET')
        );

        $liabilityTotal = $this->accountBalanceAsOf(
            $dateTo,
            $branchId,
            fn($query) => $query->where('accounts.type', 'LIABILITY')
        );

        $equityTotal = $this->accountBalanceAsOf(
            $dateTo,
            $branchId,
            fn($query) => $query->where('accounts.type', 'EQUITY')
        );

        $profitUntilDate   = $this->profitUntilDate($dateTo, $branchId);
        $finalEquity       = round($equityTotal + $profitUntilDate, 2);
        $balanceDifference = round($assetTotal - ($liabilityTotal + $finalEquity), 2);

        $items = [];

        if ($mappingIssueCount > 0) {
            $items[] = [
                'key'      => 'MAPPING_INCOMPLETE',
                'label'    => 'Mapping akun bermasalah',
                'message'  => 'Ada mapping akun yang belum lengkap, memakai akun tidak aktif, atau akun debit dan kredit sama.',
                'count'    => $mappingIssueCount,
                'severity' => 'warning',
            ];
        }

        if ($unbalancedJournalCount > 0) {
            $items[] = [
                'key'      => 'UNBALANCED_JOURNALS',
                'label'    => 'Jurnal belum balance',
                'message'  => 'Ada jurnal non-void dengan total debit dan kredit tidak sama.',
                'count'    => $unbalancedJournalCount,
                'severity' => 'danger',
            ];
        }

        if ($draftJournalCount > 0) {
            $items[] = [
                'key'      => 'DRAFT_JOURNALS',
                'label'    => 'Jurnal draft',
                'message'  => 'Ada jurnal draft yang belum diposting sehingga belum masuk laporan.',
                'count'    => $draftJournalCount,
                'severity' => 'info',
            ];
        }

        if (abs($balanceDifference) >= 0.01) {
            $items[] = [
                'key'      => 'BALANCE_SHEET_NOT_BALANCED',
                'label'    => 'Neraca tidak seimbang',
                'message'  => 'Total aset belum sama dengan total liabilitas dan ekuitas.',
                'count'    => 1,
                'severity' => 'danger',
            ];
        }

        return [
            'items'   => $items,
            'summary' => [
                'mapping_issue_count'      => $mappingIssueCount,
                'unbalanced_journal_count' => $unbalancedJournalCount,
                'draft_journal_count'      => $draftJournalCount,
                'balance_difference'       => $balanceDifference,
                'has_warning'              => count($items) > 0,
            ],
        ];
    }

    private function accountBalanceAsOf(string $dateTo, ?string $branchId, callable $accountFilter): float
    {
        $query = DB::table('accounting_journal_lines as lines')
            ->join('accounting_journal_entries as entries', 'entries.id', '=', 'lines.journal_entry_id')
            ->join('accounting_accounts as accounts', 'accounts.id', '=', 'lines.account_id')
            ->where('entries.status', 'POSTED')
            ->whereDate('entries.journal_date', '<=', $dateTo);

        if ($branchId) {
            $query->where('entries.branch_id', $branchId);
        }

        $accountFilter($query);

        $rows = $query
            ->select([
                'accounts.normal_balance',
                DB::raw('SUM(lines.debit) as total_debit'),
                DB::raw('SUM(lines.credit) as total_credit'),
            ])
            ->groupBy('accounts.normal_balance')
            ->get();

        $total = 0.0;

        foreach ($rows as $row) {
            $debit  = (float) $row->total_debit;
            $credit = (float) $row->total_credit;

            $total += $row->normal_balance === 'DEBIT'
                ? $debit - $credit
                : $credit - $debit;
        }

        return round($total, 2);
    }

    private function periodAccountBalance(string $dateFrom, string $dateTo, ?string $branchId, callable $accountFilter): float
    {
        $query = DB::table('accounting_journal_lines as lines')
            ->join('accounting_journal_entries as entries', 'entries.id', '=', 'lines.journal_entry_id')
            ->join('accounting_accounts as accounts', 'accounts.id', '=', 'lines.account_id')
            ->where('entries.status', 'POSTED')
            ->whereDate('entries.journal_date', '>=', $dateFrom)
            ->whereDate('entries.journal_date', '<=', $dateTo);

        if ($branchId) {
            $query->where('entries.branch_id', $branchId);
        }

        $accountFilter($query);

        $rows = $query
            ->select([
                'accounts.normal_balance',
                DB::raw('SUM(lines.debit) as total_debit'),
                DB::raw('SUM(lines.credit) as total_credit'),
            ])
            ->groupBy('accounts.normal_balance')
            ->get();

        $total = 0.0;

        foreach ($rows as $row) {
            $debit  = (float) $row->total_debit;
            $credit = (float) $row->total_credit;

            $total += $row->normal_balance === 'DEBIT'
                ? $debit - $credit
                : $credit - $debit;
        }

        return round($total, 2);
    }

    private function profitUntilDate(string $dateTo, ?string $branchId): float
    {
        $revenue = $this->accountBalanceAsOf(
            $dateTo,
            $branchId,
            fn($query) => $query->where('accounts.type', 'REVENUE')
        );

        $expense = $this->accountBalanceAsOf(
            $dateTo,
            $branchId,
            fn($query) => $query->where('accounts.type', 'EXPENSE')
        );

        return round($revenue - $expense, 2);
    }

    private function revenueExpenseTrend(string $dateFrom, string $dateTo, ?string $branchId): array
    {
        $query = DB::table('accounting_journal_lines as lines')
            ->join('accounting_journal_entries as entries', 'entries.id', '=', 'lines.journal_entry_id')
            ->join('accounting_accounts as accounts', 'accounts.id', '=', 'lines.account_id')
            ->where('entries.status', 'POSTED')
            ->whereIn('accounts.type', ['REVENUE', 'EXPENSE'])
            ->whereDate('entries.journal_date', '>=', $dateFrom)
            ->whereDate('entries.journal_date', '<=', $dateTo);

        if ($branchId) {
            $query->where('entries.branch_id', $branchId);
        }

        $rows = $query
            ->select([
                DB::raw('DATE(entries.journal_date) as period'),
                'accounts.type',
                'accounts.normal_balance',
                DB::raw('SUM(lines.debit) as total_debit'),
                DB::raw('SUM(lines.credit) as total_credit'),
            ])
            ->groupBy('period', 'accounts.type', 'accounts.normal_balance')
            ->orderBy('period')
            ->get();

        $items = [];

        foreach ($rows as $row) {
            $period = (string) $row->period;

            if (! isset($items[$period])) {
                $items[$period] = [
                    'period'     => $period,
                    'revenue'    => 0.0,
                    'expense'    => 0.0,
                    'net_profit' => 0.0,
                ];
            }

            $debit  = (float) $row->total_debit;
            $credit = (float) $row->total_credit;
            $amount = $row->normal_balance === 'DEBIT'
                ? $debit - $credit
                : $credit - $debit;

            if ($row->type === 'REVENUE') {
                $items[$period]['revenue'] += $amount;
            }

            if ($row->type === 'EXPENSE') {
                $items[$period]['expense'] += $amount;
            }
        }

        return array_values(array_map(function (array $item) {
            $item['revenue']    = round($item['revenue'], 2);
            $item['expense']    = round($item['expense'], 2);
            $item['net_profit'] = round($item['revenue'] - $item['expense'], 2);

            return $item;
        }, $items));
    }

    private function cashFlowTrend(string $dateFrom, string $dateTo, ?string $branchId): array
    {
        $query = DB::table('accounting_journal_lines as lines')
            ->join('accounting_journal_entries as entries', 'entries.id', '=', 'lines.journal_entry_id')
            ->join('accounting_accounts as accounts', 'accounts.id', '=', 'lines.account_id')
            ->where('entries.status', 'POSTED')
            ->where('accounts.is_cash_account', true)
            ->whereDate('entries.journal_date', '>=', $dateFrom)
            ->whereDate('entries.journal_date', '<=', $dateTo);

        if ($branchId) {
            $query->where('entries.branch_id', $branchId);
        }

        $rows = $query
            ->select([
                DB::raw('DATE(entries.journal_date) as period'),
                'accounts.normal_balance',
                DB::raw('SUM(lines.debit) as total_debit'),
                DB::raw('SUM(lines.credit) as total_credit'),
            ])
            ->groupBy('period', 'accounts.normal_balance')
            ->orderBy('period')
            ->get();

        $items = [];

        foreach ($rows as $row) {
            $period = (string) $row->period;

            if (! isset($items[$period])) {
                $items[$period] = [
                    'period'        => $period,
                    'cash_in'       => 0.0,
                    'cash_out'      => 0.0,
                    'net_cash_flow' => 0.0,
                ];
            }

            $debit  = (float) $row->total_debit;
            $credit = (float) $row->total_credit;

            if ($row->normal_balance === 'DEBIT') {
                $items[$period]['cash_in']  += $debit;
                $items[$period]['cash_out'] += $credit;
            } else {
                $items[$period]['cash_in']  += $credit;
                $items[$period]['cash_out'] += $debit;
            }
        }

        return array_values(array_map(function (array $item) {
            $item['cash_in']       = round($item['cash_in'], 2);
            $item['cash_out']      = round($item['cash_out'], 2);
            $item['net_cash_flow'] = round($item['cash_in'] - $item['cash_out'], 2);

            return $item;
        }, $items));
    }

    private function netProfitByBranch(string $dateFrom, string $dateTo, ?string $branchId): array
    {
        $query = DB::table('accounting_journal_lines as lines')
            ->join('accounting_journal_entries as entries', 'entries.id', '=', 'lines.journal_entry_id')
            ->join('accounting_accounts as accounts', 'accounts.id', '=', 'lines.account_id')
            ->leftJoin('branches', 'branches.id', '=', 'entries.branch_id')
            ->where('entries.status', 'POSTED')
            ->whereIn('accounts.type', ['REVENUE', 'EXPENSE'])
            ->whereDate('entries.journal_date', '>=', $dateFrom)
            ->whereDate('entries.journal_date', '<=', $dateTo);

        if ($branchId) {
            $query->where('entries.branch_id', $branchId);
        }

        $rows = $query
            ->select([
                'entries.branch_id',
                'branches.code as branch_code',
                'branches.name as branch_name',
                'accounts.type',
                'accounts.normal_balance',
                DB::raw('SUM(lines.debit) as total_debit'),
                DB::raw('SUM(lines.credit) as total_credit'),
            ])
            ->groupBy(
                'entries.branch_id',
                'branches.code',
                'branches.name',
                'accounts.type',
                'accounts.normal_balance'
            )
            ->orderBy('branches.name')
            ->get();

        $items = [];

        foreach ($rows as $row) {
            $id = (string) $row->branch_id;

            if (! isset($items[$id])) {
                $items[$id] = [
                    'branch_id'   => $id,
                    'branch_code' => $row->branch_code,
                    'branch_name' => $row->branch_name,
                    'revenue'     => 0.0,
                    'expense'     => 0.0,
                    'net_profit'  => 0.0,
                ];
            }

            $debit  = (float) $row->total_debit;
            $credit = (float) $row->total_credit;
            $amount = $row->normal_balance === 'DEBIT'
                ? $debit - $credit
                : $credit - $debit;

            if ($row->type === 'REVENUE') {
                $items[$id]['revenue'] += $amount;
            }

            if ($row->type === 'EXPENSE') {
                $items[$id]['expense'] += $amount;
            }
        }

        return array_values(array_map(function (array $item) {
            $item['revenue']    = round($item['revenue'], 2);
            $item['expense']    = round($item['expense'], 2);
            $item['net_profit'] = round($item['revenue'] - $item['expense'], 2);

            return $item;
        }, $items));
    }

    private function receivablesTrend(string $dateFrom, string $dateTo, ?string $branchId): array
    {
        $query = DB::table('accounting_journal_lines as lines')
            ->join('accounting_journal_entries as entries', 'entries.id', '=', 'lines.journal_entry_id')
            ->join('accounting_accounts as accounts', 'accounts.id', '=', 'lines.account_id')
            ->where('entries.status', 'POSTED')
            ->where('accounts.type', 'ASSET')
            ->where(function ($q) {
                $q->where('accounts.code', '1100')
                    ->orWhere('accounts.name', 'like', '%Piutang%');
            })
            ->whereDate('entries.journal_date', '>=', $dateFrom)
            ->whereDate('entries.journal_date', '<=', $dateTo);

        if ($branchId) {
            $query->where('entries.branch_id', $branchId);
        }

        $rows = $query
            ->select([
                DB::raw('DATE(entries.journal_date) as period'),
                'accounts.normal_balance',
                DB::raw('SUM(lines.debit) as total_debit'),
                DB::raw('SUM(lines.credit) as total_credit'),
            ])
            ->groupBy('period', 'accounts.normal_balance')
            ->orderBy('period')
            ->get();

        $items = [];

        foreach ($rows as $row) {
            $period = (string) $row->period;

            if (! isset($items[$period])) {
                $items[$period] = [
                    'period'          => $period,
                    'receivables_in'  => 0.0,
                    'receivables_out' => 0.0,
                    'net_receivables' => 0.0,
                ];
            }

            $debit  = (float) $row->total_debit;
            $credit = (float) $row->total_credit;

            if ($row->normal_balance === 'DEBIT') {
                $items[$period]['receivables_in']  += $debit;
                $items[$period]['receivables_out'] += $credit;
            } else {
                $items[$period]['receivables_in']  += $credit;
                $items[$period]['receivables_out'] += $debit;
            }
        }

        return array_values(array_map(function (array $item) {
            $item['receivables_in']  = round($item['receivables_in'], 2);
            $item['receivables_out'] = round($item['receivables_out'], 2);
            $item['net_receivables'] = round($item['receivables_in'] - $item['receivables_out'], 2);

            return $item;
        }, $items));
    }

    private function mappingIssueCount(?string $branchId): int
    {
        $query = DB::table('accounting_account_mappings as mappings')
            ->leftJoin('accounting_accounts as debit_accounts', 'debit_accounts.id', '=', 'mappings.debit_account_id')
            ->leftJoin('accounting_accounts as credit_accounts', 'credit_accounts.id', '=', 'mappings.credit_account_id')
            ->where(function ($q) {
                $q->whereNull('debit_accounts.id')
                    ->orWhereNull('credit_accounts.id')
                    ->orWhere('debit_accounts.is_active', false)
                    ->orWhere('credit_accounts.is_active', false)
                    ->orWhereColumn('mappings.debit_account_id', 'mappings.credit_account_id');
            });

        if ($branchId) {
            $query->where(function ($q) use ($branchId) {
                $q->whereNull('mappings.branch_id')
                    ->orWhere('mappings.branch_id', $branchId);
            });
        }

        return (int) $query->count();
    }

    private function unbalancedJournalCount(?string $branchId): int
    {
        $query = DB::table('accounting_journal_entries')
            ->where('status', '!=', 'VOID')
            ->whereRaw('ROUND(total_debit, 2) <> ROUND(total_credit, 2)');

        if ($branchId) {
            $query->where('branch_id', $branchId);
        }

        return (int) $query->count();
    }

    private function draftJournalCount(?string $branchId): int
    {
        $query = DB::table('accounting_journal_entries')
            ->where('status', 'DRAFT');

        if ($branchId) {
            $query->where('branch_id', $branchId);
        }

        return (int) $query->count();
    }

    private function resolveBranchId(array $filters, User $user): ?string
    {
        if ($user->hasAnyRole(['Superadmin', 'Akuntansi'])) {
            return $filters['branch_id'] ?? null;
        }

        if (! $user->branch_id) {
            throw ValidationException::withMessages([
                'branch_id' => ['User belum terikat ke cabang.'],
            ]);
        }

        return (string) $user->branch_id;
    }
}
