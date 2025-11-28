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
        // Aktifkan generator UUID di PostgreSQL (idempotent)
        if ($this->isPgsql()) {
            DB::statement('CREATE EXTENSION IF NOT EXISTS "pgcrypto";'); // gen_random_uuid()
        }

        Schema::create('payments', function (Blueprint $table) {
            // Gunakan UUID agar FK & index efisien di PG
            $table->uuid('id')->primary();
            $table->uuid('order_id');

            // ENUM aman di PG (dibuat sebagai CHECK constraint oleh Laravel)
            $table->enum('method', ['PENDING', 'DP', 'CASH', 'QRIS', 'TRANSFER'])
                  ->default('PENDING');

            $table->decimal('amount', 12, 2);
            $table->timestampTz('paid_at')->nullable();
            $table->string('note', 200)->nullable();

            // Idempotensi sederhana (per kombinasi berikut):
            $table->unique(['order_id', 'method', 'amount', 'paid_at']);
            $table->index(['order_id', 'method']);

            // Timestamps dengan timezone untuk PG
            $table->timestampsTz();

            $table->foreign('order_id')
                ->references('id')->on('orders')
                ->cascadeOnUpdate()->cascadeOnDelete();
        });

        // Default UUID hanya untuk PostgreSQL
        if ($this->isPgsql()) {
            DB::statement('ALTER TABLE payments ALTER COLUMN id SET DEFAULT gen_random_uuid();');
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
