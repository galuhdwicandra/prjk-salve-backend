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
use Illuminate\Support\Facades\Schema;

class OrderService
{
    public function __construct(
        private PricingService $pricing,
        private InvoiceService $invoice,
        private LoyaltyService $loyalty,
    ) {}

    /**
     * Create order (draft/queue) — hitung total dan harga per cabang.
     * @param array{
     *   branch_id?:string|null,
     *   customer_id?:string|null,
     *   notes?:string|null,
     *   received_at?:string|\DateTimeInterface|null,
     *   ready_at?:string|\DateTimeInterface|null,
     *   items: array<int, array{service_id:string, qty:float|int, note?:string|null}>
     * } $data
     */
    public function createDraft(array $data, User $actor): Order
    {
        $branchId = (string) ($data['branch_id'] ?? $actor->branch_id);

        return DB::transaction(function () use ($data, $actor, $branchId) {
            // Generate dua nomor sekaligus (number & invoice_no)
            $ids = $this->invoice->generatePair($branchId);
            $number = $ids['number'];

            $order = new Order([
                'id' => (string) Str::uuid(),
                'branch_id' => $branchId,
                'customer_id' => $data['customer_id'] ?? null,
                'number' => $ids['number'],
                'invoice_no' => $ids['invoice_no'],
                'status' => 'QUEUE',
                'subtotal' => $this->dec(0),
                'discount' => $this->dec(0),
                'grand_total' => $this->dec(0),
                'paid_amount' => $this->dec(0),
                'due_amount' => $this->dec(0),
                'notes' => $data['notes'] ?? null,
                'created_by' => $actor->id,
            ]);
            $order->received_at = $data['received_at'] ?? now(); // default: sekarang
            $order->ready_at    = $data['ready_at']    ?? null;
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
            $this->loyalty->applyToOrder($order, $branchId);
            $order->save();

            $this->loyalty->finalizeEarn($order);

            if (Schema::hasTable('receivables')) {
                $grand = (float) $order->getAttribute('grand_total');
                if ($grand > 0) {
                    $existing = DB::table('receivables')
                        ->where('order_id', (string) $order->getKey())
                        ->first();

                    if (!$existing) {
                        DB::table('receivables')->insert([
                            'id' => (string) Str::uuid(),
                            'order_id' => (string) $order->getKey(),
                            'remaining_amount' => $grand,
                            'status' => 'OPEN',
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
            }

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
            // ===== Tambahan: set otomatis tgl selesai ketika READY =====
            if ($next === 'READY' && !$order->ready_at) {
                $order->ready_at = now();
            }
            // ===========================================================
            $order->save();

            if ($next === 'DELIVERING') {
                $exists = Delivery::query()
                    ->where('order_id', $order->id)
                    ->exists();

                if (!$exists) {
                    app(DeliveryService::class)->create(
                        $order,
                        [
                            'type' => 'delivery',
                            'zone_id' => null,
                            'fee' => 0,
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
     *   discount?:float|int|null,
     *   items?: array<int, array{id?:string, service_id:string, qty:float|int, note?:string|null}>
     * } $data
     */
    public function update(Order $order, array $data, User $actor): Order
    {
        return DB::transaction(function () use ($order, $data, $actor) {
            // pastikan state fresh & terkunci selama perhitungan
            $order->refresh();
            if (in_array($order->status, ['DELIVERING', 'PICKED_UP', 'CANCELED'], true)) {
                abort(403, 'Order pada status ini terkunci dan tidak dapat diedit.');
            }

            if (array_key_exists('customer_id', $data)) {
                $order->customer_id = $data['customer_id'];
            }
            if (array_key_exists('notes', $data)) {
                $order->notes = $data['notes'];
            }

            if (array_key_exists('discount', $data)) {
                // normalisasi di Request; di sini cukup set
                $order->discount = $this->dec((float) max(0, (float) $data['discount']));
            }

            // ===== Tambahan: tanggal masuk & tanggal selesai =====
            if (array_key_exists('received_at', $data)) {
                $order->received_at = $data['received_at'];
            }
            if (array_key_exists('ready_at', $data)) {
                $order->ready_at = $data['ready_at'];
            }
            // =====================================================


            $recalcSubtotal = null;
            if (!empty($data['items'])) {
                // strategi sederhana: hapus & tulis ulang
                $order->items()->delete();

                $subtotal = 0.0;
                foreach ($data['items'] as $row) {
                    $price = (float) $this->pricing->getPrice($row['service_id'], $order->branch_id);
                    $qty   = (float) $row['qty'];
                    $line  = $price * $qty;
                    $subtotal += $line;

                    OrderItem::query()->create([
                        'id'        => (string) Str::uuid(),
                        'order_id'  => $order->id,
                        'service_id' => $row['service_id'],
                        'qty'       => $this->dec($qty),
                        'price'     => $this->dec($price),
                        'total'     => $this->dec($line),
                        'note'      => $row['note'] ?? null,
                    ]);
                }
                $recalcSubtotal = $subtotal;
            }

            $effectiveSubtotal = $recalcSubtotal !== null ? $recalcSubtotal : (float) $order->subtotal;
            $baseDiscount      = (float) max(0, (float) $order->discount);

            // Re-preview loyalti berdasarkan subtotal terbaru
            $preview = $this->loyalty->previewReward($order->customer_id, (string) $order->branch_id, $effectiveSubtotal);
            $order->loyalty_reward   = $preview['reward'];
            $order->loyalty_discount = $this->dec($preview['discount']);
            $effectiveDiscount       = $baseDiscount + (float) $preview['discount'];

            $grand = max(0, $effectiveSubtotal - $effectiveDiscount);
            $due   = max(0, $grand - (float) $order->paid_amount);

            $order->subtotal    = $this->dec($effectiveSubtotal);
            $order->discount    = $this->dec($effectiveDiscount);
            $order->grand_total = $this->dec($grand);
            $order->due_amount  = $this->dec($due);

            $order->save();

            if (Schema::hasTable('receivables')) {
                $existing = DB::table('receivables')
                    ->where('order_id', (string) $order->getKey())
                    ->first();

                if ($grand <= 0.0) {
                    if ($existing) {
                        DB::table('receivables')
                            ->where('id', $existing->id)
                            ->update([
                                'remaining_amount' => 0,
                                'status' => 'SETTLED',
                                'updated_at' => now(),
                            ]);
                    }
                } else {
                    if ($existing) {
                        DB::table('receivables')
                            ->where('id', $existing->id)
                            ->update([
                                'remaining_amount' => $due,
                                'status' => $due <= 0 ? 'SETTLED' : ($due < $grand ? 'PARTIAL' : 'OPEN'),
                                'updated_at' => now(),
                            ]);
                    } else {
                        // jika sebelumnya belum ada, buat baru saat kini grand_total > 0
                        DB::table('receivables')->insert([
                            'id' => (string) Str::uuid(),
                            'order_id' => (string) $order->getKey(),
                            'remaining_amount' => $due,
                            'status' => $due <= 0 ? 'SETTLED' : 'OPEN',
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
            }

            // TODO: audit('ORDER_UPDATE', ['order_id' => $order->id, 'actor' => $actor->id]);

            return $order->load(['items.service', 'customer', 'receivable']);
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
