<?php

namespace App\Http\Requests\Vouchers;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class VoucherStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', \App\Models\Voucher::class) ?? false;
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('code')) {
            $this->merge(['code' => strtoupper(trim((string) $this->input('code')))]);
        }
    }

    public function rules(): array
    {
        return [
            'branch_id' => ['nullable', 'uuid'],
            'code' => ['required', 'string', 'max:40', 'unique:vouchers,code'],
            'type' => ['required', Rule::in(['PERCENT', 'NOMINAL'])],
            'value' => ['required', 'numeric', 'min:0'],
            'start_at' => ['nullable', 'date'],
            'end_at' => ['nullable', 'date', 'after_or_equal:start_at'],
            'min_total' => ['nullable', 'numeric', 'min:0'],
            'usage_limit' => ['nullable', 'integer', 'min:1'],
            'active' => ['boolean'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($v) {
            if ($this->input('type') === 'PERCENT') {
                $val = (float) $this->input('value', 0);
                if ($val < 0 || $val > 100) {
                    $v->errors()->add('value', 'Persentase harus 0–100.');
                }
            }
            // Admin cabang hanya boleh set branch_id == branch dirinya
            $u = $this->user();
            if ($u && method_exists($u, 'hasRole') && $u->hasRole('admin')) {
                if (!$this->filled('branch_id') || $this->input('branch_id') !== $u->branch_id) {
                    $v->errors()->add('branch_id', 'Admin cabang hanya boleh membuat voucher untuk cabangnya.');
                }
            }
        });
    }
}
