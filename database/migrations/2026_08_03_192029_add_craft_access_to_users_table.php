<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role_label', 100)->nullable();
            $table->json('modules')->nullable();
            $table->boolean('manager')->default(false);
            $table->boolean('show_balance')->default(false);
            $table->boolean('custom_price')->default(false);
        });

        Schema::create('branch_user', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('branch_id')->constrained('branches')->cascadeOnDelete();
            $table->primary(['user_id', 'branch_id']);
        });

        DB::table('users')
            ->whereNotNull('branch_id')
            ->select('id', 'branch_id')
            ->chunkById(200, function ($users) {
                DB::table('branch_user')->insertOrIgnore(
                    collect($users)
                        ->map(fn($u) => ['user_id' => $u->id, 'branch_id' => $u->branch_id])
                        ->all()
                );
            });
    }

    public function down(): void
    {
        Schema::dropIfExists('branch_user');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role_label', 'modules', 'manager', 'show_balance', 'custom_price']);
        });
    }
};
