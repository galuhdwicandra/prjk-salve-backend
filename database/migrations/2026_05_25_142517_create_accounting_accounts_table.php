<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('accounting_account_mappings', function (Blueprint $table) {
            $table->char('id', 36)->primary();
            $table->char('branch_id', 36)->nullable();

            $table->string('event_key', 80);
            $table->string('payment_method', 30)->nullable();
            $table->string('expense_category', 100)->nullable();

            $table->char('debit_account_id', 36);
            $table->char('credit_account_id', 36);

            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(
                ['branch_id', 'event_key', 'payment_method', 'expense_category'],
                'accounting_mappings_unique'
            );

            $table->index('event_key', 'accounting_mappings_event_key_index');
            $table->index('debit_account_id', 'accounting_mappings_debit_account_id_index');
            $table->index('credit_account_id', 'accounting_mappings_credit_account_id_index');
            $table->index('is_active', 'accounting_mappings_is_active_index');

            $table->foreign('branch_id')
                ->references('id')
                ->on('branches')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreign('debit_account_id')
                ->references('id')
                ->on('accounting_accounts')
                ->cascadeOnUpdate();

            $table->foreign('credit_account_id')
                ->references('id')
                ->on('accounting_accounts')
                ->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('accounting_account_mappings');
    }
};
