<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Service;

class ServiceUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        $service = $this->route('service');
        return $this->user()?->can('update', $service) ?? false;
    }

    public function rules(): array
    {
        return [
            'category_id' => ['required', 'uuid', 'exists:service_categories,id'],
            'name' => ['required', 'string', 'max:150'],
            'unit' => ['required', 'string', 'max:32'],
            'price_default' => ['required', 'numeric', 'min:0'],
            'is_active' => ['required', 'boolean'],
        ];
    }
}
