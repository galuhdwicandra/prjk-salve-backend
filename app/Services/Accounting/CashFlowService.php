<?php
namespace App\Services\Accounting;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CashFlowService
{
    public function build(array $filters, User $user): array
    {
        $dateFrom = (string) $filters['date_from'];
        $dateTo   = (string) $filters['date_to'];
        $branchId = $this->resolveBranchId($filters, $user);

        $openingBalance = $this->calculateOpeningBalance($dateFrom, $branchId);
        $rows           = $this->getPeriodRows($dateFrom, $dateTo, $branchId);

        $operating = [];
        $investing = [];
        $financing = [];

        $totalCashIn  = 0.0;
        $totalCashOut = 0.0;

        foreach ($rows as $row) {
            $cashIn    = (float) $row->cash_in;
            $cashOut   = (float) $row->cash_out;
            $netAmount = $cashIn - $cashOut;

            $item = [
                'id'               => (string) $row->id,
                'journal_entry_id' => (string) $row->journal_entry_id,
                'journal_date'     => $row->journal_date,
                'journal_no'       => $row->journal_no,
                'source_type'      => $row->source_type,
                'source_no'        => $row->source_no,
                'event_key'        => $row->event_key,
                'description'      => $row->description,
                'cash_account'     => [
                    'id'             => (string) $row->account_id,
                    'code'           => $row->account_code,
                    'name'           => $row->account_name,
                    'normal_balance' => $row->normal_balance,
                ],
                'branch'           => [
                    'id'   => (string) $row->branch_id,
                    'code' => $row->branch_code,
                    'name' => $row->branch_name,
                ],
                'cash_in'          => round($cashIn, 2),
                'cash_out'         => round($cashOut, 2),
                'net_amount'       => round($netAmount, 2),
            ];

            $activity = $this->classifyActivity(
                $row->event_key,
                $row->source_type
            );

            if ($activity === 'FINANCING') {
                $financing[] = $item;
            } elseif ($activity === 'INVESTING') {
                $investing[] = $item;
            } else {
                $operating[] = $item;
            }

            $totalCashIn  += $cashIn;
            $totalCashOut += $cashOut;
        }

        $operatingTotal = $this->sumNetAmount($operating);
        $investingTotal = $this->sumNetAmount($investing);
        $financingTotal = $this->sumNetAmount($financing);
        $netCashFlow    = $operatingTotal + $investingTotal + $financingTotal;
        $endingBalance  = $openingBalance + $netCashFlow;

        return [
            'data' => [
                'operating_activities' => [
                    'label' => 'Aktivitas Operasional',
                    'items' => $operating,
                    'total' => round($operatingTotal, 2),
                ],
                'investing_activities' => [
                    'label' => 'Aktivitas Investasi',
                    'items' => $investing,
                    'total' => round($investingTotal, 2),
                ],
                'financing_activities' => [
                    'label' => 'Aktivitas Pendanaan',
                    'items' => $financing,
                    'total' => round($financingTotal, 2),
                ],
                'summary'              => [
                    'opening_balance' => round($openingBalance, 2),
                    'total_cash_in'   => round($totalCashIn, 2),
                    'total_cash_out'  => round($totalCashOut, 2),
                    'net_cash_flow'   => round($netCashFlow, 2),
                    'ending_balance'  => round($endingBalance, 2),
                ],
            ],
            'meta' => [
                'date_from' => $dateFrom,
                'date_to'   => $dateTo,
                'branch_id' => $branchId,
                'basis'     => 'POSTED',
                'source'    => 'accounting_journal_lines',
            ],
        ];
    }

    private function calculateOpeningBalance(string $dateFrom, ?string $branchId): float
    {
        $row = DB::table('accounting_journal_lines as lines')
            ->join('accounting_journal_entries as entries', 'entries.id', '=', 'lines.journal_entry_id')
            ->join('accounting_accounts as accounts', 'accounts.id', '=', 'lines.account_id')
            ->where('entries.status', 'POSTED')
            ->where('accounts.is_cash_account', true)
            ->whereDate('entries.journal_date', '<', $dateFrom)
            ->when($branchId, function ($query) use ($branchId) {
                $query->where('entries.branch_id', $branchId);
            })
            ->selectRaw("
                COALESCE(SUM(
                    CASE
                        WHEN accounts.normal_balance = 'DEBIT'
                            THEN lines.debit - lines.credit
                        ELSE lines.credit - lines.debit
                    END
                ), 0) as opening_balance
            ")
            ->first();

        return round((float) ($row->opening_balance ?? 0), 2);
    }

    private function getPeriodRows(string $dateFrom, string $dateTo, ?string $branchId)
    {
        return DB::table('accounting_journal_lines as lines')
            ->join('accounting_journal_entries as entries', 'entries.id', '=', 'lines.journal_entry_id')
            ->join('accounting_accounts as accounts', 'accounts.id', '=', 'lines.account_id')
            ->leftJoin('branches', 'branches.id', '=', 'entries.branch_id')
            ->leftJoin('accounting_account_mappings as mappings', 'mappings.id', '=', 'entries.mapping_id')
            ->where('entries.status', 'POSTED')
            ->where('accounts.is_cash_account', true)
            ->whereDate('entries.journal_date', '>=', $dateFrom)
            ->whereDate('entries.journal_date', '<=', $dateTo)
            ->when($branchId, function ($query) use ($branchId) {
                $query->where('entries.branch_id', $branchId);
            })
            ->orderBy('entries.journal_date')
            ->orderBy('entries.created_at')
            ->orderBy('lines.line_order')
            ->select([
                'lines.id',
                'lines.journal_entry_id',
                'lines.account_id',
                'lines.description',
                'lines.debit',
                'lines.credit',
                'entries.branch_id',
                'entries.journal_date',
                'entries.journal_no',
                'entries.source_type',
                'entries.source_no',
                'entries.description as journal_description',
                'accounts.code as account_code',
                'accounts.name as account_name',
                'accounts.normal_balance',
                'branches.code as branch_code',
                'branches.name as branch_name',
                'mappings.event_key',
                DB::raw("
                    CASE
                        WHEN accounts.normal_balance = 'DEBIT' AND lines.debit > lines.credit
                            THEN lines.debit - lines.credit
                        WHEN accounts.normal_balance = 'CREDIT' AND lines.credit > lines.debit
                            THEN lines.credit - lines.debit
                        ELSE 0
                    END as cash_in
                "),
                DB::raw("
                    CASE
                        WHEN accounts.normal_balance = 'DEBIT' AND lines.credit > lines.debit
                            THEN lines.credit - lines.debit
                        WHEN accounts.normal_balance = 'CREDIT' AND lines.debit > lines.credit
                            THEN lines.debit - lines.credit
                        ELSE 0
                    END as cash_out
                "),
            ])
            ->get()
            ->map(function ($row) {
                $row->description = $row->description ?: $row->journal_description;
                return $row;
            });
    }

    private function classifyActivity(?string $eventKey, ?string $sourceType): string
    {
        $eventKey   = strtoupper((string) $eventKey);
        $sourceType = strtoupper((string) $sourceType);

        if (in_array($eventKey, [
            'CASH_OPENING_FLOAT',
            'CASH_WITHDRAWAL',
        ], true)) {
            return 'FINANCING';
        }

        if (str_contains($sourceType, 'ASSET')) {
            return 'INVESTING';
        }

        return 'OPERATING';
    }

    private function sumNetAmount(array $items): float
    {
        return round(array_sum(array_map(
            fn(array $item) => (float) $item['net_amount'],
            $items
        )), 2);
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
