<?php

namespace App\Http\Requests\Orders;

use Illuminate\Foundation\Http\FormRequest;

class OrderApplyVoucherRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Otorisasi pada controller via $this->authorize('update', $order)
        return true;
    }

    public function rules(): array
    {
        return [
            'code' => ['required', 'string', 'max:40'],
        ];
    }
}
