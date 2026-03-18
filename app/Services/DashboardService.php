<?php

namespace App\Services;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    /**
     * @param Carbon $from  awal hari (inklusif)
     * @param Carbon $to    akhir hari (inklusif)
     * @param string|null $branchId
     * @return array<string,mixed>
     */
    public function summary(Carbon $from, Carbon $to, ?string $branchId): array
    {
        // === OMZET (basis kas) ===
        $omzetTotal = (float) DB::table('payments')
            ->join('orders', 'orders.id', '=', 'payments.order_id')
            ->when($branchId, fn($q) => $q->where('orders.branch_id', $branchId))
            ->whereBetween('payments.paid_at', [$from, $to])
            ->sum('payments.amount');

        // === TRANSAKSI (jumlah order dibuat) ===
        $ordersCount = (int) DB::table('orders')
            ->when($branchId, fn($q) => $q->where('orders.branch_id', $branchId))
            ->whereBetween('orders.created_at', [$from, $to])
            ->count();

        // === ONGKIR (jumlah fee pengantaran) ===
        $shippingFee = (float) DB::table('deliveries')
            ->join('orders', 'orders.id', '=', 'deliveries.order_id')
            ->when($branchId, fn($q) => $q->where('orders.branch_id', $branchId))
            ->whereBetween('deliveries.created_at', [$from, $to])
            ->sum('deliveries.fee');

        // === VOUCHER (penggunaan & nilai) ===
        $voucherAgg = DB::table('order_vouchers')
            ->join('orders', 'orders.id', '=', 'order_vouchers.order_id')
            ->when($branchId, fn($q) => $q->where('orders.branch_id', $branchId))
            ->whereBetween('order_vouchers.applied_at', [$from, $to])
            ->selectRaw('COUNT(DISTINCT order_vouchers.order_id) AS used_count,
                         COALESCE(SUM(order_vouchers.applied_amount),0) AS used_amount')
            ->first();

        $vouchersUsedCount  = (int) ($voucherAgg->used_count  ?? 0);
        $vouchersUsedAmount = (float) ($voucherAgg->used_amount ?? 0);

        // === PIUTANG (outstanding & overdue) ===
        $now = now();
        $recv = DB::table('receivables')
            ->join('orders', 'orders.id', '=', 'receivables.order_id')
            ->when($branchId, fn($q) => $q->where('orders.branch_id', $branchId))
            ->whereIn('receivables.status', ['OPEN', 'PARTIAL', 'OVERDUE'])
            ->selectRaw("
                COALESCE(SUM(receivables.remaining_amount),0) AS remaining_amount,
                COUNT(*) AS open_count,
                COALESCE(SUM(CASE WHEN receivables.due_date < ? THEN receivables.remaining_amount ELSE 0 END),0) AS overdue_amount,
                SUM(CASE WHEN receivables.due_date < ? THEN 1 ELSE 0 END) AS overdue_count
            ", [$now, $now])
            ->first();

        $receivablesOpenAmount = (float) ($recv->remaining_amount ?? 0);
        $receivablesOpenCount  = (int)   ($recv->open_count       ?? 0);
        $overdueAmount         = (float) ($recv->overdue_amount   ?? 0);
        $overdueCount          = (int)   ($recv->overdue_count    ?? 0);

        // === DP Outstanding (diletakkan di root KPI) ===
        $dp = DB::table('receivables')
            ->join('orders', 'orders.id', '=', 'receivables.order_id')
            ->when($branchId, fn($q) => $q->where('orders.branch_id', $branchId))
            ->whereIn('receivables.status', ['OPEN', 'PARTIAL'])
            ->selectRaw('COUNT(*) AS cnt, COALESCE(SUM(receivables.remaining_amount),0) AS amt')
            ->first();

        $dpOutstandingCount  = (int)   ($dp->cnt ?? 0);
        $dpOutstandingAmount = (float) ($dp->amt ?? 0);

        // === TOTAL PEMBAYARAN PER METODE ===
        $paymentMethodAgg = DB::table('payments')
            ->join('orders', 'orders.id', '=', 'payments.order_id')
            ->when($branchId, fn($q) => $q->where('orders.branch_id', $branchId))
            ->whereNotNull('payments.paid_at')
            ->whereBetween('payments.paid_at', [$from, $to])
            ->selectRaw("
                COALESCE(SUM(CASE WHEN payments.method = 'DP' THEN payments.amount ELSE 0 END), 0) AS dp_amount,
                COALESCE(SUM(CASE WHEN payments.method = 'CASH' THEN payments.amount ELSE 0 END), 0) AS cash_amount,
                COALESCE(SUM(CASE WHEN payments.method = 'TRANSFER' THEN payments.amount ELSE 0 END), 0) AS transfer_amount,
                COALESCE(SUM(CASE WHEN payments.method = 'QRIS' THEN payments.amount ELSE 0 END), 0) AS qris_amount
            ")
            ->first();

        $paymentMethodTotals = [
            'dp_amount'       => (float) ($paymentMethodAgg->dp_amount ?? 0),
            'cash_amount'     => (float) ($paymentMethodAgg->cash_amount ?? 0),
            'transfer_amount' => (float) ($paymentMethodAgg->transfer_amount ?? 0),
            'qris_amount'     => (float) ($paymentMethodAgg->qris_amount ?? 0),
        ];

        // === STATUS PEMBAYARAN ORDER ===
        $paymentStatusAgg = DB::table('orders')
            ->when($branchId, fn($q) => $q->where('orders.branch_id', $branchId))
            ->whereBetween('orders.created_at', [$from, $to])
            ->selectRaw("
                SUM(CASE WHEN orders.payment_status = 'PENDING' THEN 1 ELSE 0 END) AS pending_count,
                COALESCE(SUM(CASE WHEN orders.payment_status = 'PENDING' THEN orders.grand_total ELSE 0 END), 0) AS pending_amount,

                SUM(CASE WHEN orders.payment_status = 'DP' THEN 1 ELSE 0 END) AS dp_count,
                COALESCE(SUM(CASE WHEN orders.payment_status = 'DP' THEN orders.due_amount ELSE 0 END), 0) AS dp_due_amount,

                SUM(CASE WHEN orders.payment_status = 'PAID' THEN 1 ELSE 0 END) AS paid_count
            ")
            ->first();

        $paymentStatusTotals = [
            'pending_count'  => (int) ($paymentStatusAgg->pending_count ?? 0),
            'pending_amount' => (float) ($paymentStatusAgg->pending_amount ?? 0),
            'dp_count'       => (int) ($paymentStatusAgg->dp_count ?? 0),
            'dp_due_amount'  => (float) ($paymentStatusAgg->dp_due_amount ?? 0),
            'paid_count'     => (int) ($paymentStatusAgg->paid_count ?? 0),
        ];

        // === TOP LAYANAN (Top 5 by omzet dalam window order dibuat) ===
        $topServices = DB::table('order_items')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->join('services', 'services.id', '=', 'order_items.service_id')
            ->when($branchId, fn($q) => $q->where('orders.branch_id', $branchId))
            ->whereBetween('orders.created_at', [$from, $to])
            ->groupBy('order_items.service_id', 'services.name')
            ->selectRaw('order_items.service_id, services.name, SUM(order_items.qty) AS qty, SUM(order_items.total) AS amount')
            ->orderByDesc('amount')
            ->limit(5)
            ->get();

        // === OMZET HARIAN (time-series untuk grafik) ===
        $daily = DB::table('payments')
            ->join('orders', 'orders.id', '=', 'payments.order_id')
            ->when($branchId, fn($q) => $q->where('orders.branch_id', $branchId))
            ->whereNotNull('payments.paid_at')
            ->whereBetween('payments.paid_at', [$from, $to])
            ->selectRaw('DATE(payments.paid_at) AS d, SUM(payments.amount) AS sum')
            ->groupByRaw('DATE(payments.paid_at)')
            ->orderBy('d', 'asc')
            ->get()
            ->map(fn($r) => [
                'date' => (string) $r->d,
                'amount' => (float) $r->sum,
            ])
            ->all();

        // === OMZET BULANAN (time-series untuk grafik) ===
        $monthly = DB::table('payments')
            ->join('orders', 'orders.id', '=', 'payments.order_id')
            ->when($branchId, fn($q) => $q->where('orders.branch_id', $branchId))
            ->whereNotNull('payments.paid_at')
            ->whereBetween('payments.paid_at', [$from, $to])
            ->selectRaw("DATE_FORMAT(payments.paid_at, '%Y-%m') AS m, SUM(payments.amount) AS sum")
            ->groupByRaw("DATE_FORMAT(payments.paid_at, '%Y-%m')")
            ->orderBy('m', 'asc')
            ->get()
            ->map(fn($r) => [
                'month' => (string) $r->m,
                'amount' => (float) $r->sum,
            ])
            ->all();

        // === Payload yang DIHARAPKAN Frontend (flatten + time-series) ===
        return [
            'omzet_total' => $omzetTotal,
            'orders_count' => $ordersCount,

            'payment_method_totals' => $paymentMethodTotals,
            'payment_status_totals' => $paymentStatusTotals,

            'delivery_shipping_fee' => $shippingFee,

            'vouchers_used_count'  => $vouchersUsedCount,
            'vouchers_used_amount' => $vouchersUsedAmount,

            'receivables_open_count'  => $receivablesOpenCount,
            'receivables_open_amount' => $receivablesOpenAmount,
            'overdue_count'           => $overdueCount,
            'overdue_amount'          => $overdueAmount,

            'dp_outstanding_count'  => $dpOutstandingCount,
            'dp_outstanding_amount' => $dpOutstandingAmount,

            'omzet_daily'   => $daily,    // [{ date: 'YYYY-MM-DD', amount: number }, ...]
            'omzet_monthly' => $monthly,  // [{ month: 'YYYY-MM', amount: number }, ...]
            'top_services'  => $topServices,
        ];
    }
}
