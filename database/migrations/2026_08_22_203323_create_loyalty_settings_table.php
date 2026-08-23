<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('loyalty_settings', function (Blueprint $t) {
            $t->uuid('id')->primary();
            $t->uuid('branch_id')->nullable()->unique();
            $t->unsignedSmallInteger('target')->default(10);
            $t->string('stamp_per', 20)->default('transaksi');
            $t->json('rewards')->nullable();
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loyalty_settings');
    }
};
