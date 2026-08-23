<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BranchTypeStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', \App\Models\BranchType::class) ?? false;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:120'],
        ];
    }
}
