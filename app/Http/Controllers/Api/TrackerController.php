<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\OrderTrackerService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TrackerController extends Controller
{
    public function __construct(private OrderTrackerService $tracker)
    {
    }

    public function search(Request $request): JsonResponse
    {
        $keyword   = trim((string) $request->query('q'));
        $branchIds = $request->user()->branchScopeIds($request->query('branch_id'));

        $query = Order::query()
            ->with(['branch:id,name', 'customer:id,name'])
            ->orderByDesc('created_at')
            ->limit(20);

        if ($branchIds !== null) {
            $query->whereIn('branch_id', $branchIds);
        }

        if ($keyword !== '') {
            $query->where(function ($where) use ($keyword) {
                $where->where('number', 'like', "%{$keyword}%")
                    ->orWhere('invoice_no', 'like', "%{$keyword}%")
                    ->orWhere('customer_name', 'like', "%{$keyword}%")
                    ->orWhereHas('customer', fn($customer) => $customer->where('name', 'like', "%{$keyword}%"));
            });
        }

        $rows = $query->get()->map(fn(Order $order): array=> [
            'id'         => (string) $order->getKey(),
            'number'     => $order->number,
            'invoice_no' => $order->invoice_no,
            'customer'   => $order->customer?->name ?? $order->customer_name,
            'branch'     => $order->branch?->name,
            'status'     => $order->status,
            'ready_at'   => $order->ready_at?->toDateString(),
            'created_at' => $order->created_at?->toIso8601String(),
        ]);

        return $this->envelope($rows->all());
    }

    public function show(Request $request, Order $order): JsonResponse
    {
        $this->assertBranchAccess($request, $order);

        $order->load([
            'branch:id,code,name,type,address,phone',
            'customer:id,name',
            'creator:id,name',
            'destinationBranch:id,name',
            'destinationContact:id,name',
            'deliveryNotes',
            'items',
            'photos',
            'payments',
            'productionTask',
        ]);

        return $this->envelope([
            'id'               => (string) $order->getKey(),
            'number'           => $order->number,
            'invoice_no'       => $order->invoice_no,
            'customer'         => $order->customer?->name ?? $order->customer_name,
            'branch'           => $order->branch?->name,
            'location'         => $order->destinationBranch?->name ?? $order->branch?->name,
            'status'           => $order->status,
            'stage'            => $this->tracker->stage($order),
            'position'         => $this->tracker->position($order),
            'received_at'      => $order->received_at?->toDateString(),
            'deadline'         => $order->ready_at?->toDateString(),
            'grand_total'      => (float) $order->grand_total,
            'outstanding'      => max(0.0, (float) $order->due_amount),
            'photos'           => $this->tracker->photos($order),
            'timeline'         => $this->tracker->timeline($order),
            'tracker_url'      => $this->tracker->publicUrl($order),
            'token_expires_at' => $order->tracker_token_expires_at?->toIso8601String(),
        ]);
    }

    public function issueLink(Request $request, Order $order): JsonResponse
    {
        $this->assertBranchAccess($request, $order);

        $order = $this->tracker->issueToken($order);

        return $this->envelope([
            'tracker_url'      => $this->tracker->publicUrl($order),
            'token_expires_at' => $order->tracker_token_expires_at?->toIso8601String(),
        ], 'Link tracker dibuat');
    }

    public function revokeLink(Request $request, Order $order): JsonResponse
    {
        $this->assertBranchAccess($request, $order);

        $this->tracker->revokeToken($order);

        return $this->envelope(null, 'Link tracker dicabut');
    }

    public function publicShow(string $token): JsonResponse
    {
        $order = Order::query()
            ->where('tracker_token', $token)
            ->where(fn($query) => $query
                    ->whereNull('tracker_token_expires_at')
                    ->orWhere('tracker_token_expires_at', '>', now()))
            ->with([
                'branch:id,code,name,type,address,phone',
                'customer:id,name',
                'items',
                'photos',
                'payments',
                'productionTask',
            ])
            ->first();

        if (! $order) {
            return response()->json([
                'data'    => null,
                'meta'    => [],
                'message' => 'Link tidak valid atau kedaluwarsa. Minta link terbaru ke Salve ya.',
                'errors'  => ['token' => ['invalid']],
            ], 404);
        }

        $stage = $this->tracker->stage($order);

        return $this->envelope([
            'number'              => $order->number,
            'customer_first_name' => Str::of((string) ($order->customer?->name ?? $order->customer_name))
                ->trim()
                ->explode(' ')
                ->first(),
            'stage'               => $stage,
            'estimate'            => $order->ready_at?->toDateString(),
            'qty'                 => (float) $order->items->sum('qty'),
            'outlet'              => [
                'name'    => $order->branch?->name,
                'address' => $order->branch?->address,
                'phone'   => $order->branch?->phone,
            ],
            'show_pickup'         => $stage['index'] >= 2,
            'outstanding'         => max(0.0, (float) $order->due_amount),
            'photos'              => $this->tracker->photos($order),
            'timeline'            => $this->tracker->timeline($order, false),
            'updated_at'          => now()->toIso8601String(),
        ]);
    }

    private function assertBranchAccess(Request $request, Order $order): void
    {
        $branchIds = $request->user()->branchScopeIds();

        if ($branchIds !== null && ! in_array((string) $order->branch_id, $branchIds, true)) {
            abort(404);
        }
    }

    private function envelope(mixed $data, string $message = 'OK'): JsonResponse
    {
        return response()->json([
            'data'    => $data,
            'meta'    => [],
            'message' => $message,
            'errors'  => null,
        ]);
    }
}
