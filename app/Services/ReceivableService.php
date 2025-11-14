<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Receivable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class ReceivableService
{
    /**
     * Dipanggil saat DP dibuat.
     * Jika belum ada receivable, buat; jika sudah, sinkron sisa dari order.
     */
    public function createForDP(Order $order, ?Carbon $dueDate = null): Receivable
    {
        return DB::transaction(function () use ($order, $dueDate) {
            $rcv = Receivable::query()->firstOrNew(['order_id' => $order->id]);
            $rcv->due_date = $dueDate;
            $rcv->remaining_amount = (float) $order->grand_total - (float) $order->paid_amount;
            $rcv->status = $rcv->remaining_amount <= 0.000001 ? 'SETTLED' : ($rcv->remaining_amount < (float) $order->grand_total ? 'PARTIAL' : 'OPEN');
            $rcv->save();

            return $rcv->refresh();
        });
    }

    /**
     * Pelunasan: delegasikan ke PaymentService agar audit & idempoten seragam.
     * PaymentService sudah meng-update order & upsert receivable sesuai sisa.
     */
    public function settle(Order $order, string $method, float $amount, ?Carbon $paidAt = null, ?string $note = null): array
    {
        return DB::transaction(function () use ($order, $method, $amount, $paidAt, $note) {
            /** @var \App\Services\PaymentService $pay */
            $pay = app(\App\Services\PaymentService::class);

            $resultOrder = $pay->apply($order, $method, $amount, $paidAt, $note);

            $rcv = Receivable::query()->where('order_id', $order->id)->first();

            return ['order' => $resultOrder, 'receivable' => $rcv];
        });
    }
}
