<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserService
{
    /**
     * @param array{search?:string, branch_id?:string|null, role?:string} $filters
     */
    public function paginate(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return User::query()
            ->with('roles:id,name')
            ->when(!empty($filters['search']), function ($q) use ($filters) {
                $s = $filters['search'];
                $q->where(function ($w) use ($s) {
                    $w->where('name', 'like', "%{$s}%")
                        ->orWhere('email', 'like', "%{$s}%")
                        ->orWhere('username', 'like', "%{$s}%");
                });
            })
            ->when(!empty($filters['branch_id']), fn($q) => $q->where('branch_id', $filters['branch_id']))
            ->when(
                !empty($filters['role']),
                fn($q) =>
                $q->whereHas('roles', fn($r) => $r->where('name', $filters['role']))
            )
            ->orderBy('name')
            ->paginate($perPage);
    }

    /**
     * @param array{
     *   name:string,
     *   email:string,
     *   username?:string,
     *   password:string,
     *   is_active?:bool,
     *   branch_id?:string|null,
     *   role?:string|null,
     *   roles?:array<int,string>|null
     * } $data
     */
    public function create(array $data): User
    {
        return DB::transaction(function () use ($data) {
            $user = new User();
            $user->name = $data['name'];
            $user->email = $data['email'];
            if (array_key_exists('username', $data)) {
                $user->username = strtolower(trim((string) $data['username'])); // NEW
            }

            // PILIH SALAH SATU:
            // 1) Jika model punya casts: 'password' => 'hashed', boleh langsung assign:
            // $user->password  = $data['password'];
            // 2) Paling aman (tanpa asumsi casts):
            $user->password = Hash::make($data['password']);

            $user->is_active = (bool) ($data['is_active'] ?? true);
            $user->branch_id = $data['branch_id'] ?? null;
            $user->save();

            // roles bisa string (role) atau array (roles)
            $roles = $this->normalizeRoles($data);
            if (!empty($roles)) {
                $user->syncRoles($roles);
            }

            return $user->load('roles:id,name');
        });
    }

    /**
     * @param array{
     *   name?:string,
     *   email?:string,
     *   username?:string,
     *   password?:string|null,
     *   is_active?:bool,
     *   branch_id?:string|null,
     *   role?:string|null,
     *   roles?:array<int,string>|null
     * } $data
     */
    public function update(User $user, array $data): User
    {
        return DB::transaction(function () use ($user, $data) {
            if (array_key_exists('name', $data)) {
                $user->name = $data['name'];
            }
            if (array_key_exists('email', $data)) {
                $user->email = $data['email'];
            }
            if (array_key_exists('username', $data)) {
                $user->username = strtolower(trim((string) $data['username'])); // NEW
            }
            if (!empty($data['password'])) {
                // Sama catatan seperti create():
                // $user->password = $data['password'];
                $user->password = Hash::make($data['password']);
            }
            if (array_key_exists('is_active', $data)) {
                $user->is_active = (bool) $data['is_active'];
            }
            if (array_key_exists('branch_id', $data)) {
                $user->branch_id = $data['branch_id'];
            }

            $user->save();

            if (array_key_exists('role', $data) || array_key_exists('roles', $data)) {
                $roles = $this->normalizeRoles($data);
                $user->syncRoles($roles ?: []);
            }

            return $user->load('roles:id,name');
        });
    }

    public function delete(User $user): void
    {
        DB::transaction(function () use ($user) {
            $user->syncRoles([]); // detach
            $user->delete();
        });
    }

    public function resetPassword(User $user, string $plain): void
    {
        DB::transaction(function () use ($user, $plain) {
            // $user->password = $plain; // jika pakai casts hashed
            $user->password = Hash::make($plain); // aman tanpa asumsi casts
            $user->save();
        });
    }

    public function setActive(User $user, bool $isActive): User
    {
        return DB::transaction(function () use ($user, $isActive) {
            $user->is_active = $isActive;
            $user->save();
            return $user;
        });
    }

    /**
     * @param array<int,string> $roles
     */
    public function setRoles(User $user, array $roles): User
    {
        return DB::transaction(function () use ($user, $roles) {
            $user->syncRoles($roles);
            return $user->load('roles:id,name');
        });
    }

    /**
     * Terima 'role' (string) atau 'roles' (string[]) → kembalikan string[] unik.
     *
     * @param array{role?:string|null, roles?:array<int,string>|null} $data
     * @return array<int,string>
     */
    private function normalizeRoles(array $data): array
    {
        $roles = [];
        if (!empty($data['role'])) {
            $roles[] = (string) $data['role'];
        }
        if (!empty($data['roles']) && is_array($data['roles'])) {
            $roles = array_merge($roles, $data['roles']);
        }
        // unik & bersih
        $roles = array_values(array_unique(array_filter(array_map('strval', $roles))));
        return $roles;
    }
}
