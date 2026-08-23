<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BranchStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', \App\Models\Branch::class) ?? false;
    }

    public function rules(): array
    {
        return [
            'code'           => ['nullable', 'string', 'max:32', 'unique:branches,code'],
            'name'           => ['required', 'string', 'max:150'],
            'type'           => ['required', 'string', 'exists:branch_types,code'],
            'address'        => ['nullable', 'string', 'max:255'],
            'hours'          => ['nullable', 'string', 'max:32'],
            'invoice_prefix' => ['sometimes', 'string', 'regex:/^[A-Z]{2,8}$/'],
            'reset_policy'   => ['sometimes', 'in:monthly,never'],
            'is_active'      => ['sometimes', 'boolean'],
        ];
    }
}
