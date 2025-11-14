<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        $order = $this->route('order');
        return $this->user()?->can('transitionStatus', $order) ?? false;
    }

    public function rules(): array
    {
        return [
            'next' => ['required', 'string', 'in:QUEUE,WASHING,DRYING,IRONING,READY,DELIVERING,PICKED_UP,CANCELED'],
        ];
    }
}
