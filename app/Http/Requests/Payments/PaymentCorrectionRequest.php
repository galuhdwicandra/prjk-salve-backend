<?php

namespace App\Http\Requests\Payments;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PaymentCorrectionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'correction_type' => [
                'required',
                'string',
                Rule::in(['RESET_TO_PENDING']),
            ],
            'reason' => [
                'required',
                'string',
                'min:5',
                'max:500',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'correction_type.required' => 'Jenis koreksi wajib diisi.',
            'correction_type.in' => 'Jenis koreksi tidak valid.',
            'reason.required' => 'Alasan koreksi wajib diisi.',
            'reason.min' => 'Alasan koreksi minimal 5 karakter.',
            'reason.max' => 'Alasan koreksi maksimal 500 karakter.',
        ];
    }
}
