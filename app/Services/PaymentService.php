<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class PaymentService
{
    public function __construct(
        private CashLedgerService $cashLedger,
    ) {}

    public function apply(
        Order $order,
        string $method,
        float $amount,
        string|Carbon|null $paidAt = null,
        ?string $note = null
    ): array {
        return DB::transaction(function () use ($order, $method, $amount, $paidAt, $note) {
            $order = Order::query()->lockForUpdate()->findOrFail($order->id);
            $orderId = (string) $order->id;

            $paidAtDb = $paidAt
                ? ($paidAt instanceof Carbon ? $paidAt : Carbon::parse($paidAt))
                : now();

            // idempotency sederhana: payment identik sudah ada
            $exists = Payment::query()
                ->where('order_id', $orderId)
                ->where('method', $method)
                ->where('amount', $amount)
                ->where('paid_at', $paidAtDb)
                ->where('note', $note)
                ->first();

            if ($exists) {
                return [
                    'ok' => true,
                    'order' => $order->fresh(['items']),
                    'payment' => $exists,
                    'idempotent' => true,
                ];
            }

            $payment = Payment::query()->create([
                'id' => (string) Str::uuid(),
                'order_id' => $orderId,
                'method' => $method,
                'amount' => $amount,
                'paid_at' => $paidAtDb,
                'note' => $note,
            ]);

            $paidAmount = (float) $order->paid_amount + $amount;
            $grand = (float) $order->grand_total;

            $paymentStatus = 'PENDING';
            if ($method === 'DP' || $paidAmount < $grand) {
                $paymentStatus = 'DP';
            }
            if ($paidAmount >= $grand && $grand > 0) {
                $paymentStatus = 'PAID';
            }

            $newDp = (float) $order->dp_amount;
            if ($method === 'DP') {
                $newDp += $amount;
            }

            $order->forceFill([
                'paid_amount' => $paidAmount,
                'dp_amount' => $newDp,
                'payment_status' => $paymentStatus,
                'paid_at' => ($paymentStatus === 'PAID' && !$order->paid_at)
                    ? ($paidAtDb ?: now())
                    : $order->paid_at,
                'due_amount' => max($grand - $paidAmount, 0),
            ])->save();

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
                } elseif ($row) {
                    DB::table('receivables')->where('order_id', $orderId)->update([
                        'remaining_amount' => $remaining,
                        'status' => $remaining > 0
                            ? ((float) $paidAmount > 0 ? 'PARTIAL' : 'OPEN')
                            : 'SETTLED',
                        'updated_at' => now(),
                    ]);
                }
            }

            if ($method === 'CASH') {
                $this->cashLedger->syncPayment($payment);
            }

            return [
                'ok' => true,
                'order' => $order->fresh(['items']),
                'payment' => $payment->fresh(),
                'idempotent' => false,
            ];
        });
    }
}