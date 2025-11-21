<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Drop index lama (non-unique) bila ada, lalu buat unique index.
        Schema::table('orders', function (Blueprint $table) {
            // Nama index sesuai dump: orders_invoice_no_index
            try {
                $table->dropIndex('orders_invoice_no_index');
            } catch (\Throwable $e) {
            }

            // Unique agar setiap invoice_no benar-benar tunggal
            $table->unique('invoice_no', 'orders_invoice_no_unique');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            try {
                $table->dropUnique('orders_invoice_no_unique');
            } catch (\Throwable $e) {
            }
            $table->index('invoice_no', 'orders_invoice_no_index');
        });
    }
};
