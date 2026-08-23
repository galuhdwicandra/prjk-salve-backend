<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('branches', function (Blueprint $table) {
            $table->string('type', 32)->default('workshop')->change();
            $table->boolean('is_active')->default(true)->after('reset_policy');
        });
    }

    public function down(): void
    {
        Schema::table('branches', function (Blueprint $table) {
            $table->dropColumn('is_active');
            $table->enum('type', ['workshop', 'droppoint'])->default('workshop')->change();
        });
    }
};
