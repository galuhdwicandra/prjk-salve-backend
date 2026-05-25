<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('accounting_accounts', function (Blueprint $table) {
            $table->char('id', 36)->primary();
            $table->char('branch_id', 36)->nullable();
            $table->char('parent_id', 36)->nullable();

            $table->string('code', 32);
            $table->string('name', 150);
            $table->enum('type', ['ASSET', 'LIABILITY', 'EQUITY', 'REVENUE', 'EXPENSE']);
            $table->enum('normal_balance', ['DEBIT', 'CREDIT']);
            $table->boolean('is_cash_account')->default(false);
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->unique(['branch_id', 'code'], 'accounting_accounts_branch_code_unique');
            $table->index('parent_id', 'accounting_accounts_parent_id_index');
            $table->index('type', 'accounting_accounts_type_index');
            $table->index('is_active', 'accounting_accounts_is_active_index');

            $table->foreign('branch_id')
                ->references('id')
                ->on('branches')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreign('parent_id')
                ->references('id')
                ->on('accounting_accounts')
                ->cascadeOnUpdate()
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('accounting_accounts');
    }
};
