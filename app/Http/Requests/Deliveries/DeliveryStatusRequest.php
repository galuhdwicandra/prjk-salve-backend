<?php
namespace App\Http\Requests\Deliveries;

use App\Models\Delivery;
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
                    'CANCELLED',
                ]),
            ],
            'note'   => ['nullable', 'string', 'max:200'],
            'photo'  => [
                Rule::requiredIf(function (): bool {
                    $delivery = $this->route('delivery');

                    return $this->input('status') === 'COMPLETED'
                    && $delivery instanceof Delivery
                    && ! $delivery->handover_photo;
                }),
                'image',
                'max:4096',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'photo.required' => 'Foto serah-terima wajib ditambahkan.',
            'photo.image' => 'File foto harus berupa gambar.',
            'photo.max'   => 'Ukuran foto maksimal 4MB.',
        ];
    }
}
