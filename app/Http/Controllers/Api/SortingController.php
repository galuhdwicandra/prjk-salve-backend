<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\SortingService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SortingController extends Controller
{
    public function __construct(private SortingService $service)
    {
        $this->middleware('auth:sanctum');
    }

    public function index(Request $request, string $tab)
    {
        $branchIds = $request->user()->branchScopeIds($request->query('branch_id'));

        $query = $this->service->query($tab, $branchIds)
            ->with([
                'branch:id,name,code,type',
                'customer:id,name,whatsapp,address',
                'items:id,order_id,qty',
                'photos:id,order_id,kind,path',
                'productionTask:id,order_id,finished_date',
                'destinationContact:id,name',
            ]);

        if ($term = trim((string) $request->query('q', ''))) {
            $query->where(function ($w) use ($term) {
                $w->where('number', 'like', "%{$term}%")
                    ->orWhere('invoice_no', 'like', "%{$term}%")
                    ->orWhere('customer_name', 'like', "%{$term}%")
                    ->orWhereHas('customer', fn($c) => $c->where('name', 'like', "%{$term}%"));
            });
        }

        if ($photo = $request->query('photo')) {
            $query->when(
                $photo === 'yes',
                fn($q) => $q->whereHas('photos', fn($p) => $p->where('kind', 'before')),
                fn($q) => $q->whereDoesntHave('photos', fn($p) => $p->where('kind', 'before')),
            );
        }

        if ($destination = $request->query('processing_destination')) {
            $query->where('processing_destination', $destination);
        }

        if ($contactId = $request->query('contact_id')) {
            $query->where('destination_contact_id', $contactId);
        }

        $perPage = in_array((int) $request->query('per_page', 25), [25, 50, 100], true)
            ? (int) $request->query('per_page', 25)
            : 25;

        $page  = $query->orderByDesc('created_at')->paginate($perPage);
        $today = now('Asia/Jakarta')->toDateString();

        $items = collect($page->items())->map(function (Order $order) use ($today) {
            $readyAt = optional($order->ready_at)->format('Y-m-d');

            return [
                'id'                     => (string) $order->id,
                'number'                 => $order->number,
                'invoice_no'             => $order->invoice_no,
                'customer_name'          => $order->customer?->name ?? $order->customer_name,
                'customer_id'            => $order->customer_id ? (string) $order->customer_id : null,
                'customer_address'       => $order->customer?->address,
                'customer_whatsapp'      => $order->customer?->whatsapp,
                'branch'                 => $order->branch
                    ? [
                    'id'   => (string) $order->branch->id,
                    'name' => $order->branch->name,
                    'code' => $order->branch->code,
                    'type' => $order->branch->type,
                ]
                    : null,
                'qty'                    => (float) $order->items->sum('qty'),
                'processing_destination' => $order->processing_destination,
                'destination_contact'    => $order->destinationContact
                    ? ['id' => (string) $order->destinationContact->id, 'name' => $order->destinationContact->name]
                    : null,
                'has_before_photo'       => $order->photos->contains('kind', 'before'),
                'photos'                 => $order->photos->map(fn($p) => ['kind' => $p->kind, 'path' => $p->path])->values(),
                'received_at'            => optional($order->received_at)->format('Y-m-d'),
                'ready_at'               => $readyAt,
                'is_late'                => $readyAt !== null && $readyAt < $today && $order->status !== 'READY',
                'completed_at'           => $readyAt ?? optional($order->productionTask?->finished_date)->format('Y-m-d'),
                'due_amount'             => (float) $order->due_amount,
                'is_paid'                => (float) $order->due_amount <= 0,
                'created_at'             => optional($order->created_at)->toISOString(),
            ];
        })->all();

        return $this->ok($items, [
            'tab'          => $tab,
            'current_page' => $page->currentPage(),
            'per_page'     => $page->perPage(),
            'total'        => $page->total(),
            'last_page'    => $page->lastPage(),
        ]);
    }

    public function sort(Request $request)
    {
        $payload = $request->validate([
            'order_ids'   => ['required', 'array', 'min:1'],
            'order_ids.*' => ['uuid'],
            'destination' => ['required', Rule::in(['workshop', 'vendor'])],
        ]);

        $result = $this->service->bulkSort(
            $payload['order_ids'],
            $payload['destination'],
            $request->user(),
            $request->user()->branchScopeIds($request->query('branch_id')),
        );

        return $this->ok($result, [], count($result['sorted']) . ' order disortir.');
    }

    public function handover(Request $request)
    {
        $payload = $request->validate([
            'order_ids'   => ['required', 'array', 'min:1'],
            'order_ids.*' => ['uuid'],
            'photos'      => ['required', 'array', 'min:1'],
            'photos.*'    => ['image', 'max:5120'],
        ]);

        $result = $this->service->handover(
            $payload['order_ids'],
            $request->file('photos', []),
            $request->user(),
            $request->user()->branchScopeIds($request->input('branch_id')),
        );

        return $this->ok($result, [], count($result['handed_over']) . ' order diserahkan.');
    }

    public function courier(Request $request)
    {
        $payload = $request->validate([
            'order_ids'   => ['required', 'array', 'min:1'],
            'order_ids.*' => ['uuid'],
            'addresses'   => ['nullable', 'array'],
            'addresses.*' => ['string', 'max:255'],
        ]);

        $result = $this->service->courier(
            $payload['order_ids'],
            $payload['addresses'] ?? [],
            $request->user(),
            $request->user()->branchScopeIds($request->input('branch_id')),
        );

        return $this->ok($result, [], count($result['sent']) . ' order dikirim via kurir.');
    }
}
