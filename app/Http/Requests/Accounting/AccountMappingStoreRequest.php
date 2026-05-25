<?php

namespace App\Http\Requests\Accounting;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AccountMappingStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'branch_id' => ['nullable', 'uuid', 'exists:branches,id'],
            'event_key' => [
                'required',
                'string',
                'max:80',
                Rule::in([
                    'ORDER_PAID_CASH',
                    'ORDER_PAID_QRIS',
                    'ORDER_PAID_TRANSFER',
                    'ORDER_RECEIVABLE_CREATED',
                    'RECEIVABLE_SETTLED_CASH',
                    'EXPENSE_CASH_BOX',
                    'EXPENSE_NON_CASH',
                    'CASH_OPENING_FLOAT',
                    'CASH_WITHDRAWAL',
                    'CASH_ADJUSTMENT_IN',
                    'CASH_ADJUSTMENT_OUT',
                    'ORDER_DISCOUNT',
                ]),
            ],
            'payment_method' => ['nullable', 'string', 'max:30'],
            'expense_category' => ['nullable', 'string', 'max:100'],
            'debit_account_id' => ['required', 'uuid', 'exists:accounting_accounts,id'],
            'credit_account_id' => ['required', 'uuid', 'exists:accounting_accounts,id', 'different:debit_account_id'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'event_key.required' => 'Event mapping wajib dipilih.',
            'event_key.in' => 'Event mapping tidak valid.',
            'debit_account_id.required' => 'Akun debit wajib dipilih.',
            'credit_account_id.required' => 'Akun kredit wajib dipilih.',
            'credit_account_id.different' => 'Akun debit dan kredit tidak boleh sama.',
        ];
    }
}
