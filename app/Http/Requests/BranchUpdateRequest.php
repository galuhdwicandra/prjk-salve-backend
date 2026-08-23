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
            'code'           => ['required', 'string', 'max:32', 'unique:branches,code,' . $branch->id],
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
