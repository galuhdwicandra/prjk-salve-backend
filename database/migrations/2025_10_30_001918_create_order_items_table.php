<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('order_id')->index();
            $table->uuid('service_id');

            $table->decimal('qty', 10, 2);        // laundry sering pakai kg/pcs
            $table->decimal('price', 12, 2);      // harga per unit (sinkron server)
            $table->decimal('total', 12, 2);      // qty * price
            $table->string('note', 200)->nullable();

            $table->timestamps();

            $table->foreign('order_id')->references('id')->on('orders')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('service_id')->references('id')->on('services')->cascadeOnUpdate()->restrictOnDelete();

            // integritas: tidak boleh duplicate layanan baris sama? (opsional, dibiarkan)
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
