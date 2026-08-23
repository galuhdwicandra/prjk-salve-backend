<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\ProductionTask;
use App\Models\ProductionTaskLog;
use App\Models\User;
use App\Services\ProductionTaskService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;

class ProductionBoardController extends Controller
{
    public function __construct(private ProductionTaskService $service)
    {
        $this->middleware('auth:sanctum');
    }

    public function index(Request $request)
    {
        $this->authorizeProductionAccess($request);

        $branchIds = $this->branchScopeIds($request);

        $this->service->syncOpenOrdersToTasks($branchIds);

        $user = $request->user();

        $query = ProductionTask::query()
            ->with($this->service->taskRelations())
            ->whereIn('current_status', ProductionTaskService::BOARD_STATUSES)
            ->when($this->isOnlyLaundryStaff($user), function ($q) use ($user) {
                $q->where(function ($w) use ($user) {
                    $w->where(function ($queue) {
                        $queue->where('current_status', 'QUEUE')
                            ->whereNull('assigned_to');
                    })->orWhere('assigned_to', $user->id);
                });
            })
            ->orderByRaw("FIELD(current_status, 'QUEUE', 'WASHING', 'DRYING', 'IRONING', 'READY')")
            ->orderByDesc('created_at');

        if ($branchIds !== null) {
            $query->whereIn('branch_id', $branchIds);
        }

        if ($user->hasRole('Superadmin')) {
            if ($assignedTo = $request->query('assigned_to')) {
                $query->where('assigned_to', $assignedTo);
            }
        }

        if ($status = $request->query('status')) {
            if ((string) $status === 'OVERDUE') {
                $today = now('Asia/Jakarta')->toDateString();

                $query
                    ->where('current_status', '!=', 'READY')
                    ->whereHas('order', function ($orderQuery) use ($today) {
                        $orderQuery
                            ->whereNotNull('ready_at')
                            ->whereDate('ready_at', '<', $today);
                    });
            } else {
                $query->where('current_status', $status);
            }
        }

        if ($phase = (string) $request->query('phase', '')) {

            if (! isset(ProductionTaskService::PHASES[$phase])) {
                abort(422, 'Fase workshop tidak valid.');
            }

            $query->whereIn('current_status', ProductionTaskService::PHASES[$phase]);
        }

        if ($q = trim((string) $request->query('q', ''))) {
            $query->whereHas('order', function ($orderQuery) use ($q) {
                $orderQuery
                    ->where('number', 'like', "%{$q}%")
                    ->orWhere('invoice_no', 'like', "%{$q}%")
                    ->orWhereHas('customer', function ($customerQuery) use ($q) {
                        $customerQuery->where('name', 'like', "%{$q}%");
                    });
            });
        }

        $allowedPerPages  = [10, 20, 25, 50, 100];
        $requestedPerPage = (int) $request->query('per_page', 20);
        $perPage          = in_array($requestedPerPage, $allowedPerPages, true)
            ? $requestedPerPage
            : 20;

        $page = $query->paginate($perPage);

        $columns = [];
        foreach (ProductionTaskService::BOARD_STATUSES as $status) {
            $columns[$status] = [];
        }

        $items = [];

        foreach ($page->items() as $task) {
            $formatted = $this->formatTask($task);

            $columns[$task->current_status][] = $formatted;
            $items[]                          = $formatted;
        }

        return response()->json([
            'data'    => [
                'columns' => $columns,
                'items'   => $items,
            ],
            'meta'    => [
                'branch_id'    => $branchIds,
                'statuses'     => ProductionTaskService::BOARD_STATUSES,
                'current_page' => $page->currentPage(),
                'per_page'     => $page->perPage(),
                'total'        => $page->total(),
                'last_page'    => $page->lastPage(),
            ],
            'message' => 'OK',
            'errors'  => null,
        ]);
    }

    public function start(Request $request, Order $order)
    {
        $this->authorizeProductionAccess($request);

        $payload = $request->validate([
            'note' => ['nullable', 'string'],
        ]);

        $task = $this->service->start(
            $order,
            $request->user(),
            $payload['note'] ?? null
        );

        return response()->json([
            'data'    => $this->formatTask($task),
            'meta'    => [],
            'message' => 'Cucian berhasil diambil.',
            'errors'  => null,
        ]);
    }

