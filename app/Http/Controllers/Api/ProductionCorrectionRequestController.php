<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\ProductionTaskCorrectionRequest;
use App\Services\ProductionTaskService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductionCorrectionRequestController extends Controller
{
    public function __construct(private ProductionTaskService $service)
    {
        $this->middleware('auth:sanctum');
    }

    public function index(Request $request)
    {
        $user = $request->user();
        $this->authorizeProductionAccess($request);

        $query = ProductionTaskCorrectionRequest::query()
            ->with([
                'task:id,order_id,branch_id,assigned_to,current_status,qty,started_date,finished_date',
                'order:id,branch_id,customer_id,number,invoice_no,status,received_at,ready_at',
                'order.customer:id,name,whatsapp',
                'requester:id,name',
                'reviewer:id,name',
            ])
            ->orderByDesc('created_at');

        if ($user->hasRole('Superadmin')) {
            if ($branchId = $request->query('branch_id')) {
                $query->where('branch_id', $branchId);
            }
        } else {
            $query->where('branch_id', $user->branch_id);
        }

        if ($this->isOnlyLaundryStaff($user)) {
            $query->where('requested_by', $user->id);
        }

        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }

        if ($type = $request->query('type')) {
            $query->where('type', $type);
        }

        $perPage = min(max((int) $request->query('per_page', 20), 1), 100);
        $page = $query->paginate($perPage);

        return response()->json([
            'data' => collect($page->items())->map(fn ($item) => $this->formatRequest($item))->values(),
            'meta' => [
                'current_page' => $page->currentPage(),
                'per_page'     => $page->perPage(),
                'total'        => $page->total(),
                'last_page'    => $page->lastPage(),
            ],
            'message' => 'OK',
            'errors' => null,
        ]);
    }

    public function store(Request $request, Order $order)
    {
        $this->authorizeProductionAccess($request);

        $payload = $request->validate([
            'type'   => ['required', 'string', Rule::in(ProductionTaskService::CORRECTION_TYPES)],
            'reason' => ['required', 'string', 'min:3'],
            'direct' => ['nullable', 'boolean'],
        ]);

        $result = $this->service->createCorrectionRequest(
            $order,
            $request->user(),
            (string) $payload['type'],
            (string) $payload['reason'],
            (bool) ($payload['direct'] ?? false)
        );

        $isDirect = (bool) ($payload['direct'] ?? false);

        return response()->json([
            'data' => $isDirect
                ? $result
                : $this->formatRequest($result),
            'meta' => [],
            'message' => $isDirect
                ? 'Koreksi cucian berhasil dijalankan.'
                : 'Pengajuan koreksi cucian berhasil dikirim.',
            'errors' => null,
        ], $isDirect ? 200 : 201);
    }

    public function approve(Request $request, ProductionTaskCorrectionRequest $correctionRequest)
    {
        $payload = $request->validate([
            'review_note' => ['nullable', 'string'],
        ]);

        $result = $this->service->approveCorrectionRequest(
            $correctionRequest,
            $request->user(),
            $payload['review_note'] ?? null
        );

        return response()->json([
            'data' => $this->formatRequest($result),
            'meta' => [],
            'message' => 'Pengajuan koreksi disetujui.',
            'errors' => null,
        ]);
    }

    public function reject(Request $request, ProductionTaskCorrectionRequest $correctionRequest)
    {
        $payload = $request->validate([
            'review_note' => ['nullable', 'string'],
        ]);

        $result = $this->service->rejectCorrectionRequest(
            $correctionRequest,
            $request->user(),
            $payload['review_note'] ?? null
        );

        return response()->json([
            'data' => $this->formatRequest($result),
            'meta' => [],
            'message' => 'Pengajuan koreksi ditolak.',
            'errors' => null,
        ]);
    }

    private function authorizeProductionAccess(Request $request): void
    {
        $user = $request->user();

        if (
            ! $user->hasRole('Superadmin')
            && ! $user->hasRole('Admin Cabang')
            && ! $user->hasRole('Petugas Cuci')
        ) {
            abort(403, 'Anda tidak memiliki akses ke modul production.');
        }

        if (! $user->hasRole('Superadmin') && ! $user->branch_id) {
            abort(403, 'Akun Anda belum terikat ke cabang.');
        }
    }

    private function isOnlyLaundryStaff($user): bool
    {
        return $user->hasRole('Petugas Cuci')
            && ! $user->hasRole('Superadmin')
            && ! $user->hasRole('Admin Cabang');
    }

    private function formatRequest(ProductionTaskCorrectionRequest $item): array
    {
        return [
            'id'                 => (string) $item->id,
            'production_task_id' => (string) $item->production_task_id,
            'order_id'           => (string) $item->order_id,
            'branch_id'          => (string) $item->branch_id,
            'requested_by'       => $item->requested_by,
            'reviewed_by'        => $item->reviewed_by,
            'type'               => (string) $item->type,
            'from_status'        => (string) $item->from_status,
            'to_status'          => (string) $item->to_status,
            'reason'             => (string) $item->reason,
            'status'             => (string) $item->status,
            'review_note'        => $item->review_note,
            'requested_date'     => optional($item->requested_date)->toDateString(),
            'reviewed_date'      => optional($item->reviewed_date)->toDateString(),
            'created_at'         => optional($item->created_at)->toDateTimeString(),
            'updated_at'         => optional($item->updated_at)->toDateTimeString(),
            'order'              => $item->order,
            'task'               => $item->task,
            'requester'          => $item->requester
                ? [
                    'id' => $item->requester->id,
                    'name' => $item->requester->name,
                ]
                : null,
            'reviewer'           => $item->reviewer
                ? [
                    'id' => $item->reviewer->id,
                    'name' => $item->reviewer->name,
                ]
                : null,
        ];
    }
}
