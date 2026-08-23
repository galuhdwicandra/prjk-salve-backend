<?php
namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        /** @var \App\Models\User|null $target */
        $target = $this->route('user') ?? User::query()->find($this->route('id'));
        return $target ? ($this->user()?->can('update', $target) ?? false) : false;
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('username')) {
            $this->merge(['username' => strtolower(trim((string) $this->input('username')))]);
        }
        if ($this->has('email')) {
            $this->merge(['email' => strtolower(trim((string) $this->input('email')))]);
        }
    }

    public function rules(): array
    {
        /** @var \App\Models\User|null $target */
        $target = $this->route('user') ?? User::query()->find($this->route('id'));

        return [
            'name'         => ['sometimes', 'string', 'max:150'],
            'email'        => [
                'sometimes',
                'email',
                'max:190',
                $target ? Rule::unique('users', 'email')->ignore($target->id) : Rule::unique('users', 'email'),
            ],
            'username'     => [
                'sometimes',
                'string',
                'min:3',
                'max:50',
                'regex:/^[a-z0-9_.]+$/',
                $target ? Rule::unique('users', 'username')->ignore($target->id) : Rule::unique('users', 'username'),
            ],
            // opsional: kuatkan rule password
            'password'     => ['nullable', Password::min(8)->mixedCase()->numbers()],
            'is_active'    => ['sometimes', 'boolean'],
            'branch_id'    => ['sometimes', 'nullable', 'uuid', 'exists:branches,id'],
            'role'         => [
                'sometimes',
                'string',
                'max:100',
                Rule::exists('roles', 'name')->where('guard_name', 'web'),
            ],
            'role_label'   => ['sometimes', 'nullable', 'string', 'max:100'],
            'modules'      => ['sometimes', 'array'],
            'modules.*'    => ['string', Rule::in(User::MODULES)],
            'manager'      => ['sometimes', 'boolean'],
            'all_branches' => ['sometimes', 'boolean'],
            'show_balance' => ['sometimes', 'boolean'],
            'custom_price' => ['sometimes', 'boolean'],
            'branch_ids'   => ['sometimes', 'array'],
            'branch_ids.*' => ['uuid', 'exists:branches,id'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($v) {
            /** @var \App\Models\User|null $actor */
            $actor = $this->user();
            /** @var \App\Models\User|null $target */
            $target = $this->route('user');

            if (! $actor || ! $target) {
                return;
            }

            if ($actor->all_branches) {
                return;
            }

            if (! $actor->inBranch((string) $target->branch_id)) {
                $v->errors()->add('user', 'Anda tidak berwenang mengubah user di cabang lain.');
            }

            if ($this->has('branch_id') && ! $actor->inBranch((string) $this->input('branch_id'))) {
                $v->errors()->add('branch_id', 'branch_id harus berada di cabang Anda.');
            }

            if ($this->boolean('all_branches')) {
                $v->errors()->add('all_branches', 'Anda tidak boleh memberi akses semua cabang.');
            }

            if ($this->has('branch_ids')) {
                $ids = array_map('strval', (array) $this->input('branch_ids', []));
                if (array_diff($ids, $actor->branchIds())) {
                    $v->errors()->add('branch_ids', 'Anda hanya boleh memberi akses ke cabang Anda sendiri.');
                }
            }
        });
    }
}
