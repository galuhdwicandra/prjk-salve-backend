<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('cash_mutations', function (Blueprint $table) {
            $table->string('category', 100)->nullable()->after('note');
            $table->string('proof_path')->nullable()->after('category');
        });
    }

    public function down(): void
    {
        Schema::table('cash_mutations', function (Blueprint $table) {
            $table->dropColumn(['category', 'proof_path']);
        });
    }
};
