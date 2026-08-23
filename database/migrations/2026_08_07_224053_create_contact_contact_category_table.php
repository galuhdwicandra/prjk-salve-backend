<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contact_contact_category', function (Blueprint $table) {
            $table->foreignUuid('contact_id')->constrained('contacts')->cascadeOnDelete();
            $table->foreignUuid('contact_category_id')->constrained('contact_categories')->cascadeOnDelete();
            $table->primary(['contact_id', 'contact_category_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_contact_category');
    }
};
