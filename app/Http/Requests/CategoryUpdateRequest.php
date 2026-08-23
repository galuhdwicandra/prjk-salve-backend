<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\ServiceCategory;
use Illuminate\Validation\Rule;

class CategoryUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        $category = $this->route('category');
        return $this->user()?->can('update', $category) ?? false;
    }

    public function rules(): array
    {
        $category = $this->route('category');
        return [
            'name' => ['required', 'string', 'max:120', Rule::unique('service_categories', 'name')->ignore($category->id)],
            'is_active' => ['required', 'boolean'],
        ];
    }
}
