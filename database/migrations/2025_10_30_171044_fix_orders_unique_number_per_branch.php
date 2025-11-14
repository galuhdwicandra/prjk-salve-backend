<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $t) {
            // hapus unique lama
            $t->dropUnique('orders_number_unique'); // sesuaikan nama index jika berbeda
            // tambahkan unique komposit
            $t->unique(['branch_id', 'number'], 'orders_branch_number_unique');
        });
    }
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $t) {
            $t->dropUnique('orders_branch_number_unique');
            $t->unique('number', 'orders_number_unique');
        });
    }
};
