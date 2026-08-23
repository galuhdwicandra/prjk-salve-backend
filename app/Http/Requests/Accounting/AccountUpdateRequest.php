<?php
namespace App\Http\Requests\Accounting;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AccountUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        $accountId = $this->route('account')?->id ?? $this->route('account');

        return [
            'branch_id'       => ['nullable', 'uuid', 'exists:branches,id'],
            'parent_id'       => [
                'nullable',
                'uuid',
                'exists:accounting_accounts,id',
                Rule::notIn([(string) $accountId]),
            ],
            'code'            => [
                'sometimes',
                'required',
                'string',
                'max:32',
                Rule::unique('accounting_accounts', 'code')
                    ->ignore($accountId)
                    ->where(fn($query) => $query->where('branch_id', $this->input('branch_id'))),
            ],
            'name'            => ['sometimes', 'required', 'string', 'max:150'],
            'description'     => ['nullable', 'string', 'max:500'],
            'type'            => ['sometimes', 'required', Rule::in(['ASSET', 'LIABILITY', 'EQUITY', 'REVENUE', 'EXPENSE'])],
            'normal_balance'  => ['sometimes', 'required', Rule::in(['DEBIT', 'CREDIT'])],
            'is_cash_account' => ['sometimes', 'boolean'],
            'is_active'       => ['sometimes', 'boolean'],
            'sort_order'      => ['sometimes', 'integer', 'min:0'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            if (! $this->has('type') || ! $this->has('normal_balance')) {
                return;
            }

            $type   = $this->input('type');
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
            'code.required'    => 'Kode akun wajib diisi.',
            'code.unique'      => 'Kode akun sudah digunakan pada scope cabang yang sama.',
            'parent_id.not_in' => 'Akun induk tidak boleh sama dengan akun yang sedang diedit.',
            'name.required'    => 'Nama akun wajib diisi.',
        ];
    }
}
