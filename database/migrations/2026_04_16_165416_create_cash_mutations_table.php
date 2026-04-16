<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('cash_mutations', function (Blueprint $table) {
            $table->char('id', 36)->primary();
            $table->char('cash_session_id', 36);
            $table->char('branch_id', 36);

            $table->enum('type', [
                'OPENING_FLOAT',
                'SALE_CASH',
                'RECEIVABLE_CASH_SETTLEMENT',
                'EXPENSE_CASH',
                'WITHDRAWAL',
                'ADJUSTMENT_IN',
                'ADJUSTMENT_OUT',
            ]);

            $table->enum('direction', ['IN', 'OUT']);
            $table->decimal('amount', 12, 2);
            $table->string('source_type', 50)->nullable();
            $table->char('source_id', 36)->nullable();
            $table->string('reference_no', 100)->nullable();
            $table->string('note', 200)->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamp('effective_at');
            $table->timestamps();

            $table->index(['branch_id', 'effective_at']);
            $table->index(['cash_session_id', 'type']);
            $table->unique(['source_type', 'source_id', 'type'], 'cash_mutations_source_unique');

            $table->foreign('cash_session_id')->references('id')->on('cash_sessions')->cascadeOnDelete();
            $table->foreign('branch_id')->references('id')->on('branches')->cascadeOnDelete();
            $table->foreign('created_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cash_mutations');
    }
};