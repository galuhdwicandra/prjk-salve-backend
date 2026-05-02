<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('production_task_correction_requests', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('production_task_id');
            $table->uuid('order_id');
            $table->uuid('branch_id');

            $table->unsignedBigInteger('requested_by');
            $table->unsignedBigInteger('reviewed_by')->nullable();

            $table->string('type', 20); // REWASH | ROLLBACK
            $table->string('from_status', 20);
            $table->string('to_status', 20);

            $table->text('reason');
            $table->string('status', 20)->default('PENDING'); // PENDING | APPROVED | REJECTED
            $table->text('review_note')->nullable();

            $table->date('requested_date');
            $table->date('reviewed_date')->nullable();

            $table->timestamps();

            $table->foreign('production_task_id')
                ->references('id')
                ->on('production_tasks')
                ->cascadeOnDelete();

            $table->foreign('order_id')
                ->references('id')
                ->on('orders')
                ->cascadeOnDelete();

            $table->foreign('branch_id')
                ->references('id')
                ->on('branches')
                ->cascadeOnDelete();

            $table->foreign('requested_by')
                ->references('id')
                ->on('users')
                ->restrictOnDelete();

            $table->foreign('reviewed_by')
                ->references('id')
                ->on('users')
                ->nullOnDelete();

            $table->index(['branch_id', 'status'], 'ptcr_branch_status_idx');
            $table->index(['order_id', 'status'], 'ptcr_order_status_idx');
            $table->index(['requested_by', 'status'], 'ptcr_requested_status_idx');
            $table->index(['production_task_id', 'status'], 'ptcr_task_status_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('production_task_correction_requests');
    }
};
