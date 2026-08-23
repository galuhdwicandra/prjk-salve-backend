<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BranchTypeUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('update', $this->route('branchType')) ?? false;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:120'],
        ];
    }
}
