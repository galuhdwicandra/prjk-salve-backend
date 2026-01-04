<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderStoreRequest;
use App\Http\Requests\OrderUpdateRequest;
use App\Http\Requests\OrderStatusRequest;
use App\Models\Order;
use App\Services\OrderService;
use App\Services\LoyaltyService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\URL;

class OrderController extends Controller
{
    public function __construct(private OrderService $svc)
    {
        $this->middleware('auth:sanctum');
    }

    // GET /orders
    public function index(Request $request)
    {
        $this->authorize('viewAny', Order::class);

        $me = $request->user();
        $q = Order::query()
            ->with(['customer', 'items.service', 'receivable'])
            ->withCount('payments');

        // ===== (1) Sorting yang fleksibel =====
        $sortBy  = in_array($request->query('sort_by'), ['created_at', 'received_at', 'ready_at'])
            ? $request->query('sort_by') : 'created_at';
        $sortDir = strtolower((string) $request->query('sort_dir')) === 'asc' ? 'asc' : 'desc';
        $q->orderBy($sortBy, $sortDir);

        // ===== (2) Scope cabang =====
        if ($me->hasRole('Superadmin')) {
            if ($branchId = (string) $request->query('branch_id')) {
                $q->where('branch_id', $branchId);
            }
        } else if ($me->branch_id) {
            $q->where('branch_id', $me->branch_id);
        }

        // ===== (3) Pencarian cepat =====
        if ($s = $request->query('q')) {
            $q->where(function ($w) use ($s) {
                $w->where('number', 'like', "%{$s}%")
                    ->orWhere('notes', 'like', "%{$s}%");
            });
        }
        if ($st = $request->query('status')) {
            $q->where('status', $st);
        }

        // ===== (4) Filter tanggal existing (created_at) — tetap dipertahankan =====
        if ($from = $request->query('from')) {
            $q->whereDate('created_at', '>=', $from);
        }
        if ($to = $request->query('to')) {
            $q->whereDate('created_at', '<=', $to);
        }

        // ===== (5) Filter tanggal baru: received_at =====
        if ($rf = $request->query('received_from')) {
            $q->whereDate('received_at', '>=', $rf);
        }
        if ($rt = $request->query('received_to')) {
            $q->whereDate('received_at', '<=', $rt);
        }

        // ===== (6) Filter tanggal baru: ready_at =====
        if ($yf = $request->query('ready_from')) {
            $q->whereDate('ready_at', '>=', $yf);
        }
        if ($yt = $request->query('ready_to')) {
            $q->whereDate('ready_at', '<=', $yt);
        }

        $per  = (int) max(1, min(100, (int) $request->query('per_page', 10)));
        $page = $q->paginate($per);

        return response()->json([
            'data' => $page->items(),
            'meta' => [
                'current_page' => $page->currentPage(),
                'per_page'     => $page->perPage(),
                'total'        => $page->total(),
                'last_page'    => $page->lastPage(),
                'sort_by'      => $sortBy,
                'sort_dir'     => $sortDir,
            ],
            'message' => 'OK',
            'errors'  => null,
        ]);
    }


    public function show(Order $order)
    {
        $this->authorize('view', $order);

        return response()->json([
            'data' => $order->load(['customer', 'items.service', 'photos', 'receivable']),
            'meta' => [],
            'message' => 'OK',
            'errors' => null,
        ]);
    }

    // POST /orders
    public function store(OrderStoreRequest $request)
    {
        $this->authorize('create', Order::class);
        $payload = $request->validated();
        $me = $request->user();
        // Non-Superadmin SELALU dipaksa ke cabang user (abaikan branch_id dari FE)
        if (! $me->hasRole('Superadmin')) {
            if (! $me->branch_id) {
                abort(422, 'Akun Anda belum terikat ke cabang.');
            }
            $payload['branch_id'] = (string) $me->branch_id;
        }

        // (Opsional, tapi disarankan) Customer harus di cabang yang sama
        if (! empty($payload['customer_id'])) {
            $customerId = (string) ($payload['customer_id'] ?? '');
            $branchId   = (string) $payload['branch_id'];
            $custOk = \App\Models\Customer::query()
                ->whereKey($customerId)
                ->where('branch_id', $branchId)
                ->exists();
            if (! $custOk) {
                abort(422, 'Customer tidak berada di cabang ini.');
            }
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
        if (!$request->hasValidSignature()) {
            $this->authorize('view', $order);
        }

        $order->load([
            'items.service:id,name',
            'branch:id,name,address',
        ]);

        $loy = null;
        if ($order->customer_id) {
            /** @var \App\Services\LoyaltyService $loySvc */
            $loySvc = app(LoyaltyService::class);
            $acc    = $loySvc->getOrCreateAccount(
                (string) $order->customer_id,
                (string) $order->branch_id
            );
            $cycle   = LoyaltyService::CYCLE;
            $stamps  = (int) $acc->stamps;
            $next    = ($stamps % $cycle) + 1;
            $target25  = 5;
            $target100 = 10;
            // sisa transaksi (0 artinya reward terjadi pada transaksi ini)
            $sisa25   = ($target25  - $next + $cycle) % $cycle;
            $sisa100  = ($target100 - $next + $cycle) % $cycle;
            $loy = [
                'stamps'  => $stamps,
                'cycle'   => $cycle,
                'next'    => $next,
                'sisa25'  => $sisa25,
                'sisa100' => $sisa100,
            ];
        }

        $html = view('orders.receipt', [
            'order'     => $order,
            'branch'    => $order->getRelation('branch'),
            'printedAt' => now(),
            'loy'       => $loy,
        ])->render();

        return new Response($html, 200, ['Content-Type' => 'text/html; charset=UTF-8']);
    }

    // POST /orders/{order}/share-link
    public function shareLink(Request $request, Order $order): JsonResponse
    {
        // Staff yang berhak melihat order juga berhak membuat link struk
        $this->authorize('view', $order);

        // Buat signed URL ke route publik: /r/receipt/{order}
        $shareUrl = URL::temporarySignedRoute(
            'public.receipts.show',
            now()->addMinutes(120),
            ['order' => (string) $order->getKey()]
        );

        return response()->json([
            'data' => [
                'share_url' => $shareUrl,
                'expires_in_minutes' => 120,
            ],
            'meta' => (object)[],
            'message' => 'OK',
            'errors' => null,
        ]);
    }

    // POST /orders/{order}/status
    public function transitionStatus(OrderStatusRequest $request, Order $order)
    {
        $this->authorize('transitionStatus', $order);
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
