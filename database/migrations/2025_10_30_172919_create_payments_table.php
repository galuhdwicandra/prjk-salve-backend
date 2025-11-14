<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->char('id', 36)->primary();
            $table->char('order_id', 36);
            $table->enum('method', ['PENDING', 'DP', 'CASH', 'QRIS', 'TRANSFER']); // sesuai SOP
            $table->decimal('amount', 12, 2);
            $table->timestamp('paid_at')->nullable();
            $table->string('note', 200)->nullable();
            // Idempotensi sederhana: satu kombinasi unik per (order, method, amount, paid_at)
            $table->unique(['order_id', 'method', 'amount', 'paid_at']);
            $table->index(['order_id', 'method']);
            $table->timestamps();

            $table->foreign('order_id')->references('id')->on('orders')
                ->onUpdate('cascade')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
