<?php
namespace App\Http\Requests\PaymentMethods;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PaymentMethodRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'code'       => [
                'sometimes',
                'nullable',
                'string',
                'max:30',
                'regex:/^[A-Za-z0-9_]+$/',
                Rule::unique('payment_methods', 'code')->ignore($this->route('paymentMethod')),
            ],
            'name'       => [$this->isMethod('POST') ? 'required' : 'sometimes', 'string', 'max:100'],
            'is_active'  => ['sometimes', 'boolean'],
            'sort_order' => ['sometimes', 'integer', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'code.unique'   => 'Kode metode pembayaran sudah digunakan.',
            'code.regex'    => 'Kode hanya boleh huruf, angka, dan underscore.',
            'name.required' => 'Nama metode pembayaran wajib diisi.',
        ];
    }
}
