<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', \App\Models\User::class) ?? false;
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
        return [
            'name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:190', 'unique:users,email'],
            'username' => [
                'required',
                'string',
                'min:3',
                'max:50',
                'regex:/^[a-z0-9_.]+$/',
                'unique:users,username',
            ],
            'password' => ['required', Password::min(8)->mixedCase()->numbers()],
            // Jika kamu ingin default aktif = true, ubah ke 'sometimes|boolean' lalu handle default di controller
            'is_active' => ['required', 'boolean'],
            'branch_id' => ['nullable', 'uuid', 'exists:branches,id'],
            'role' => [
                'required',
                'string',
                'max:100',
                Rule::exists('roles', 'name')->where('guard_name', 'web'),
            ],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($v) {
            $actor = $this->user();

            if (!$actor)
                return;

            // Admin Cabang: branch_id WAJIB ada & harus sama dengan cabang aktor
            if ($actor->hasRole('Admin Cabang')) {
                $branchId = (string) $this->input('branch_id', '');
                if ($branchId === '') {
                    $v->errors()->add('branch_id', 'branch_id wajib diisi untuk Admin Cabang.');
                } elseif ($branchId !== (string) $actor->branch_id) {
                    $v->errors()->add('branch_id', 'Anda hanya boleh membuat user pada cabang Anda sendiri.');
                }

                // Admin Cabang dilarang set Superadmin
                if ($this->input('role') === 'Superadmin') {
                    $v->errors()->add('role', 'Admin Cabang tidak boleh menetapkan role Superadmin.');
                }
            }
        });
    }

    public function messages(): array
    {
        return [
            'role.required' => 'Role wajib diisi.',
            'role.exists' => 'Role tidak valid.',
            'branch_id.uuid' => 'branch_id harus berupa UUID yang valid.',
            'username.required' => 'Username wajib diisi.',
            'username.unique' => 'Username sudah digunakan.',
            'username.regex' => 'Username hanya boleh huruf/angka/underscore/titik (lowercase).',
        ];
    }
}
