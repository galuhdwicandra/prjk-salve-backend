<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // --- Pastikan roles tersedia (sesuai dump & guard 'web') ---
        $roleNames = ['Superadmin', 'Admin Cabang', 'Kasir', 'Petugas Cuci', 'Kurir'];
        foreach ($roleNames as $r) {
            Role::query()->firstOrCreate(['name' => $r, 'guard_name' => 'web']);
        }

        // --- Siapkan branch default (jika tabel branches ada dan masih kosong) ---
        $defaultBranchId = null;
        if (Schema::hasTable('branches')) {
            $defaultBranchId = DB::table('branches')->value('id');
            if (!$defaultBranchId) {
                $defaultBranchId = (string) Str::uuid();
                DB::table('branches')->insert([
                    'id'            => $defaultBranchId,
                    'code'          => 'CBG-001',
                    'name'          => 'Cabang Utama',
                    'address'       => 'Alamat Cabang Utama',
                    'invoice_prefix'=> 'SLV',
                    'reset_policy'  => 'monthly',
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ]);
            }
        }

        // --- Data seed user inti ---
        $users = [
            [
                'name'       => 'Superadmin',
                'email'      => 'superadmin@gmail.com',
                'password'   => 'password',
                'role'       => 'Superadmin',
                'branch_id'  => null,               // Superadmin tanpa cabang
                'is_active'  => true,
            ],
            [
                'name'       => 'Admin Cabang',
                'email'      => 'admincabang@gmail.com',
                'password'   => 'password',
                'role'       => 'Admin Cabang',
                'branch_id'  => $defaultBranchId,   // wajib punya cabang
                'is_active'  => true,
            ],
            [
                'name'       => 'Kasir',
                'email'      => 'kasir@gmail.com',
                'password'   => 'password',
                'role'       => 'Kasir',
                'branch_id'  => $defaultBranchId,
                'is_active'  => true,
            ],
            [
                'name'       => 'Petugas Cuci',
                'email'      => 'petugascuci@gmail.com',
                'password'   => 'password',
                'role'       => 'Petugas Cuci',
                'branch_id'  => $defaultBranchId,
                'is_active'  => true,
            ],
            [
                'name'       => 'Kurir',
                'email'      => 'kurir@gmail.com',
                'password'   => 'password',
                'role'       => 'Kurir',
                'branch_id'  => $defaultBranchId,
                'is_active'  => true,
            ],
        ];

        // --- Boolean: apakah kolom branch_uuid ada? (beberapa dump masih menyertakan kolom ini) ---
        $hasBranchUuid = Schema::hasColumn('users', 'branch_uuid');

        DB::transaction(function () use ($users, $hasBranchUuid) {
            foreach ($users as $u) {
                /** @var \App\Models\User $user */
                $user = User::query()->firstOrCreate(
                    ['email' => $u['email']],
                    [
                        'name'       => $u['name'],
                        'password'   => Hash::make($u['password']),
                        'is_active'  => (bool) $u['is_active'],
                        'branch_id'  => $u['branch_id'],
                        // jangan set email_verified_at; biarkan null
                    ]
                );

                // Jika user sudah ada, update nama/is_active/branch saja (JANGAN overwrite password)
                $dirty = false;
                if ($user->name !== $u['name']) { $user->name = $u['name']; $dirty = true; }
                if ((bool)$user->is_active !== (bool)$u['is_active']) { $user->is_active = (bool)$u['is_active']; $dirty = true; }
                if ((string)$user->branch_id !== (string)$u['branch_id']) { $user->branch_id = $u['branch_id']; $dirty = true; }
                if ($dirty) { $user->save(); }

                // Sinkronkan branch_uuid jika kolom tersedia
                if ($hasBranchUuid) {
                    $branchUuid = $u['branch_id'] ?: null;
                    if ($user->branch_uuid !== $branchUuid) {
                        $user->forceFill(['branch_uuid' => $branchUuid])->save();
                    }
                }

                // Pastikan role terpasang (tanpa duplikasi)
                if (method_exists($user, 'syncRoles')) {
                    // Banyak project lebih suka 1 role/user; kalau mau multi-role ganti ke syncWithoutDetaching
                    $user->syncRoles([$u['role']]);
                } elseif (method_exists($user, 'assignRole')) {
                    if (!$user->hasRole($u['role'])) {
                        $user->assignRole($u['role']);
                    }
                }
            }
        });
    }
}
