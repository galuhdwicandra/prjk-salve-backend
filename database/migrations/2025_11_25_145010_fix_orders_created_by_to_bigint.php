<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // 1) Drop kolom lama (uuid)
        DB::statement('ALTER TABLE public.orders DROP COLUMN IF EXISTS created_by');

        // 2) Tambah kolom baru bigint nullable
        DB::statement('ALTER TABLE public.orders ADD COLUMN created_by BIGINT NULL');

        // 3) Index (opsional tapi disarankan)
        DB::statement('CREATE INDEX IF NOT EXISTS orders_created_by_index ON public.orders (created_by)');

        // 4) Foreign key ke users(id)
        DB::statement('ALTER TABLE public.orders
            ADD CONSTRAINT orders_created_by_foreign
            FOREIGN KEY (created_by) REFERENCES public.users(id)
            ON UPDATE CASCADE ON DELETE SET NULL');
    }

    public function down(): void
    {
        // Rollback: lepas FK & index, kembalikan ke uuid (tanpa FK, sesuai skema awal)
        DB::statement('ALTER TABLE public.orders DROP CONSTRAINT IF EXISTS orders_created_by_foreign');
        DB::statement('DROP INDEX IF EXISTS orders_created_by_index');
        DB::statement('ALTER TABLE public.orders DROP COLUMN IF EXISTS created_by');
        DB::statement('ALTER TABLE public.orders ADD COLUMN created_by uuid');
    }
};
