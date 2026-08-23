<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->char('id', 36)->primary();
            $table->string('code', 30)->unique();
            $table->string('name', 100);
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->index('is_active', 'payment_methods_is_active_index');
        });

        Schema::create('payment_method_accounts', function (Blueprint $table) {
            $table->char('id', 36)->primary();
            $table->char('payment_method_id', 36);
            $table->char('branch_id', 36);
            $table->char('account_id', 36);
            $table->timestamps();

            $table->unique(['payment_method_id', 'branch_id'], 'payment_method_accounts_unique');
            $table->index('account_id', 'payment_method_accounts_account_id_index');

            $table->foreign('payment_method_id')
                ->references('id')
                ->on('payment_methods')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreign('branch_id')
                ->references('id')
                ->on('branches')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreign('account_id')
                ->references('id')
                ->on('accounting_accounts')
                ->cascadeOnUpdate();
        });

        $now = now();

        $rows = [
            ['CASH', 'Cash', 1],
            ['QRIS', 'QRIS', 2],
            ['TRANSFER', 'Transfer', 3],
        ];

        foreach ($rows as [$code, $name, $sort]) {
            DB::table('payment_methods')->insert([
                'id' => (string) Str::uuid(),
                'code' => $code,
                'name' => $name,
                'is_active' => true,
                'sort_order' => $sort,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_method_accounts');
        Schema::dropIfExists('payment_methods');
    }
};
