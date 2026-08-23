<?php
namespace App\Services;

use App\Models\CashMutation;
use App\Models\Expense;
use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Support\Carbon;

class CashLedgerService
{
    public function syncPayment(Payment $payment, ?User $actor = null): void
    {
        if ($payment->method !== 'CASH') {
            return;
        }

        $payment->loadMissing('order');
        /** @var Order|null $order */
        $order = $payment->order;

        if (! $order || ! $order->branch_id) {
            return;
        }

        $paidAt  = $payment->paid_at ? Carbon::parse($payment->paid_at) : now();

        $isReceivableSettlement = ((float) $order->paid_amount > (float) $payment->amount);

        CashMutation::query()->updateOrCreate(
            [
                'source_type' => 'payment',
                'source_id'   => $payment->id,
                'type'        => $isReceivableSettlement ? 'RECEIVABLE_CASH_SETTLEMENT' : 'SALE_CASH',
            ],
            [
                'branch_id'       => $order->branch_id,
                'direction'       => 'IN',
                'amount'          => $payment->amount,
                'reference_no'    => $order->invoice_no ?: $order->number,
                'note'            => $isReceivableSettlement
                    ? 'Pelunasan piutang tunai'
                    : 'Pembayaran order tunai',
                'created_by'      => $actor?->id ?: $order->created_by,
                'effective_at'    => $paidAt,
            ]
        );
    }

    public function syncExpense(Expense $expense, ?User $actor = null): void
    {
        if (($expense->payment_source ?? 'NON_CASH') !== 'CASH_BOX') {
            CashMutation::query()
                ->where('source_type', 'expense')
                ->where('source_id', $expense->id)
                ->where('type', 'EXPENSE_CASH')
                ->delete();
            return;
        }

        $effectiveAt = $expense->created_at
            ? Carbon::parse($expense->created_at)
            : now();

        CashMutation::query()->updateOrCreate(
            [
                'source_type' => 'expense',
                'source_id'   => $expense->id,
                'type'        => 'EXPENSE_CASH',
            ],
            [
                'branch_id'       => $expense->branch_id,
                'direction'       => 'OUT',
                'amount'          => $expense->amount,
                'reference_no'    => null,
                'note'            => $expense->note ?: ('Expense: ' . $expense->category),
                'created_by'      => $actor?->id,
                'effective_at'    => $effectiveAt,
            ]
        );
    }

    public function deleteExpenseMutation(string $expenseId): void
    {
        CashMutation::query()
            ->where('source_type', 'expense')
            ->where('source_id', $expenseId)
            ->where('type', 'EXPENSE_CASH')
            ->delete();
    }

}
