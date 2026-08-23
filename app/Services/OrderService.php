<?php
namespace App\Services;

use App\Models\Delivery;
use App\Models\Order;
use App\Models\OrderAuditLog;
use App\Models\OrderItem;
use App\Models\OrderPhoto;
use App\Models\Receivable;
use App\Models\User;
use App\Services\DeliveryService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
     *   discount_type?:string|null,
     *   discount_value?:float|int|null,
     *   received_at?:string|\DateTimeInterface|null,
     *   ready_at?:string|\DateTimeInterface|null,
     *   items: array<int, array{service_id:string, qty:float|int, note?:string|null, discount_type?:string|null, discount_value?:float|int|null}>
     * } $data
     */
    public function createDraft(array $data, User $actor): Order
    {
        $branchId = (string) ($data['branch_id'] ?? $actor->branch_id);

        return DB::transaction(function () use ($data, $actor, $branchId) {
            // Generate dua nomor sekaligus (number & invoice_no)
            $ids    = $this->invoice->generatePair($branchId);
            $number = $ids['number'];

            $order = new Order([
                'id'                     => (string) Str::uuid(),
                'branch_id'              => $branchId,
                'customer_id'            => $data['customer_id'] ?? null,
                'customer_name'          => $data['customer_id']
                    ? optional(\App\Models\Customer::find($data['customer_id']))->name
                    : null,
                'number'                 => $ids['number'],
                'invoice_no'             => $ids['invoice_no'],
                'status'                 => 'QUEUE',
                'processing_destination' => $data['processing_destination'] ?? null,
                'destination_branch_id'  => $data['destination_branch_id'] ?? null,
                'subtotal'               => $this->dec(0),
                'discount'               => $this->dec(0),
                'discount_type'          => $data['discount_type'] ?? null,
                'discount_value'         => $this->dec($data['discount_value'] ?? 0),
                'grand_total'            => $this->dec(0),
                'paid_amount'            => $this->dec(0),
                'due_amount'             => $this->dec(0),
                'notes'                  => $data['notes'] ?? null,
                'created_by'             => $actor->id,
            ]);
            $order->received_at = $data['received_at'] ?? now(); // default: sekarang
            $order->ready_at    = $data['ready_at'] ?? null;
            $order->save();

            $subtotal          = 0.0;
            $itemDiscountTotal = 0.0;

            foreach ($data['items'] as $row) {
                $price        = $this->resolvePrice($row, $actor, $branchId);
                $qty          = (float) $row['qty'];
                $lineGross    = $price * $qty;
                $itemDiscount = $this->computeDiscountAmount(
                    $row['discount_type'] ?? null,
                    (float) ($row['discount_value'] ?? 0),
                    $lineGross
                );
                $lineTotal = $lineGross - $itemDiscount;

                $subtotal          += $lineGross;
                $itemDiscountTotal += $itemDiscount;

                OrderItem::query()->create([
                    'id'              => (string) Str::uuid(),
                    'order_id'        => $order->id,
                    'service_id'      => $row['service_id'],
                    'qty'             => $this->dec($qty),
                    'price'           => $this->dec($price),
                    'discount_type'   => $row['discount_type'] ?? null,
                    'discount_value'  => $this->dec($row['discount_value'] ?? 0),
                    'discount_amount' => $this->dec($itemDiscount),
                    'total'           => $this->dec($lineTotal),
                    'note'            => $row['note'] ?? null,
                ]);
            }

            $orderDiscount = $this->computeDiscountAmount(
                $data['discount_type'] ?? null,
                (float) ($data['discount_value'] ?? 0),
                max(0, $subtotal - $itemDiscountTotal)
            );
            $manualDiscount = $itemDiscountTotal + $orderDiscount;

            $order->subtotal = $this->dec($subtotal);
            $order->discount = $this->dec($manualDiscount);

            $this->loyalty->applyToOrder($order, $branchId);
            $order->save();

            $this->loyalty->finalizeEarn($order);

            if (Schema::hasTable('receivables')) {
                $grand = (float) $order->getAttribute('grand_total');
                if ($grand > 0) {
                    $existing = DB::table('receivables')
                        ->where('order_id', (string) $order->getKey())
                        ->first();

                    if (! $existing) {
                        DB::table('receivables')->insert([
                            'id'               => (string) Str::uuid(),
                            'order_id'         => (string) $order->getKey(),
                            'remaining_amount' => $grand,
                            'status'           => 'OPEN',
                            'due_date'         => $order->ready_at
                                ? Carbon::parse($order->ready_at)->toDateString()
                                : null,
                            'created_at'       => now(),
                            'updated_at'       => now(),
                        ]);
                    }
                }
            }

            return $order->load(['items.service', 'customer']);
        });
    }

