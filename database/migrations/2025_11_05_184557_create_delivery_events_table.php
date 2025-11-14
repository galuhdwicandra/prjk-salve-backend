<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('delivery_events', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('delivery_id');
            $table->string('status', 32);
            $table->string('note', 200)->nullable();
            $table->timestamps();

            $table->index('delivery_id');
            $table->foreign('delivery_id')->references('id')->on('deliveries')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('delivery_events');
    }
};
