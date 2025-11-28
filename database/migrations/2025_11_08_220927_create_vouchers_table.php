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

        Schema::create('vouchers', function (Blueprint $t) {
            $t->uuid('id')->primary();
            $t->uuid('branch_id')->nullable();

            $t->string('code', 40)->unique();
            $t->enum('type', ['PERCENT', 'NOMINAL']);
            $t->decimal('value', 12, 2);

            $t->timestampTz('start_at')->nullable();
            $t->timestampTz('end_at')->nullable();

            $t->decimal('min_total', 12, 2)->default(0);
            $t->integer('usage_limit')->nullable();
            $t->boolean('active')->default(true);

            $t->timestampsTz();

            // Index & FK cabang (nullable → SET NULL)
            $t->index('branch_id');
            $t->foreign('branch_id')
              ->references('id')->on('branches')
              ->cascadeOnUpdate()->nullOnDelete();
        });

        // Default UUID khusus PostgreSQL
        if ($this->isPgsql()) {
            DB::statement('ALTER TABLE vouchers ALTER COLUMN id SET DEFAULT gen_random_uuid();');
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('vouchers');
    }
};
