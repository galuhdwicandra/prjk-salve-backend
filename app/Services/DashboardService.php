<?php

namespace App\Services;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    /**
     * @param Carbon $from  mulai (awal hari)
     * @param Carbon $to    akhir (akhir hari)
     * @param string|null $branchId
     * @return array<string,mixed>
     */
    public function summary(Carbon $from, Carbon $to, ?string $branchId): array
    {
        // OMZET (basis kas): sum payments.amount dalam window paid_at
        $omzet = DB::table('payments')
            ->join('orders', 'orders.id', '=', 'payments.order_id')
            ->when($branchId, fn($q) => $q->where('orders.branch_id', $branchId))
            ->whereBetween('payments.paid_at', [$from, $to])
            ->sum('payments.amount');

        // TRANSAKSI: count orders by created_at
        $transaksi = DB::table('orders')
            ->when($branchId, fn($q) => $q->where('orders.branch_id', $branchId))
            ->whereBetween('orders.created_at', [$from, $to])
            ->count();

        // ONGKIR: sum deliveries.fee by deliveries.created_at
        $ongkir = DB::table('deliveries')
            ->join('orders', 'orders.id', '=', 'deliveries.order_id')
            ->when($branchId, fn($q) => $q->where('orders.branch_id', $branchId))
            ->whereBetween('deliveries.created_at', [$from, $to])
            ->sum('deliveries.fee');

        // VOUCHER: jumlah order yang pakai voucher & total applied_amount by applied_at
        $voucher = DB::table('order_vouchers')
            ->join('orders', 'orders.id', '=', 'order_vouchers.order_id')
            ->when($branchId, fn($q) => $q->where('orders.branch_id', $branchId))
            ->whereBetween('order_vouchers.applied_at', [$from, $to])
            ->selectRaw('COUNT(DISTINCT order_vouchers.order_id) as used_count, COALESCE(SUM(order_vouchers.applied_amount),0) as used_amount')
            ->first();

        // PIUTANG: outstanding (OPEN/PARTIAL/OVERDUE) per due_date vs now()
        $now = now();
        $piutang = DB::table('receivables')
            ->join('orders', 'orders.id', '=', 'receivables.order_id')
            ->when($branchId, fn($q) => $q->where('orders.branch_id', $branchId))
            ->whereIn('receivables.status', ['OPEN', 'PARTIAL', 'OVERDUE'])
            ->selectRaw('
                COALESCE(SUM(receivables.remaining_amount),0) as remaining,
                COUNT(*) as open_count,
                COALESCE(SUM(CASE WHEN receivables.due_date < ? THEN receivables.remaining_amount ELSE 0 END),0) as overdue_amount,
                SUM(CASE WHEN receivables.due_date < ? THEN 1 ELSE 0 END) as overdue_count
            ', [$now, $now])
            ->first();

        // TOP LAYANAN: top 5 by omzet (order_items.total) dalam window orders.created_at
        $topServices = DB::table('order_items')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->join('services', 'services.id', '=', 'order_items.service_id')
            ->when($branchId, fn($q) => $q->where('orders.branch_id', $branchId))
            ->whereBetween('orders.created_at', [$from, $to])
            ->groupBy('order_items.service_id', 'services.name')
            ->selectRaw('order_items.service_id, services.name, SUM(order_items.qty) as qty, SUM(order_items.total) as amount')
            ->orderByDesc('amount')
            ->limit(5)
            ->get();

        return [
            'omzet' => (float) $omzet,
            'transaksi' => (int) $transaksi,
            'ongkir' => (float) $ongkir,
            'voucher' => [
                'used_count' => (int) ($voucher->used_count ?? 0),
                'used_amount' => (float) ($voucher->used_amount ?? 0),
            ],
            'piutang' => [
                'remaining' => (float) ($piutang->remaining ?? 0),
                'open_count' => (int) ($piutang->open_count ?? 0),
                'overdue_amount' => (float) ($piutang->overdue_amount ?? 0),
                'overdue_count' => (int) ($piutang->overdue_count ?? 0),
            ],
            'top_services' => $topServices,
        ];
    }
}
