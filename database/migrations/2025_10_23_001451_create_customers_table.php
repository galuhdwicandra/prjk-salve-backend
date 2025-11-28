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

        Schema::create('customers', function (Blueprint $table) {
            // Pakai tipe native UUID
            $table->uuid('id')->primary();
            $table->uuid('branch_id');

            $table->string('name', 150);
            $table->string('whatsapp', 32);
            $table->string('address', 255)->nullable();
            $table->text('notes')->nullable();

            // Gunakan timestamps dengan timezone untuk PG
            $table->timestampsTz();

            $table->index('whatsapp');
            $table->unique(['branch_id', 'whatsapp'], 'customers_branch_wa_unique');

            $table->foreign('branch_id')
                ->references('id')->on('branches')
                ->cascadeOnUpdate()->cascadeOnDelete();
        });

        // Set default UUID hanya untuk PostgreSQL
        if ($this->isPgsql()) {
            DB::statement('ALTER TABLE customers ALTER COLUMN id SET DEFAULT gen_random_uuid();');
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
