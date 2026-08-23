<?php

namespace App\Http\Requests\CashTransactions;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CashTransactionStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'branch_id' => ['nullable', 'uuid', 'exists:branches,id'],
            'kind' => ['required', Rule::in(['IN', 'OUT'])],
            'trx_date' => ['required', 'date'],
            'cash_account_id' => ['required', 'uuid', 'exists:accounting_accounts,id'],
            'contact_id' => ['nullable', 'uuid', 'exists:contacts,id'],
            'description' => ['nullable', 'string', 'max:1000'],
            'attachment' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:10240'],
            'lines' => ['required', 'array', 'min:1'],
            'lines.*.transaction_category_id' => ['required', 'uuid', 'exists:transaction_categories,id'],
            'lines.*.description' => ['nullable', 'string', 'max:200'],
            'lines.*.amount' => ['required', 'numeric', 'gt:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'trx_date.required' => 'Tanggal transaksi wajib diisi.',
            'cash_account_id.required' => 'Akun kas/bank wajib dipilih.',
            'cash_account_id.exists' => 'Akun kas/bank tidak ditemukan.',
            'lines.required' => 'Minimal satu baris transaksi wajib diisi.',
            'lines.*.transaction_category_id.required' => 'Kategori wajib dipilih.',
            'lines.*.amount.gt' => 'Nominal harus lebih dari nol.',
        ];
    }
}
