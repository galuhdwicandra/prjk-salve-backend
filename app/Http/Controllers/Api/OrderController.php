<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderStatusRequest;
use App\Http\Requests\OrderStoreRequest;
use App\Http\Requests\OrderUpdateRequest;
use App\Models\Order;
use App\Services\LoyaltyService;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

// use Illuminate\Support\Facades\URL;

class OrderController extends Controller
{
    public function __construct(private OrderService $svc)
    {
        $this->middleware('auth:sanctum')->except(['receipt']);
    }

    // GET /orders
    public function index(Request $request)
    {
        $this->authorize('viewAny', Order::class);

        $me = $request->user();
        $q  = Order::query()
            ->with(['customer', 'items.service', 'receivable'])
            ->withCount('payments')
            ->addSelect([
                'latest_payment_method' => \App\Models\Payment::query()
                    ->select('method')
                    ->whereColumn('payments.order_id', 'orders.id')
                    ->orderByDesc('created_at')
                    ->limit(1),
            ]);

        // ===== (1) Sorting yang fleksibel =====
        $sortBy = in_array($request->query('sort_by'), ['created_at', 'received_at', 'ready_at'])
            ? $request->query('sort_by')
            : 'created_at';

        $sortDir = strtolower((string) $request->query('sort_dir')) === 'asc' ? 'asc' : 'desc';

        $q->orderBy($sortBy, $sortDir);

        // ===== (2) Scope cabang =====
        if ($me->hasRole('Superadmin')) {
            if ($branchId = (string) $request->query('branch_id')) {
                $q->where('branch_id', $branchId);
            }
        } elseif ($me->branch_id) {
            $q->where('branch_id', $me->branch_id);
        }

        // ===== (3) Pencarian cepat diperluas =====
        if ($s = trim((string) $request->query('q'))) {
            $q->where(function ($w) use ($s) {
                $w->where('number', 'like', "%{$s}%")
                    ->orWhere('invoice_no', 'like', "%{$s}%")
                    ->orWhere('notes', 'like', "%{$s}%")
                    ->orWhereHas('customer', function ($cq) use ($s) {
                        $cq->where('name', 'like', "%{$s}%")
                            ->orWhere('whatsapp', 'like', "%{$s}%");
                    });
            });
        }

        // ===== (4) Filter status order =====
        if ($st = $request->query('status')) {
            $q->where('status', $st);
        }

        // ===== (5) Filter status pembayaran =====
        if ($paymentStatus = $request->query('payment_status')) {
            $q->where('payment_status', $paymentStatus);
        }

        // ===== (6) Filter metode pembayaran =====
        if ($paymentMethod = $request->query('payment_method')) {
            $q->whereHas('payments', function ($pq) use ($paymentMethod) {
                $pq->where('method', $paymentMethod);
            });
        }

        // ===== (7) Filter tanggal order dibuat =====
        if ($from = $request->query('from')) {
            $q->whereDate('created_at', '>=', $from);
        }
        if ($to = $request->query('to')) {
            $q->whereDate('created_at', '<=', $to);
        }

        // ===== (8) Filter tanggal diterima =====
        if ($rf = $request->query('received_from')) {
            $q->whereDate('received_at', '>=', $rf);
        }
        if ($rt = $request->query('received_to')) {
            $q->whereDate('received_at', '<=', $rt);
        }

        // ===== (9) Filter tanggal jadi =====
        if ($yf = $request->query('ready_from')) {
            $q->whereDate('ready_at', '>=', $yf);
        }
        if ($yt = $request->query('ready_to')) {
            $q->whereDate('ready_at', '<=', $yt);
        }

        $allowedPerPages  = [10, 100, 200, 500];
        $requestedPerPage = (int) $request->query('per_page', 10);
        $per              = in_array($requestedPerPage, $allowedPerPages, true) ? $requestedPerPage : 10;

        $page = $q->paginate($per);

        return response()->json([
            'data'    => $page->items(),
            'meta'    => [
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
            'data'    => $order->load(['customer', 'items.service', 'photos', 'receivable']),
            'meta'    => [],
            'message' => 'OK',
            'errors'  => null,
        ]);
    }

    // POST /orders
    public function store(OrderStoreRequest $request)
    {
        $this->authorize('create', Order::class);
        $payload = $request->validated();
        $me      = $request->user();
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
            $custOk     = \App\Models\Customer::query()
                ->whereKey($customerId)
                ->where('branch_id', $branchId)
                ->exists();
            if (! $custOk) {
                abort(422, 'Customer tidak berada di cabang ini.');
            }
        }

        $fingerprint = sha1(json_encode([
            'user_id'     => (string) $me->id,
            'branch_id'   => (string) ($payload['branch_id'] ?? ''),
            'customer_id' => (string) ($payload['customer_id'] ?? ''),
            'items'       => collect($payload['items'] ?? [])
                ->map(fn($it) => [
                    'service_id' => (string) ($it['service_id'] ?? ''),
                    'qty'        => (float) ($it['qty'] ?? 0),
                    'note'       => (string) ($it['note'] ?? ''),
                ])
                ->values()
                ->all(),
            'discount'    => (float) ($payload['discount'] ?? 0),
            'notes'       => (string) ($payload['notes'] ?? ''),
            'received_at' => (string) ($payload['received_at'] ?? ''),
            'ready_at'    => (string) ($payload['ready_at'] ?? ''),
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));

        $cacheKey = 'order:create:' . $fingerprint;

        if (\Illuminate\Support\Facades\Cache::has($cacheKey)) {
            $existingOrderId = \Illuminate\Support\Facades\Cache::get($cacheKey);

            $existingOrder = Order::query()
                ->with(['customer', 'items.service'])
                ->find($existingOrderId);

            if ($existingOrder) {
                return response()->json([
                    'data'    => $existingOrder,
                    'meta'    => ['idempotent' => true],
                    'message' => 'Created',
                    'errors'  => null,
                ], 201);
            }
        }

        $order = $this->svc->createDraft($payload, $request->user())
            ->load(['customer', 'items.service']); // optional: konsisten dengan show()

        \Illuminate\Support\Facades\Cache::put(
            $cacheKey,
            (string) $order->getKey(),
            now()->addSeconds(15)
        );

        return response()->json([
            'data'    => $order,
            'meta'    => [],
            'message' => 'Created',
            'errors'  => null,
        ], 201);
    }

    // PUT /orders/{order}
    public function update(OrderUpdateRequest $request, Order $order)
    {
        $this->authorize('update', $order);

        $order = $this->svc->update($order, $request->validated(), $request->user());

        return response()->json([
            'data'    => $order,
            'meta'    => [],
            'message' => 'Updated',
            'errors'  => null,
        ]);
    }

    // DELETE /orders/{order}
    public function destroy(Order $order)
    {
        $this->authorize('delete', $order);

        DB::transaction(function () use ($order) {
            $order->delete();
        });

        return response()->json([
            'data'    => null,
            'meta'    => [],
            'message' => 'Deleted',
            'errors'  => null,
        ]);
    }

    public function receipt(Request $request, Order $order)
    {
        Log::info('RECEIPT_PUBLIC_ACCESS', [
            'order_id' => (string) $order->getKey(),
            'full_url' => $request->fullUrl(),
            'url'      => $request->url(),
            'path'     => $request->path(),
            'method'   => $request->method(),
            'query'    => $request->query(),
            'app_url'  => config('app.url'),
            'host'     => $request->getHost(),
            'scheme'   => $request->getScheme(),
        ]);

        $order->load([
            'items.service:id,name',
            'branch:id,name,address',
            'photos',
            'payments' => fn($q) => $q->orderBy('paid_at')->orderBy('created_at'),
        ]);

        $loy = null;
        if ($order->customer_id) {
            /** @var \App\Services\LoyaltyService $loySvc */
            $loySvc = app(LoyaltyService::class);
            $acc    = $loySvc->getOrCreateAccount(
                (string) $order->customer_id,
                (string) $order->branch_id
            );
            $cycle     = LoyaltyService::CYCLE;
            $stamps    = (int) $acc->stamps;
            $next      = ($stamps % $cycle) + 1;
            $target25  = 5;
            $target100 = 10;
            $sisa25    = ($target25 - $next + $cycle) % $cycle;
            $sisa100   = ($target100 - $next + $cycle) % $cycle;

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
        $this->authorize('view', $order);

        $shareUrl = route('public.receipts.show', [
            'order' => (string) $order->getKey(),
        ]);

        Log::info('RECEIPT_SHARE_LINK_PUBLIC', [
            'order_id'            => (string) $order->getKey(),
            'generated_share_url' => $shareUrl,
            'app_url'             => config('app.url'),
            'request_scheme'      => $request->getScheme(),
            'request_host'        => $request->getHost(),
            'request_uri'         => $request->getRequestUri(),
            'full_url'            => $request->fullUrl(),
        ]);

        return response()->json([
            'data'    => [
                'share_url'          => $shareUrl,
                'expires_in_minutes' => null,
            ],
            'meta'    => (object) [],
            'message' => 'OK',
            'errors'  => null,
        ]);
    }

    // POST /orders/{order}/status
    public function transitionStatus(OrderStatusRequest $request, Order $order)
    {
        $this->authorize('transitionStatus', $order);
        $order = $this->svc->transition($order, $request->validated()['next'], $request->user());

        return response()->json([
            'data'    => [
                'id'     => (string) $order->getKey(),
                'status' => (string) $order->getAttribute('status'),
            ],
            'meta'    => [],
            'message' => 'Status updated',
            'errors'  => null,
        ]);
    }
}
