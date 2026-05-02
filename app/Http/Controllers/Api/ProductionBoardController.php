<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\ProductionTask;
use App\Models\ProductionTaskLog;
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

        $branchId = $this->branchScopeFor($request);

        $this->service->syncOpenOrdersToTasks($branchId);

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

        if ($branchId) {
            $query->where('branch_id', $branchId);
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

        $allowedPerPages  = [10, 20, 50, 100];
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
                'branch_id'    => $branchId,
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

    public function staffDailyReport(Request $request)
    {
        $this->authorizeProductionAccess($request);

        $payload = $request->validate([
            'date_from' => ['nullable', 'date'],
            'date_to'   => ['nullable', 'date'],
            'branch_id' => ['nullable', 'string'],
            'user_id'   => ['nullable', 'integer'],
        ]);

        $from     = Carbon::parse($payload['date_from'] ?? now('Asia/Jakarta')->toDateString())->toDateString();
        $to       = Carbon::parse($payload['date_to'] ?? $from)->toDateString();
        $branchId = $this->branchScopeFor($request);
        $user     = $request->user();

        $logs = ProductionTaskLog::query()
            ->with([
                'user:id,name',
                'task:id,order_id,assigned_to,current_status,qty,started_date,finished_date',
                'order:id,branch_id,customer_id,number,invoice_no,status,received_at,ready_at',
                'order.customer:id,name',
            ])
            ->whereBetween('process_date', [$from, $to])
            ->when($branchId, fn($query) => $query->where('branch_id', $branchId))
            ->when($this->isOnlyLaundryStaff($user), fn($query) => $query->where('user_id', $user->id))
            ->when(
                $user->hasRole('Superadmin') && ! empty($payload['user_id']),
                fn($query) => $query->where('user_id', (int) $payload['user_id'])
            )
            ->orderBy('process_date')
            ->orderBy('created_at')
            ->get();

        if ($logs->isEmpty() && $this->isOnlyLaundryStaff($user)) {
            $tasks = ProductionTask::query()
                ->with([
                    'assignee:id,name',
                    'order:id,branch_id,customer_id,number,invoice_no,status,received_at,ready_at',
                    'order.customer:id,name',
                ])
                ->where('assigned_to', $user->id)
                ->whereBetween('started_date', [$from, $to])
                ->when($branchId, fn($query) => $query->where('branch_id', $branchId))
                ->orderBy('started_date')
                ->orderBy('created_at')
                ->get();

            $rows = $tasks
                ->map(function (ProductionTask $task) use ($user) {
                    $order = $task->order;

                    $readyAt = $order?->ready_at
                        ? Carbon::parse($order->ready_at)->toDateString()
                        : null;

                    $finishedDate = $task->finished_date
                        ? Carbon::parse($task->finished_date)->toDateString()
                        : null;

                    $today = now('Asia/Jakarta')->toDateString();

                    $isOverdue = $readyAt !== null
                    && $task->current_status !== 'READY'
                    && $readyAt < $today;

                    $overdueDays = 0;
                    if ($isOverdue) {
                        $overdueDays = Carbon::parse($readyAt)->diffInDays(Carbon::parse($today));
                    }

                    return [
                        'user_id'       => (string) $user->id,
                        'staff_name'    => (string) $user->name,
                        'total_invoice' => 1,
                        'total_qty'     => (float) $task->qty,
                        'finished'      => $task->current_status === 'READY' ? 1 : 0,
                        'unfinished'    => $task->current_status !== 'READY' ? 1 : 0,
                        'overdue'       => $isOverdue ? 1 : 0,
                        'details'       => [[
                            'order_id'       => (string) $task->order_id,
                            'invoice_no'     => $order?->invoice_no,
                            'number'         => $order?->number,
                            'customer_name'  => $order?->customer?->name,
                            'qty'            => (float) $task->qty,
                            'current_status' => $task->current_status,
                            'received_at'    => $order?->received_at,
                            'ready_at'       => $order?->ready_at,
                            'started_date'   => $task->started_date,
                            'finished_date'  => $finishedDate,
                            'is_overdue'     => $isOverdue,
                            'overdue_days'   => $overdueDays,
                            'overdue_text'   => $isOverdue ? "Terlambat {$overdueDays} hari" : null,
                        ]],
                    ];
                })
                ->values();

            return response()->json([
                'data'    => $rows,
                'meta'    => [
                    'from'      => $from,
                    'to'        => $to,
                    'branch_id' => $branchId,
                ],
                'message' => 'OK',
                'errors'  => null,
            ]);
        }

        $grouped = $logs
            ->groupBy('user_id')
            ->map(function ($userLogs) {
                $first = $userLogs->first();

                $details = $userLogs
                    ->unique('order_id')
                    ->map(function ($log) {
                        $task  = $log->task;
                        $order = $log->order;

                        $readyAt      = optional($order?->ready_at)->format('Y-m-d');
                        $finishedDate = optional($task?->finished_date)->format('Y-m-d');
                        $overdue      = $this->overdueMeta($readyAt, $finishedDate);

                        return [
                            'order_id'       => (string) $log->order_id,
                            'invoice_no'     => $order?->invoice_no,
                            'number'         => $order?->number,
                            'customer_name'  => $order?->customer?->name,
                            'qty'            => (float) ($task?->qty ?? $log->qty ?? 0),
                            'current_status' => $task?->current_status,
                            'received_at'    => optional($order?->received_at)->format('Y-m-d'),
                            'ready_at'       => $readyAt,
                            'started_date'   => optional($task?->started_date)->format('Y-m-d'),
                            'finished_date'  => $finishedDate,
                            'is_overdue'     => $overdue['is_overdue'],
                            'overdue_days'   => $overdue['overdue_days'],
                            'overdue_text'   => $overdue['overdue_text'],
                        ];
                    })
                    ->values();

                return [
                    'user_id'       => (string) $first->user_id,
                    'staff_name'    => $first->user?->name ?? '-',
                    'total_invoice' => $details->count(),
                    'total_qty'     => (float) $details->sum('qty'),
                    'finished'      => $details->whereNotNull('finished_date')->count(),
                    'unfinished'    => $details->whereNull('finished_date')->count(),
                    'overdue'       => $details->where('is_overdue', true)->count(),
                    'details'       => $details,
                ];
            })
            ->values();

        return response()->json([
            'data'    => $grouped,
            'meta'    => [
                'from'      => $from,
                'to'        => $to,
                'branch_id' => $branchId,
                'user_id'   => $user->hasRole('Superadmin') ? ($payload['user_id'] ?? null) : null,
            ],
            'message' => 'OK',
            'errors'  => null,
        ]);
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
            'order'          => $order
                ? [
                'id'          => (string) $order->id,
                'branch_id'   => (string) $order->branch_id,
                'number'      => $order->number,
                'invoice_no'  => $order->invoice_no,
                'status'      => $order->status,
                'received_at' => optional($order->received_at)->format('Y-m-d'),
                'ready_at'    => optional($order->ready_at)->format('Y-m-d'),
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

    private function branchScopeFor(Request $request): ?string
    {
        $user = $request->user();

        if ($user->hasRole('Superadmin')) {
            $branchId = (string) $request->query('branch_id', '');
            return $branchId !== '' ? $branchId : null;
        }

        return $user->branch_id ? (string) $user->branch_id : null;
    }
}
