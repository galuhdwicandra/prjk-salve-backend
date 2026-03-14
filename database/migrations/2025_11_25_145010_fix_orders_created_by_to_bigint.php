<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('orders') || !Schema::hasTable('users')) {
            return;
        }

        // Lepas foreign key / index lama jika ada
        try {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropForeign(['created_by']);
            });
        } catch (\Throwable $e) {
            // abaikan jika belum ada
        }

        try {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropIndex(['created_by']);
            });
        } catch (\Throwable $e) {
            // abaikan jika belum ada
        }

        // Hapus kolom lama jika ada
        if (Schema::hasColumn('orders', 'created_by')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropColumn('created_by');
            });
        }

        // Tambah ulang dengan tipe bigint agar cocok dengan users.id
        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedBigInteger('created_by')->nullable();
            $table->index('created_by', 'orders_created_by_index');
            $table->foreign('created_by', 'orders_created_by_foreign')
                ->references('id')
                ->on('users')
                ->cascadeOnUpdate()
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('orders')) {
            return;
        }

        try {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropForeign('orders_created_by_foreign');
            });
        } catch (\Throwable $e) {
            // abaikan jika belum ada
        }

        try {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropIndex('orders_created_by_index');
            });
        } catch (\Throwable $e) {
            // abaikan jika belum ada
        }

        if (Schema::hasColumn('orders', 'created_by')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropColumn('created_by');
            });
        }

        Schema::table('orders', function (Blueprint $table) {
            $table->uuid('created_by')->nullable();
        });
    }
};
