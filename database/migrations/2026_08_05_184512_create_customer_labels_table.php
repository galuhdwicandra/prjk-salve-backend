<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customer_labels', function (Blueprint $table) {
            $table->char('id', 36)->primary();
            $table->string('name', 60)->unique();
            $table->string('color', 20)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('is_active', 'customer_labels_is_active_index');
        });

        $now = now();

        $rows = [
            ['VIP', 'amber'],
            ['Langganan', 'emerald'],
            ['Corporate', 'blue'],
            ['Member', 'violet'],
            ['Prioritas', 'rose'],
            ['Outlet', 'cyan'],
            ['Komplain', 'orange'],
            ['Blacklist', 'red'],
        ];

        foreach ($rows as [$name, $color]) {
            DB::table('customer_labels')->insert([
                'id' => (string) Str::uuid(),
                'name' => $name,
                'color' => $color,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_labels');
    }
};