    public function move(Request $request, Order $order)
    {
        $this->authorizeProductionAccess($request);

        $payload = $request->validate([
            'to_status' => ['required', 'string', Rule::in(ProductionTaskService::STATUSES)],
            'note'      => ['nullable', 'string'],
        ]);

        $task = $this->service->move(
            $order,
            $request->user(),
            $payload['to_status'],
            $payload['note'] ?? null
        );

        return response()->json([
            'data'    => $this->formatTask($task),
            'meta'    => [],
            'message' => 'Status cucian berhasil dipindahkan.',
            'errors'  => null,
        ]);
    }

    public function finish(Request $request, Order $order)
    {
        $this->authorizeProductionAccess($request);

        $payload = $request->validate([
            'note' => ['nullable', 'string'],
        ]);

        $task = $this->service->finish(
            $order,
            $request->user(),
            $payload['note'] ?? null
        );

        return response()->json([
            'data'    => $this->formatTask($task),
            'meta'    => [],
            'message' => 'Cucian berhasil ditandai selesai.',
            'errors'  => null,
        ]);
    }

    public function workRecap(Request $request)
    {
        $this->authorizeProductionAccess($request);

        $payload = $request->validate([
            'date_from' => ['nullable', 'date'],
            'date_to'   => ['nullable', 'date'],
            'branch_id' => ['nullable', 'string'],
            'user_id'   => ['nullable', 'integer'],
            'phase'     => ['nullable', Rule::in(array_keys(ProductionTaskService::PHASES))],
            'page'      => ['nullable', 'integer', 'min:1'],
            'per_page'  => ['nullable', 'integer', 'min:1', 'max:100'],
        ]);

        $from      = Carbon::parse($payload['date_from'] ?? now('Asia/Jakarta')->toDateString())->toDateString();
        $to        = Carbon::parse($payload['date_to'] ?? $from)->toDateString();
        $branchIds = $this->branchScopeIds($request);
        $user      = $request->user();

        $staffFilter = $this->isOnlyLaundryStaff($user)
            ? (int) $user->id
            : (isset($payload['user_id']) ? (int) $payload['user_id'] : null);

        $base = ProductionTaskLog::query()
            ->whereBetween('process_date', [$from, $to])
            ->whereIn('to_status', array_merge(...array_values(ProductionTaskService::PHASES)))
            ->when($branchIds !== null, fn($query) => $query->whereIn('branch_id', $branchIds));

        $technicians = User::query()
            ->whereIn('id', (clone $base)->select('user_id'))
            ->orderBy('name')
            ->get(['id', 'name'])
            ->map(fn(User $row) => ['id' => (string) $row->id, 'name' => $row->name])
            ->values();

        $filtered = (clone $base)
            ->when($staffFilter !== null, fn($query) => $query->where('user_id', $staffFilter))
            ->when(
                ! empty($payload['phase']),
                fn($query) => $query->whereIn('to_status', ProductionTaskService::PHASES[$payload['phase']])
            );

        $summary = ['activities' => 0, 'persiapan' => 0, 'finishing' => 0, 'pairs' => 0.0];

        foreach ((clone $filtered)->groupBy('to_status')->selectRaw('to_status, COUNT(*) as activities, SUM(qty) as pairs')->get() as $row) {
            $summary['activities']                             = (int) $row->activities;
            $summary['pairs']                                  = (float) $row->pairs;
            $summary[$this->phaseOf((string) $row->to_status)] = (int) $row->activities;
        }

        $names = $technicians->pluck('name', 'id');

        $byTechnician = (clone $filtered)
            ->groupBy('user_id')
            ->selectRaw('user_id, COUNT(*) as activities')
            ->pluck('activities', 'user_id')
            ->map(fn($activities, $id) => [
                'user_id'    => (string) $id,
                'name'       => $names[(string) $id] ?? '-',
                'activities' => (int) $activities,
            ])
            ->values();

        $page = (clone $filtered)
            ->with([
                'user:id,name',
                'branch:id,code,name',
                'order:id,customer_id,number,invoice_no',
                'order.customer:id,name',
            ])
            ->orderBy('created_at')
            ->paginate((int) ($payload['per_page'] ?? 25));

        $rows = collect($page->items())
            ->map(fn(ProductionTaskLog $log) => [
                'id'            => (string) $log->id,
                'logged_at'     => optional($log->created_at)->toISOString(),
                'process_date'  => optional($log->process_date)->format('Y-m-d'),
                'order_id'      => (string) $log->order_id,
                'order_number'  => $log->order?->number,
                'invoice_no'    => $log->order?->invoice_no,
                'customer_name' => $log->order?->customer?->name,
                'workshop_code' => $log->branch?->code,
                'workshop_name' => $log->branch?->name,
                'phase'         => $this->phaseOf((string) $log->to_status),
                'to_status'     => (string) $log->to_status,
                'qty'           => (float) $log->qty,
                'technician'    => $log->user?->name,
            ])
            ->values();

        return response()->json([
            'data'    => $rows,
            'meta'    => [
                'from'          => $from,
                'to'            => $to,
                'branch_id'     => $branchIds,
                'user_id'       => $staffFilter !== null ? (string) $staffFilter : null,
                'phase'         => $payload['phase'] ?? null,
                'current_page'  => $page->currentPage(),
                'per_page'      => $page->perPage(),
                'total'         => $page->total(),
                'last_page'     => $page->lastPage(),
                'summary'       => $summary,
                'by_technician' => $byTechnician,
                'technicians'   => $technicians,
            ],
            'message' => 'OK',
            'errors'  => null,
        ]);
    }

