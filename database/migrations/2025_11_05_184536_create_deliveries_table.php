<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('deliveries', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('order_id');
            $table->string('type', 20); // pickup|delivery|return
            $table->uuid('zone_id')->nullable();
            $table->decimal('fee', 12, 2)->default(0);
            $table->unsignedBigInteger('assigned_to')->nullable(); // users.id
            $table->boolean('auto_assigned')->default(false);
            $table->string('status', 32)->default('CREATED');
            $table->string('handover_photo')->nullable();
            $table->timestamps();

            $table->index('order_id');
            $table->index('assigned_to');

            $table->foreign('order_id')->references('id')->on('orders')->cascadeOnDelete();
            $table->foreign('assigned_to')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('deliveries');
    }
};
