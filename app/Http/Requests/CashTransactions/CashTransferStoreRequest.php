<?php

namespace App\Http\Requests\CashTransactions;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CashTransferStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'branch_id' => ['nullable', 'uuid', 'exists:branches,id'],
            'trx_date' => ['required', 'date'],
            'from_account_id' => ['required', 'uuid', 'exists:accounting_accounts,id', 'different:to_account_id'],
            'to_account_id' => ['required', 'uuid', 'exists:accounting_accounts,id'],
            'amount' => ['required', 'numeric', 'gt:0'],
            'description' => ['nullable', 'string', 'max:1000'],
            'fee_amount' => ['nullable', 'numeric', 'min:0'],
            'fee_bearer' => ['nullable', Rule::in(['SENDER', 'RECEIVER'])],
            'fee_category_id' => ['nullable', 'uuid', 'exists:transaction_categories,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'trx_date.required' => 'Tanggal pindah dana wajib diisi.',
            'from_account_id.required' => 'Akun asal wajib dipilih.',
            'from_account_id.different' => 'Akun asal dan akun tujuan tidak boleh sama.',
            'to_account_id.required' => 'Akun tujuan wajib dipilih.',
            'amount.gt' => 'Nominal transfer harus lebih dari nol.',
        ];
    }
}
