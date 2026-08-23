<?php
namespace App\Http\Requests\Vouchers;

use App\Models\Voucher;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class VoucherUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        /** @var Voucher $voucher */
        $voucher = $this->route('voucher');
        return $this->user()?->can('update', $voucher) ?? false;
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('code')) {
            $this->merge(['code' => strtoupper(trim((string) $this->input('code')))]);
        }
    }

    public function rules(): array
    {
        /** @var Voucher $voucher */
        $voucher = $this->route('voucher');

        return [
            'branch_id'              => ['nullable', 'uuid'],
            'code'                   => ['sometimes', 'string', 'regex:/^[A-Z0-9]{4,10}$/', Rule::unique('vouchers', 'code')->ignore($voucher?->id, 'id')],
            'name'                   => ['sometimes', 'string', 'max:100'],
            'type'                   => ['sometimes', Rule::in(['PERCENT', 'NOMINAL'])],
            'value'                  => ['sometimes', 'numeric', 'min:0'],
            'start_at'               => ['nullable', 'date'],
            'end_at'                 => ['nullable', 'date', 'after_or_equal:start_at'],
            'min_total'              => ['nullable', 'numeric', 'min:0'],
            'usage_limit'            => ['nullable', 'integer', 'min:1'],
            'active'                 => ['boolean'],
            'is_archived'            => ['boolean'],
            'stack_voucher'          => ['boolean'],
            'stack_discount'         => ['boolean'],
            'percent_after_discount' => ['boolean'],
        ];
    }
}
