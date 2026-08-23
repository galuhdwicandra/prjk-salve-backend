<?php
namespace App\Http\Requests;

use App\Models\Service;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ServiceStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', Service::class) ?? false;
    }

    public function rules(): array
    {
        return [
            'category_id'   => ['required', 'uuid', 'exists:service_categories,id'],
            'parent_id'     => ['nullable', 'uuid', Rule::exists('services', 'id')->whereNull('parent_id')],
            'name'          => [
                'required',
                'string',
                'max:150',
                Rule::unique('services', 'name')
                    ->where('category_id', $this->input('category_id'))
                    ->where('parent_id', $this->input('parent_id') ?: null),
            ],
            'unit'          => ['required', 'string', 'max:32'],
            'price_default' => ['required', 'numeric', 'min:0'],
            'is_active'     => ['required', 'boolean'],
        ];
    }
}
