<?php
namespace App\Services\Accounting;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CashFlowService
{
    private const SECTIONS = [
        'OPERATING' => 'ARUS KAS DARI AKTIVITAS OPERASI',
        'INVESTING' => 'ARUS KAS DARI AKTIVITAS INVESTASI',
        'FINANCING' => 'ARUS KAS DARI AKTIVITAS PENDANAAN',
    ];

    private const EVENT_GROUPS = [
        'ORDER_PAID_CASH'         => ['Penjualan', 'OPERATING'],
        'ORDER_PAID_DP'           => ['Penjualan', 'OPERATING'],
        'ORDER_PAID_QRIS'         => ['Penjualan', 'OPERATING'],
        'ORDER_PAID_TRANSFER'     => ['Penjualan', 'OPERATING'],
        'RECEIVABLE_SETTLED_CASH' => ['Pelunasan Piutang', 'OPERATING'],
        'EXPENSE_CASH_BOX'        => ['Beban Operasional', 'OPERATING'],
        'EXPENSE_NON_CASH'        => ['Beban Operasional', 'OPERATING'],
        'CASH_ADJUSTMENT_IN'      => ['Penyesuaian Kas', 'OPERATING'],
        'CASH_ADJUSTMENT_OUT'     => ['Penyesuaian Kas', 'OPERATING'],
        'CASH_OPENING_FLOAT'      => ['Setoran Modal', 'FINANCING'],
        'CASH_WITHDRAWAL'         => ['Penarikan Pemilik (Prive)', 'FINANCING'],
    ];

    private const SOURCE_GROUPS = [
        'manual'   => 'Jurnal Manual',
        'transfer' => 'Pindah Dana',
    ];

    public function build(array $filters, User $user): array
    {
        $dateFrom = (string) $filters['date_from'];
        $dateTo   = (string) $filters['date_to'];
        $branchId = $this->resolveBranchId($filters, $user);

        $rows = array_merge(
            $this->journalRows($dateFrom, $dateTo, $branchId),
            $this->cashTransactionRows($dateFrom, $dateTo, $branchId),
        );

        $groups   = $this->groupRows($rows);
        $sections = [];

        foreach (self::SECTIONS as $sectionKey => $sectionLabel) {
            $sectionGroups = array_values(array_filter(
                $groups,
                static fn(array $group) => $group['section'] === $sectionKey
            ));

            $visible = array_values(array_filter(
                $sectionGroups,
                static fn(array $group) => abs($group['amount']) >= 0.01
            ));

            usort($visible, static fn(array $a, array $b) => strcmp($a['label'], $b['label']));

            $sections[] = [
                'key'      => $sectionKey,
                'label'    => $sectionLabel,
                'inflows'  => $this->shape($visible, true),
                'outflows' => $this->shape($visible, false),
                'net'      => round(array_sum(array_column($sectionGroups, 'amount')), 2),
            ];
        }

        $openingBalance = $this->cashBalanceAsOf($dateFrom, '<', $branchId);
        $endingBalance  = $this->cashBalanceAsOf($dateTo, '<=', $branchId);
        $netChange      = round(array_sum(array_column($sections, 'net')), 2);
        $balanceChange  = round($endingBalance - $openingBalance, 2);

        return [
            'data' => [
                'sections' => $sections,
                'summary'  => [
                    'opening_balance' => $openingBalance,
                    'net_change'      => $netChange,
                    'ending_balance'  => $endingBalance,
                    'is_balanced'     => abs($netChange - $balanceChange) < 0.01,
                ],
            ],
            'meta' => [
                'date_from' => $dateFrom,
                'date_to'   => $dateTo,
                'branch_id' => $branchId,
                'basis'     => 'POSTED',
                'row_count' => count($rows),
            ],
        ];
    }

    private function groupRows(array $rows): array
    {
        $groups = [];

        foreach ($rows as $row) {
            $key = $row['section'] . '|' . $row['label'];

            if (! isset($groups[$key])) {
                $groups[$key] = [
                    'key'     => $key,
                    'section' => $row['section'],
                    'label'   => $row['label'],
                    'amount'  => 0.0,
                    'items'   => [],
                ];
            }

            $groups[$key]['amount']  += $row['amount'];
            $groups[$key]['items'][]  = [
                'id'          => $row['id'],
                'no'          => $row['no'],
                'date'        => $row['date'],
                'account'     => $row['account'],
                'description' => $row['description'],
                'amount'      => round(abs($row['amount']), 2),
                'link'        => $row['link'],
            ];
        }

        return $groups;
    }

    private function shape(array $groups, bool $inflow): array
    {
        $selected = array_filter(
            $groups,
            static fn(array $group) => $inflow ? $group['amount'] > 0 : $group['amount'] < 0
        );

        return array_values(array_map(static fn(array $group) => [
            'key'    => $group['key'],
            'label'  => $group['label'],
            'amount' => round($group['amount'], 2),
            'items'  => $group['items'],
        ], $selected));
    }

