<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesTableSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            'Superadmin',
            'Admin Cabang',
            'Kasir',
            'Petugas Cuci',
            'Kurir',
        ];

        foreach ($roles as $name) {
            Role::query()->firstOrCreate(
                ['name' => $name, 'guard_name' => 'web'], // guard default
                []
            );
        }
    }
}
