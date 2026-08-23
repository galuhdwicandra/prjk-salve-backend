<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_audit_logs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('order_id');
            $table->string('action', 40);
            $table->string('reason', 500)->nullable();
            $table->unsignedBigInteger('actor_id')->nullable();
            $table->json('snapshot')->nullable();
            $table->timestampsTz();

            $table->index(['order_id', 'created_at']);

            $table->foreign('order_id')
                ->references('id')
                ->on('orders')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_audit_logs');
    }
};
