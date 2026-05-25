<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('accounting_journal_lines', function (Blueprint $table) {
            $table->char('id', 36)->primary();

            $table->char('journal_entry_id', 36);
            $table->char('account_id', 36);

            $table->text('description')->nullable();
            $table->decimal('debit', 14, 2)->default(0);
            $table->decimal('credit', 14, 2)->default(0);
            $table->integer('line_order')->default(1);

            $table->timestamps();

            $table->index('journal_entry_id', 'accounting_journal_lines_entry_id_index');
            $table->index('account_id', 'accounting_journal_lines_account_id_index');

            $table->foreign('journal_entry_id')
                ->references('id')
                ->on('accounting_journal_entries')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('account_id')
                ->references('id')
                ->on('accounting_accounts')
                ->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('accounting_journal_lines');
    }
};
