<?php

namespace App\Http\Requests\Vouchers;

use Illuminate\Foundation\Http\FormRequest;

class VoucherPreviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'code'        => ['required', 'string', 'max:40'],
            'subtotal'    => ['required', 'numeric', 'min:0'],
            'branch_id'   => ['nullable', 'uuid', 'exists:branches,id'],
            'customer_id' => ['nullable', 'uuid', 'exists:customers,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'code.required'     => 'Kode voucher wajib diisi.',
            'subtotal.required' => 'Subtotal order wajib dikirim.',
        ];
    }
}