    private function phaseOf(string $status): string
    {
        return in_array($status, ProductionTaskService::PHASES['finishing'], true) ? 'finishing' : 'persiapan';
    }

    private function overdueMeta(?string $readyAt, ?string $finishedDate): array
    {
        if (! $readyAt) {
            return [
                'is_overdue'   => false,
                'overdue_days' => 0,
                'overdue_text' => null,
            ];
        }

        $targetDate = Carbon::parse($readyAt, 'Asia/Jakarta')->startOfDay();

        $actualDate = $finishedDate
            ? Carbon::parse($finishedDate, 'Asia/Jakarta')->startOfDay()
            : now('Asia/Jakarta')->startOfDay();

        if ($actualDate->lessThanOrEqualTo($targetDate)) {
            return [
                'is_overdue'   => false,
                'overdue_days' => 0,
                'overdue_text' => null,
            ];
        }

        $days = $targetDate->diffInDays($actualDate);

        return [
            'is_overdue'   => true,
            'overdue_days' => $days,
            'overdue_text' => 'Terlambat ' . $days . ' hari',
        ];
    }

    private function formatTask(ProductionTask $task): array
    {
        $order = $task->order;

        return [
            'id'             => (string) $task->id,
            'order_id'       => (string) $task->order_id,
            'branch_id'      => (string) $task->branch_id,
            'assigned_to'    => $task->assigned_to ? (string) $task->assigned_to : null,
            'current_status' => (string) $task->current_status,
            'qty'            => (float) $task->qty,
            'started_date'   => optional($task->started_date)->format('Y-m-d'),
            'finished_date'  => optional($task->finished_date)->format('Y-m-d'),
            'note'           => $task->note,
            'created_at'     => optional($task->created_at)->toISOString(),
            'updated_at'     => optional($task->updated_at)->toISOString(),
            'assignee'       => $task->assignee
                ? [
                'id'   => (string) $task->assignee->id,
                'name' => $task->assignee->name,
            ]
                : null,
            'branch'         => $task->branch
                ? [
                'id'   => (string) $task->branch->id,
                'code' => $task->branch->code,
                'name' => $task->branch->name,
                'type' => $task->branch->type,
            ]
                : null,
            'order'          => $order
                ? [
                'id'          => (string) $order->id,
                'branch_id'   => (string) $order->branch_id,
                'number'      => $order->number,
                'invoice_no'  => $order->invoice_no,
                'status'      => $order->status,
                'received_at' => optional($order->received_at)->format('Y-m-d'),
                'ready_at'    => optional($order->ready_at)->format('Y-m-d'),
                'branch'      => $order->branch
                    ? [
                    'id'   => (string) $order->branch->id,
                    'code' => $order->branch->code,
                    'name' => $order->branch->name,
                    'type' => $order->branch->type,
                ]
                    : null,
                'customer'    => $order->customer
                    ? [
                    'id'       => (string) $order->customer->id,
                    'name'     => $order->customer->name,
                    'whatsapp' => $order->customer->whatsapp,
                ]
                    : null,
            ]
                : null,
        ];
    }

    private function authorizeProductionAccess(Request $request): void
    {
        $user = $request->user();

        if (
            ! $user->hasRole('Superadmin')
            && ! $user->hasRole('Admin Cabang')
            && ! $user->hasRole('Petugas Cuci')
        ) {
            abort(403, 'Anda tidak memiliki izin mengakses Live Cucian.');
        }
    }

    private function isOnlyLaundryStaff($user): bool
    {
        return $user->hasRole('Petugas Cuci')
        && ! $user->hasRole('Superadmin')
        && ! $user->hasRole('Admin Cabang');
    }

    private function branchScopeIds(Request $request): ?array
    {
        return $request->user()->branchScopeIds($request->query('branch_id'));
    }
}
