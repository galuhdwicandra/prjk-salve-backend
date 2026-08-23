<?php
namespace App\Services;

use App\Models\Order;
use App\Models\OrderAuditLog;
use App\Models\Payment;
use App\Models\User;
use App\Services\Accounting\AccountingPostingService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class PaymentService
{
    public function __construct(
        private CashLedgerService $cashLedger,
        private AccountingPostingService $accountingPosting,
    ) {}

    public function apply(
        Order $order,
        string $method,
        float $amount,
        string | Carbon | null $paidAt = null,
        ?string $note = null,
        bool $fromReceivableSettlement = false
    ): array {
        return DB::transaction(function () use ($order, $method, $amount, $paidAt, $note, $fromReceivableSettlement) {
            $order   = Order::query()->lockForUpdate()->findOrFail($order->id);
            $orderId = (string) $order->id;

            $paidAtDb = $paidAt
                ? ($paidAt instanceof Carbon ? $paidAt : Carbon::parse($paidAt))
                : now();

            /** @var User|null $actor */
            $actor = Auth::user();

// idempotency sederhana: payment identik sudah ada
            $exists = Payment::query()
                ->where('order_id', $orderId)
                ->where('method', $method)
                ->where('amount', $amount)
                ->where('paid_at', $paidAtDb)
                ->where('note', $note)
                ->first();

            if ($exists) {
                if (! $fromReceivableSettlement) {
                    $this->accountingPosting->postPayment(
                        $exists,
                        $actor
                    );
                }

                return [
                    'ok'         => true,
                    'order'      => $order->fresh(['items']),
                    'payment'    => $exists,
                    'idempotent' => true,
                ];
            }

            $payment = Payment::query()->create([
                'id'       => (string) Str::uuid(),
                'order_id' => $orderId,
                'method'   => $method,
                'amount'   => $amount,
                'paid_at'  => $paidAtDb,
                'note'     => $note,
            ]);

            $paidAmount = (float) $order->paid_amount + $amount;
            $grand      = (float) $order->grand_total;

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
                'paid_amount'    => $paidAmount,
                'dp_amount'      => $newDp,
                'payment_status' => $paymentStatus,
                'paid_at'        => ($paymentStatus === 'PAID' && ! $order->paid_at)
                    ? ($paidAtDb ?: now())
                    : $order->paid_at,
                'due_amount'     => max($grand - $paidAmount, 0),
            ])->save();

            $remaining = max($grand - $paidAmount, 0);

            if (Schema::hasTable('receivables')) {
                $row = DB::table('receivables')->where('order_id', $orderId)->first();

                $dueDate = $order->ready_at
                    ? Carbon::parse($order->ready_at)->toDateString()
                    : null;

                if (! $row && $remaining > 0) {
                    DB::table('receivables')->insert([
                        'id'               => (string) Str::uuid(),
                        'order_id'         => $orderId,
                        'remaining_amount' => $remaining,
                        'status'           => $remaining >= $grand ? 'OPEN' : 'PARTIAL',
                        'due_date'         => $dueDate,
                        'created_at'       => now(),
                        'updated_at'       => now(),
                    ]);
                } elseif ($row) {
                    DB::table('receivables')->where('order_id', $orderId)->update([
                        'remaining_amount' => $remaining,
                        'status'           => $remaining > 0
                            ? ((float) $paidAmount > 0 ? 'PARTIAL' : 'OPEN')
                            : 'SETTLED',
                        'due_date'         => $dueDate,
                        'updated_at'       => now(),
                    ]);
                }
            }

            if ($method === 'CASH') {
                $this->cashLedger->syncPayment(
                    $payment,
                    $actor
                );
            }

            if (! $fromReceivableSettlement) {
                $this->accountingPosting->postPayment(
                    $payment,
                    $actor
                );
            }

            return [
                'ok'         => true,
                'order'      => $order->fresh(['items']),
                'payment'    => $payment->fresh(),
                'idempotent' => false,
            ];
        });
    }

    public function resetToPending(Order $order, User $actor, string $reason): array
    {
        return DB::transaction(function () use ($order, $actor, $reason) {
            $order = Order::query()
                ->lockForUpdate()
                ->findOrFail($order->id);

            $orderId = (string) $order->getKey();

            $paymentIds = Payment::query()
                ->where('order_id', $orderId)
                ->pluck('id')
                ->map(fn($id) => (string) $id)
                ->values();

            if ($paymentIds->isNotEmpty() && Schema::hasTable('cash_mutations')) {
                DB::table('cash_mutations')
                    ->where('source_type', 'payment')
                    ->whereIn('source_id', $paymentIds->all())
                    ->delete();
            }

            $this->voidJournals('payment', $paymentIds->all());

            Payment::query()
                ->where('order_id', $orderId)
                ->delete();

            $grandTotal = (float) $order->grand_total;
            $dueDate    = $order->ready_at
                ? Carbon::parse($order->ready_at)->toDateString()
                : null;

            $order->forceFill([
                'payment_status' => 'PENDING',
                'paid_amount'    => 0,
                'dp_amount'      => 0,
                'paid_at'        => null,
                'due_amount'     => $grandTotal,
            ])->save();

            if (Schema::hasTable('receivables')) {
                $existingReceivable = DB::table('receivables')
                    ->where('order_id', $orderId)
                    ->first();

                if ($grandTotal > 0) {
                    if ($existingReceivable) {
                        DB::table('receivables')
                            ->where('order_id', $orderId)
                            ->update([
                                'remaining_amount' => $grandTotal,
                                'status'           => 'OPEN',
                                'due_date'         => $dueDate,
                                'updated_at'       => now(),
                            ]);
                    } else {
                        DB::table('receivables')->insert([
                            'id'               => (string) Str::uuid(),
                            'order_id'         => $orderId,
                            'remaining_amount' => $grandTotal,
                            'status'           => 'OPEN',
                            'due_date'         => $dueDate,
                            'created_at'       => now(),
                            'updated_at'       => now(),
                        ]);
                    }
                } elseif ($existingReceivable) {
                    DB::table('receivables')
                        ->where('order_id', $orderId)
                        ->update([
                            'remaining_amount' => 0,
                            'status'           => 'SETTLED',
                            'due_date'         => $dueDate,
                            'updated_at'       => now(),
                        ]);
                }
            }

            OrderAuditLog::query()->create([
                'order_id' => $orderId,
                'action'   => 'PAYMENT_RESET_TO_PENDING',
                'reason'   => $reason,
                'actor_id' => $actor->id,
                'snapshot' => [
                    'grand_total'      => (float) $order->grand_total,
                    'deleted_payments' => $paymentIds->all(),
                ],
            ]);

            return [
                'ok'    => true,
                'order' => $order->fresh(['customer', 'items.service', 'photos', 'receivable']),
                'meta'  => [
                    'correction_type' => 'RESET_TO_PENDING',
                    'reason'          => $reason,
                    'corrected_by'    => $actor->id,
                ],
            ];
        });
    }

    public function updatePayment(
        Payment $payment,
        string $method,
        float $amount,
        string | Carbon | null $paidAt,
        ?string $note,
        User $actor
    ): Order {
        return DB::transaction(function () use ($payment, $method, $amount, $paidAt, $note, $actor) {
            $order = Order::query()->lockForUpdate()->findOrFail($payment->order_id);

            $this->discardPaymentPostings($payment);

            $payment->forceFill([
                'method'  => $method,
                'amount'  => $amount,
                'paid_at' => $paidAt
                    ? ($paidAt instanceof Carbon ? $paidAt : Carbon::parse($paidAt))
                    : $payment->paid_at,
                'note'    => $note,
            ])->save();

            $fresh = $payment->fresh();

            if ($method === 'CASH') {
                $this->cashLedger->syncPayment($fresh, $actor);
            }

            $this->accountingPosting->postPayment($fresh, $actor);

            return $this->recalculate($order, $actor, 'PAYMENT_UPDATED', [
                'payment_id' => (string) $payment->getKey(),
                'amount'     => $amount,
                'method'     => $method,
            ]);
        });
    }

    public function deletePayment(Payment $payment, User $actor): Order
    {
        return DB::transaction(function () use ($payment, $actor) {
            $order     = Order::query()->lockForUpdate()->findOrFail($payment->order_id);
            $paymentId = (string) $payment->getKey();
            $amount    = (float) $payment->amount;

            $this->discardPaymentPostings($payment);
            $payment->delete();

            return $this->recalculate($order, $actor, 'PAYMENT_DELETED', [
                'payment_id' => $paymentId,
                'amount'     => $amount,
            ]);
        });
    }

    public function voidOrder(Order $order, User $actor, string $reason): Order
    {
        return DB::transaction(function () use ($order, $actor, $reason) {
            $order   = Order::query()->lockForUpdate()->findOrFail($order->id);
            $orderId = (string) $order->getKey();

            $payments = Payment::query()->where('order_id', $orderId)->get();

            foreach ($payments as $payment) {
                $this->discardPaymentPostings($payment);
            }

            Payment::query()->where('order_id', $orderId)->delete();

            $this->voidJournals('order_discount', [$orderId]);

            if (Schema::hasTable('receivables')) {
                $receivableIds = DB::table('receivables')
                    ->where('order_id', $orderId)
                    ->pluck('id')
                    ->map(fn($id) => (string) $id)
                    ->all();

                $this->voidJournals('receivable', $receivableIds);

                DB::table('receivables')->where('order_id', $orderId)->update([
                    'remaining_amount' => 0,
                    'status'           => 'SETTLED',
                    'updated_at'       => now(),
                ]);
            }

            $order->forceFill([
                'status'         => 'CANCELED',
                'payment_status' => 'PENDING',
                'paid_amount'    => 0,
                'dp_amount'      => 0,
                'paid_at'        => null,
                'due_amount'     => 0,
            ])->save();

            OrderAuditLog::query()->create([
                'order_id' => $orderId,
                'action'   => 'ORDER_VOIDED',
                'reason'   => $reason,
                'actor_id' => $actor->id,
                'snapshot' => [
                    'grand_total'      => (float) $order->grand_total,
                    'deleted_payments' => $payments->pluck('id')->map(fn($id) => (string) $id)->all(),
                ],
            ]);

            return $order->fresh(['customer', 'items.service', 'photos', 'receivable', 'payments']);
        });
    }

    private function recalculate(Order $order, User $actor, string $action, array $snapshot): Order
    {
        $orderId = (string) $order->getKey();
        $rows    = Payment::query()->where('order_id', $orderId)->orderBy('paid_at')->get();

        $paidAmount = (float) $rows->sum(fn($row) => (float) $row->amount);
        $dpAmount   = (float) $rows->where('method', 'DP')->sum(fn($row) => (float) $row->amount);
        $grandTotal = (float) $order->grand_total;

        $paymentStatus = 'PENDING';
        if ($paidAmount > 0) {
            $paymentStatus = 'DP';
        }
        if ($paidAmount >= $grandTotal && $grandTotal > 0) {
            $paymentStatus = 'PAID';
        }

        $remaining = max($grandTotal - $paidAmount, 0);

        $order->forceFill([
            'paid_amount'    => $paidAmount,
            'dp_amount'      => $dpAmount,
            'payment_status' => $paymentStatus,
            'paid_at'        => $paymentStatus === 'PAID' ? ($rows->last()?->paid_at ?? now()) : null,
            'due_amount'     => $remaining,
        ])->save();

        if (Schema::hasTable('receivables')) {
            $existing = DB::table('receivables')->where('order_id', $orderId)->first();
            $dueDate  = $order->ready_at ? Carbon::parse($order->ready_at)->toDateString() : null;

            $status = $remaining > 0
                ? ($paidAmount > 0 ? 'PARTIAL' : 'OPEN')
                : 'SETTLED';

            if ($existing) {
                DB::table('receivables')->where('order_id', $orderId)->update([
                    'remaining_amount' => $remaining,
                    'status'           => $status,
                    'due_date'         => $dueDate,
                    'updated_at'       => now(),
                ]);
            } elseif ($remaining > 0) {
                DB::table('receivables')->insert([
                    'id'               => (string) Str::uuid(),
                    'order_id'         => $orderId,
                    'remaining_amount' => $remaining,
                    'status'           => $status,
                    'due_date'         => $dueDate,
                    'created_at'       => now(),
                    'updated_at'       => now(),
                ]);
            }
        }

        OrderAuditLog::query()->create([
            'order_id' => $orderId,
            'action'   => $action,
            'reason'   => null,
            'actor_id' => $actor->id,
            'snapshot' => $snapshot + ['paid_amount' => $paidAmount],
        ]);
        return $order->fresh(['customer', 'items.service', 'photos', 'receivable', 'payments']);
    }
    private function discardPaymentPostings(Payment $payment): void
    {
        $paymentId = (string) $payment->getKey();
        if (Schema::hasTable('cash_mutations')) {
            DB::table('cash_mutations')
                ->where('source_type', 'payment')
                ->where('source_id', $paymentId)
                ->delete();
        }
        $this->voidJournals('payment', [$paymentId]);
    }
    private function voidJournals(string $sourceType, array $sourceIds): void
    {
        if ($sourceIds === [] || ! Schema::hasTable('accounting_journal_entries')) {
            return;
        }

        DB::table('accounting_journal_entries')
            ->where('source_type', $sourceType)
            ->whereIn('source_id', $sourceIds)
            ->where('status', 'POSTED')
            ->update(['status' => 'VOID', 'updated_at' => now()]);
    }
}
