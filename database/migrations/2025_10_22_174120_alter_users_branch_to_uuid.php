<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // 1) Drop semua FK yang mungkin mengikat ke users.branch_id (jika ada)
        $dbName = DB::getDatabaseName();
        $constraints = DB::select("
            SELECT CONSTRAINT_NAME
            FROM information_schema.KEY_COLUMN_USAGE
            WHERE TABLE_SCHEMA = ?
              AND TABLE_NAME = 'users'
              AND COLUMN_NAME = 'branch_id'
              AND REFERENCED_TABLE_NAME IS NOT NULL
        ", [$dbName]);

        foreach ($constraints as $c) {
            $name = $c->CONSTRAINT_NAME;
            try {
                DB::statement("ALTER TABLE `users` DROP FOREIGN KEY `{$name}`");
            } catch (\Throwable $e) {
                // ignore if already dropped / not exists
            }
        }

        // 2) Hapus kolom branch_id lama bila masih ada (BIGINT)
        if (Schema::hasColumn('users', 'branch_id')) {
            try {
                Schema::table('users', function (Blueprint $table) {
                    $table->dropColumn('branch_id');
                });
            } catch (\Throwable $e) {
                // jika tipe/constraint menyulitkan, fallback: coba raw
                try {
                    DB::statement("ALTER TABLE `users` DROP COLUMN `branch_id`");
                } catch (\Throwable $e2) {
                    // biarkan gagal diam, lanjut tambahkan kolom baru (akan bentrok jika masih ada)
                }
            }
        }

        // 3) Tambah kolom branch_id baru (UUID) + index
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'branch_id')) {
                $table->uuid('branch_id')->nullable()->after('email_verified_at');
                $table->index('branch_id', 'users_branch_id_index');
            }
        });

        // 4) Tambah FK ke branches.id (UUID)
        Schema::table('users', function (Blueprint $table) {
            // Jika FK belum ada, tambahkan
            try {
                $table->foreign('branch_id', 'users_branch_id_foreign')
                    ->references('id')->on('branches')
                    ->cascadeOnUpdate()
                    ->nullOnDelete();
            } catch (\Throwable $e) {
                // abaikan bila sudah ada
            }
        });

        // 5) Inisialisasi nilai (opsional): biarkan NULL karena tidak ada mapping BIGINT→UUID
        // DB::table('users')->whereNull('branch_id')->update([...]);
    }

    public function down(): void
    {
        // Hapus FK & index baru
        try {
            DB::statement("ALTER TABLE `users` DROP FOREIGN KEY `users_branch_id_foreign`");
        } catch (\Throwable $e) {
        }
        Schema::table('users', function (Blueprint $table) {
            try {
                $table->dropIndex('users_branch_id_index');
            } catch (\Throwable $e) {
            }
        });

        // Hapus kolom UUID
        if (Schema::hasColumn('users', 'branch_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('branch_id');
            });
        }

        // Kembalikan ke BIGINT nullable (tanpa FK)
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('branch_id')->nullable()->after('email_verified_at');
        });
    }
};
