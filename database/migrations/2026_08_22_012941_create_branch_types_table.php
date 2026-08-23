<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('branch_types', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('code', 32)->unique();
            $table->string('name', 120);
            $table->timestamps();
        });

        $labels = ['workshop' => 'Workshop', 'droppoint' => 'Drop Point'];
        $codes  = DB::table('branches')->distinct()->pluck('type')->filter()->all();

        foreach (array_unique(array_merge(['workshop', 'droppoint'], $codes)) as $code) {
            DB::table('branch_types')->insert([
                'id'         => (string) Str::uuid(),
                'code'       => $code,
                'name'       => $labels[$code] ?? Str::title(str_replace('_', ' ', $code)),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('branch_types');
    }
};
