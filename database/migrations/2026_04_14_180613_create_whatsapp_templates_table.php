<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('whatsapp_templates', function (Blueprint $table) {
            $table->char('id', 36)->primary();
            $table->char('branch_id', 36)->nullable();
            $table->string('key', 50); // receipt_pending | receipt_paid
            $table->string('name', 100);
            $table->text('content');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['branch_id', 'key'], 'wa_templates_branch_key_unique');
            $table->index(['key', 'is_active'], 'wa_templates_key_active_idx');

            $table->foreign('branch_id')
                ->references('id')
                ->on('branches')
                ->nullOnDelete()
                ->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('whatsapp_templates');
    }
};