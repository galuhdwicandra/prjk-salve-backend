<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('production_task_logs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('production_task_id');
            $table->uuid('order_id');
            $table->uuid('branch_id');
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('from_status', 20)->nullable();
            $table->string('to_status', 20);
            $table->decimal('qty', 12, 2)->default(0);
            $table->date('process_date');
            $table->date('started_date')->nullable();
            $table->date('finished_date')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();

            $table->foreign('production_task_id')->references('id')->on('production_tasks')->cascadeOnDelete();
            $table->foreign('order_id')->references('id')->on('orders')->cascadeOnDelete();
            $table->foreign('branch_id')->references('id')->on('branches')->cascadeOnDelete();

            $table->index(['branch_id', 'process_date']);
            $table->index(['user_id', 'process_date']);
            $table->index(['order_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('production_task_logs');
    }
};
