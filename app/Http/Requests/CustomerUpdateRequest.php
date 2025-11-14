<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Customer;

class CustomerUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        /** @var Customer $customer */
        $customer = $this->route('customer');
        return $this->user()?->can('update', $customer) ?? false;
    }

    public function rules(): array
    {
        /** @var Customer $customer */
        $customer = $this->route('customer');
        $branchId = $customer->branch_id;

        return [
            'name' => ['sometimes', 'required', 'string', 'max:150'],
            'whatsapp' => [
                'sometimes',
                'required',
                'string',
                'max:32',
                Rule::unique('customers', 'whatsapp')
                    ->where(fn($q) => $q->where('branch_id', $branchId))
                    ->ignore($customer->id, 'id'),
            ],
            'address' => ['sometimes', 'nullable', 'string', 'max:255'],
            'notes' => ['sometimes', 'nullable', 'string'],
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('whatsapp')) {
            $this->merge(['whatsapp' => preg_replace('/\s+/', '', (string) $this->input('whatsapp'))]);
        }
    }
}
