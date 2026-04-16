<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('cash_sessions', function (Blueprint $table) {
            $table->char('id', 36)->primary();
            $table->char('branch_id', 36);
            $table->date('business_date');
            $table->enum('status', ['OPEN', 'CLOSED'])->default('OPEN');

            $table->unsignedBigInteger('opened_by');
            $table->timestamp('opened_at');
            $table->decimal('opening_cash', 12, 2)->default(0);

            $table->unsignedBigInteger('closed_by')->nullable();
            $table->timestamp('closed_at')->nullable();

            $table->decimal('closing_cash_system', 12, 2)->nullable();
            $table->decimal('closing_cash_counted', 12, 2)->nullable();
            $table->decimal('difference_amount', 12, 2)->nullable();

            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['branch_id', 'business_date'], 'cash_sessions_branch_business_date_unique');
            $table->index(['branch_id', 'status']);
            $table->foreign('branch_id')->references('id')->on('branches')->cascadeOnDelete();
            $table->foreign('opened_by')->references('id')->on('users')->restrictOnDelete();
            $table->foreign('closed_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cash_sessions');
    }
};