<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BranchUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        $branch = $this->route('branch');
        return $this->user()?->can('update', $branch) ?? false;
    }

    public function rules(): array
    {
        $branch = $this->route('branch');
        return [
            'code' => ['required', 'string', 'max:32', 'unique:branches,code,' . $branch->id],
            'name' => ['required', 'string', 'max:150'],
            'address' => ['nullable', 'string', 'max:255'],
            'invoice_prefix' => ['required', 'string', 'regex:/^[A-Z]{2,8}$/'],
            'reset_policy' => ['required', 'in:monthly,never'],
        ];
    }
}
