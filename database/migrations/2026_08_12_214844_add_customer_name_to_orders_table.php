<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('customer_name', 150)->nullable()->after('customer_id');
        });

        DB::statement(
            'UPDATE orders SET customer_name = (SELECT name FROM customers WHERE customers.id = orders.customer_id) WHERE customer_id IS NOT NULL'
        );
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('customer_name');
        });
    }
};
