<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('tracker_token', 64)->nullable()->unique()->after('number');
            $table->timestampTz('tracker_token_expires_at')->nullable()->after('tracker_token');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropUnique(['tracker_token']);
            $table->dropColumn(['tracker_token', 'tracker_token_expires_at']);
        });
    }
};
