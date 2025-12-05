<?php

namespace App\Services;

use App\Models\LoyaltyAccount;
use App\Models\LoyaltyLog;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

final class LoyaltyService
{
    public const CYCLE = 10;

    public function getOrCreateAccount(string $customerId, string $branchId): LoyaltyAccount
    {
        return DB::transaction(function () use ($customerId, $branchId) {
            $acc = LoyaltyAccount::query()
                ->where('customer_id', $customerId)
                ->where('branch_id', $branchId)
                ->lockForUpdate()
                ->first();

            if (!$acc) {
                $acc = LoyaltyAccount::create([
                    'id' => (string) Str::uuid(),
                    'customer_id' => $customerId,
                    'branch_id' => $branchId,
                    'stamps' => 0,
                    'lifetime' => 0,
                ]);
            }
            return $acc;
        });
    }

    /** Hitung reward untuk order baru (tanpa mengubah state). */
    public function previewReward(?string $customerId, string $branchId, float $subtotal): array
    {
        if (!$customerId || $subtotal <= 0) {
            return ['reward' => 'NONE', 'discount' => 0.0];
        }
        $acc = $this->getOrCreateAccount($customerId, $branchId);
        $next = ($acc->stamps % self::CYCLE) + 1; // 1..10

        if ($next === 5)  return ['reward' => 'DISC25',  'discount' => round($subtotal * 0.25, 2)];
        if ($next === 10) return ['reward' => 'FREE100', 'discount' => round($subtotal, 2)];
        return ['reward' => 'NONE', 'discount' => 0.0];
    }

    /** Terapkan loyalti pada draft order (mengisi kolom meta & perhitungan total). */
    public function applyToOrder(Order $order, string $branchId): void
    {
        $subtotal = (float) $order->getAttribute('subtotal');
        $p = $this->previewReward($order->customer_id, $branchId, $subtotal);

        $order->loyalty_reward   = $p['reward'];     // NONE|DISC25|FREE100
        $order->loyalty_discount = $p['discount'];   // angka rupiah
        $order->discount         = (float) $order->discount + $p['discount'];
        $order->grand_total      = max(0, $subtotal - (float) $order->discount);
        $order->due_amount       = max(0, (float) $order->grand_total - (float) $order->paid_amount);
    }

    /** Earn + logging saat order dinyatakan selesai (PICKED_UP). */
    public function finalizeEarn(Order $order): void
    {
        if (!$order->customer_id) return;

        DB::transaction(function () use ($order) {
            // idempotensi per order
            if (LoyaltyLog::query()->where('order_id', (string) $order->getKey())->exists()) {
                return;
            }

            $acc = $this->getOrCreateAccount((string) $order->customer_id, (string) $order->branch_id);
            $before = (int) $acc->stamps;

            // catat reward yang dipakai di order ini
            if ($order->loyalty_reward === 'DISC25') {
                LoyaltyLog::create([
                    'id' => (string) Str::uuid(),
                    'order_id' => null,
                    'customer_id' => (string) $order->customer_id,
                    'branch_id' => (string) $order->branch_id,
                    'action' => 'REWARD25',
                    'before' => $before,
                    'after' => $before,
                ]);
            } elseif ($order->loyalty_reward === 'FREE100') {
                LoyaltyLog::create([
                    'id' => (string) Str::uuid(),
                    'order_id' => null,
                    'customer_id' => (string) $order->customer_id,
                    'branch_id' => (string) $order->branch_id,
                    'action' => 'REWARD100',
                    'before' => $before,
                    'after' => $before,
                ]);
            }

            // earn +1, reset jika mencapai 10
            $afterEarn = $before + 1;     // nilai setelah earn
            $didReset  = $afterEarn >= self::CYCLE;
            $after     = $didReset ? 0 : $afterEarn;

            $acc->update(['stamps' => $after, 'lifetime' => (int) $acc->lifetime + 1]);

            // SATU-SATUNYA log yang mengikat order_id: EARN
            LoyaltyLog::create([
                'id' => (string) Str::uuid(),
                'order_id' => (string) $order->getKey(),
                'customer_id' => (string) $order->customer_id,
                'branch_id' => (string) $order->branch_id,
                'action' => 'EARN',
                'before' => $before,
                'after' => $after,
            ]);

            // Jika terjadi reset (menyentuh ke-10), catat RESET sebagai histori tambahan TANPA order_id
            if ($didReset) {
                LoyaltyLog::create([
                    'id' => (string) Str::uuid(),
                    'order_id' => null,
                    'customer_id' => (string) $order->customer_id,
                    'branch_id' => (string) $order->branch_id,
                    'action' => 'RESET',
                    'before' => $afterEarn,
                    'after' => 0,
                ]);
            }
        });
    }
}
