<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use App\Models\User;

class UserUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        /** @var \App\Models\User|null $target */
        $target = $this->route('user') ?? User::query()->find($this->route('id'));
        return $target ? ($this->user()?->can('update', $target) ?? false) : false;
    }

    public function rules(): array
    {
        /** @var \App\Models\User|null $target */
        $target = $this->route('user') ?? User::query()->find($this->route('id'));

        return [
            'name' => ['sometimes', 'string', 'max:150'],
            'email' => [
                'sometimes',
                'email',
                'max:190',
                $target ? Rule::unique('users', 'email')->ignore($target->id) : Rule::unique('users', 'email')
            ],
            // opsional: kuatkan rule password
            'password' => ['nullable', Password::min(8)->mixedCase()->numbers()],
            'is_active' => ['sometimes', 'boolean'],
            'branch_id' => ['sometimes', 'nullable', 'uuid', 'exists:branches,id'],
            'role' => [
                'sometimes',
                'string',
                'max:100',
                Rule::exists('roles', 'name')->where('guard_name', 'web'),
            ],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($v) {
            /** @var \App\Models\User|null $actor */
            $actor = $this->user();
            /** @var \App\Models\User|null $target */
            $target = $this->route('user');

            if (!$actor || !$target)
                return;

            // Admin Cabang hanya boleh ubah user di cabangnya (Policy juga cek; ini double guard)
            if ($actor->hasRole('Admin Cabang') && (string) $actor->branch_id !== (string) $target->branch_id) {
                $v->errors()->add('user', 'Anda tidak berwenang mengubah user di cabang lain.');
            }

            // Admin Cabang: jika mengubah branch_id, harus tetap sama dengan cabang aktor
            if ($this->has('branch_id') && $actor->hasRole('Admin Cabang')) {
                $branchId = (string) $this->input('branch_id');
                if ($branchId !== (string) $actor->branch_id) {
                    $v->errors()->add('branch_id', 'branch_id harus sama dengan cabang Anda.');
                }
            }

            // Admin Cabang: tidak boleh set role Superadmin
            if ($this->has('role') && $actor->hasRole('Admin Cabang') && $this->input('role') === 'Superadmin') {
                $v->errors()->add('role', 'Admin Cabang tidak boleh menetapkan role Superadmin.');
            }
        });
    }
}
