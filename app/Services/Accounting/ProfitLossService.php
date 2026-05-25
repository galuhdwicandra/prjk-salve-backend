<?php

namespace App\Services\Accounting;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ProfitLossService
{
    public function build(array $filters, User $user): array
    {
        $dateFrom = $filters['date_from'];
        $dateTo = $filters['date_to'];
        $branchId = $this->resolveBranchId($filters, $user);

        $rows = DB::table('accounting_journal_lines as lines')
            ->join('accounting_journal_entries as entries', 'entries.id', '=', 'lines.journal_entry_id')
            ->join('accounting_accounts as accounts', 'accounts.id', '=', 'lines.account_id')
            ->leftJoin('branches', 'branches.id', '=', 'entries.branch_id')
            ->where('entries.status', 'POSTED')
            ->whereDate('entries.journal_date', '>=', $dateFrom)
            ->whereDate('entries.journal_date', '<=', $dateTo)
            ->whereIn('accounts.type', ['REVENUE', 'EXPENSE'])
            ->when($branchId, function ($query) use ($branchId) {
                $query->where('entries.branch_id', $branchId);
            })
            ->groupBy(
                'accounts.id',
                'accounts.code',
                'accounts.name',
                'accounts.type',
                'accounts.normal_balance'
            )
            ->orderBy('accounts.code')
            ->select([
                'accounts.id',
                'accounts.code',
                'accounts.name',
                'accounts.type',
                'accounts.normal_balance',
                DB::raw('COALESCE(SUM(lines.debit), 0) as total_debit'),
                DB::raw('COALESCE(SUM(lines.credit), 0) as total_credit'),
            ])
            ->get();

        $revenues = [];
        $contraRevenues = [];
        $expenses = [];

        $totalGrossRevenue = 0.0;
        $totalContraRevenue = 0.0;
        $totalExpense = 0.0;

        foreach ($rows as $row) {
            $debit = round((float) $row->total_debit, 2);
            $credit = round((float) $row->total_credit, 2);

            $amount = $this->calculateAccountAmount(
                (string) $row->normal_balance,
                $debit,
                $credit
            );

            $item = [
                'account_id' => (string) $row->id,
                'code' => (string) $row->code,
                'name' => (string) $row->name,
                'type' => (string) $row->type,
                'normal_balance' => (string) $row->normal_balance,
                'debit' => $debit,
                'credit' => $credit,
                'amount' => $amount,
            ];

            if ($row->type === 'REVENUE' && $row->normal_balance === 'DEBIT') {
                $contraRevenues[] = $item;
                $totalContraRevenue += $amount;
                continue;
            }

            if ($row->type === 'REVENUE') {
                $revenues[] = $item;
                $totalGrossRevenue += $amount;
                continue;
            }

            if ($row->type === 'EXPENSE') {
                $expenses[] = $item;
                $totalExpense += $amount;
            }
        }

        $totalGrossRevenue = round($totalGrossRevenue, 2);
        $totalContraRevenue = round($totalContraRevenue, 2);
        $netRevenue = round($totalGrossRevenue - $totalContraRevenue, 2);
        $totalExpense = round($totalExpense, 2);
        $netProfit = round($netRevenue - $totalExpense, 2);

        return [
            'data' => [
                'revenues' => $revenues,
                'contra_revenues' => $contraRevenues,
                'expenses' => $expenses,
                'summary' => [
                    'total_gross_revenue' => $totalGrossRevenue,
                    'total_contra_revenue' => $totalContraRevenue,
                    'net_revenue' => $netRevenue,
                    'total_expense' => $totalExpense,
                    'net_profit' => $netProfit,
                    'is_profit' => $netProfit >= 0,
                ],
            ],
            'meta' => [
                'date_from' => $dateFrom,
                'date_to' => $dateTo,
                'branch_id' => $branchId,
                'basis' => 'POSTED',
            ],
        ];
    }

    private function resolveBranchId(array $filters, User $user): ?string
    {
        if ($user->hasRole('Superadmin')) {
            return $filters['branch_id'] ?? null;
        }

        $branchId = $user->branch_id ?? null;

        if (! $branchId) {
            throw ValidationException::withMessages([
                'branch_id' => ['User cabang tidak memiliki cabang.'],
            ]);
        }

        return (string) $branchId;
    }

    private function calculateAccountAmount(string $normalBalance, float $debit, float $credit): float
    {
        if ($normalBalance === 'DEBIT') {
            return round($debit - $credit, 2);
        }

        return round($credit - $debit, 2);
    }
}
