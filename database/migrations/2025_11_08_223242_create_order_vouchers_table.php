<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    private function isPgsql(): bool
    {
        return Schema::getConnection()->getDriverName() === 'pgsql';
    }

    public function up(): void
    {
        // Generator UUID untuk PostgreSQL
        if ($this->isPgsql()) {
            DB::statement('CREATE EXTENSION IF NOT EXISTS "pgcrypto";'); // gen_random_uuid()
        }

        Schema::create('order_vouchers', function (Blueprint $t) {
            // ID utama & relasi berbasis UUID
            $t->uuid('id')->primary();
            $t->uuid('order_id');
            $t->uuid('voucher_id');

            $t->decimal('applied_amount', 12, 2)->default(0);
            // users.id umumnya bigint di Laravel default:
            $t->foreignId('applied_by')->nullable()->constrained('users')->nullOnDelete()->cascadeOnUpdate();

            $t->timestampTz('applied_at')->nullable();
            $t->timestampsTz();

            // Satu voucher per order
            $t->unique('order_id');
            $t->index('voucher_id');

            $t->foreign('order_id')->references('id')->on('orders')->cascadeOnUpdate()->cascadeOnDelete();
            $t->foreign('voucher_id')->references('id')->on('vouchers')->cascadeOnUpdate()->cascadeOnDelete();
        });

        // Default UUID untuk kolom id (khusus PostgreSQL)
        if ($this->isPgsql()) {
            DB::statement('ALTER TABLE order_vouchers ALTER COLUMN id SET DEFAULT gen_random_uuid();');

            // (Opsional) Pastikan nominal tidak negatif:
            // DB::statement("ALTER TABLE order_vouchers
            //     ADD CONSTRAINT order_vouchers_applied_amount_nonneg CHECK (applied_amount >= 0)");
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('order_vouchers');
    }
};
