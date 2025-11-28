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
        // Aktifkan generator UUID di PostgreSQL
        if ($this->isPgsql()) {
            DB::statement('CREATE EXTENSION IF NOT EXISTS "pgcrypto";'); // gen_random_uuid()
        }

        Schema::create('receivables', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('order_id')->unique();   // satu piutang per order

            $table->decimal('remaining_amount', 12, 2)->default(0);
            $table->enum('status', ['OPEN', 'PARTIAL', 'SETTLED'])->default('OPEN');
            $table->date('due_date')->nullable();

            $table->timestampsTz();

            $table->foreign('order_id')
                ->references('id')->on('orders')
                ->cascadeOnUpdate()->cascadeOnDelete();

            $table->index(['status']);
        });

        // Default UUID hanya di PostgreSQL
        if ($this->isPgsql()) {
            DB::statement('ALTER TABLE receivables ALTER COLUMN id SET DEFAULT gen_random_uuid();');
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('receivables');
    }
};
