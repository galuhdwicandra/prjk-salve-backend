<?php
namespace App\Services;

use App\Models\Branch;
use App\Models\Contact;
use App\Models\Customer;
use App\Models\Delivery;
use App\Models\DeliveryNote;
use App\Models\Order;
use App\Models\OrderAuditLog;
use App\Models\OrderPhoto;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class SortingService
{
    public const TABS = ['incoming', 'delivery-note', 'at-subcon', 'ready'];

    public function __construct(
        private DocumentNumberService $numbers,
        private OrderService $orders,
    ) {}

    public function query(string $tab, ?array $branchIds): Builder
    {
        $base = Order::query()
            ->where('status', '!=', 'CANCELED')
            ->when($branchIds !== null, fn(Builder $q) => $q->whereIn('branch_id', $branchIds));

        return match ($tab) {
            'incoming'      => $base
                ->where('status', 'QUEUE')
                ->whereNull('processing_destination'),

            'delivery-note' => $base
                ->whereNotNull('processing_destination')
                ->whereNull('destination_branch_id')
                ->whereDoesntHave('deliveryNotes', fn($q) => $q->where('kind', 'kirim')),

            'at-subcon'     => $base
                ->where('processing_destination', 'vendor')
                ->whereHas('deliveryNotes', fn($q) => $q->where('kind', 'kirim')->where('status', 'COMPLETED'))
                ->whereDoesntHave('deliveryNotes', fn($q) => $q->where('kind', 'ambil')),

            default         => $base->where('status', 'READY'),
        };
    }

    public function sort(Order $order, string $destination, User $user): Order
    {
        return DB::transaction(function () use ($order, $destination, $user) {
            $locked = Order::query()->whereKey($order->getKey())->lockForUpdate()->firstOrFail();

            if ($locked->status === 'CANCELED') {
                throw ValidationException::withMessages(['order_ids' => ['Order sudah dibatalkan.']]);
            }

            if ($locked->processing_destination !== null) {
                throw ValidationException::withMessages(['order_ids' => ['Order sudah disortir.']]);
            }

            if (! $locked->photos()->where('kind', 'before')->exists()) {
                throw ValidationException::withMessages(['order_ids' => ['Order belum memiliki foto before.']]);
            }

            $locked->processing_destination = $destination;

            if ($destination === 'workshop' && $locked->branch?->type === 'workshop') {
                $locked->destination_branch_id = $locked->branch_id;
            }

            $locked->save();

            $this->audit($locked, 'SORT', $user, [
                'processing_destination' => $destination,
                'destination_branch_id'  => $locked->destination_branch_id,
            ]);

            return $locked;
        });
    }

    public function bulkSort(array $orderIds, string $destination, User $user, ?array $branchIds): array
    {
        $orders = $this->scoped($orderIds, $branchIds);

        $sorted  = [];
        $skipped = $this->missing($orderIds, $orders);

        foreach ($orders as $order) {
            try {
                $this->sort($order, $destination, $user);
                $sorted[] = ['order_id' => (string) $order->getKey(), 'number' => $order->number];
            } catch (ValidationException $exception) {
                $skipped[] = [
                    'order_id' => (string) $order->getKey(),
                    'number'   => $order->number,
                    'reason'   => $exception->validator->errors()->first(),
                ];
            }
        }

        return ['sorted' => $sorted, 'skipped' => $skipped];
    }

    public function createShippingNote(
        array $orderIds,
        string $toType,
        string $destinationId,
        string $noteDate,
        User $user
    ): DeliveryNote {
        return DB::transaction(function () use ($orderIds, $toType, $destinationId, $noteDate, $user) {
            $orders = Order::query()->whereIn('id', $orderIds)->lockForUpdate()->get();

            if ($orders->count() !== count(array_unique($orderIds))) {
                throw ValidationException::withMessages(['order_ids' => ['Sebagian order tidak ditemukan.']]);
            }

            foreach ($orders as $order) {
                if ($order->status === 'CANCELED') {
                    throw ValidationException::withMessages([
                        'order_ids' => ["Order {$order->number} sudah dibatalkan."],
                    ]);
                }

                if ($order->processing_destination !== $toType) {
                    throw ValidationException::withMessages([
                        'order_ids' => ["Order {$order->number} tidak menuju tujuan yang sama."],
                    ]);
                }

                if ($order->deliveryNotes()->where('kind', 'kirim')->exists()) {
                    throw ValidationException::withMessages([
                        'order_ids' => ["Order {$order->number} sudah memiliki surat jalan."],
                    ]);
                }
            }

            $branch = Branch::query()->findOrFail((string) $orders->first()->branch_id);

            $note = DeliveryNote::query()->create([
                'number'        => $this->numbers->next($branch, 'suratjalan'),
                'kind'          => 'kirim',
                'note_date'     => $noteDate,
                'branch_id'     => $branch->id,
                'to_type'       => $toType,
                'to_branch_id'  => $toType === 'workshop' ? $destinationId : null,
                'to_contact_id' => $toType === 'vendor' ? $destinationId : null,
                'status'        => 'PENDING',
                'proofs'        => [],
                'created_by'    => $user->id,
            ]);

            $note->orders()->attach($orders->modelKeys());

            if ($toType === 'vendor') {
                Order::query()
                    ->whereIn('id', $orders->modelKeys())
                    ->update(['destination_contact_id' => $destinationId]);
            }

            foreach ($orders as $order) {
                $this->audit($order, 'DELIVERY_NOTE', $user, ['number' => $note->number, 'kind' => 'kirim']);
            }

            return $note->load($this->relations());
        });
    }

    public function createPickupNote(
        array $orderIds,
        string $workshopBranchId,
        string $noteDate,
        User $user
    ): DeliveryNote {
        return DB::transaction(function () use ($orderIds, $workshopBranchId, $noteDate, $user) {
            $orders = Order::query()->whereIn('id', $orderIds)->lockForUpdate()->get();

            if ($orders->count() !== count(array_unique($orderIds))) {
                throw ValidationException::withMessages(['order_ids' => ['Sebagian order tidak ditemukan.']]);
            }

            $contactIds = $orders->pluck('destination_contact_id')->filter()->unique();

            if ($contactIds->count() !== 1) {
                throw ValidationException::withMessages([
                    'order_ids' => ['Pilih order dari subcon yang sama saja.'],
                ]);
            }

            foreach ($orders as $order) {
                if ($order->status === 'CANCELED') {
                    throw ValidationException::withMessages([
                        'order_ids' => ["Order {$order->number} sudah dibatalkan."],
                    ]);
                }

                if ($order->deliveryNotes()->where('kind', 'ambil')->exists()) {
                    throw ValidationException::withMessages([
                        'order_ids' => ["Order {$order->number} sudah memiliki surat jalan ambil."],
                    ]);
                }
            }

            $branch = Branch::query()->findOrFail((string) $orders->first()->branch_id);

            $note = DeliveryNote::query()->create([
                'number'          => $this->numbers->next($branch, 'suratjalan'),
                'kind'            => 'ambil',
                'note_date'       => $noteDate,
                'branch_id'       => $branch->id,
                'to_type'         => 'workshop',
                'to_branch_id'    => $workshopBranchId,
                'from_contact_id' => (string) $contactIds->first(),
                'status'          => 'PENDING',
                'proofs'          => [],
                'created_by'      => $user->id,
            ]);

            $note->orders()->attach($orders->modelKeys());

            foreach ($orders as $order) {
                $this->audit($order, 'PICKUP_NOTE', $user, ['number' => $note->number]);
            }

            return $note->load($this->relations());
        });
    }

    public function complete(DeliveryNote $note, array $photos, User $user): DeliveryNote
    {
        return DB::transaction(function () use ($note, $photos, $user) {
            if ($note->kind !== 'kirim') {
                throw ValidationException::withMessages(['status' => ['Gunakan aksi Tiba di Workshop untuk surat jalan ambil.']]);
            }

            if ($note->status === 'COMPLETED') {
                throw ValidationException::withMessages(['status' => ['Surat jalan sudah selesai.']]);
            }

            $note->fill([
                'status'       => 'COMPLETED',
                'proofs'       => array_merge($note->proofs ?? [], ['sent' => $this->store($note, 'sent', $photos)]),
                'completed_by' => $user->id,
                'completed_at' => now(),
            ])->save();

            if ($note->to_type === 'workshop') {
                Order::query()
                    ->whereIn('id', $note->orders()->pluck('orders.id'))
                    ->update(['destination_branch_id' => $note->to_branch_id]);
            }

            foreach ($note->orders as $order) {
                $this->audit($order, 'SHIPPED', $user, ['number' => $note->number, 'to_type' => $note->to_type]);
            }

            return $note->fresh($this->relations());
        });
    }

    public function pick(DeliveryNote $note, array $photos, User $user): DeliveryNote
    {
        return DB::transaction(function () use ($note, $photos, $user) {
            if ($note->kind !== 'ambil') {
                throw ValidationException::withMessages(['status' => ['Surat jalan ini bukan surat jalan ambil.']]);
            }

            if ($note->status !== 'PENDING') {
                throw ValidationException::withMessages(['status' => ['Surat jalan sudah diambil.']]);
            }

            $note->fill([
                'status'    => 'PICKED',
                'proofs'    => array_merge($note->proofs ?? [], ['picked' => $this->store($note, 'picked', $photos)]),
                'picked_by' => $user->id,
                'picked_at' => now(),
            ])->save();

            foreach ($note->orders as $order) {
                $this->audit($order, 'SUBCON_PICKED', $user, ['number' => $note->number]);
            }

            return $note->fresh($this->relations());
        });
    }

    public function arrive(DeliveryNote $note, array $photos, User $user): DeliveryNote
    {
        return DB::transaction(function () use ($note, $photos, $user) {
            if ($note->kind !== 'ambil') {
                throw ValidationException::withMessages(['status' => ['Surat jalan ini bukan surat jalan ambil.']]);
            }

            if ($note->status !== 'PICKED') {
                throw ValidationException::withMessages(['status' => ['Barang belum diambil dari subcon.']]);
            }

            $note->fill([
                'status'       => 'COMPLETED',
                'proofs'       => array_merge($note->proofs ?? [], ['arrived' => $this->store($note, 'arrived', $photos)]),
                'completed_by' => $user->id,
                'completed_at' => now(),
            ])->save();

            Order::query()
                ->whereIn('id', $note->orders()->pluck('orders.id'))
                ->update([
                    'processing_destination' => 'workshop',
                    'destination_branch_id'  => $note->to_branch_id,
                    'destination_contact_id' => null,
                ]);

            foreach ($note->orders as $order) {
                $this->audit($order, 'SUBCON_ARRIVED', $user, [
                    'number'                => $note->number,
                    'destination_branch_id' => $note->to_branch_id,
                ]);
            }

            return $note->fresh($this->relations());
        });
    }

    public function handover(array $orderIds, array $photos, User $user, ?array $branchIds): array
    {
        $orders = $this->scoped($orderIds, $branchIds)->where('status', 'READY');

        $skipped = $this->missing($orderIds, $orders);
        $done    = [];

        $paths = $this->storeOrderPhotos($photos);

        foreach ($orders as $order) {
            if ((float) $order->due_amount > 0) {
                $skipped[] = [
                    'order_id' => (string) $order->getKey(),
                    'number'   => $order->number,
                    'reason'   => 'Pembayaran belum lunas.',
                ];

                continue;
            }

            DB::transaction(function () use ($order, $paths, $user) {
                foreach ($paths as $path) {
                    OrderPhoto::query()->create([
                        'order_id' => (string) $order->getKey(),
                        'kind'     => 'handover',
                        'path'     => $path,
                    ]);
                }

                $this->orders->transition($order, 'PICKED_UP', $user);
                $this->audit($order, 'HANDOVER', $user, ['mode' => 'pickup', 'photos' => count($paths)]);
            });

            $done[] = ['order_id' => (string) $order->getKey(), 'number' => $order->number];
        }

        return ['handed_over' => $done, 'skipped' => $skipped];
    }

    public function courier(array $orderIds, array $addresses, User $user, ?array $branchIds): array
    {
        $orders = $this->scoped($orderIds, $branchIds)->where('status', 'READY');

        $skipped = $this->missing($orderIds, $orders);
        $sent    = [];

        foreach ($addresses as $customerId => $address) {
            Customer::query()->whereKey($customerId)->update(['address' => $address]);
        }

        foreach ($orders as $order) {
            if ((float) $order->due_amount > 0) {
                $skipped[] = [
                    'order_id' => (string) $order->getKey(),
                    'number'   => $order->number,
                    'reason'   => 'Pembayaran belum lunas.',
                ];

                continue;
            }

            $order->loadMissing('customer');

            if (trim((string) $order->customer?->address) === '') {
                $skipped[] = [
                    'order_id'    => (string) $order->getKey(),
                    'number'      => $order->number,
                    'customer_id' => (string) $order->customer_id,
                    'reason'      => 'Alamat customer belum lengkap.',
                ];

                continue;
            }

            $number = DB::transaction(function () use ($order, $user) {
                $this->orders->transition($order, 'DELIVERING', $user);

                $delivery = Delivery::query()
                    ->where('order_id', (string) $order->getKey())
                    ->latest('created_at')
                    ->firstOrFail();

                if (! $delivery->number) {
                    $branch = Branch::query()->findOrFail((string) $order->branch_id);
                    $delivery->forceFill(['number' => $this->numbers->next($branch, 'delivery')])->save();
                }

                $this->audit($order, 'COURIER', $user, ['delivery_number' => $delivery->number]);

                return $delivery->number;
            });

            $sent[] = [
                'order_id'        => (string) $order->getKey(),
                'number'          => $order->number,
                'delivery_number' => $number,
            ];
        }

        return ['sent' => $sent, 'skipped' => $skipped];
    }

    public function relations(): array
    {
        return [
            'branch:id,name,code,type',
            'toBranch:id,name,code,type',
            'toContact:id,name,phone,address',
            'fromContact:id,name,phone,address',
            'creator:id,name',
            'orders:id,branch_id,customer_id,number,invoice_no,customer_name,ready_at',
            'orders.branch:id,name,code',
            'orders.customer:id,name',
            'orders.items:id,order_id,service_id,qty',
            'orders.items.service:id,name',
        ];
    }

    public function subconContactIds(): array
    {
        return Contact::query()
            ->where('is_active', true)
            ->whereHas('categories', fn($q) => $q->whereRaw('LOWER(contact_categories.name) = ?', ['subcon']))
            ->pluck('id')
            ->map('strval')
            ->all();
    }

    private function scoped(array $orderIds, ?array $branchIds)
    {
        return Order::query()
            ->with('customer:id,name,address')
            ->where('status', '!=', 'CANCELED')
            ->whereIn('id', $orderIds)
            ->when($branchIds !== null, fn(Builder $q) => $q->whereIn('branch_id', $branchIds))
            ->get();
    }

    private function missing(array $orderIds, $found): array
    {
        $foundIds = $found->modelKeys();

        return collect($orderIds)
            ->reject(fn($id) => in_array((string) $id, array_map('strval', $foundIds), true))
            ->map(fn($id) => [
                'order_id' => (string) $id,
                'number'   => null,
                'reason'   => 'Order tidak ditemukan, sudah void, atau di luar akses outlet Anda.',
            ])
            ->values()
            ->all();
    }

    /**
     * @param  array<int, UploadedFile>  $photos
     * @return array<int, string>
     */
    private function store(DeliveryNote $note, string $stage, array $photos): array
    {
        $paths = [];

        foreach ($photos as $photo) {
            $stored  = $photo->store("uploads/delivery-notes/{$note->getKey()}/{$stage}", 'public');
            $paths[] = "storage/{$stored}";
        }

        return $paths;
    }

    /**
     * @param  array<int, UploadedFile>  $photos
     * @return array<int, string>
     */
    private function storeOrderPhotos(array $photos): array
    {
        $paths = [];

        foreach ($photos as $photo) {
            $stored  = $photo->store('uploads/handovers/' . now('Asia/Jakarta')->format('Y/m'), 'public');
            $paths[] = "storage/{$stored}";
        }

        return $paths;
    }

    private function audit(Order $order, string $action, User $user, array $snapshot): void
    {
        OrderAuditLog::query()->create([
            'order_id' => (string) $order->getKey(),
            'action'   => $action,
            'actor_id' => $user->id,
            'snapshot' => $snapshot,
        ]);
    }
}
