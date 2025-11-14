<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // status pembayaran mengikuti SOP: PENDING/DP/PAID (SETTLED)
            $table->string('payment_status', 20)->default('PENDING')->after('status');
            $table->decimal('dp_amount', 12, 2)->default(0)->after('discount');
            // paid_amount sudah ada di DB; tambahkan paid_at snapshot & invoice_no
            $table->timestamp('paid_at')->nullable()->after('paid_amount');
            $table->string('invoice_no', 40)->nullable()->after('number'); // selaras SOP
            $table->index('payment_status');
            $table->index('paid_at');
            $table->index('invoice_no');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex(['payment_status']);
            $table->dropIndex(['paid_at']);
            $table->dropIndex(['invoice_no']);
            $table->dropColumn(['payment_status', 'dp_amount', 'paid_at', 'invoice_no']);
        });
    }
};
