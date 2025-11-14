<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('order_photos', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('order_id')->index();
            $table->enum('kind', ['before', 'after'])->index(); // sesuai spesifikasi
            $table->string('path', 255); // simpan path (disk public) — upload di FE/endpoint terpisah bila perlu
            $table->timestamps();

            $table->foreign('order_id')->references('id')->on('orders')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_photos');
    }
};
