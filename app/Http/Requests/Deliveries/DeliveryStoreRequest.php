<?php

namespace App\Http\Requests\Deliveries;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DeliveryStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', \App\Models\Delivery::class) ?? false;
    }

    protected function prepareForValidation(): void
    {
        // normalisasi 'type'
        if ($t = $this->input('type')) {
            $this->merge(['type' => strtolower($t)]);
        }
    }

    public function rules(): array
    {
        $branchId = $this->user()?->branch_id;

        return [
            'order_id' => [
                'required', 'uuid',
                Rule::exists('orders', 'id')
                    ->when(!$this->user()?->hasRole('Superadmin'),
                        fn($q) => $q->where('branch_id', $branchId)),
            ],
            'type' => ['required', Rule::in(['pickup','delivery','return'])],
            'zone_id' => ['nullable','uuid'],
            'fee' => ['nullable','numeric','min:0'],
        ];
    }
}
