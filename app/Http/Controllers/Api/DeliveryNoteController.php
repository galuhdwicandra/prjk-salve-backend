<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DeliveryNote;
use App\Services\SortingService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DeliveryNoteController extends Controller
{
    public function __construct(private SortingService $service)
    {
        $this->middleware('auth:sanctum');
    }

    public function index(Request $request)
    {
        $branchIds = $request->user()->branchScopeIds($request->query('branch_id'));

        $query = DeliveryNote::query()
            ->with($this->service->relations())
            ->withCount('orders')
            ->when($branchIds !== null, fn($q) => $q->whereIn('branch_id', $branchIds))
            ->when($request->query('kind'), fn($q, $kind) => $q->where('kind', $kind))
            ->when($request->query('status'), fn($q, $status) => $q->whereIn('status', explode(',', $status)))
            ->when(trim((string) $request->query('q', '')), fn($q, $term) => $q->where(function ($w) use ($term) {
                $w->where('number', 'like', "%{$term}%")
                    ->orWhereHas('toBranch', fn($b) => $b->where('name', 'like', "%{$term}%")->orWhere('code', 'like', "%{$term}%"))
                    ->orWhereHas('toContact', fn($c) => $c->where('name', 'like', "%{$term}%"));
            }))
            ->orderByDesc('note_date')
            ->orderByDesc('created_at');

        $page = $query->paginate(max(1, min(200, (int) $request->query('per_page', 50))));

        return $this->ok($page->items(), [
            'current_page' => $page->currentPage(),
            'per_page'     => $page->perPage(),
            'total'        => $page->total(),
            'last_page'    => $page->lastPage(),
        ]);
    }

    public function show(DeliveryNote $deliveryNote)
    {
        return $this->ok($deliveryNote->load($this->service->relations()));
    }

    public function store(Request $request)
    {
        $payload = $request->validate([
            'order_ids'      => ['required', 'array', 'min:1'],
            'order_ids.*'    => ['uuid'],
            'note_date'      => ['required', 'date'],
            'to_type'        => ['required', Rule::in(['workshop', 'vendor'])],
            'destination_id' => ['required', 'string'],
        ]);

        $this->assertDestination($payload['to_type'], $payload['destination_id']);

        $note = $this->service->createShippingNote(
            $payload['order_ids'],
            $payload['to_type'],
            $payload['destination_id'],
            $payload['note_date'],
            $request->user(),
        );

        return $this->ok($note, [], "Surat jalan {$note->number} dibuat.", 201);
    }

    public function storePickup(Request $request)
    {
        $payload = $request->validate([
            'order_ids'   => ['required', 'array', 'min:1'],
            'order_ids.*' => ['uuid'],
            'note_date'   => ['required', 'date'],
            'branch_id'   => ['required', 'uuid', Rule::exists('branches', 'id')->where('type', 'workshop')],
        ]);

        $note = $this->service->createPickupNote(
            $payload['order_ids'],
            $payload['branch_id'],
            $payload['note_date'],
            $request->user(),
        );

        return $this->ok($note, [], "Surat jalan ambil {$note->number} dibuat.", 201);
    }

    public function complete(Request $request, DeliveryNote $deliveryNote)
    {
        return $this->ok(
            $this->service->complete($deliveryNote, $this->photos($request), $request->user()),
            [],
            'Pengiriman selesai.',
        );
    }

    public function pick(Request $request, DeliveryNote $deliveryNote)
    {
        return $this->ok(
            $this->service->pick($deliveryNote, $this->photos($request), $request->user()),
            [],
            'Barang sudah diambil dari subcon.',
        );
    }

    public function arrive(Request $request, DeliveryNote $deliveryNote)
    {
        return $this->ok(
            $this->service->arrive($deliveryNote, $this->photos($request), $request->user()),
            [],
            'Barang tiba di workshop.',
        );
    }

    private function photos(Request $request): array
    {
        $request->validate([
            'photos'   => ['required', 'array', 'min:1'],
            'photos.*' => ['image', 'max:5120'],
        ]);

        return $request->file('photos', []);
    }

    private function assertDestination(string $toType, string $destinationId): void
    {
        if ($toType === 'workshop') {
            validator(
                ['destination_id' => $destinationId],
                ['destination_id' => [Rule::exists('branches', 'id')->where('type', 'workshop')]],
            )->validate();

            return;
        }

        validator(
            ['destination_id' => $destinationId],
            ['destination_id' => [Rule::in($this->service->subconContactIds())]],
        )->validate();
    }
}
