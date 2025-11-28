<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderStoreRequest;
use App\Http\Requests\OrderUpdateRequest;
use App\Http\Requests\OrderStatusRequest;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Illuminate\Http\Response;

class OrderController extends Controller
{
    public function __construct(private OrderService $svc)
    {
    }

    // GET /orders
    public function index(Request $request)
    {
        $this->authorize('viewAny', Order::class);

        $me = $request->user();
        $q = Order::query()
            ->with(['customer', 'items.service'])
            ->orderByDesc('created_at');

        // scope cabang
        if ($me->hasRole('Superadmin')) {
            if ($branchId = (string) $request->query('branch_id')) {
                $q->where('branch_id', $branchId);
            }
        } else if ($me->branch_id) {
            $q->where('branch_id', $me->branch_id);
        }

        if ($s = $request->query('q')) {
            $q->where(function ($w) use ($s) {
                $w->where('number', 'like', "%{$s}%")
                    ->orWhere('notes', 'like', "%{$s}%");
            });
        }
        if ($st = $request->query('status')) {
            $q->where('status', $st);
        }

        $per = (int) $request->query('per_page', 10);
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

    public function show(Order $order)
    {
        $this->authorize('view', $order);

        return response()->json([
            'data' => $order->load(['customer', 'items.service', 'photos']),
            'meta' => [],
            'message' => 'OK',
            'errors' => null,
        ]);
    }

    // POST /orders
    public function store(OrderStoreRequest $request)
    {
        $payload = $request->validated();

        // Admin Cabang/Kasir: fallback branch ke cabang aktor
        if (empty($payload['branch_id'])) {
            $payload['branch_id'] = $request->user()->branch_id;
        }

        $order = $this->svc->createDraft($payload, $request->user())
            ->load(['customer', 'items.service']); // optional: konsisten dengan show()

        return response()->json([
            'data' => $order,
            'meta' => [],
            'message' => 'Created',
            'errors' => null,
        ], 201);
    }

    // PUT /orders/{order}
    public function update(OrderUpdateRequest $request, Order $order)
    {
        $this->authorize('update', $order);

        $order = $this->svc->update($order, $request->validated(), $request->user());

        return response()->json([
            'data' => $order,
            'meta' => [],
            'message' => 'Updated',
            'errors' => null,
        ]);
    }

    public function receipt(Request $request, Order $order)
    {
        $this->authorize('view', $order);

        $order->load([
            'items.service:id,name',
            'branch:id,name,address',
        ]);

        $html = view('orders.receipt', [
            'order' => $order,
            'branch' => $order->getRelation('branch'),
            'printedAt' => now(),
        ])->render();

        return new Response($html, 200, ['Content-Type' => 'text/html; charset=UTF-8']);
    }

    // POST /orders/{order}/status
    public function transitionStatus(OrderStatusRequest $request, Order $order)
    {
        $order = $this->svc->transition($order, $request->validated()['next'], $request->user());

        return response()->json([
            'data' => [
                'id' => (string) $order->getKey(),
                'status' => (string) $order->getAttribute('status'),
            ],
            'meta' => [],
            'message' => 'Status updated',
            'errors' => null,
        ]);
    }
}
