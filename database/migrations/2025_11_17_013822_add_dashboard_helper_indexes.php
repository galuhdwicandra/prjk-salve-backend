<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $t) {
            $t->index(['branch_id', 'created_at'], 'idx_orders_branch_created');
            $t->index('paid_at', 'idx_orders_paid_at');
        });
        Schema::table('payments', function (Blueprint $t) {
            $t->index('paid_at', 'idx_payments_paid_at');
            $t->index('order_id', 'idx_payments_order');
        });
        Schema::table('order_items', function (Blueprint $t) {
            $t->index('order_id', 'idx_order_items_order');
            $t->index('service_id', 'idx_order_items_service');
        });
        Schema::table('order_vouchers', function (Blueprint $t) {
            if (!Schema::hasColumn('order_vouchers', 'applied_at')) {
                // Jika kolom sudah ada di dump Anda, bagian ini di-skip saat runtime.
                // $t->timestamp('applied_at')->nullable()->after('applied_by');
            }
            $t->index('applied_at', 'idx_order_vouchers_applied_at');
            $t->index('order_id', 'idx_order_vouchers_order');
        });
        Schema::table('deliveries', function (Blueprint $t) {
            $t->index('order_id', 'idx_deliveries_order');
            $t->index('created_at', 'idx_deliveries_created');
        });
        Schema::table('receivables', function (Blueprint $t) {
            $t->index(['status', 'due_date'], 'idx_receivables_status_due');
            $t->index('order_id', 'idx_receivables_order');
        });
        Schema::table('expenses', function (Blueprint $t) {
            $t->index(['branch_id', 'created_at'], 'idx_expenses_branch_created');
        });
    }

    public function down(): void
    {
        // penghapusan indeks (opsional)
    }
};
