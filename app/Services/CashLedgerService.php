<?php
namespace App\Services;

use App\Models\CashMutation;
use App\Models\CashSession;
use App\Models\Expense;
use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class CashLedgerService
{
    public function findOpenSession(string $branchId, Carbon $businessDate): ?CashSession
    {
        return CashSession::query()
            ->where('branch_id', $branchId)
            ->whereDate('business_date', $businessDate->toDateString())
            ->where('status', 'OPEN')
            ->first();
    }

    public function requireOpenSession(string $branchId, Carbon $businessDate): CashSession
    {
        $session = $this->findOpenSession($branchId, $businessDate);

        if (! $session) {
            abort(422, 'Sesi kas belum dibuka untuk cabang dan tanggal ini.');
        }

        return $session;
    }

    public function openSession(string $branchId, Carbon $businessDate, float $openingCash, User $user, ?string $notes = null): CashSession
    {
        return DB::transaction(function () use ($branchId, $businessDate, $openingCash, $user, $notes) {
            $existing = CashSession::query()
                ->where('branch_id', $branchId)
                ->whereDate('business_date', $businessDate->toDateString())
                ->lockForUpdate()
                ->first();

            if ($existing) {
                abort(422, 'Sesi kas untuk cabang dan tanggal tersebut sudah ada.');
            }

            $session = CashSession::query()->create([
                'id'            => (string) Str::uuid(),
                'branch_id'     => $branchId,
                'business_date' => $businessDate->toDateString(),
                'status'        => 'OPEN',
                'opened_by'     => $user->id,
                'opened_at'     => now(),
                'opening_cash'  => $openingCash,
                'notes'         => $notes,
            ]);

            CashMutation::query()->create([
                'id'              => (string) Str::uuid(),
                'cash_session_id' => $session->id,
                'branch_id'       => $branchId,
                'type'            => 'OPENING_FLOAT',
                'direction'       => 'IN',
                'amount'          => $openingCash,
                'source_type'     => 'cash_session',
                'source_id'       => $session->id,
                'reference_no'    => null,
                'note'            => 'Modal awal kas',
                'created_by'      => $user->id,
                'effective_at'    => now(),
            ]);

            return $session->load(['branch', 'opener']);
        });
    }

    public function closeSession(CashSession $session, float $countedCash, User $user, ?string $notes = null): CashSession
    {
        return DB::transaction(function () use ($session, $countedCash, $user, $notes) {
            $session->refresh();

            if ($session->status !== 'OPEN') {
                abort(422, 'Sesi kas sudah ditutup.');
            }

            $system     = $this->computeSystemClosing($session->id);
            $difference = $countedCash - $system;

            $session->forceFill([
                'status'               => 'CLOSED',
                'closed_by'            => $user->id,
                'closed_at'            => now(),
                'closing_cash_system'  => $system,
                'closing_cash_counted' => $countedCash,
                'difference_amount'    => $difference,
                'notes'                => $notes ?: $session->notes,
            ])->save();

            return $session->fresh(['branch', 'opener', 'closer']);
        });
    }

    public function createWithdrawal(CashSession $session, float $amount, User $user, ?Carbon $effectiveAt = null, ?string $note = null): CashMutation
    {
        return DB::transaction(function () use ($session, $amount, $user, $effectiveAt, $note) {
            $session->refresh();

            if ($session->status !== 'OPEN') {
                abort(422, 'Tidak bisa melakukan penarikan karena sesi kas sudah ditutup.');
            }

            return CashMutation::query()->create([
                'id'              => (string) Str::uuid(),
                'cash_session_id' => $session->id,
                'branch_id'       => $session->branch_id,
                'type'            => 'WITHDRAWAL',
                'direction'       => 'OUT',
                'amount'          => $amount,
                'source_type'     => 'withdrawal',
                'source_id'       => (string) Str::uuid(),
                'reference_no'    => null,
                'note'            => $note ?: 'Penarikan kas harian',
                'created_by'      => $user->id,
                'effective_at'    => $effectiveAt ?: now(),
            ]);
        });
    }

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
        $session = $this->requireOpenSession((string) $order->branch_id, $paidAt->copy()->startOfDay());

        $isReceivableSettlement = ((float) $order->paid_amount > (float) $payment->amount);

        CashMutation::query()->updateOrCreate(
            [
                'source_type' => 'payment',
                'source_id'   => $payment->id,
                'type'        => $isReceivableSettlement ? 'RECEIVABLE_CASH_SETTLEMENT' : 'SALE_CASH',
            ],
            [
                'cash_session_id' => $session->id,
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

        $session = $this->requireOpenSession((string) $expense->branch_id, $effectiveAt->copy()->startOfDay());

        CashMutation::query()->updateOrCreate(
            [
                'source_type' => 'expense',
                'source_id'   => $expense->id,
                'type'        => 'EXPENSE_CASH',
            ],
            [
                'cash_session_id' => $session->id,
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

    public function updateSession(CashSession $session, float $openingCash, User $user, ?string $notes = null): CashSession
    {
        return DB::transaction(function () use ($session, $openingCash, $user, $notes) {
            $session = CashSession::query()
                ->whereKey($session->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ((string) $session->status !== 'OPEN') {
                throw ValidationException::withMessages([
                    'cash_session' => ['Hanya sesi kas yang masih OPEN yang boleh diedit.'],
                ]);
            }

            $openingMutation = CashMutation::query()
                ->where('cash_session_id', $session->id)
                ->where('type', 'OPENING_FLOAT')
                ->where('direction', 'IN')
                ->where('source_type', 'cash_session')
                ->where('source_id', $session->id)
                ->lockForUpdate()
                ->first();

            if (! $openingMutation) {
                throw ValidationException::withMessages([
                    'cash_session' => ['Mutasi OPENING_FLOAT tidak ditemukan untuk sesi kas ini.'],
                ]);
            }

            $session->opening_cash = $openingCash;
            $session->notes        = $notes;
            $session->save();

            $openingMutation->amount     = $openingCash;
            $openingMutation->note       = $notes ?: 'Modal awal kas';
            $openingMutation->created_by = $user->id;
            $openingMutation->save();

            return $session->fresh([
                'branch:id,name',
                'opener:id,name',
                'closer:id,name',
                'mutations' => fn($q) => $q->orderByDesc('effective_at')->orderByDesc('created_at'),
                'mutations.creator:id,name',
            ]);
        });
    }

    public function deleteExpenseMutation(string $expenseId): void
    {
        CashMutation::query()
            ->where('source_type', 'expense')
            ->where('source_id', $expenseId)
            ->where('type', 'EXPENSE_CASH')
            ->delete();
    }

    public function computeSystemClosing(string $cashSessionId): float
    {
        $in = (float) CashMutation::query()
            ->where('cash_session_id', $cashSessionId)
            ->where('direction', 'IN')
            ->sum('amount');

        $out = (float) CashMutation::query()
            ->where('cash_session_id', $cashSessionId)
            ->where('direction', 'OUT')
            ->sum('amount');

        return $in - $out;
    }
}
