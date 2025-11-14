<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Tambah branch_id (nullable) + index jika belum ada
            if (!Schema::hasColumn('users', 'branch_id')) {
                $table->unsignedBigInteger('branch_id')->nullable()->after('password');
                $table->index('branch_id', 'users_branch_id_index');
            }

            // Tambah is_active (default true) jika belum ada
            if (!Schema::hasColumn('users', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('branch_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Hapus is_active jika ada
            if (Schema::hasColumn('users', 'is_active')) {
                $table->dropColumn('is_active');
            }

            // Hapus index + kolom branch_id jika ada
            if (Schema::hasColumn('users', 'branch_id')) {
                // Note: nama index harus sesuai saat create
                $table->dropIndex('users_branch_id_index');
                $table->dropColumn('branch_id');
            }
        });
    }
};
