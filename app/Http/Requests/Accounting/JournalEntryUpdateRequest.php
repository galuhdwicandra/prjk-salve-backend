<?php

namespace App\Http\Requests\Accounting;

use Illuminate\Foundation\Http\FormRequest;

class JournalEntryUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'branch_id' => ['nullable', 'uuid', 'exists:branches,id'],
            'journal_date' => ['required', 'date'],
            'description' => ['nullable', 'string', 'max:1000'],

            'lines' => ['required', 'array', 'min:2'],
            'lines.*.account_id' => ['required', 'uuid', 'exists:accounting_accounts,id'],
            'lines.*.description' => ['nullable', 'string', 'max:1000'],
            'lines.*.debit' => ['nullable', 'numeric', 'min:0'],
            'lines.*.credit' => ['nullable', 'numeric', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'journal_date.required' => 'Tanggal jurnal wajib diisi.',
            'journal_date.date' => 'Tanggal jurnal tidak valid.',
            'lines.required' => 'Detail jurnal wajib diisi.',
            'lines.array' => 'Detail jurnal tidak valid.',
            'lines.min' => 'Jurnal minimal memiliki dua baris.',
            'lines.*.account_id.required' => 'Akun wajib dipilih.',
            'lines.*.account_id.exists' => 'Akun tidak ditemukan.',
        ];
    }
}
