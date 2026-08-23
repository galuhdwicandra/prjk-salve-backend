<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->char('destination_contact_id', 36)->nullable()->after('destination_branch_id');
            $table->foreign('destination_contact_id')->references('id')->on('contacts')->nullOnDelete();
        });

        Schema::table('deliveries', function (Blueprint $table) {
            $table->string('number', 40)->nullable()->unique()->after('id');
        });

        DB::statement("ALTER TABLE order_photos MODIFY kind ENUM('before','after','handover') NOT NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE order_photos MODIFY kind ENUM('before','after') NOT NULL");

        Schema::table('deliveries', function (Blueprint $table) {
            $table->dropUnique(['number']);
            $table->dropColumn('number');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['destination_contact_id']);
            $table->dropColumn('destination_contact_id');
        });
    }
};
