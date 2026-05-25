<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CashSessionOpenRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

public function rules(): array
{
    $isSuperadmin = $this->user()?->hasRole('Superadmin') ?? false;

    return [
        'branch_id' => [
            $isSuperadmin ? 'required' : 'nullable',
            'string',
            'exists:branches,id',
        ],
        'business_date' => ['required', 'date'],
        'opening_cash' => ['required', 'numeric', 'min:0'],
        'notes' => ['nullable', 'string'],
    ];
}

public function messages(): array
{
    return [
        'branch_id.required' => 'Cabang wajib dipilih.',
        'branch_id.exists' => 'Cabang yang dipilih tidak valid.',
        'business_date.required' => 'Tanggal bisnis wajib diisi.',
        'opening_cash.required' => 'Kas awal wajib diisi.',
    ];
}
}
