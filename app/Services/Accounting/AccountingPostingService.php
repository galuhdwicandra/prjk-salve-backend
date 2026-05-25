<?php
namespace App\Services\Accounting;

use App\Models\AccountingJournalEntry;
use App\Models\AccountingJournalLine;
use App\Models\CashMutation;
use App\Models\Expense;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Receivable;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AccountingPostingService
{
    public function __construct(
        private AccountingMappingResolver $mappingResolver,
        private AccountingJournalNumberService $numberService,
    ) {}

    public function postPayment(Payment $payment, ?User $actor = null): ?AccountingJournalEntry
    {
        $payment->loadMissing('order');

        $order = $payment->order;

        if (! $order || ! $order->branch_id) {
            return null;
        }

        $method = (string) $payment->method;

        $eventKey = match ($method) {
            'DP'       => 'ORDER_PAID_DP',
            'CASH'     => 'ORDER_PAID_CASH',
            'QRIS'     => 'ORDER_PAID_QRIS',
            'TRANSFER' => 'ORDER_PAID_TRANSFER',
            default    => null,
        };

        if (! $eventKey) {
            return null;
        }

        return $this->postSimpleEntry(
            eventKey: $eventKey,
            branchId: (string) $order->branch_id,
            amount: (float) $payment->amount,
            sourceType: 'payment',
            sourceId: (string) $payment->id,
            sourceNo: $order->invoice_no ?: $order->number,
            journalDate: $payment->paid_at ? Carbon::parse($payment->paid_at) : now('Asia/Jakarta'),
            description: 'Posting otomatis pembayaran order',
            actorId: $actor?->id ?: $order->created_by,
            paymentMethod: $method,
        );
    }

    public function postOrderDiscount(Order $order, ?User $actor = null): ?AccountingJournalEntry
    {
        if (! $order->branch_id) {
            return null;
        }

        $amount = (float) $order->discount;

        if ($amount <= 0) {
            return null;
        }

        return $this->postSimpleEntry(
            eventKey: 'ORDER_DISCOUNT',
            branchId: (string) $order->branch_id,
            amount: $amount,
            sourceType: 'order_discount',
            sourceId: (string) $order->id,
            sourceNo: $order->invoice_no ?: $order->number,
            journalDate: $order->created_at ? Carbon::parse($order->created_at) : now('Asia/Jakarta'),
            description: 'Posting otomatis diskon/voucher order',
            actorId: $actor?->id ?: $order->created_by,
        );
    }

    public function postReceivableCreated(Receivable $receivable, ?User $actor = null): ?AccountingJournalEntry
    {
        $receivable->loadMissing('order');

        $order = $receivable->order;

        if (! $order || ! $order->branch_id) {
            return null;
        }

        $amount = (float) $receivable->remaining_amount;

        if ($amount <= 0) {
            return null;
        }

        return $this->postSimpleEntry(
            eventKey: 'ORDER_RECEIVABLE_CREATED',
            branchId: (string) $order->branch_id,
            amount: $amount,
            sourceType: 'receivable',
            sourceId: (string) $receivable->id,
            sourceNo: $order->invoice_no ?: $order->number,
            journalDate: $receivable->created_at ? Carbon::parse($receivable->created_at) : now('Asia/Jakarta'),
            description: 'Posting otomatis piutang order',
            actorId: $actor?->id ?: $order->created_by,
        );
    }

    public function postReceivableSettlement(Payment $payment, ?User $actor = null): ?AccountingJournalEntry
    {
        $payment->loadMissing('order');

        $order = $payment->order;

        if (! $order || ! $order->branch_id) {
            return null;
        }

        if ((string) $payment->method !== 'CASH') {
            return null;
        }

        return $this->postSimpleEntry(
            eventKey: 'RECEIVABLE_SETTLED_CASH',
            branchId: (string) $order->branch_id,
            amount: (float) $payment->amount,
            sourceType: 'payment',
            sourceId: (string) $payment->id,
            sourceNo: $order->invoice_no ?: $order->number,
            journalDate: $payment->paid_at ? Carbon::parse($payment->paid_at) : now('Asia/Jakarta'),
            description: 'Posting otomatis pelunasan piutang tunai',
            actorId: $actor?->id ?: $order->created_by,
            paymentMethod: 'CASH',
        );
    }

    public function postExpense(Expense $expense, ?User $actor = null): ?AccountingJournalEntry
    {
        if (! $expense->branch_id) {
            return null;
        }

        $paymentSource = (string) ($expense->payment_source ?? 'NON_CASH');

        $eventKey = $paymentSource === 'CASH_BOX'
            ? 'EXPENSE_CASH_BOX'
            : 'EXPENSE_NON_CASH';

        return $this->postSimpleEntry(
            eventKey: $eventKey,
            branchId: (string) $expense->branch_id,
            amount: (float) $expense->amount,
            sourceType: 'expense',
            sourceId: (string) $expense->id,
            sourceNo: null,
            journalDate: $expense->created_at ? Carbon::parse($expense->created_at) : now('Asia/Jakarta'),
            description: 'Posting otomatis pengeluaran: ' . (string) $expense->category,
            actorId: $actor?->id,
            expenseCategory: $expense->category,
        );
    }

    public function postCashMutation(CashMutation $mutation, ?User $actor = null): ?AccountingJournalEntry
    {
        if (! $mutation->branch_id) {
            return null;
        }

        $eventKey = match ((string) $mutation->type) {
            'OPENING_FLOAT'  => 'CASH_OPENING_FLOAT',
            'WITHDRAWAL'     => 'CASH_WITHDRAWAL',
            'ADJUSTMENT_IN'  => 'CASH_ADJUSTMENT_IN',
            'ADJUSTMENT_OUT' => 'CASH_ADJUSTMENT_OUT',
            default          => null,
        };

        if (! $eventKey) {
            return null;
        }

        return $this->postSimpleEntry(
            eventKey: $eventKey,
            branchId: (string) $mutation->branch_id,
            amount: (float) $mutation->amount,
            sourceType: 'cash_mutation',
            sourceId: (string) $mutation->id,
            sourceNo: $mutation->reference_no,
            journalDate: $mutation->effective_at ? Carbon::parse($mutation->effective_at) : now('Asia/Jakarta'),
            description: $mutation->note ?: 'Posting otomatis mutasi kas',
            actorId: $actor?->id ?: $mutation->created_by,
        );
    }

    private function postSimpleEntry(
        string $eventKey,
        string $branchId,
        float $amount,
        string $sourceType,
        string $sourceId,
        ?string $sourceNo,
        Carbon | string $journalDate,
        ?string $description,
        int | string | null $actorId = null,
        ?string $paymentMethod = null,
        ?string $expenseCategory = null,
    ): ?AccountingJournalEntry {
        if ($amount <= 0) {
            return null;
        }

        return DB::transaction(function () use (
            $eventKey,
            $branchId,
            $amount,
            $sourceType,
            $sourceId,
            $sourceNo,
            $journalDate,
            $description,
            $actorId,
            $paymentMethod,
            $expenseCategory,
        ) {
            $existing = AccountingJournalEntry::query()
                ->with(['lines.account'])
                ->where('source_type', $sourceType)
                ->where('source_id', $sourceId)
                ->lockForUpdate()
                ->first();

            if ($existing) {
                return $existing;
            }

            $date = $journalDate instanceof Carbon
                ? $journalDate
                : Carbon::parse($journalDate);

            $mapping = $this->mappingResolver->resolve(
                eventKey: $eventKey,
                branchId: $branchId,
                paymentMethod: $paymentMethod,
                expenseCategory: $expenseCategory,
            );

            $this->validateMappingAccounts($mapping);

            $journal = AccountingJournalEntry::query()->create([
                'id'           => (string) Str::uuid(),
                'branch_id'    => $branchId,
                'mapping_id'   => $mapping->id,
                'journal_no'   => $this->numberService->next($date),
                'journal_date' => $date->toDateString(),
                'source_type'  => $sourceType,
                'source_id'    => $sourceId,
                'source_no'    => $sourceNo,
                'status'       => 'POSTED',
                'description'  => $description,
                'total_debit'  => $amount,
                'total_credit' => $amount,
                'created_by'   => $actorId,
                'posted_by'    => $actorId,
                'posted_at'    => now(),
            ]);

            AccountingJournalLine::query()->create([
                'id'               => (string) Str::uuid(),
                'journal_entry_id' => $journal->id,
                'account_id'       => $mapping->debit_account_id,
                'description'      => $description,
                'debit'            => $amount,
                'credit'           => 0,
                'line_order'       => 1,
            ]);

            AccountingJournalLine::query()->create([
                'id'               => (string) Str::uuid(),
                'journal_entry_id' => $journal->id,
                'account_id'       => $mapping->credit_account_id,
                'description'      => $description,
                'debit'            => 0,
                'credit'           => $amount,
                'line_order'       => 2,
            ]);

            return $journal->load([
                'mapping',
                'lines.account:id,code,name,type,normal_balance',
            ]);
        });
    }

    private function validateMappingAccounts($mapping): void
    {
        if (! $mapping->debitAccount || ! $mapping->creditAccount) {
            throw ValidationException::withMessages([
                'mapping' => ['Mapping akun belum memiliki akun debit dan kredit yang valid.'],
            ]);
        }

        if (! $mapping->debitAccount->is_active || ! $mapping->creditAccount->is_active) {
            throw ValidationException::withMessages([
                'mapping' => ['Akun debit atau kredit pada mapping sudah tidak aktif.'],
            ]);
        }

        if ((string) $mapping->debit_account_id === (string) $mapping->credit_account_id) {
            throw ValidationException::withMessages([
                'mapping' => ['Akun debit dan kredit tidak boleh sama.'],
            ]);
        }
    }
}
