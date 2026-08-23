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
use App\Models\CashTransaction;
use App\Models\CashTransactionLine;

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
            expenseCategory: $mutation->category,
        );
    }

    public function postCashTransaction(CashTransaction $transaction, ?User $actor = null): AccountingJournalEntry
    {
        $transaction->loadMissing(['lines.category']);

        $rows = $this->cashTransactionRows($transaction);
        $total = round(array_sum(array_column($rows, 'debit')), 2);

        return DB::transaction(function () use ($transaction, $rows, $total, $actor) {
            $date = Carbon::parse($transaction->trx_date);

            $journal = AccountingJournalEntry::query()
                ->where('source_type', 'cash_transaction')
                ->where('source_id', $transaction->id)
                ->lockForUpdate()
                ->first();

            $attributes = [
                'branch_id'    => $transaction->branch_id,
                'journal_date' => $date->toDateString(),
                'source_no'    => $transaction->no,
                'status'       => 'POSTED',
                'description'  => $transaction->description ?: 'Transaksi ' . $transaction->no,
                'total_debit'  => $total,
                'total_credit' => $total,
                'posted_by'    => $actor?->id,
                'posted_at'    => now(),
                'voided_by'    => null,
                'voided_at'    => null,
                'void_reason'  => null,
            ];

            if ($journal) {
                AccountingJournalLine::query()->where('journal_entry_id', $journal->id)->delete();
                $journal->fill($attributes)->save();
            } else {
                $journal = AccountingJournalEntry::query()->create($attributes + [
                    'id'          => (string) Str::uuid(),
                    'mapping_id'  => null,
                    'journal_no'  => $this->numberService->next($date),
                    'source_type' => 'cash_transaction',
                    'source_id'   => $transaction->id,
                    'created_by'  => $actor?->id,
                ]);
            }

            foreach ($rows as $index => $row) {
                AccountingJournalLine::query()->create([
                    'id'               => (string) Str::uuid(),
                    'journal_entry_id' => $journal->id,
                    'account_id'       => $row['account_id'],
                    'description'      => $row['description'],
                    'debit'            => $row['debit'],
                    'credit'           => $row['credit'],
                    'line_order'       => $index + 1,
                ]);
            }

            return $journal;
        });
    }

    public function voidCashTransaction(CashTransaction $transaction, ?User $actor = null): void
    {
        AccountingJournalEntry::query()
            ->where('source_type', 'cash_transaction')
            ->where('source_id', $transaction->id)
            ->update([
                'status'      => 'VOID',
                'voided_by'   => $actor?->id,
                'voided_at'   => now(),
                'void_reason' => 'Transaksi ' . $transaction->no . ' dihapus.',
            ]);
    }

    private function cashTransactionRows(CashTransaction $transaction): array
    {
        $rows = [];

        if ($transaction->kind === 'TRANSFER') {
            $amount = round((float) $transaction->amount, 2);

            $rows[] = ['account_id' => $transaction->to_account_id, 'description' => $transaction->description, 'debit' => $amount, 'credit' => 0.0];
            $rows[] = ['account_id' => $transaction->cash_account_id, 'description' => $transaction->description, 'debit' => 0.0, 'credit' => $amount];

            $fee = round((float) $transaction->fee_amount, 2);

            if ($fee > 0) {
                $bearerAccountId = $transaction->fee_bearer === 'RECEIVER'
                    ? $transaction->to_account_id
                    : $transaction->cash_account_id;

                $rows[] = ['account_id' => $this->counterAccountId($transaction->lines->first(), 'OUT'), 'description' => 'Biaya admin transfer', 'debit' => $fee, 'credit' => 0.0];
                $rows[] = ['account_id' => $bearerAccountId, 'description' => 'Biaya admin transfer', 'debit' => 0.0, 'credit' => $fee];
            }

            return $rows;
        }

        $total = 0.0;

        foreach ($transaction->lines as $line) {
            $amount = round((float) $line->amount, 2);
            $total += $amount;
            $accountId = $this->counterAccountId($line, $transaction->kind);

            $rows[] = $transaction->kind === 'IN'
                ? ['account_id' => $accountId, 'description' => $line->description, 'debit' => 0.0, 'credit' => $amount]
                : ['account_id' => $accountId, 'description' => $line->description, 'debit' => $amount, 'credit' => 0.0];
        }

        $total = round($total, 2);

        $rows[] = $transaction->kind === 'IN'
            ? ['account_id' => $transaction->cash_account_id, 'description' => $transaction->description, 'debit' => $total, 'credit' => 0.0]
            : ['account_id' => $transaction->cash_account_id, 'description' => $transaction->description, 'debit' => 0.0, 'credit' => $total];

        return $rows;
    }

    private function counterAccountId(?CashTransactionLine $line, string $direction): string
    {
        $category = $line?->category;
        $accountId = $direction === 'IN' ? $category?->in_account_id : $category?->out_account_id;

        if (! $accountId) {
            throw ValidationException::withMessages([
                'lines' => ['Kategori "' . ($category?->name ?? '-') . '" belum dipetakan ke akun COA. Atur di Pengaturan > Master Kategori Transaksi.'],
            ]);
        }

        return (string) $accountId;
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
