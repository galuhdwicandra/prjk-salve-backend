<?php

namespace App\Http\Requests\Deliveries;

use Illuminate\Foundation\Http\FormRequest;

class DeliveryAssignRequest extends FormRequest
{
    public function authorize(): bool
    {
        // authorize di controller via policy assignCourier
        return true;
    }

    public function rules(): array
    {
        return [
            'courier_id' => ['required', 'integer', 'exists:users,id'],
        ];
    }
}
