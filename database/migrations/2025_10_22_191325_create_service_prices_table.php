<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('service_prices', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('service_id');
            $table->uuid('branch_id');
            $table->decimal('price', 14, 2);
            $table->timestamps();

            $table->foreign('service_id')->references('id')->on('services')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('branch_id')->references('id')->on('branches')->cascadeOnUpdate()->cascadeOnDelete();

            $table->unique(['service_id', 'branch_id']); // override unik per cabang
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_prices');
    }
};
