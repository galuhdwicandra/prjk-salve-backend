<?php
namespace App\Http\Requests\Accounting;

use Illuminate\Foundation\Http\FormRequest;

class BalanceSheetFilterRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();

        return $user !== null
        && $user->hasAnyRole(['Superadmin', 'Admin Cabang', 'Akuntansi']);
    }

    public function rules(): array
    {
        return [
            'date_from' => ['required', 'date'],
            'date_to'   => ['required', 'date', 'after_or_equal:date_from'],
            'branch_id' => ['nullable', 'uuid', 'exists:branches,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'date_from.required'     => 'Tanggal awal wajib diisi.',
            'date_from.date'         => 'Tanggal awal tidak valid.',
            'date_to.required'       => 'Tanggal akhir wajib diisi.',
            'date_to.date'           => 'Tanggal akhir tidak valid.',
            'date_to.after_or_equal' => 'Tanggal akhir harus sama atau setelah tanggal awal.',
            'branch_id.uuid'         => 'Cabang tidak valid.',
            'branch_id.exists'       => 'Cabang tidak ditemukan.',
        ];
    }
}
