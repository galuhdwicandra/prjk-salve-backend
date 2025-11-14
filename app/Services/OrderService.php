<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderPhoto;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Delivery;
use App\Services\DeliveryService;

class OrderService
{
    public function __construct(
        private PricingService $pricing,
        private InvoiceService $invoice,
    ) {
    }

    /**
     * Create order (draft/queue) — hitung total dan harga per cabang.
     * @param array{
     *   branch_id?:string|null,
     *   customer_id?:string|null,
     *   notes?:string|null,
     *   items: array<int, array{service_id:string, qty:float|int, note?:string|null}>
     * } $data
     */
    public function createDraft(array $data, User $actor): Order
    {
        $branchId = (string) ($data['branch_id'] ?? $actor->branch_id);

        return DB::transaction(function () use ($data, $actor, $branchId) {
            $number = $this->invoice->generate($branchId);

            $order = new Order([
                'id' => (string) Str::uuid(),
                'branch_id' => $branchId,
                'customer_id' => $data['customer_id'] ?? null,
                'number' => $number,
                'status' => 'QUEUE',
                // isi nilai decimal dengan string 2 desimal
                'subtotal' => $this->dec(0),
                'discount' => $this->dec(0),
                'grand_total' => $this->dec(0),
                'paid_amount' => $this->dec(0),
                'due_amount' => $this->dec(0),
                'notes' => $data['notes'] ?? null,
                'created_by' => $actor->id,
            ]);
            $order->save();

            $subtotal = 0.0;

            foreach ($data['items'] as $row) {
                $price = (float) $this->pricing->getPrice($row['service_id'], $branchId);
                $qty = (float) $row['qty'];
                $line = $price * $qty;
                $subtotal += $line;

                OrderItem::query()->create([
                    'id' => (string) Str::uuid(),
                    'order_id' => $order->id,
                    'service_id' => $row['service_id'],
                    'qty' => $this->dec($qty),
                    'price' => $this->dec($price),
                    'total' => $this->dec($line),
                    'note' => $row['note'] ?? null,
                ]);
            }

            $order->subtotal = $this->dec($subtotal);
            $order->discount = $this->dec(0);
            $order->grand_total = $this->dec(((float) $order->subtotal) - ((float) $order->discount));
            $order->due_amount = $this->dec(((float) $order->grand_total) - ((float) $order->paid_amount));
            $order->save();

            // TODO: audit('ORDER_CREATE', ['order_id' => $order->id, 'number' => $order->number]);

            return $order->load(['items.service', 'customer']);
        });
    }

    /**
     * Lampirkan foto before/after ke order.
     * @param array{kind:'before'|'after', path:string}[] $photos
     */
    public function attachPhotos(Order $order, array $photos): Order
    {
        DB::transaction(function () use ($order, $photos) {
            foreach ($photos as $p) {
                OrderPhoto::query()->create([
                    'id' => (string) Str::uuid(),
                    'order_id' => $order->id,
                    'kind' => $p['kind'],
                    'path' => $p['path'],
                ]);
            }
            // TODO: audit('ORDER_ATTACH_PHOTO', ['order_id' => $order->id, 'count' => count($photos)]);
        });

        return $order->load('photos');
    }

    /**
     * Transisi status: validasi transisi legal & simpan.
     */
    public function transition(Order $order, string $next, User $actor): Order
    {
        $allowed = $this->allowedNext($order->status);
        if (!in_array($next, $allowed, true)) {
            abort(422, 'Invalid status transition');
        }

        DB::transaction(function () use ($order, $next, $actor) {
            $from = $order->status;
            $order->status = $next;
            $order->save();

            // POLA 1 — server-driven: saat masuk DELIVERING, bikin delivery + auto-assign kurir
            if ($next === 'DELIVERING') {
                // Idempoten: jangan dobel kalau sudah ada
                $exists = Delivery::query()
                    ->where('order_id', $order->id)
                    ->exists();

                if (!$exists) {
                    // Panggil method yang BENAR (create), bukan createForOrder
                    app(DeliveryService::class)->create(
                        $order,
                        [
                            'type' => 'delivery', // sesuaikan jika enum/type kamu beda
                            'zone_id' => null,
                            'fee' => 0,          // kalau ada rule fee/zone, hitung di service
                        ],
                        $actor
                    );
                }

                // Auto-assign kurir (round-robin per cabang) — method ini memang ada
                app(DeliveryService::class)->autoAssign($order->id);
            }

            // TODO: audit('ORDER_STATUS', ['order_id' => $order->id, 'from' => $from, 'to' => $next, 'actor' => $actor->id]);
        });

        return $order;
    }

    /**
     * Update isi order (re-hitung total).
     * @param array{
     *   customer_id?:string|null,
     *   notes?:string|null,
     *   items?: array<int, array{id?:string, service_id:string, qty:float|int, note?:string|null}>
     * } $data
     */
    public function update(Order $order, array $data, User $actor): Order
    {
        return DB::transaction(function () use ($order, $data, $actor) {
            if (array_key_exists('customer_id', $data)) {
                $order->customer_id = $data['customer_id'];
            }
            if (array_key_exists('notes', $data)) {
                $order->notes = $data['notes'];
            }

            if (!empty($data['items'])) {
                // strategi sederhana: hapus & tulis ulang
                $order->items()->delete();

                $subtotal = 0.0;

                foreach ($data['items'] as $row) {
                    $price = (float) app(PricingService::class)->getPrice($row['service_id'], $order->branch_id);
                    $qty = (float) $row['qty'];
                    $line = $price * $qty;
                    $subtotal += $line;

                    OrderItem::query()->create([
                        'id' => (string) Str::uuid(),
                        'order_id' => $order->id,
                        'service_id' => $row['service_id'],
                        'qty' => $this->dec($qty),
                        'price' => $this->dec($price),
                        'total' => $this->dec($line),
                        'note' => $row['note'] ?? null,
                    ]);
                }

                $order->subtotal = $this->dec($subtotal);
                $order->grand_total = $this->dec(((float) $order->subtotal) - ((float) $order->discount));
                $order->due_amount = $this->dec(((float) $order->grand_total) - ((float) $order->paid_amount));
            }

            $order->save();

            // TODO: audit('ORDER_UPDATE', ['order_id' => $order->id, 'actor' => $actor->id]);

            return $order->load(['items.service', 'customer']);
        });
    }

    /**
     * Format angka menjadi string desimal dengan presisi tetap (default 2).
     */
    private function dec(float|int|string|null $n, int $scale = 2): string
    {
        $v = is_numeric($n) ? (float) $n : 0.0;
        return number_format($v, $scale, '.', '');
    }

    /**
     * State machine sederhana.
     * @return array<int,string>
     */
    private function allowedNext(string $current): array
    {
        return match ($current) {
            'QUEUE' => ['WASHING', 'CANCELED'],
            'WASHING' => ['DRYING', 'CANCELED'],
            'DRYING' => ['IRONING', 'READY', 'CANCELED'],
            'IRONING' => ['READY', 'CANCELED'],
            'READY' => ['DELIVERING', 'PICKED_UP', 'CANCELED'],
            'DELIVERING' => ['PICKED_UP', 'CANCELED'],
            default => [],
        };
    }
}
