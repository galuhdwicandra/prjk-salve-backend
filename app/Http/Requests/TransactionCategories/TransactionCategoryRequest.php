<?php
namespace App\Http\Requests\TransactionCategories;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TransactionCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        $creating = $this->isMethod('POST');

        return [
            'name'           => [
                $creating ? 'required' : 'sometimes',
                'string',
                'max:100',
                Rule::unique('transaction_categories', 'name')->ignore($this->route('transactionCategory')),
            ],
            'cash_in'        => [$creating ? 'required' : 'sometimes', 'boolean'],
            'cash_out'       => [$creating ? 'required' : 'sometimes', 'boolean'],
            'cashflow'       => [$creating ? 'required' : 'sometimes', Rule::in(['OPERATING', 'INVESTING', 'FINANCING'])],
            'in_account_id'  => ['sometimes', 'nullable', 'uuid', 'exists:accounting_accounts,id'],
            'out_account_id' => ['sometimes', 'nullable', 'uuid', 'exists:accounting_accounts,id'],
            'description'    => ['nullable', 'string', 'max:200'],
            'is_active'      => ['sometimes', 'boolean'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            if ($validator->errors()->isNotEmpty()) {
                return;
            }

            $cashIn  = $this->has('cash_in') ? $this->boolean('cash_in') : null;
            $cashOut = $this->has('cash_out') ? $this->boolean('cash_out') : null;

            if ($cashIn === null && $cashOut === null) {
                return;
            }

            $category = $this->route('transactionCategory');

            $finalIn  = $cashIn ?? (bool) $category?->cash_in;
            $finalOut = $cashOut ?? (bool) $category?->cash_out;

            if (! $finalIn && ! $finalOut) {
                $validator->errors()->add('cash_in', 'Pilih minimal satu jenis: Cash In atau Cash Out.');
            }
        });
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama kategori wajib diisi.',
            'name.unique'   => 'Nama kategori sudah digunakan.',
            'cashflow.in'   => 'Cashflow criteria tidak valid.',
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('name')) {
            $this->merge(['name' => trim((string) $this->input('name'))]);
        }

        if ($this->has('description')) {
            $description = trim((string) $this->input('description'));
            $this->merge(['description' => $description === '' ? null : $description]);
        }
    }
}
