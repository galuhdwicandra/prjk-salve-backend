<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->char('id', 36)->primary();
            $table->string('name', 150);
            $table->string('phone', 32)->nullable();
            $table->text('address')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('is_active', 'contacts_is_active_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};
