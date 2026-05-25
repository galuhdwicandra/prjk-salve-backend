<?php

namespace App\Http\Requests\Accounting;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AccountStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'branch_id' => ['nullable', 'uuid', 'exists:branches,id'],
            'parent_id' => ['nullable', 'uuid', 'exists:accounting_accounts,id'],
            'code' => [
                'required',
                'string',
                'max:32',
                Rule::unique('accounting_accounts', 'code')
                    ->where(fn ($query) => $query->where('branch_id', $this->input('branch_id'))),
            ],
            'name' => ['required', 'string', 'max:150'],
            'type' => ['required', Rule::in(['ASSET', 'LIABILITY', 'EQUITY', 'REVENUE', 'EXPENSE'])],
            'normal_balance' => ['required', Rule::in(['DEBIT', 'CREDIT'])],
            'is_cash_account' => ['sometimes', 'boolean'],
            'is_active' => ['sometimes', 'boolean'],
            'sort_order' => ['sometimes', 'integer', 'min:0'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $type = $this->input('type');
            $normal = $this->input('normal_balance');

            $expected = match ($type) {
                'ASSET', 'EXPENSE' => 'DEBIT',
                'LIABILITY', 'EQUITY', 'REVENUE' => 'CREDIT',
                default => null,
            };

            if ($expected !== null && $normal !== $expected) {
                $validator->errors()->add(
                    'normal_balance',
                    "Normal balance untuk {$type} harus {$expected}."
                );
            }
        });
    }

    public function messages(): array
    {
        return [
            'code.required' => 'Kode akun wajib diisi.',
            'code.unique' => 'Kode akun sudah digunakan pada scope cabang yang sama.',
            'name.required' => 'Nama akun wajib diisi.',
            'type.required' => 'Tipe akun wajib dipilih.',
            'normal_balance.required' => 'Normal balance wajib dipilih.',
        ];
    }
}
