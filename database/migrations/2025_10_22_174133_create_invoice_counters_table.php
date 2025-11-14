<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('invoice_counters', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('branch_id');
            $table->string('prefix', 8)->default('SLV');
            $table->unsignedInteger('seq')->default(0);
            $table->enum('reset_policy', ['monthly', 'never'])->default('monthly');
            $table->char('last_reset_month', 6)->nullable(); // format YYYYMM
            $table->timestamps();

            $table->foreign('branch_id')
                ->references('id')->on('branches')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->unique(['branch_id', 'prefix']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoice_counters');
    }
};
