<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->uuid('parent_id')->nullable()->after('category_id');
            $table->foreign('parent_id')->references('id')->on('services')->cascadeOnUpdate()->restrictOnDelete();
            $table->unique(['category_id', 'parent_id', 'name']);
            $table->dropUnique(['category_id', 'name']);
        });
    }

    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->unique(['category_id', 'name']);
            $table->dropUnique(['category_id', 'parent_id', 'name']);
            $table->dropForeign(['parent_id']);
            $table->dropColumn('parent_id');
        });
    }
};
