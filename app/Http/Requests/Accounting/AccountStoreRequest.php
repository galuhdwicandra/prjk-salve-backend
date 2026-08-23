<?php
namespace App\Http\Requests\Accounting;

use App\Models\AccountingAccount;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AccountStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();

        if ($user === null) {
            return false;
        }

        return $user->canModule('set-coa') || $this->boolean('is_cash_account');
    }

    protected function prepareForValidation(): void
    {
        if (! $this->boolean('is_cash_account')) {
            return;
        }

        $this->merge([
            'code'           => $this->filled('code') ? $this->input('code') : $this->nextCashCode(),
            'type'           => $this->input('type', 'ASSET'),
            'normal_balance' => $this->input('normal_balance', 'DEBIT'),
        ]);
    }

    private function nextCashCode(): string
    {
        $max = AccountingAccount::query()
            ->pluck('code')
            ->filter(fn($code) => ctype_digit((string) $code))
            ->map(fn($code) => (int) $code)
            ->filter(fn($code) => $code >= 1000 && $code < 2000)
            ->max() ?? 1100;

        return (string) ($max + 1);
    }

    public function rules(): array
    {
        return [
            'branch_id'       => ['nullable', 'uuid', 'exists:branches,id'],
            'parent_id'       => ['nullable', 'uuid', 'exists:accounting_accounts,id'],
            'code'            => [
                'required',
                'string',
                'max:32',
                Rule::unique('accounting_accounts', 'code')
                    ->where(fn($query) => $query->where('branch_id', $this->input('branch_id'))),
            ],
            'name'            => ['required', 'string', 'max:150'],
            'description'     => ['nullable', 'string', 'max:500'],
            'type'            => ['required', Rule::in(['ASSET', 'LIABILITY', 'EQUITY', 'REVENUE', 'EXPENSE'])],
            'normal_balance'  => ['required', Rule::in(['DEBIT', 'CREDIT'])],
            'is_cash_account' => ['sometimes', 'boolean'],
            'is_active'       => ['sometimes', 'boolean'],
            'sort_order'      => ['sometimes', 'integer', 'min:0'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
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
            'code.required'           => 'Kode akun wajib diisi.',
            'code.unique'             => 'Kode akun sudah digunakan pada scope cabang yang sama.',
            'name.required'           => 'Nama akun wajib diisi.',
            'type.required'           => 'Tipe akun wajib dipilih.',
            'normal_balance.required' => 'Normal balance wajib dipilih.',
        ];
    }
}
