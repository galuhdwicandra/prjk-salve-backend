<?php

namespace App\Services;

use Illuminate\Support\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class ReportService
{
    /** SALES (basis kas) – window: payments.paid_at */
    public function buildSalesQuery(Carbon $from, Carbon $to, ?string $branchId, ?string $method = null)
    {
        $q = DB::table('payments')
            ->join('orders', 'orders.id', '=', 'payments.order_id')
            ->leftJoin('branches', 'branches.id', '=', 'orders.branch_id')
            ->leftJoin('users', 'users.id', '=', 'orders.created_by') // kasir (opsional)
            ->when($branchId, fn($qq) => $qq->where('orders.branch_id', $branchId))
            ->whereBetween('payments.paid_at', [$from, $to])
            ->selectRaw("
                branches.name AS branch,
                payments.paid_at,
                COALESCE(orders.invoice_no, orders.number) AS invoice,
                payments.method,
                payments.amount,
                users.name AS cashier
            ");

        if ($method) {
            $q->where('payments.method', $method);
        }

        return $q->orderBy('payments.paid_at', 'asc');
    }

    /** ORDERS (basis transaksi dibuat) – window: orders.created_at */
    public function buildOrdersQuery(Carbon $from, Carbon $to, ?string $branchId, ?string $status = null)
    {
        $q = DB::table('orders')
            ->leftJoin('branches', 'branches.id', '=', 'orders.branch_id')
            ->leftJoin('customers', 'customers.id', '=', 'orders.customer_id')
            ->when($branchId, fn($qq) => $qq->where('orders.branch_id', $branchId))
            ->whereBetween('orders.created_at', [$from, $to])
            ->selectRaw("
                branches.name AS branch,
                orders.created_at,
                orders.number,
                orders.invoice_no,
                customers.name AS customer,
                orders.status,
                orders.grand_total,
                orders.paid_amount,
                orders.payment_status
            ")
            ->orderBy('orders.created_at', 'asc');

        if ($status) {
            $q->where('orders.status', $status);
        }

        return $q;
    }

    /** RECEIVABLES (Piutang) – window: receivables.due_date (atau created_at bila due_date null) */
    public function buildReceivablesQuery(Carbon $from, Carbon $to, ?string $branchId, ?string $status = null)
    {
        $q = DB::table('receivables')
            ->join('orders', 'orders.id', '=', 'receivables.order_id')
            ->leftJoin('branches', 'branches.id', '=', 'orders.branch_id')
            ->when($branchId, fn($qq) => $qq->where('orders.branch_id', $branchId))
            ->where(function ($w) use ($from, $to) {
                $w->whereBetween('receivables.due_date', [$from->toDateString(), $to->toDateString()])
                    ->orWhereBetween('receivables.created_at', [$from, $to]);
            })
            ->selectRaw("
                branches.name AS branch,
                COALESCE(receivables.due_date::text, to_char(receivables.created_at, 'YYYY-MM-DD')) AS date,
                COALESCE(orders.invoice_no, orders.number) AS invoice,
                receivables.remaining_amount,
                receivables.status
            ")
            ->orderByRaw("COALESCE(receivables.due_date, receivables.created_at) ASC");

        if ($status) {
            $q->where('receivables.status', $status);
        }

        return $q;
    }

    /** EXPENSES – window: expenses.created_at */
    public function buildExpensesQuery(Carbon $from, Carbon $to, ?string $branchId)
    {
        return DB::table('expenses')
            ->leftJoin('branches', 'branches.id', '=', 'expenses.branch_id')
            ->when($branchId, fn($qq) => $qq->where('expenses.branch_id', $branchId))
            ->whereBetween('expenses.created_at', [$from, $to])
            ->selectRaw("
                branches.name AS branch,
                expenses.created_at,
                expenses.category,
                expenses.amount,
                expenses.note
            ")
            ->orderBy('expenses.created_at', 'asc');
    }

    /** Paginate untuk preview JSON */
    public function paginate($builder, int $perPage = 20): LengthAwarePaginator
    {
        return $builder->paginate($perPage);
    }

    /** Stream CSV dengan header kolom berurutan */
    public function streamCsv($builder, array $headers, string $filename, string $delimiter = ';')
    {
        return response()->streamDownload(function () use ($builder, $headers, $delimiter) {
            $out = fopen('php://output', 'w');
            // BOM agar Excel nyaman membaca UTF-8 (opsional)
            fprintf($out, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($out, $headers, $delimiter);
            foreach ($builder->cursor() as $row) {
                // urutkan nilai sesuai headers
                $line = [];
                foreach ($headers as $h) {
                    $key = $this->normalizeKey($h);
                    $line[] = $row->{$key} ?? null;
                }
                fputcsv($out, $line, $delimiter);
            }
            fclose($out);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    private function normalizeKey(string $header): string
    {
        // "Tanggal Bayar" -> "tanggal_bayar" (mapping sederhana: gunakan alias di SELECT agar sudah snake_case)
        return str_replace(' ', '_', strtolower($header));
    }
}
