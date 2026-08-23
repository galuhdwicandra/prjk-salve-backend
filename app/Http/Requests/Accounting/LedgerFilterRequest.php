<?php
namespace App\Http\Requests\Accounting;

use Illuminate\Foundation\Http\FormRequest;

class LedgerFilterRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();

        return $user !== null
        && ($user->hasAnyRole(['Superadmin', 'Admin Cabang', 'Akuntansi']) || $user->canModule('fin-kas'));
    }

    public function rules(): array
    {
        return [
            'account_id' => ['nullable', 'uuid', 'exists:accounting_accounts,id'],
            'branch_id'  => ['nullable', 'uuid', 'exists:branches,id'],
            'date_from'  => ['required', 'date'],
            'date_to'    => ['required', 'date', 'after_or_equal:date_from'],
            'per_page'   => ['nullable', 'integer', 'min:1', 'max:500'],
            'page'       => ['nullable', 'integer', 'min:1'],
        ];
    }

    public function messages(): array
    {
        return [
            'account_id.exists'      => 'Akun tidak ditemukan.',
            'date_from.required'     => 'Tanggal awal wajib diisi.',
            'date_to.required'       => 'Tanggal akhir wajib diisi.',
            'date_to.after_or_equal' => 'Tanggal akhir tidak boleh lebih kecil dari tanggal awal.',
        ];
    }
}