/**
 * Simpan foto order.
 *
 * Jika $replaceExisting = true:
 * - file foto lama pada kategori yang sama dihapus dari storage
 * - record lama di tabel order_photos dihapus permanen
 *
 * @param array<int, array{kind:string, path:string}> $photos
 */
    public function attachPhotos(Order $order, array $photos, bool $replaceExisting = false): Order
    {
        DB::transaction(function () use ($order, $photos, $replaceExisting) {
            $kindsToReplace = collect($photos)
                ->pluck('kind')
                ->filter()
                ->unique()
                ->values()
                ->all();

            if ($replaceExisting && count($kindsToReplace) > 0) {
                $oldPhotos = OrderPhoto::query()
                    ->where('order_id', $order->id)
                    ->whereIn('kind', $kindsToReplace)
                    ->get(['id', 'path']);

                foreach ($oldPhotos as $oldPhoto) {
                    $relativePath = preg_replace('#^storage/#', '', (string) $oldPhoto->path);

                    if (is_string($relativePath) && $relativePath !== '') {
                        Storage::disk('public')->delete($relativePath);
                    }
                }

                OrderPhoto::query()
                    ->where('order_id', $order->id)
                    ->whereIn('kind', $kindsToReplace)
                    ->delete();
            }

            foreach ($photos as $p) {
                OrderPhoto::query()->create([
                    'id'       => (string) Str::uuid(),
                    'order_id' => $order->id,
                    'kind'     => $p['kind'],
                    'path'     => $p['path'],
                ]);
            }
        });

        return $order->load('photos');
    }

    /**
     * Transisi status: validasi transisi legal & simpan.
     */
    public function transition(Order $order, string $next, User $actor): Order
    {
        $validStatuses = [
            'QUEUE',
            'WASHING',
            'DRYING',
            'IRONING',
            'READY',
            'DELIVERING',
            'PICKED_UP',
            'CANCELED',
        ];

        if (! in_array($next, $validStatuses, true)) {
            abort(422, 'Invalid order status');
        }

        if ($order->status === $next) {
            return $order;
        }

        if ($order->status === 'PICKED_UP') {
            abort(422, 'Order yang sudah diambil tidak boleh diubah lagi.');
        }

        if ($order->status === 'CANCELED') {
            abort(422, 'Order yang dibatalkan tidak boleh diubah lagi.');
        }

        DB::transaction(function () use ($order, $next, $actor) {
            $from          = $order->status;
            $order->status = $next;
            // ===== Tambahan: set otomatis tgl selesai ketika READY =====
            if ($next === 'READY' && ! $order->ready_at) {
                $order->ready_at = now();
            }
            // ===========================================================
            $order->save();

            if ($next === 'CANCELED' && $order->customer_id) {
                $this->loyalty->adjustManual(
                    (string) $order->customer_id,
                    (string) $order->branch_id,
                    'subtract',
                    1,
                    'VOID ' . $order->number
                );
            }

            if (Schema::hasTable('receivables')) {
                DB::table('receivables')
                    ->where('order_id', (string) $order->getKey())
                    ->update([
                        'due_date'   => $order->ready_at
                            ? Carbon::parse($order->ready_at)->toDateString()
                            : null,
                        'updated_at' => now(),
                    ]);
            }

            if ($next === 'DELIVERING') {
                $exists = Delivery::query()
                    ->where('order_id', $order->id)
                    ->exists();

                if (! $exists) {
                    app(DeliveryService::class)->create(
                        $order,
                        [
                            'type'    => 'delivery',
                            'zone_id' => null,
                            'fee'     => 0,
                        ],
                        $actor
                    );
                }

                // Auto-assign kurir (round-robin per cabang) — method ini memang ada
                app(DeliveryService::class)->autoAssign($order->id);
            }

            OrderAuditLog::query()->create([
                'order_id' => (string) $order->getKey(),
                'action'   => $next === 'CANCELED' ? 'VOID' : 'STATUS_' . $next,
                'actor_id' => $actor->id,
                'snapshot' => ['from' => $from, 'to' => $next],
            ]);
        });

        return $order;
    }

    /**
     * Update isi order (re-hitung total).
     * @param array{
     *   customer_id?:string|null,
     *   notes?:string|null,
     *   discount_type?:string|null,
     *   discount_value?:float|int|null,
     *   items?: array<int, array{id?:string, service_id:string, qty:float|int, note?:string|null, discount_type?:string|null, discount_value?:float|int|null}>
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

            $currentLoyaltyReward   = (string) ($order->loyalty_reward ?: 'NONE');
            $currentLoyaltyDiscount = (float) ($order->loyalty_discount ?? 0);

            if (array_key_exists('customer_id', $data)) {
                $order->customer_id   = $data['customer_id'];
                $order->customer_name = $data['customer_id']
                    ? optional(\App\Models\Customer::find($data['customer_id']))->name
                    : null;
            }

            if (array_key_exists('notes', $data)) {
                $order->notes = $data['notes'];
            }

            if (array_key_exists('invoice_no', $data)) {
                $order->invoice_no = $data['invoice_no'];
            }

            if (array_key_exists('discount_type', $data)) {
                $order->discount_type = $data['discount_type'];
            }

            if (array_key_exists('discount_value', $data)) {
                $order->discount_value = $this->dec($data['discount_value']);
            }

            if (array_key_exists('processing_destination', $data)) {
                $order->processing_destination = $data['processing_destination'];
            }
            if (array_key_exists('destination_branch_id', $data)) {
                $order->destination_branch_id = $data['destination_branch_id'];
            }

            // ===== Tambahan: tanggal masuk & tanggal selesai =====
            if (array_key_exists('received_at', $data)) {
                $order->received_at = $data['received_at'];
            }
            if (array_key_exists('ready_at', $data)) {
                $order->ready_at = $data['ready_at'];
            }
            // =====================================================

            $recalcSubtotal          = null;
            $recalcItemDiscountTotal = null;
            if (! empty($data['items'])) {
                // strategi sederhana: hapus & tulis ulang
                $order->items()->delete();

                $subtotal          = 0.0;
                $itemDiscountTotal = 0.0;
                foreach ($data['items'] as $row) {
                    $price        = $this->resolvePrice($row, $actor, (string) $order->branch_id);
                    $qty          = (float) $row['qty'];
                    $lineGross    = $price * $qty;
                    $itemDiscount = $this->computeDiscountAmount(
                        $row['discount_type'] ?? null,
                        (float) ($row['discount_value'] ?? 0),
                        $lineGross
                    );
                    $lineTotal = $lineGross - $itemDiscount;

                    $subtotal          += $lineGross;
                    $itemDiscountTotal += $itemDiscount;

                    OrderItem::query()->create([
                        'id'              => (string) Str::uuid(),
                        'order_id'        => $order->id,
                        'service_id'      => $row['service_id'],
                        'qty'             => $this->dec($qty),
                        'price'           => $this->dec($price),
                        'discount_type'   => $row['discount_type'] ?? null,
                        'discount_value'  => $this->dec($row['discount_value'] ?? 0),
                        'discount_amount' => $this->dec($itemDiscount),
                        'total'           => $this->dec($lineTotal),
                        'note'            => $row['note'] ?? null,
                    ]);
                }
                $recalcSubtotal           = $subtotal;
                $recalcItemDiscountTotal  = $itemDiscountTotal;
            }

            $effectiveSubtotal = $recalcSubtotal !== null ? $recalcSubtotal : (float) $order->subtotal;

            $hasOrderDiscountInput = array_key_exists('discount_type', $data) || array_key_exists('discount_value', $data);

            if ($hasOrderDiscountInput) {
                $itemDiscountTotal = $recalcItemDiscountTotal !== null
                    ? $recalcItemDiscountTotal
                    : (float) $order->items()->sum('discount_amount');

                $manualDiscount = $itemDiscountTotal + $this->computeDiscountAmount(
                    $order->discount_type,
                    (float) ($order->discount_value ?? 0),
                    max(0, $effectiveSubtotal - $itemDiscountTotal)
                );
            } else {
                $manualDiscount = (float) max(0, (float) $order->discount - (float) $order->loyalty_discount);
            }

            $baseDiscount = min($manualDiscount, $effectiveSubtotal);

            $order->loyalty_reward   = $currentLoyaltyReward;
            $order->loyalty_discount = $this->dec($currentLoyaltyDiscount);
            $loyaltyDiscount         = $currentLoyaltyDiscount;

            $effectiveDiscount = $baseDiscount + $loyaltyDiscount;

            $grand = max(0, $effectiveSubtotal - $effectiveDiscount);
            $due   = max(0, $grand - (float) $order->paid_amount);

            $order->subtotal    = $this->dec($effectiveSubtotal);
            $order->discount    = $this->dec($effectiveDiscount);
            $order->grand_total = $this->dec($grand);
            $order->due_amount  = $this->dec($due);

            $paidAmount            = (float) $order->paid_amount;
            $order->payment_status = ($due <= 0 && $grand > 0)
                ? 'PAID'
                : ($paidAmount > 0 ? 'DP' : 'PENDING');

            if ($order->payment_status !== 'PAID') {
                $order->paid_at = null;
            }

            $order->save();

            if (Schema::hasTable('receivables')) {
                $existing = DB::table('receivables')
                    ->where('order_id', (string) $order->getKey())
                    ->first();

                $dueDate = $order->ready_at
                    ? \Illuminate\Support\Carbon::parse($order->ready_at)->toDateString()
                    : null;

                if ($grand <= 0) {
                    if ($existing) {
                        DB::table('receivables')
                            ->where('id', $existing->id)
                            ->update([
                                'remaining_amount' => 0,
                                'status'           => 'SETTLED',
                                'due_date'         => $dueDate,
                                'updated_at'       => now(),
                            ]);
                    }
                } else {
                    if ($existing) {
                        DB::table('receivables')
                            ->where('id', $existing->id)
                            ->update([
                                'remaining_amount' => $due,
                                'status'           => $due <= 0 ? 'SETTLED' : ($due < $grand ? 'PARTIAL' : 'OPEN'),
                                'due_date'         => $dueDate,
                                'updated_at'       => now(),
                            ]);
                    } else {
                        DB::table('receivables')->insert([
                            'id'               => (string) Str::uuid(),
                            'order_id'         => (string) $order->getKey(),
                            'remaining_amount' => $due,
                            'status'           => $due <= 0 ? 'SETTLED' : 'OPEN',
                            'due_date'         => $dueDate,
                            'created_at'       => now(),
                            'updated_at'       => now(),
                        ]);
                    }
                }
            }

            // TODO: audit('ORDER_UPDATE', ['order_id' => $order->id, 'actor' => $actor->id]);

            return $order->load(['items.service', 'customer', 'receivable']);
        });
    }

    public function applyManualLoyaltyCorrection(Order $order, string $reward, string $note, User $actor): Order
    {
        return DB::transaction(function () use ($order, $reward, $note, $actor) {
            /** @var Order $locked */
            $locked = Order::query()
                ->with(['items.service', 'customer', 'receivable'])
                ->lockForUpdate()
                ->findOrFail($order->id);

            if (! $actor->hasRole('Superadmin') && ! $actor->hasRole('Admin Cabang')) {
                abort(403, 'Hanya Superadmin atau Admin Cabang yang dapat melakukan koreksi loyalty.');
            }

            if ($actor->hasRole('Admin Cabang') && (string) $locked->branch_id !== (string) $actor->branch_id) {
                abort(403, 'Anda tidak memiliki akses ke order cabang ini.');
            }

            if (! $locked->customer_id) {
                abort(422, 'Order ini belum memiliki pelanggan, sehingga loyalty tidak dapat diterapkan.');
            }

            if (! in_array($reward, ['DISC25', 'FREE100'], true)) {
                abort(422, 'Jenis reward loyalty tidak valid.');
            }

            $subtotal = (float) $locked->subtotal;
            $paid     = (float) $locked->paid_amount;

            $loyaltyDiscount = $reward === 'FREE100'
                ? $subtotal
                : round($subtotal * 0.25, 2);

            $grandTotal = max(0, $subtotal - $loyaltyDiscount);
            $dueAmount  = max(0, $grandTotal - $paid);

            $locked->forceFill([
                'loyalty_reward'   => $reward,
                'loyalty_discount' => $this->dec($loyaltyDiscount),
                'discount'         => $this->dec($loyaltyDiscount),
                'grand_total'      => $this->dec($grandTotal),
                'due_amount'       => $this->dec($dueAmount),
                'payment_status'   => $dueAmount <= 0 ? 'PAID' : ($paid > 0 ? 'DP' : 'PENDING'),
            ])->save();

            $existing = Receivable::query()
                ->where('order_id', (string) $locked->getKey())
                ->lockForUpdate()
                ->first();

            if ($existing) {
                $existing->forceFill([
                    'remaining_amount' => $this->dec($dueAmount),
                    'status'           => $dueAmount <= 0 ? 'SETTLED' : ($dueAmount < $grandTotal ? 'PARTIAL' : 'OPEN'),
                    'updated_at'       => now(),
                ])->save();
            }

            DB::table('loyalty_logs')->insert([
                'id'          => (string) Str::uuid(),
                'order_id'    => null,
                'customer_id' => (string) $locked->customer_id,
                'branch_id'   => (string) $locked->branch_id,
                'action'      => $reward === 'FREE100' ? 'MANUAL_REWARD100' : 'MANUAL_REWARD25',
                'note'        => $note . ' | Order: ' . ($locked->invoice_no ?: $locked->number) . ' | Koreksi oleh: ' . $actor->name,
                'before'      => 0,
                'after'       => 0,
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);

            return $locked->fresh(['items.service', 'customer', 'receivable']);
        });
    }

    /**
     * Hitung nominal diskon dari type (NOMINAL|PERCENT) + value, dibatasi maksimal $base.
     */
    private function computeDiscountAmount(?string $type, float $value, float $base): float
    {
        $value  = max(0.0, $value);
        $base   = max(0.0, $base);
        $amount = $type === 'PERCENT'
            ? round($base * min($value, 100) / 100, 2)
            : $value;

        return max(0.0, min($amount, $base));
    }

    /**
     * Harga item: override klien hanya dipakai jika actor punya flag custom_price
     * dan mengirim nilai; selain itu selalu dihitung server via PricingService.
     */
    private function resolvePrice(array $row, User $actor, string $branchId): float
    {
        if ($actor->custom_price && isset($row['price']) && $row['price'] !== null && $row['price'] !== '') {
            return max(0.0, (float) $row['price']);
        }

        return (float) $this->pricing->getPrice($row['service_id'], $branchId);
    }

    /**
     * Format angka menjadi string desimal dengan presisi tetap (default 2).
     */
    private function dec(float | int | string | null $n, int $scale = 2): string
    {
        $v = is_numeric($n) ? (float) $n : 0.0;
        return number_format($v, $scale, '.', '');
    }
}
