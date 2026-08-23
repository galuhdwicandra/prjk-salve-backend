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
        Schema::create('transaction_categories', function (Blueprint $table) {
            $table->char('id', 36)->primary();
            $table->string('name', 100)->unique();
            $table->boolean('cash_in')->default(false);
            $table->boolean('cash_out')->default(false);
            $table->enum('cashflow', ['OPERATING', 'INVESTING', 'FINANCING'])->default('OPERATING');
            $table->string('description', 200)->nullable();
            $table->boolean('is_default')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('is_active', 'transaction_categories_is_active_index');
        });

        $now = now();

        $rows = [
            ['Bahan & HPP', false, true, 'OPERATING', 'Sabun, chemical, sparepart, lem', false],
            ['Bayar Cicilan Pinjaman', false, true, 'FINANCING', null, false],
            ['Biaya Admin', false, true, 'OPERATING', 'Biaya admin transfer bank', true],
            ['Gaji & Upah', false, true, 'OPERATING', 'Gaji karyawan, borongan', false],
            ['Lain-lain', true, true, 'OPERATING', null, false],
            ['Listrik & Air', false, true, 'OPERATING', 'Utilitas', false],
            ['Marketing', false, true, 'OPERATING', 'Iklan, konten, promosi', false],
            ['Pembelian Aset', false, true, 'INVESTING', null, false],
            ['Penarikan Pemilik (Prive)', false, true, 'FINANCING', null, false],
            ['Pendapatan Lain-lain', true, false, 'OPERATING', 'Pemasukan di luar penjualan', false],
            ['Penjualan Aset', true, false, 'INVESTING', 'Hasil jual aset/alat', false],
            ['Perlengkapan', false, true, 'OPERATING', 'Alat & perlengkapan operasional', false],
            ['Setoran Modal', true, false, 'FINANCING', null, false],
            ['Sewa Tempat', false, true, 'OPERATING', 'Sewa gerai / workshop', false],
            ['Terima Pinjaman', true, false, 'FINANCING', null, false],
            ['Transfer Antar Kas', true, true, 'OPERATING', null, false],
        ];

        foreach ($rows as [$name, $cashIn, $cashOut, $cashflow, $description, $isDefault]) {
            DB::table('transaction_categories')->insert([
                'id' => (string) Str::uuid(),
                'name' => $name,
                'cash_in' => $cashIn,
                'cash_out' => $cashOut,
                'cashflow' => $cashflow,
                'description' => $description,
                'is_default' => $isDefault,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('transaction_categories');
    }
};
