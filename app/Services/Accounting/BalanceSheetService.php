<?php
namespace App\Services\Accounting;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class BalanceSheetService
{
    public function build(array $filters, User $user): array
    {
        $dateFrom = (string) $filters['date_from'];
        $dateTo   = (string) $filters['date_to'];
        $branchId = $this->resolveBranchId($filters, $user);

        $rows = DB::table('accounting_journal_lines as lines')
            ->join('accounting_journal_entries as entries', 'entries.id', '=', 'lines.journal_entry_id')
            ->join('accounting_accounts as accounts', 'accounts.id', '=', 'lines.account_id')
            ->leftJoin('branches', 'branches.id', '=', 'entries.branch_id')
            ->where('entries.status', 'POSTED')
            ->whereDate('entries.journal_date', '<=', $dateTo)
            ->whereIn('accounts.type', ['ASSET', 'LIABILITY', 'EQUITY', 'REVENUE', 'EXPENSE'])
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

        $assets      = [];
        $liabilities = [];
        $equities    = [];

        $totalAssets      = 0.0;
        $totalLiabilities = 0.0;
        $totalEquities    = 0.0;
        $totalRevenue     = 0.0;
        $totalExpense     = 0.0;

        foreach ($rows as $row) {
            $debit   = (float) $row->total_debit;
            $credit  = (float) $row->total_credit;
            $balance = $this->calculateAccountBalance(
                (string) $row->normal_balance,
                $debit,
                $credit
            );

            $item = [
                'id'             => (string) $row->id,
                'code'           => (string) $row->code,
                'name'           => (string) $row->name,
                'type'           => (string) $row->type,
                'normal_balance' => (string) $row->normal_balance,
                'total_debit'    => round($debit, 2),
                'total_credit'   => round($credit, 2),
                'balance'        => round($balance, 2),
            ];

            if ($row->type === 'ASSET') {
                $assets[]     = $item;
                $totalAssets += $balance;
            }

            if ($row->type === 'LIABILITY') {
                $liabilities[]     = $item;
                $totalLiabilities += $balance;
            }

            if ($row->type === 'EQUITY') {
                $equities[]     = $item;
                $totalEquities += $balance;
            }

            if ($row->type === 'REVENUE') {
                $totalRevenue += $balance;
            }

            if ($row->type === 'EXPENSE') {
                $totalExpense += $balance;
            }
        }

        $currentYearProfit = round($totalRevenue - $totalExpense, 2);

        $currentYearProfitRow = [
            'id'             => null,
            'code'           => '3999',
            'name'           => 'Laba Tahun Berjalan',
            'type'           => 'EQUITY',
            'normal_balance' => 'CREDIT',
            'total_debit'    => 0.0,
            'total_credit'   => 0.0,
            'balance'        => $currentYearProfit,
        ];

        $equityWithProfit  = $equities;

        if ($currentYearProfit !== 0.0) {
            $equityWithProfit[] = $currentYearProfitRow;
        }

        $totalEquityWithProfit = round($totalEquities + $currentYearProfit, 2);
        $rightSideTotal        = round($totalLiabilities + $totalEquityWithProfit, 2);
        $totalAssets           = round($totalAssets, 2);
        $difference            = round($totalAssets - $rightSideTotal, 2);

        return [
            'data' => [
                'assets'              => $assets,
                'liabilities'         => $liabilities,
                'equities'            => $equityWithProfit,
                'current_year_profit' => $currentYearProfit,
                'summary'             => [
                    'total_assets'                   => $totalAssets,
                    'total_liabilities'              => round($totalLiabilities, 2),
                    'total_equities'                 => $totalEquityWithProfit,
                    'total_liabilities_and_equities' => $rightSideTotal,
                    'difference'                     => $difference,
                    'is_balanced'                    => bccomp((string) $totalAssets, (string) $rightSideTotal, 2) === 0,
                    'status'                         => bccomp((string) $totalAssets, (string) $rightSideTotal, 2) === 0
                        ? 'BALANCED'
                        : 'NOT_BALANCED',
                ],
            ],
            'meta' => [
                'date_from'  => $dateFrom,
                'date_to'    => $dateTo,
                'as_of_date' => $dateTo,
                'branch_id'  => $branchId,
                'basis'      => 'posted',
            ],
        ];
    }

    private function resolveBranchId(array $filters, User $user): ?string
    {
        if ($user->hasAnyRole(['Superadmin', 'Akuntansi'])) {
            return $filters['branch_id'] ?? null;
        }

        if (! $user->branch_id) {
            throw ValidationException::withMessages([
                'branch_id' => ['User admin cabang belum memiliki cabang.'],
            ]);
        }

        if (! empty($filters['branch_id'])
            && (string) $filters['branch_id'] !== (string) $user->branch_id) {
            throw ValidationException::withMessages([
                'branch_id' => ['Anda hanya dapat melihat Neraca cabang sendiri.'],
            ]);
        }

        return (string) $user->branch_id;
    }

    private function calculateAccountBalance(string $normalBalance, float $debit, float $credit): float
    {
        if ($normalBalance === 'DEBIT') {
            return round($debit - $credit, 2);
        }

        return round($credit - $debit, 2);
    }
}
