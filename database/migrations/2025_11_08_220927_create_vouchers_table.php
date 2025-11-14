<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('vouchers', function (Blueprint $t) {
            $t->char('id', 36)->primary();
            $t->char('branch_id', 36)->nullable();
            $t->string('code', 40)->unique();
            $t->enum('type', ['PERCENT', 'NOMINAL']);
            $t->decimal('value', 12, 2);
            $t->timestamp('start_at')->nullable();
            $t->timestamp('end_at')->nullable();
            $t->decimal('min_total', 12, 2)->default(0.00);
            $t->integer('usage_limit')->nullable();
            $t->boolean('active')->default(true);
            $t->timestamps();
        });

        Schema::table('vouchers', function (Blueprint $t) {
            $t->index('branch_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vouchers');
    }
};
