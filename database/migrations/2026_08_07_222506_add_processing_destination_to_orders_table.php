<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->enum('processing_destination', ['workshop', 'vendor'])->nullable()->after('branch_id');
            $table->uuid('destination_branch_id')->nullable()->after('processing_destination');

            $table->foreign('destination_branch_id')->references('id')->on('branches')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['destination_branch_id']);
            $table->dropColumn(['processing_destination', 'destination_branch_id']);
        });
    }
};
