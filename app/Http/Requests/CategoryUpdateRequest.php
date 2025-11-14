<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\ServiceCategory;

class CategoryUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        $category = $this->route('category');
        return $this->user()?->can('update', $category) ?? false;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:120'],
            'is_active' => ['required', 'boolean'],
        ];
    }
}
