<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cash_transaction_lines', function (Blueprint $table) {
            $table->char('id', 36)->primary();
            $table->char('cash_transaction_id', 36);
            $table->char('transaction_category_id', 36);

            $table->string('description', 200)->nullable();
            $table->decimal('amount', 14, 2);
            $table->unsignedInteger('line_order')->default(1);
            $table->timestamps();

            $table->index('cash_transaction_id', 'cash_transaction_lines_trx_index');

            $table->foreign('cash_transaction_id')->references('id')->on('cash_transactions')->cascadeOnDelete();
            $table->foreign('transaction_category_id')->references('id')->on('transaction_categories')->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cash_transaction_lines');
    }
};
