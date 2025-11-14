<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('branch_id');
            $table->uuid('customer_id')->nullable();

            // nomor invoice (unik per sistem; jika mau unik per cabang tambahkan index composite)
            $table->string('number', 40)->unique();

            // state machine: QUEUE → WASHING → DRYING → IRONING → READY → DELIVERING → PICKED_UP; CANCELED terminal
            $table->string('status', 20)->index(); // enum di level app

            // ringkasan nilai
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('discount', 12, 2)->default(0);
            $table->decimal('grand_total', 12, 2)->default(0);
            $table->decimal('paid_amount', 12, 2)->default(0);
            $table->decimal('due_amount', 12, 2)->default(0);

            // optional catatan
            $table->text('notes')->nullable();

            // siapa pembuat (opsional, boleh null jika tidak dipakai)
            $table->uuid('created_by')->nullable();

            $table->timestamps();

            // FK
            $table->foreign('branch_id')->references('id')->on('branches')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreign('customer_id')->references('id')->on('customers')->cascadeOnUpdate()->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