    private function journalRows(string $dateFrom, string $dateTo, ?string $branchId): array
    {
        return DB::table('accounting_journal_lines as lines')
            ->join('accounting_journal_entries as entries', 'entries.id', '=', 'lines.journal_entry_id')
            ->join('accounting_accounts as accounts', 'accounts.id', '=', 'lines.account_id')
            ->leftJoin('accounting_account_mappings as mappings', 'mappings.id', '=', 'entries.mapping_id')
            ->leftJoin('payments', function ($join) {
                $join->on('payments.id', '=', 'entries.source_id')
                    ->where('entries.source_type', '=', 'payment');
            })
            ->leftJoin('receivables', function ($join) {
                $join->on('receivables.id', '=', 'entries.source_id')
                    ->where('entries.source_type', '=', 'receivable');
            })
            ->where('entries.status', 'POSTED')
            ->where('accounts.is_cash_account', true)
            ->where(function ($query) {
                $query->whereNull('entries.source_type')
                    ->orWhere('entries.source_type', '!=', 'cash_transaction');
            })
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
                'lines.description as line_description',
                'entries.id as journal_entry_id',
                'entries.journal_date',
                'entries.journal_no',
                'entries.source_type',
                'entries.source_id',
                'entries.source_no',
                'entries.description as entry_description',
                'accounts.name as account_name',
                'mappings.event_key',
                'payments.order_id as payment_order_id',
                'receivables.order_id as receivable_order_id',
                DB::raw("
                    CASE
                        WHEN accounts.normal_balance = 'DEBIT'
                            THEN lines.debit - lines.credit
                        ELSE lines.credit - lines.debit
                    END as net_amount
                "),
            ])
            ->get()
            ->map(function ($row) {
                [$label, $section] = self::EVENT_GROUPS[strtoupper((string) $row->event_key)] ?? [self::SOURCE_GROUPS[(string) $row->source_type] ?? 'Lainnya', 'OPERATING'];

                return [
                    'id'          => (string) $row->id,
                    'no'          => $row->source_no ?: $row->journal_no,
                    'date'        => $row->journal_date,
                    'account'     => $row->account_name,
                    'description' => $row->line_description ?: $row->entry_description,
                    'amount'      => round((float) $row->net_amount, 2),
                    'label'       => $label,
                    'section'     => $section,
                    'link'        => $this->linkFor($row),
                ];
            })
            ->all();
    }

    private function linkFor(object $row): array
    {
        $sourceType = (string) $row->source_type;

        if ($sourceType === 'order_discount' && $row->source_id) {
            return ['type' => 'order', 'id' => (string) $row->source_id];
        }

        if ($sourceType === 'payment' && $row->payment_order_id) {
            return ['type' => 'order', 'id' => (string) $row->payment_order_id];
        }

        if ($sourceType === 'receivable' && $row->receivable_order_id) {
            return ['type' => 'order', 'id' => (string) $row->receivable_order_id];
        }

        return ['type' => 'journal', 'id' => (string) $row->journal_entry_id];
    }

    private function cashTransactionRows(string $dateFrom, string $dateTo, ?string $branchId): array
    {
        return DB::table('cash_transaction_lines as lines')
            ->join('cash_transactions as trx', 'trx.id', '=', 'lines.cash_transaction_id')
            ->join('transaction_categories as categories', 'categories.id', '=', 'lines.transaction_category_id')
            ->join('accounting_accounts as accounts', 'accounts.id', '=', 'trx.cash_account_id')
            ->join('accounting_journal_entries as entries', function ($join) {
                $join->on('entries.source_id', '=', 'trx.id')
                    ->where('entries.source_type', '=', 'cash_transaction')
                    ->where('entries.status', '=', 'POSTED');
            })
            ->whereDate('trx.trx_date', '>=', $dateFrom)
            ->whereDate('trx.trx_date', '<=', $dateTo)
            ->when($branchId, function ($query) use ($branchId) {
                $query->where('trx.branch_id', $branchId);
            })
            ->orderBy('trx.trx_date')
            ->orderBy('trx.created_at')
            ->orderBy('lines.line_order')
            ->select([
                'lines.id',
                'lines.amount',
                'lines.description as line_description',
                'trx.id as trx_id',
                'trx.no',
                'trx.kind',
                'trx.trx_date',
                'trx.description as trx_description',
                'entries.id as journal_entry_id',
                'accounts.name as account_name',
                'categories.name as category_name',
                'categories.cashflow',
            ])
            ->get()
            ->map(function ($row) {
                $amount  = round((float) $row->amount, 2);
                $section = (string) $row->cashflow;

                return [
                    'id'          => (string) $row->id,
                    'no'          => $row->no,
                    'date'        => $row->trx_date,
                    'account'     => $row->account_name,
                    'description' => $row->line_description ?: $row->trx_description,
                    'amount'      => $row->kind === 'IN' ? $amount : -$amount,
                    'label'       => $row->category_name,
                    'section'     => isset(self::SECTIONS[$section]) ? $section : 'OPERATING',
                    'link'        => $row->kind === 'TRANSFER'
                        ? ['type' => 'journal', 'id' => (string) $row->journal_entry_id]
                        : ['type' => 'cash_transaction', 'id' => (string) $row->trx_id],
                ];
            })
            ->all();
    }

    private function cashBalanceAsOf(string $date, string $operator, ?string $branchId): float
    {
        $row = DB::table('accounting_journal_lines as lines')
            ->join('accounting_journal_entries as entries', 'entries.id', '=', 'lines.journal_entry_id')
            ->join('accounting_accounts as accounts', 'accounts.id', '=', 'lines.account_id')
            ->where('entries.status', 'POSTED')
            ->where('accounts.is_cash_account', true)
            ->whereDate('entries.journal_date', $operator, $date)
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
                ), 0) as balance
            ")
            ->first();

        return round((float) ($row->balance ?? 0), 2);
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
