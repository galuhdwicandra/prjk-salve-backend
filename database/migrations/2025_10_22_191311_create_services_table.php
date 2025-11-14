<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('category_id');
            $table->string('name', 150);
            $table->string('unit', 32); // contoh: ITEM|KG|PASANG
            $table->decimal('price_default', 14, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('category_id')->references('id')->on('service_categories')->cascadeOnUpdate()->restrictOnDelete();
            $table->unique(['category_id', 'name']); // unik per kategori
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
