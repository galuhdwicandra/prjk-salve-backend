<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $t) {
            // nullable dulu supaya tidak mengganggu data existing
            $t->string('username', 50)->nullable()->after('email');
            $t->unique('username');
        });

        // Backfill username dari prefix email (lowercase, aman duplikasi)
        $rows = DB::table('users')->select('id', 'email')->get();
        foreach ($rows as $r) {
            $base = strtolower(preg_replace('/[^a-z0-9_.]+/i', '', explode('@', (string) $r->email)[0] ?? 'user'));
            $u = $base !== '' ? $base : 'user';
            $i = 1;
            while (DB::table('users')->where('username', $u)->exists()) {
                $u = $base . $i;
                $i++;
            }
            DB::table('users')->where('id', $r->id)->update(['username' => $u]);
        }

        // Opsional jika ingin paksa NOT NULL setelah backfill:
        // Schema::table('users', function (Blueprint $t) {
        //     $t->string('username', 50)->nullable(false)->change();
        // });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $t) {
            $t->dropUnique(['username']);
            $t->dropColumn('username');
        });
    }
};
