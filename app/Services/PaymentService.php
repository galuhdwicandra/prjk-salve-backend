<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Order;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class PaymentService
{
    public function apply(Order $order, string $method, float $amount, ?string $paidAt = null, ?string $note = null): array
    {
        return DB::transaction(function () use ($order, $method, $amount, $paidAt, $note) {
            $orderId = (string) $order->getKey();

            // [BARU] Normalisasi paid_at ke format SQL (YYYY-mm-dd HH:ii:ss)
            $paidAtDb = $paidAt ? Carbon::parse($paidAt)->format('Y-m-d H:i:s') : null;

            // 1) Idempotency check (kombinasi unik)
            $exists = DB::table('payments')->where([
                'order_id' => $orderId,
                'method' => $method,
                'amount' => $amount,
            ])
                // ganti $paidAt -> $paidAtDb
                ->when($paidAtDb, fn($q) => $q->where('paid_at', $paidAtDb))
                ->exists();

            if ($exists) {
                return ['ok' => true, 'order' => $order->fresh(), 'payment' => null, 'idempotent' => true];
            }

            // 2) Create payment row
            $paymentId = (string) Str::uuid();
            DB::table('payments')->insert([
                'id' => $paymentId,
                'order_id' => $orderId,
                'method' => $method,
                'amount' => $amount,
                // ganti $paidAt -> $paidAtDb
                'paid_at' => $paidAtDb,
                'note' => $note,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // 3) Update order payment aggregates
            $paidAmount = (float) $order->getAttribute('paid_amount') + $amount;
            $grand = (float) $order->getAttribute('grand_total');

            $paymentStatus = 'PENDING';
            if ($method === 'DP' || $paidAmount < $grand) {
                $paymentStatus = 'DP';
            }
            if ($paidAmount >= $grand && $grand > 0) {
                $paymentStatus = 'PAID';
            }

            $newDp = (float) $order->getAttribute('dp_amount');
            if ($method === 'DP') {
                $newDp += $amount;
            }

            $order->forceFill([
                'paid_amount' => $paidAmount,
                'dp_amount' => $newDp,
                'payment_status' => $paymentStatus,
                // ganti $paidAt -> $paidAtDb
                'paid_at' => ($paymentStatus === 'PAID' && !$order->getAttribute('paid_at'))
                    ? ($paidAtDb ?: now())
                    : $order->getAttribute('paid_at'),
                'due_amount' => max($grand - $paidAmount, 0),
            ])->save();

            // 4) Receivables upsert (tetap sama) ...
            // (biarkan bagian receivables apa adanya)
            $remaining = max($grand - $paidAmount, 0);
            if (Schema::hasTable('receivables')) {
                $row = DB::table('receivables')->where('order_id', $orderId)->first();
                if (!$row && $remaining > 0) {
                    DB::table('receivables')->insert([
                        'id' => (string) Str::uuid(),
                        'order_id' => $orderId,
                        'remaining_amount' => $remaining,
                        'status' => $remaining >= $grand ? 'OPEN' : 'PARTIAL',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                } else {
                    DB::table('receivables')->where('order_id', $orderId)->update([
                        'remaining_amount' => $remaining,
                        'status' => $remaining > 0 ? 'PARTIAL' : 'SETTLED',
                        'updated_at' => now(),
                    ]);
                }
            }

            $payment = DB::table('payments')->where('id', $paymentId)->first();

            return ['ok' => true, 'order' => $order->fresh(['items']), 'payment' => $payment, 'idempotent' => false];
        });
    }
}
