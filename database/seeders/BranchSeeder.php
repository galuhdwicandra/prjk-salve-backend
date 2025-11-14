<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class BranchSeeder extends Seeder
{
    public function run(): void
    {
        if (!Schema::hasTable('branches')) {
            // modul cabang belum dimigrasi pada M0 → skip aman
            return;
        }

        $exists = DB::table('branches')->count() > 0;
        if ($exists)
            return;

        DB::table('branches')->insert([
            'id' => (string) Str::uuid(),
            'code' => 'CBG-001',
            'name' => 'Cabang Utama',
            'address' => 'Jl. Contoh No. 1',
            'invoice_prefix' => 'SLV',
            'reset_policy' => 'monthly',
        ]);
    }
}
