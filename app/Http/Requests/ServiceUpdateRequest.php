<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ServiceUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        $service = $this->route('service');
        return $this->user()?->can('update', $service) ?? false;
    }

    public function rules(): array
    {
        $service = $this->route('service');
        return [
            'category_id'   => ['required', 'uuid', 'exists:service_categories,id'],
            'parent_id'     => $service->variants()->exists()
                ? ['prohibited']
                : [
                'nullable',
                'uuid',
                Rule::exists('services', 'id')->whereNull('parent_id')->whereNot('id', $service->id),
            ],
            'name'          => [
                'required',
                'string',
                'max:150',
                Rule::unique('services', 'name')
                    ->where('category_id', $this->input('category_id'))
                    ->where('parent_id', $this->input('parent_id') ?: null)
                    ->ignore($service->id),
            ],
            'unit'          => ['required', 'string', 'max:32'],
            'price_default' => ['required', 'numeric', 'min:0'],
            'is_active'     => ['required', 'boolean'],
        ];
    }
}
