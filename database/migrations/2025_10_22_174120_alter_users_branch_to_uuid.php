<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        $driver = DB::getDriverName();

        // 1) Drop semua FK yang mungkin mengikat ke users.branch_id
        if ($driver === 'pgsql') {
            $rows = DB::select(<<<'SQL'
                SELECT tc.constraint_name
                FROM information_schema.table_constraints AS tc
                JOIN information_schema.key_column_usage AS kcu
                  ON tc.constraint_name = kcu.constraint_name
                 AND tc.constraint_schema = kcu.constraint_schema
               WHERE tc.constraint_type = 'FOREIGN KEY'
                 AND tc.table_schema = current_schema()
                 AND tc.table_name = 'users'
                 AND kcu.column_name = 'branch_id'
            SQL);
            foreach ($rows as $row) {
                DB::statement('ALTER TABLE "users" DROP CONSTRAINT "' . $row->constraint_name . '"');
            }
        } elseif ($driver === 'mysql') {
            $rows = DB::select(<<<'SQL'
                SELECT CONSTRAINT_NAME
                FROM information_schema.KEY_COLUMN_USAGE
                WHERE TABLE_SCHEMA = DATABASE()
                  AND TABLE_NAME = 'users'
                  AND COLUMN_NAME = 'branch_id'
                  AND REFERENCED_TABLE_NAME IS NOT NULL
            SQL);
            foreach ($rows as $row) {
                DB::statement('ALTER TABLE `users` DROP FOREIGN KEY `' . $row->CONSTRAINT_NAME . '`');
            }
        } else {
            // Fallback generic
            try {
                Schema::table('users', function (Blueprint $table) {
                    $table->dropForeign(['branch_id']);
                });
            } catch (\Throwable $e) { /* ignore */
            }
        }

        // 2) Drop kolom lama bila ada
        if (Schema::hasColumn('users', 'branch_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('branch_id');
            });
        }

        // 3) Tambah kolom UUID + FK portable (tanpa after())
        Schema::table('users', function (Blueprint $table) {
            $table->foreignUuid('branch_id')->nullable()
                ->constrained('branches')->nullOnDelete()->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        $driver = DB::getDriverName();

        // Drop FK
        if ($driver === 'pgsql') {
            try {
                DB::statement('ALTER TABLE "users" DROP CONSTRAINT "users_branch_id_foreign"');
            } catch (\Throwable $e) {
                // fallback: temukan nama constraint secara dinamis
                $rows = DB::select(<<<'SQL'
                    SELECT tc.constraint_name
                    FROM information_schema.table_constraints AS tc
                    JOIN information_schema.key_column_usage AS kcu
                      ON tc.constraint_name = kcu.constraint_name
                     AND tc.constraint_schema = kcu.constraint_schema
                   WHERE tc.constraint_type = 'FOREIGN KEY'
                     AND tc.table_schema = current_schema()
                     AND tc.table_name = 'users'
                     AND kcu.column_name = 'branch_id'
                SQL);
                foreach ($rows as $row) {
                    DB::statement('ALTER TABLE "users" DROP CONSTRAINT "' . $row->constraint_name . '"');
                }
            }
        } elseif ($driver === 'mysql') {
            try {
                DB::statement('ALTER TABLE `users` DROP FOREIGN KEY `users_branch_id_foreign`');
            } catch (\Throwable $e) { /* ignore */
            }
        } else {
            try {
                Schema::table('users', function (Blueprint $table) {
                    $table->dropForeign(['branch_id']);
                });
            } catch (\Throwable $e) { /* ignore */
            }
        }

        // Drop kolom UUID
        if (Schema::hasColumn('users', 'branch_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('branch_id');
            });
        }

        // Kembalikan ke BIGINT nullable (tanpa FK)
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('branch_id')->nullable();
        });
    }
};
