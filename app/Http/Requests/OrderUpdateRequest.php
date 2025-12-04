<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_id'        => ['sometimes', 'nullable', 'uuid', 'exists:customers,id'],
            'discount'           => ['sometimes', 'numeric', 'min:0'],
            'notes'              => ['sometimes', 'nullable', 'string', 'max:500'],

            'items'              => ['sometimes', 'array', 'min:1'],
            'items.*.id'         => ['sometimes', 'uuid', 'exists:order_items,id'],
            'items.*.service_id' => ['required_with:items', 'uuid', 'exists:services,id', 'distinct'],
            'items.*.qty'        => ['required_with:items', 'integer', 'min:1'],
            'items.*.note'       => ['sometimes', 'nullable', 'string', 'max:300'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $data = $this->all();
        if (isset($data['discount']) && $data['discount'] !== null) {
            $data['discount'] = is_numeric($data['discount']) ? (float) $data['discount'] : $data['discount'];
        }
        if (isset($data['items']) && is_array($data['items'])) {
            foreach ($data['items'] as $k => $row) {
                if (isset($row['qty'])) {
                    $data['items'][$k]['qty'] = is_numeric($row['qty']) ? (int) $row['qty'] : $row['qty'];
                }
            }
        }
        $this->replace($data);
    }
}
