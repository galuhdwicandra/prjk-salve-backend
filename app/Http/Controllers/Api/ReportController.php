<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Reports\ReportFilterRequest;
use App\Services\ReportService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class ReportController extends Controller
{
    public function __construct(private ReportService $svc) {}

    /** GET /reports/{kind} – preview JSON (paginated) */
    public function preview(string $kind, ReportFilterRequest $req): JsonResponse
    {
        [$q, $columns] = $this->resolveQuery($kind, $req);

        $perPage = (int) max(1, min(100, (int) $req->input('per_page', 20)));
        $page = $this->svc->paginate($q, $perPage);

        return response()->json([
            'data' => $page->items(),
            'meta' => [
                'current_page' => $page->currentPage(),
                'per_page'     => $page->perPage(),
                'total'        => $page->total(),
                'last_page'    => $page->lastPage(),
                'kind'         => $kind,
                'columns'      => $columns,
            ],
            'message' => 'OK',
            'errors'  => null,
        ]);
    }

    /** GET /reports/{kind}/export?format=csv|xlsx – file download (CSV baseline) */
    public function export(string $kind, ReportFilterRequest $req)
    {
        [$q, $columns] = $this->resolveQuery($kind, $req);

        // format saat ini: CSV (Excel-ready). XLSX bisa ditambahkan kemudian.
        $format = $req->input('format', 'csv');
        $from   = $req->input('from');
        $to     = $req->input('to');
        $branch = $req->branchId() ? 'branch' : 'all';
        $filename = sprintf('%s_%s-%s_%s.%s', $kind, str_replace('-', '', $from), str_replace('-', '', $to), $branch, $format);
        $delimiterKey = $req->input('delimiter', 'semicolon');
        $delimiter = match ($delimiterKey) {
            'comma' => ',',
            'tab' => "\t",
            default => ';', // 'semicolon'
        };

        if ($format === 'csv') {
            return $this->svc->streamCsv($q, $columns, $filename, $delimiter);
        }

        // Fallback, untuk saat ini tetap CSV bila XLSX belum diaktifkan
        return $this->svc->streamCsv($q, $columns, Str::replaceLast('.xlsx', '.csv', $filename), $delimiter);
    }

    /** Resolver: mapping jenis report -> query builder + urutan kolom */
    private function resolveQuery(string $kind, ReportFilterRequest $req): array
    {
        $from = $req->fromDate();
        $to   = $req->toDate();
        $bid  = $req->branchId();

        switch ($kind) {
            case 'sales':
            case 'payments':
                $q = $this->svc->buildSalesQuery($from, $to, $bid, $req->input('method'));
                // alias kolom SELECT sudah snake_case agar pas ke CSV
                $columns = ['branch', 'paid_at', 'invoice', 'method', 'amount', 'cashier'];
                return [$q, $columns];

            case 'orders':
                $q = $this->svc->buildOrdersQuery($from, $to, $bid, $req->input('status'));
                $columns = ['branch', 'created_at', 'number', 'invoice_no', 'customer', 'status', 'grand_total', 'paid_amount', 'payment_status'];
                return [$q, $columns];

            case 'receivables':
                $q = $this->svc->buildReceivablesQuery($from, $to, $bid, $req->input('status'));
                $columns = ['branch', 'date', 'invoice', 'remaining_amount', 'status'];
                return [$q, $columns];

            case 'expenses':
                $q = $this->svc->buildExpensesQuery($from, $to, $bid);
                $columns = ['branch', 'created_at', 'category', 'amount', 'note'];
                return [$q, $columns];
        }

        abort(404, 'Unknown report kind.');
    }
}
