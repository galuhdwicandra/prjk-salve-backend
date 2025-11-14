<?php

namespace App\Services;

use App\Models\Order;
use App\Models\User;
use App\Models\Voucher;
use App\Models\OrderVoucher;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class VoucherService
{
    /**
     * Validasi bisnis: aktif, periode, cabang, min_total, usage_limit.
     * Lempar ValidationException bila tidak valid.
     */
    public function validate(Order $order, Voucher $voucher): void
    {
        if (!$voucher->active) {
            throw ValidationException::withMessages(['code' => 'Voucher tidak aktif.']);
        }

        // Scope cabang: voucher global atau sama dengan cabang order
        if ($voucher->branch_id !== null && $voucher->branch_id !== $order->branch_id) {
            throw ValidationException::withMessages(['code' => 'Voucher tidak berlaku untuk cabang ini.']);
        }

        // Periode
        $now = now();
        if ($voucher->start_at && $now->lt($voucher->start_at)) {
            throw ValidationException::withMessages(['code' => 'Voucher belum berlaku.']);
        }
        if ($voucher->end_at && $now->gt($voucher->end_at)) {
            throw ValidationException::withMessages(['code' => 'Voucher telah kedaluwarsa.']);
        }

        // Minimum total dibanding subtotal (sebelum diskon)
        if ((float) $order->subtotal < (float) $voucher->min_total) {
            throw ValidationException::withMessages(['code' => 'Subtotal belum memenuhi minimum voucher.']);
        }

        // Usage limit global: hitung dari pivot
        if ($voucher->usage_limit !== null) {
            $used = OrderVoucher::query()->where('voucher_id', $voucher->id)->count();
            if ($used >= (int) $voucher->usage_limit) {
                throw ValidationException::withMessages(['code' => 'Batas pemakaian voucher sudah tercapai.']);
            }
        }
    }

    /**
     * Terapkan voucher ke order (idempotent).
     * - Satu voucher per order (pivot unique order_id).
     * - Recalculate discount, grand_total, due_amount.
     */
    public function apply(Order $order, Voucher $voucher, User $actor): Order
    {
        return DB::transaction(function () use ($order, $voucher, $actor) {
            // Idempotensi: jika sudah ada pivot, kembalikan apa adanya
            $existing = $order->vouchers()->first();
            if ($existing) {
                return $order->refresh()->load(['items.service', 'vouchers']);
            }

            $this->validate($order, $voucher);

            // Hitung nilai potongan
            $subtotal = (float) $order->subtotal;
            $amount = match ($voucher->type) {
                'PERCENT' => round($subtotal * ((float) $voucher->value / 100), 2),
                default => (float) $voucher->value,
            };
            // Batasi tidak melebihi subtotal
            $amount = max(0.0, min($amount, $subtotal));

            // Simpan pivot
            OrderVoucher::query()->create([
                'id' => (string) Str::uuid(),
                'order_id' => $order->id,
                'voucher_id' => $voucher->id,
                'applied_amount' => number_format($amount, 2, '.', ''),
                'applied_by' => $actor->id ?? null,
                'applied_at' => now(),
            ]);

            // Update kolom diskon & total
            $order->discount = number_format(((float) $order->discount) + $amount, 2, '.', '');
            $order->grand_total = number_format(((float) $order->subtotal) - ((float) $order->discount), 2, '.', '');
            $order->due_amount = number_format(((float) $order->grand_total) - ((float) $order->paid_amount), 2, '.', '');
            $order->save();

            // TODO: audit('ORDER_VOUCHER_APPLY', ['order_id' => $order->id, 'voucher' => $voucher->code, 'amount' => $amount, 'actor' => $actor->id]);

            return $order->load(['items.service', 'vouchers']);
        });
    }
}
