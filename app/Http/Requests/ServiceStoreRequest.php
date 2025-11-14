<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Service;

class ServiceStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', Service::class) ?? false;
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
