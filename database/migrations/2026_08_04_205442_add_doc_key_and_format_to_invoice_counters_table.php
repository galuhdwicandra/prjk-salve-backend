<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('invoice_counters', function (Blueprint $table) {
            if (! Schema::hasColumn('invoice_counters', 'doc_key')) {
                $table->string('doc_key', 20)->nullable()->after('branch_id');
            }

            if (! Schema::hasColumn('invoice_counters', 'format')) {
                $table->string('format', 40)->nullable()->after('prefix');
            }
        });

        DB::statement("ALTER TABLE invoice_counters MODIFY reset_policy ENUM('monthly','yearly','never') NOT NULL DEFAULT 'monthly'");

        foreach (DB::table('branches')->select('id', 'invoice_prefix')->get() as $branch) {
            DB::table('invoice_counters')
                ->where('branch_id', $branch->id)
                ->where('prefix', $branch->invoice_prefix)
                ->update([
                    'doc_key' => 'order',
                    'format'  => '[OUTLET]-[YYYY][MM]-[NUMBER:6]',
                ]);
        }

        Schema::table('invoice_counters', function (Blueprint $table) {
            $table->unique(['branch_id', 'doc_key']);
            $table->dropUnique(['branch_id', 'prefix']);
        });
    }

    public function down(): void
    {
        Schema::table('invoice_counters', function (Blueprint $table) {
            $table->unique(['branch_id', 'prefix']);
            $table->dropUnique(['branch_id', 'doc_key']);
            $table->dropColumn(['doc_key', 'format']);
        });

        DB::statement("ALTER TABLE invoice_counters MODIFY reset_policy ENUM('monthly','never') NOT NULL DEFAULT 'monthly'");
    }
};
