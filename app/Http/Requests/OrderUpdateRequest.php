<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        $order = $this->route('order');
        return $this->user()?->can('update', $order) ?? false;
    }

    public function rules(): array
    {
        return [
            'customer_id' => ['sometimes', 'nullable', 'uuid', 'exists:customers,id'],
            'notes' => ['sometimes', 'nullable', 'string'],

            'items' => ['sometimes', 'array', 'min:1'],
            'items.*.id' => ['sometimes', 'uuid'], // jika edit baris
            'items.*.service_id' => ['required_with:items.*.qty', 'uuid', 'exists:services,id'],
            'items.*.qty' => ['required_with:items', 'numeric', 'gt:0'],
        ];
    }
}
