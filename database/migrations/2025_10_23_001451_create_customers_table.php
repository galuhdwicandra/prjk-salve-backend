<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->char('id', 36)->primary();
            $table->char('branch_id', 36);
            $table->string('name', 150);
            $table->string('whatsapp', 32);
            $table->string('address', 255)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('whatsapp');
            $table->unique(['branch_id', 'whatsapp'], 'customers_branch_wa_unique');

            $table->foreign('branch_id')
                ->references('id')->on('branches')
                ->onUpdate('cascade')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
