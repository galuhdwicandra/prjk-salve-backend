<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class BranchSeeder extends Seeder
{
    public function run(): void
    {
        if (! Schema::hasTable('branches')) {
            // modul cabang belum dimigrasi pada M0 → skip aman
            return;
        }

        $exists = DB::table('branches')->count() > 0;
        if ($exists) {
            return;
        }

        DB::table('branches')->insert([
            'id'             => (string) Str::uuid(),
            'code'           => 'CBG-001',
            'name'           => 'Cabang Permata Biru',
            'address'        => 'Komplek Permata Biru Blok Ar.06',
            'invoice_prefix' => 'SLV',
            'reset_policy'   => 'monthly',
        ]);
        DB::table('branches')->insert([
            'id'             => (string) Str::uuid(),
            'code'           => 'CBG-002',
            'name'           => 'Cabang Ujung Berung',
            'address'        => 'Jl. A.H. Nasution No. 112',
            'invoice_prefix' => 'SLV',
            'reset_policy'   => 'monthly',
        ]);
        DB::table('branches')->insert([
            'id'             => (string) Str::uuid(),
            'code'           => 'CBG-003',
            'name'           => 'Cabang Rancabolang',
            'address'        => 'Jl. Rancabolang',
            'invoice_prefix' => 'SLV',
            'reset_policy'   => 'monthly',
        ]);
    }
}
