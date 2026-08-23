<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contacts', function (Blueprint $table) {
            $table->string('code', 20)->nullable()->unique()->after('id');
        });

        $seq = 0;

        foreach (DB::table('contacts')->orderBy('created_at')->orderBy('id')->pluck('id') as $id) {
            $seq++;

            DB::table('contacts')->where('id', $id)->update([
                'code' => 'VND-' . str_pad((string) $seq, 4, '0', STR_PAD_LEFT),
            ]);
        }
    }

    public function down(): void
    {
        Schema::table('contacts', function (Blueprint $table) {
            $table->dropUnique(['code']);
            $table->dropColumn('code');
        });
    }
};
