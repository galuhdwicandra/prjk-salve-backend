<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cash_transactions', function (Blueprint $table) {
            $table->char('id', 36)->primary();
            $table->char('branch_id', 36);

            $table->enum('kind', ['IN', 'OUT', 'TRANSFER']);
            $table->string('no', 50)->unique();
            $table->date('trx_date');

            $table->char('cash_account_id', 36);
            $table->char('to_account_id', 36)->nullable();
            $table->char('contact_id', 36)->nullable();

            $table->decimal('amount', 14, 2)->default(0);
            $table->decimal('fee_amount', 14, 2)->default(0);
            $table->enum('fee_bearer', ['SENDER', 'RECEIVER'])->nullable();

            $table->text('description')->nullable();
            $table->string('attachment_path')->nullable();

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete()->cascadeOnUpdate();
            $table->timestamps();

            $table->index(['branch_id', 'kind', 'trx_date'], 'cash_transactions_branch_kind_date_index');

            $table->foreign('branch_id')->references('id')->on('branches')->cascadeOnUpdate();
            $table->foreign('cash_account_id')->references('id')->on('accounting_accounts')->cascadeOnUpdate();
            $table->foreign('to_account_id')->references('id')->on('accounting_accounts')->cascadeOnUpdate();
            $table->foreign('contact_id')->references('id')->on('contacts')->nullOnDelete()->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cash_transactions');
    }
};
