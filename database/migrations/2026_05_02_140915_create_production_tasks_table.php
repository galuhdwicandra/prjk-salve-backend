<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('production_tasks', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('order_id')->unique();
            $table->uuid('branch_id');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->string('current_status', 20)->default('QUEUE');
            $table->decimal('qty', 12, 2)->default(0);
            $table->date('started_date')->nullable();
            $table->date('finished_date')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();

            $table->foreign('order_id')->references('id')->on('orders')->cascadeOnDelete();
            $table->foreign('branch_id')->references('id')->on('branches')->cascadeOnDelete();

            $table->index(['branch_id', 'current_status']);
            $table->index(['assigned_to', 'finished_date']);
            $table->index(['started_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('production_tasks');
    }
};
