<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('order_vouchers', function (Blueprint $t) {
            $t->char('id', 36)->primary();
            $t->char('order_id', 36);
            $t->char('voucher_id', 36);
            $t->decimal('applied_amount', 12, 2)->default(0.00);
            $t->unsignedBigInteger('applied_by')->nullable(); // users.id (bigint)
            $t->timestamp('applied_at')->nullable();
            $t->timestamps();

            $t->unique(['order_id']); // satu voucher per order (sederhana & aman untuk F9)
            $t->index(['voucher_id']);
            $t->foreign('order_id')->references('id')->on('orders')->onDelete('cascade')->onUpdate('cascade');
            $t->foreign('voucher_id')->references('id')->on('vouchers')->onDelete('cascade')->onUpdate('cascade');
            $t->foreign('applied_by')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_vouchers');
    }
};
