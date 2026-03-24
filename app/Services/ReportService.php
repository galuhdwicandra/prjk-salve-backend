<?php

namespace App\Services;

use Illuminate\Support\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class ReportService
{
    public function buildServiceItemsQuery(Carbon $from, Carbon $to, ?string $branchId)
    {
        return DB::table('order_items as oi')
            ->join('orders as o', 'o.id', '=', 'oi.order_id')
            ->join('services as s', 's.id', '=', 'oi.service_id')
            ->leftJoin('branches as b', 'b.id', '=', 'o.branch_id')
            ->when($branchId, fn($qq) => $qq->where('o.branch_id', $branchId))
            ->whereBetween('o.created_at', [$from, $to])
            ->selectRaw("
                b.name AS branch,
                s.name AS service,
                s.unit AS unit,
                CAST(SUM(oi.qty) AS CHAR) AS qty,
                SUM(oi.qty * oi.price) AS amount
            ")
            ->groupBy('b.name', 's.name', 's.unit')
            ->orderByRaw('SUM(oi.qty) DESC');
    }

    /** SALES (basis kas) – window: payments.paid_at */
    public function buildSalesQuery(Carbon $from, Carbon $to, ?string $branchId, ?string $method = null)
    {
        $q = DB::table('payments')
            ->join('orders', 'orders.id', '=', 'payments.order_id')
            ->leftJoin('branches', 'branches.id', '=', 'orders.branch_id')
            ->leftJoin('users', 'users.id', '=', 'orders.created_by')
            ->leftJoin('customers', 'customers.id', '=', 'orders.customer_id')
            ->when($branchId, fn($qq) => $qq->where('orders.branch_id', $branchId))
            ->whereBetween('payments.paid_at', [$from, $to])
            ->selectRaw("
            branches.code AS branch_code,
            branches.name AS branch_name,
            COALESCE(orders.invoice_no, orders.number) AS invoice,
            orders.number AS order_number,
            orders.invoice_no AS invoice_no,
            DATE_FORMAT(orders.created_at, '%Y-%m-%d %H:%i:%s') AS order_created_at,
            DATE_FORMAT(orders.received_at, '%Y-%m-%d %H:%i:%s') AS received_at,
            DATE_FORMAT(orders.ready_at, '%Y-%m-%d %H:%i:%s') AS ready_at,
            customers.name AS customer_name,
            customers.whatsapp AS customer_whatsapp,
            customers.address AS customer_address,
            orders.status AS order_status,
            orders.payment_status,
            payments.method AS payment_method,
            payments.amount AS payment_amount,
            DATE_FORMAT(payments.paid_at, '%Y-%m-%d %H:%i:%s') AS paid_at,
            payments.note AS payment_note,
            orders.subtotal,
            orders.discount,
            orders.dp_amount,
            orders.grand_total,
            orders.paid_amount,
            orders.due_amount,
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
            ->leftJoin('order_items as oi', 'oi.order_id', '=', 'orders.id')
            ->leftJoin('services as s', 's.id', '=', 'oi.service_id')
            ->when($branchId, fn($qq) => $qq->where('orders.branch_id', $branchId))
            ->whereBetween('orders.created_at', [$from, $to])
            ->selectRaw("
                branches.name AS branch,
                orders.created_at,
                orders.number,
                orders.invoice_no,
                customers.name AS customer,
                orders.status,
                GROUP_CONCAT(
                    CONCAT(s.name, ' x', CAST(oi.qty AS CHAR))
                    ORDER BY s.name
                    SEPARATOR '; '
                ) AS services,
                CAST(SUM(oi.qty) AS CHAR) AS qty,
                orders.grand_total,
                orders.paid_amount,
                orders.payment_status
            ")
            ->groupBy(
                'branches.name',
                'orders.created_at',
                'orders.number',
                'orders.invoice_no',
                'customers.name',
                'orders.status',
                'orders.grand_total',
                'orders.paid_amount',
                'orders.payment_status'
            )
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
                COALESCE(
                    DATE_FORMAT(receivables.due_date, '%Y-%m-%d'),
                    DATE_FORMAT(receivables.created_at, '%Y-%m-%d')
                ) AS date,
                COALESCE(orders.invoice_no, orders.number) AS invoice,
                receivables.remaining_amount,
                receivables.status
            ")
            ->orderByRaw('COALESCE(receivables.due_date, receivables.created_at) ASC');

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

            fprintf($out, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($out, $headers, $delimiter);

            foreach ($builder->cursor() as $row) {
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
        return str_replace(' ', '_', strtolower($header));
    }
}
