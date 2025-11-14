<?php

namespace App\Services;

use App\Models\{Delivery, DeliveryEvent, Order, User};
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DeliveryService
{
    private const TERMINAL = ['COMPLETED', 'FAILED', 'CANCELLED'];

    public function create(Order $order, array $data, User $actor): Delivery
    {
        return DB::transaction(function () use ($order, $data) {
            /** @var Delivery $d */
            $d = new Delivery([
                'order_id' => $order->getKey(),
                'type' => (string) $data['type'],
                'zone_id' => $data['zone_id'] ?? null,
                'fee' => (float) ($data['fee'] ?? 0),
                'status' => 'CREATED',
                'auto_assigned' => false,
            ]);
            $d->save();

            DeliveryEvent::query()->create([
                'id' => (string) Str::uuid(),
                'delivery_id' => $d->getKey(),
                'status' => 'CREATED',
                'note' => 'Delivery created',
            ]);

            return $d->fresh(['order', 'courier', 'events']);
        });
    }

    public function autoAssign(string $orderId): array
    {
        return DB::transaction(function () use ($orderId) {
            /** @var Delivery $delivery */
            $delivery = Delivery::query()->where('order_id', $orderId)->latest('created_at')->firstOrFail();
            $order = $delivery->order()->firstOrFail();

            if ($delivery->assigned_to && !in_array($delivery->status, self::TERMINAL, true)) {
                return ['delivery' => $delivery->fresh(['courier']), 'idempotent' => true];
            }

            $pool = User::role('Kurir')
                ->where('is_active', 1)
                ->where('branch_id', $order->branch_id)
                ->get(['id']);

            if ($pool->isEmpty()) {
                return ['delivery' => $delivery, 'idempotent' => true];
            }

            $counts = DB::table('deliveries')
                ->select('assigned_to', DB::raw('COUNT(*) as total'))
                ->whereNotIn('status', self::TERMINAL)
                ->whereIn('assigned_to', $pool->pluck('id')->all())
                ->groupBy('assigned_to')
                ->pluck('total', 'assigned_to');

            // pilih kurir dengan beban paling kecil; jika tie → id terkecil
            $chosen = $pool
                ->map(fn($u) => ['id' => (int) $u->id, 'load' => (int) ($counts[$u->id] ?? 0)])
                ->sortBy(['load', 'id'])
                ->first();

            $delivery->forceFill([
                'assigned_to' => $chosen['id'],
                'auto_assigned' => true,
                'status' => 'ASSIGNED',
            ])->save();

            DeliveryEvent::query()->create([
                'id' => (string) Str::uuid(),
                'delivery_id' => $delivery->getKey(),
                'status' => 'ASSIGNED',
                'note' => 'Auto-assigned courier #' . $chosen['id'],
            ]);

            return ['delivery' => $delivery->fresh(['courier']), 'idempotent' => false];
        });
    }

    public function assignManual(Delivery $delivery, int $courierId, User $actor): Delivery
    {
        return DB::transaction(function () use ($delivery, $courierId) {
            $delivery->forceFill([
                'assigned_to' => $courierId,
                'auto_assigned' => false,
                'status' => 'ASSIGNED',
            ])->save();

            DeliveryEvent::query()->create([
                'id' => (string) Str::uuid(),
                'delivery_id' => $delivery->getKey(),
                'status' => 'ASSIGNED',
                'note' => 'Manually assigned courier #' . $courierId,
            ]);

            return $delivery->fresh(['courier']);
        });
    }

    public function updateStatus(Delivery $delivery, string $status, ?UploadedFile $photo, ?string $note, User $actor): Delivery
    {
        return DB::transaction(function () use ($delivery, $status, $photo, $note) {
            $delivery->forceFill(['status' => $status])->save();

            if ($photo && $status === 'HANDOVER') {
                $dir = "uploads/deliveries/{$delivery->getKey()}/{$status}";
                $p = $photo->store($dir, 'public');
                $delivery->forceFill(['handover_photo' => "storage/{$p}"])->save();
            }

            DeliveryEvent::query()->create([
                'id' => (string) Str::uuid(),
                'delivery_id' => $delivery->getKey(),
                'status' => $status,
                'note' => $note,
            ]);

            return $delivery->fresh(['courier', 'events']);
        });
    }
}
