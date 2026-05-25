<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('accounting_journal_entries', function (Blueprint $table) {
            $table->char('id', 36)->primary();

            $table->char('branch_id', 36);
            $table->char('mapping_id', 36)->nullable();

            $table->string('journal_no', 50)->unique();
            $table->date('journal_date');

            $table->string('source_type', 80)->nullable();
            $table->char('source_id', 36)->nullable();
            $table->string('source_no', 100)->nullable();

            $table->enum('status', ['DRAFT', 'POSTED', 'VOID'])->default('POSTED');
            $table->text('description')->nullable();

            $table->decimal('total_debit', 14, 2)->default(0);
            $table->decimal('total_credit', 14, 2)->default(0);

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete()->cascadeOnUpdate();
            $table->foreignId('posted_by')->nullable()->constrained('users')->nullOnDelete()->cascadeOnUpdate();
            $table->timestamp('posted_at')->nullable();

            $table->foreignId('voided_by')->nullable()->constrained('users')->nullOnDelete()->cascadeOnUpdate();
            $table->timestamp('voided_at')->nullable();
            $table->text('void_reason')->nullable();

            $table->timestamps();

            $table->unique(['source_type', 'source_id'], 'accounting_journal_source_unique');
            $table->index(['branch_id', 'journal_date'], 'accounting_journal_entries_branch_date_index');
            $table->index('status', 'accounting_journal_entries_status_index');
            $table->index('mapping_id', 'accounting_journal_entries_mapping_id_index');

            $table->foreign('branch_id')
                ->references('id')
                ->on('branches')
                ->cascadeOnUpdate();

            $table->foreign('mapping_id')
                ->references('id')
                ->on('accounting_account_mappings')
                ->nullOnDelete()
                ->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('accounting_journal_entries');
    }
};
