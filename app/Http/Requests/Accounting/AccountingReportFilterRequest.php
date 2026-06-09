<?php
namespace App\Http\Requests\Accounting;

use Illuminate\Foundation\Http\FormRequest;

class AccountingReportFilterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null
        && $this->user()->hasAnyRole(['Superadmin', 'Admin Cabang', 'Akuntansi']);
    }

    public function rules(): array
    {
        return [
            'date_from' => ['required', 'date'],
            'date_to'   => ['required', 'date', 'after_or_equal:date_from'],
            'branch_id' => ['nullable', 'uuid'],
            'basis'     => ['nullable', 'string', 'in:posted,journal_posted'],
        ];
    }

    public function messages(): array
    {
        return [
            'date_from.required'     => 'Tanggal awal wajib diisi.',
            'date_from.date'         => 'Tanggal awal tidak valid.',
            'date_to.required'       => 'Tanggal akhir wajib diisi.',
            'date_to.date'           => 'Tanggal akhir tidak valid.',
            'date_to.after_or_equal' => 'Tanggal akhir tidak boleh lebih kecil dari tanggal awal.',
            'branch_id.uuid'         => 'Cabang tidak valid.',
            'basis.in'               => 'Basis laporan tidak valid.',
        ];
    }
}
