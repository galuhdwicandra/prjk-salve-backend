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
        Schema::table('transaction_categories', function (Blueprint $table) {
            $table->char('in_account_id', 36)->nullable()->after('cashflow');
            $table->char('out_account_id', 36)->nullable()->after('in_account_id');

            $table->foreign('in_account_id')->references('id')->on('accounting_accounts')->nullOnDelete()->cascadeOnUpdate();
            $table->foreign('out_account_id')->references('id')->on('accounting_accounts')->nullOnDelete()->cascadeOnUpdate();
        });

        $now = now();

        $newAccounts = [
            ['1050', 'Peralatan & Aset Tetap', 'ASSET', 'DEBIT', 15],
            ['2020', 'Utang Pinjaman', 'LIABILITY', 'CREDIT', 22],
            ['4020', 'Pendapatan Lain-lain', 'REVENUE', 'CREDIT', 42],
        ];

        foreach ($newAccounts as [$code, $name, $type, $normalBalance, $sortOrder]) {
            $exists = DB::table('accounting_accounts')->whereNull('branch_id')->where('code', $code)->exists();

            if ($exists) {
                continue;
            }

            DB::table('accounting_accounts')->insert([
                'id' => (string) Str::uuid(),
                'branch_id' => null,
                'parent_id' => null,
                'code' => $code,
                'name' => $name,
                'type' => $type,
                'normal_balance' => $normalBalance,
                'is_cash_account' => false,
                'is_active' => true,
                'sort_order' => $sortOrder,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        $idByCode = DB::table('accounting_accounts')->whereNull('branch_id')->pluck('id', 'code');

        $defaults = [
            'Bahan & HPP' => [null, '5020'],
            'Bayar Cicilan Pinjaman' => [null, '2020'],
            'Biaya Admin' => [null, '5090'],
            'Gaji & Upah' => [null, '5010'],
            'Lain-lain' => ['4020', '5090'],
            'Listrik & Air' => [null, '5030'],
            'Marketing' => [null, '5010'],
            'Pembelian Aset' => [null, '1050'],
            'Penarikan Pemilik (Prive)' => [null, '3020'],
            'Pendapatan Lain-lain' => ['4020', null],
            'Penjualan Aset' => ['1050', null],
            'Perlengkapan' => [null, '5010'],
            'Setoran Modal' => ['3010', null],
            'Sewa Tempat' => [null, '5010'],
            'Terima Pinjaman' => ['2020', null],
        ];

        foreach ($defaults as $name => [$inCode, $outCode]) {
            DB::table('transaction_categories')->where('name', $name)->update([
                'in_account_id' => $inCode ? ($idByCode[$inCode] ?? null) : null,
                'out_account_id' => $outCode ? ($idByCode[$outCode] ?? null) : null,
                'updated_at' => $now,
            ]);
        }
    }

    public function down(): void
    {
        Schema::table('transaction_categories', function (Blueprint $table) {
            $table->dropForeign(['in_account_id']);
            $table->dropForeign(['out_account_id']);
            $table->dropColumn(['in_account_id', 'out_account_id']);
        });
    }
};
