<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Deliveries\DeliveryStoreRequest;
use App\Http\Requests\Deliveries\DeliveryAssignRequest;
use App\Http\Requests\Deliveries\DeliveryStatusRequest;
use App\Models\{Order, Delivery};
use App\Services\DeliveryService;
use Illuminate\Http\Request;

class DeliveryController extends Controller
{
    public function __construct(private DeliveryService $svc)
    {
        $this->middleware('auth:sanctum');
    }

    public function index(Request $request)
    {
        $this->authorize('viewAny', Delivery::class);

        $user = $request->user();
        $q = Delivery::query()
            ->with(['courier:id,name', 'order:id,branch_id,number'])
            ->latest('created_at');

        // Filter umum
        if ($status = $request->query('status')) {
            $q->where('status', $status);
        }
        if ($courierId = $request->query('courier_id')) {
            $q->where('assigned_to', $courierId);
        }
        if ($term = trim((string) $request->query('q', ''))) {
            $q->where(function ($w) use ($term) {
                $w->where('id', 'like', "%{$term}%")
                    ->orWhere('order_id', 'like', "%{$term}%")
                    ->orWhereHas('order', fn($oq) => $oq->where('number', 'like', "%{$term}%"));
            });
        }

        // Scope cabang & peran (pola sama seperti controller lain yang Anda pakai)
        $branchId = $this->branchScopeFor($request); // lihat helper di bawah
        if ($branchId) {
            $q->whereHas('order', fn($oq) => $oq->where('branch_id', $branchId));
        }

        // Kurir hanya lihat yang ditugaskan
        if ($user->hasRole('Kurir')) {
            $q->where('assigned_to', $user->id);
        }

        $per = max(1, min(200, (int) $request->query('per_page', 50)));
        $page = $q->paginate($per);

        return response()->json([
            'data' => $page->items(),
            'meta' => [
                'current_page' => $page->currentPage(),
                'per_page' => $page->perPage(),
                'total' => $page->total(),
                'last_page' => $page->lastPage(),
            ],
            'message' => 'OK',
            'errors' => null,
        ]);
    }

    public function show(Delivery $delivery)
    {
        $this->authorize('view', $delivery);
        return response()->json([
            'data' => $delivery->load(['courier:id,name', 'events']),
            'meta' => null,
            'message' => 'OK',
            'errors' => null,
        ]);
    }

    public function store(DeliveryStoreRequest $request)
    {
        $payload = $request->validated();
        $order = Order::query()->with('branch')->findOrFail($payload['order_id']);

        $this->authorize('create', Delivery::class);

        $delivery = $this->svc->create($order, $payload, $request->user());
        $res = $this->svc->autoAssign($order->getKey());

        return response()->json([
            'data' => [
                'delivery' => $res['delivery'],
            ],
            'meta' => ['idempotent' => $res['idempotent']],
            'message' => $res['idempotent'] ? 'Created (already assigned)' : 'Created & auto-assigned',
            'errors' => null,
        ], 201);
    }

    public function assign(DeliveryAssignRequest $request, Delivery $delivery)
    {
        $delivery->loadMissing('order');
        $this->authorize('assignCourier', $delivery);

        $next = $this->svc->assignManual($delivery, (int) $request->validated()['courier_id'], $request->user());

        return response()->json([
            'data' => $next,
            'meta' => [],
            'message' => 'Courier assigned',
            'errors' => null,
        ]);
    }

    public function updateStatus(DeliveryStatusRequest $request, Delivery $delivery)
    {
        $delivery->loadMissing('order');
        $this->authorize('updateStatus', $delivery);

        $f = $request->file('photo');
        $next = $this->svc->updateStatus(
            $delivery,
            $request->validated()['status'],
            $f,
            $request->validated()['note'] ?? null,
            $request->user()
        );

        return response()->json([
            'data' => $next,
            'meta' => [],
            'message' => 'Status updated',
            'errors' => null,
        ]);
    }

    private function branchScopeFor(Request $request): ?int
    {
        $u = $request->user();
        if ($u->hasRole('Superadmin')) {
            $bid = $request->query('branch_id');
            return $bid ? (int) $bid : null;
        }
        return (int) ($u->branch_id ?? 0) ?: null;
    }
}
