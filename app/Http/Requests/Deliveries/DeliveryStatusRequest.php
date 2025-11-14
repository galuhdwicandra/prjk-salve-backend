<?php

namespace App\Http\Requests\Deliveries;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DeliveryStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => [
                'required',
                Rule::in([
                    'CREATED',
                    'ASSIGNED',
                    'ON_THE_WAY',
                    'PICKED',
                    'HANDOVER',
                    'COMPLETED',
                    'FAILED',
                    'CANCELLED'
                ]),
            ],
            'note' => ['nullable', 'string', 'max:200'],
            'photo' => ['nullable', 'image', 'max:4096'],
        ];
    }

    public function messages(): array
    {
        return [
            'photo.image' => 'File foto harus berupa gambar.',
            'photo.max' => 'Ukuran foto maksimal 4MB.',
        ];
    }
}
