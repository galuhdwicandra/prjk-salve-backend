<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('receivables', function (Blueprint $table) {
            $table->char('id', 36)->primary();
            $table->char('order_id', 36)->unique();   // satu piutang per order
            $table->decimal('remaining_amount', 12, 2)->default(0);
            $table->enum('status', ['OPEN', 'PARTIAL', 'SETTLED'])->default('OPEN');
            $table->date('due_date')->nullable();
            $table->timestamps();

            $table->foreign('order_id')->references('id')->on('orders')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->index(['status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('receivables');
    }
};
