<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('receivables')) {
            Schema::create('receivables', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->uuid('order_id')->unique();
                $table->date('due_date')->nullable();
                $table->decimal('remaining_amount', 12, 2)->default(0);
                $table->enum('status', ['OPEN', 'PARTIAL', 'SETTLED', 'CANCELLED'])->default('OPEN');
                $table->timestamps();

                $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('receivables');
    }
};
