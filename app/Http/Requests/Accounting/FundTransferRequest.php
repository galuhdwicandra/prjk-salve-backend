<?php
namespace App\Http\Requests\Accounting;

use Illuminate\Foundation\Http\FormRequest;

class FundTransferRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'branch_id'       => ['nullable', 'uuid', 'exists:branches,id'],
            'journal_date'    => ['required', 'date'],
            'description'     => ['nullable', 'string', 'max:1000'],
            'from_account_id' => ['required', 'uuid', 'exists:accounting_accounts,id', 'different:to_account_id'],
            'to_account_id'   => ['required', 'uuid', 'exists:accounting_accounts,id'],
            'amount'          => ['required', 'numeric', 'gt:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'journal_date.required'     => 'Tanggal transfer wajib diisi.',
            'journal_date.date'         => 'Tanggal transfer tidak valid.',
            'from_account_id.required'  => 'Akun asal wajib dipilih.',
            'from_account_id.exists'    => 'Akun asal tidak ditemukan.',
            'from_account_id.different' => 'Akun asal dan akun tujuan tidak boleh sama.',
            'to_account_id.required'    => 'Akun tujuan wajib dipilih.',
            'to_account_id.exists'      => 'Akun tujuan tidak ditemukan.',
            'amount.required'           => 'Jumlah transfer wajib diisi.',
            'amount.gt'                 => 'Jumlah transfer harus lebih dari nol.',
        ];
    }
}
