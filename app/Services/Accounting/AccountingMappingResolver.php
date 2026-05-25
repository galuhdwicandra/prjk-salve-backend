<?php

namespace App\Services\Accounting;

use App\Models\AccountingAccountMapping;
use Illuminate\Validation\ValidationException;

class AccountingMappingResolver
{
    public function resolve(
        string $eventKey,
        ?string $branchId = null,
        ?string $paymentMethod = null,
        ?string $expenseCategory = null,
    ): AccountingAccountMapping {
        $branchMapping = null;

        if ($branchId) {
            $branchMapping = $this->baseQuery($eventKey, $paymentMethod, $expenseCategory)
                ->where('branch_id', $branchId)
                ->first();
        }

        $mapping = $branchMapping
            ?: $this->baseQuery($eventKey, $paymentMethod, $expenseCategory)
                ->whereNull('branch_id')
                ->first();

        if (! $mapping) {
            throw ValidationException::withMessages([
                'mapping' => [
                    "Mapping akun untuk event {$eventKey} belum tersedia atau belum aktif.",
                ],
            ]);
        }

        return $mapping;
    }

    private function baseQuery(
        string $eventKey,
        ?string $paymentMethod,
        ?string $expenseCategory,
    ) {
        $query = AccountingAccountMapping::query()
            ->with([
                'debitAccount:id,code,name,type,normal_balance,is_active',
                'creditAccount:id,code,name,type,normal_balance,is_active',
            ])
            ->where('event_key', $eventKey)
            ->where('is_active', true);

        if ($paymentMethod) {
            $query->where('payment_method', $paymentMethod);
        } else {
            $query->whereNull('payment_method');
        }

        if ($expenseCategory) {
            $query->where(function ($q) use ($expenseCategory) {
                $q->where('expense_category', $expenseCategory)
                    ->orWhereNull('expense_category');
            })->orderByRaw('expense_category IS NULL ASC');
        } else {
            $query->whereNull('expense_category');
        }

        return $query;
    }
}
