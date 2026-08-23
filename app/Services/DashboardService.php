<?php
namespace App\Services;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    private const COMPLETED = ['READY', 'DELIVERING', 'PICKED_UP'];
    private const VOID = 'CANCELED';

    public function summary(Carbon $from, Carbon $to, ?array $branchIds): array
    {
        $qtyPerOrder = DB::table('order_items')
            ->selectRaw('order_id, SUM(qty) AS qty')
            ->groupBy('order_id');

        $liveOrders = fn() => DB::table('orders')
            ->leftJoinSub($qtyPerOrder, 'item_qty', 'item_qty.order_id', '=', 'orders.id')
            ->when($branchIds !== null, fn($q) => $q->whereIn('orders.branch_id', $branchIds))
            ->where('orders.status', '!=', self::VOID)
            ->whereBetween('orders.created_at', [$from, $to]);

        $all = $liveOrders()
            ->selectRaw('
                COALESCE(SUM(item_qty.qty), 0) AS pairs,
                COALESCE(SUM(GREATEST(orders.grand_total - orders.paid_amount, 0)), 0) AS outstanding
            ')
            ->first();

        $done = $liveOrders()
            ->whereIn('orders.status', self::COMPLETED)
            ->selectRaw('
                COALESCE(SUM(orders.grand_total), 0) AS revenue,
                COALESCE(SUM(item_qty.qty), 0) AS pairs
            ')
            ->first();

        $unearned = (float) $liveOrders()
            ->whereNotIn('orders.status', self::COMPLETED)
            ->sum('orders.paid_amount');

        $revenueRecognized = (float) ($done->revenue ?? 0);
        $pairsRecognized = (float) ($done->pairs ?? 0);

        $cashflowDaily = DB::table('cash_mutations')
            ->when($branchIds !== null, fn($q) => $q->whereIn('cash_mutations.branch_id', $branchIds))
            ->whereBetween('cash_mutations.effective_at', [$from, $to])
            ->selectRaw("
                DATE(cash_mutations.effective_at) AS d,
                COALESCE(SUM(CASE WHEN cash_mutations.direction = 'IN' THEN cash_mutations.amount ELSE 0 END), 0) AS cash_in,
                COALESCE(SUM(CASE WHEN cash_mutations.direction = 'OUT' THEN cash_mutations.amount ELSE 0 END), 0) AS cash_out
            ")
            ->groupByRaw('DATE(cash_mutations.effective_at)')
            ->orderBy('d')
            ->get()
            ->map(fn($r) => [
                'date'     => (string) $r->d,
                'cash_in'  => (float) $r->cash_in,
                'cash_out' => (float) $r->cash_out,
            ])
            ->all();

        $revenueByBranch = DB::table('orders')
            ->join('branches', 'branches.id', '=', 'orders.branch_id')
            ->when($branchIds !== null, fn($q) => $q->whereIn('orders.branch_id', $branchIds))
            ->whereIn('orders.status', self::COMPLETED)
            ->whereBetween('orders.created_at', [$from, $to])
            ->groupBy('orders.branch_id', 'branches.code', 'branches.name')
            ->selectRaw('orders.branch_id, branches.code, branches.name, COALESCE(SUM(orders.grand_total), 0) AS amount')
            ->orderByDesc('amount')
            ->get()
            ->map(fn($r) => [
                'branch_id' => (string) $r->branch_id,
                'code'      => (string) $r->code,
                'name'      => (string) $r->name,
                'amount'    => (float) $r->amount,
            ])
            ->all();

        $firstOrderAt = DB::table('orders')
            ->where('status', '!=', self::VOID)
            ->whereNotNull('customer_id')
            ->selectRaw('customer_id, MIN(created_at) AS first_at')
            ->groupBy('customer_id');

        $customers = DB::table('orders')
            ->joinSub($firstOrderAt, 'first_order', 'first_order.customer_id', '=', 'orders.customer_id')
            ->when($branchIds !== null, fn($q) => $q->whereIn('orders.branch_id', $branchIds))
            ->where('orders.status', '!=', self::VOID)
            ->whereBetween('orders.created_at', [$from, $to])
            ->selectRaw('
                SUM(CASE WHEN orders.created_at = first_order.first_at THEN 1 ELSE 0 END) AS new_count,
                SUM(CASE WHEN orders.created_at > first_order.first_at THEN 1 ELSE 0 END) AS returning_count
            ')
            ->first();

        $categoryMix = DB::table('order_items')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->join('services', 'services.id', '=', 'order_items.service_id')
            ->join('service_categories', 'service_categories.id', '=', 'services.category_id')
            ->when($branchIds !== null, fn($q) => $q->whereIn('orders.branch_id', $branchIds))
            ->where('orders.status', '!=', self::VOID)
            ->whereBetween('orders.created_at', [$from, $to])
            ->groupBy('service_categories.id', 'service_categories.name')
            ->selectRaw('
                service_categories.name,
                COALESCE(SUM(order_items.qty), 0) AS qty,
                COALESCE(SUM(order_items.total), 0) AS amount
            ')
            ->orderByDesc('amount')
            ->get()
            ->map(fn($r) => [
                'name'   => (string) $r->name,
                'qty'    => (float) $r->qty,
                'amount' => (float) $r->amount,
            ])
            ->all();

        return [
            'revenue_recognized'   => $revenueRecognized,
            'unearned_revenue'     => $unearned,
            'pairs'                => (float) ($all->pairs ?? 0),
            'atv_per_pair'         => $pairsRecognized > 0 ? round($revenueRecognized / $pairsRecognized, 2) : 0.0,
            'outstanding'          => (float) ($all->outstanding ?? 0),
            'cashflow_daily'       => $cashflowDaily,
            'revenue_by_branch'    => $revenueByBranch,
            'customers_new'        => (int) ($customers->new_count ?? 0),
            'customers_returning'  => (int) ($customers->returning_count ?? 0),
            'category_mix'         => $categoryMix,
        ];
    }
}

