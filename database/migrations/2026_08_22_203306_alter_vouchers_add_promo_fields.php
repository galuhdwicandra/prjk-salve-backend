<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vouchers', function (Blueprint $table) {
            $table->string('name', 100)->nullable()->after('code');
            $table->boolean('is_archived')->default(false)->after('active');
            $table->boolean('stack_voucher')->default(false)->after('is_archived');
            $table->boolean('stack_discount')->default(true)->after('stack_voucher');
            $table->boolean('percent_after_discount')->default(true)->after('stack_discount');
            $table->index('is_archived', 'vouchers_is_archived_index');
        });

        DB::table('vouchers')->whereNull('name')->update(['name' => DB::raw('code')]);
    }

    public function down(): void
    {
        Schema::table('vouchers', function (Blueprint $table) {
            $table->dropIndex('vouchers_is_archived_index');
            $table->dropColumn([
                'name',
                'is_archived',
                'stack_voucher',
                'stack_discount',
                'percent_after_discount',
            ]);
        });
    }
};
