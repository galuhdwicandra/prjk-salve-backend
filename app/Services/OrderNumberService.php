<?php

// app/Services/OrderNumberService.php
namespace App\Services;

use App\Models\InvoiceCounter;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class OrderNumberService
{
    public function next(string $branchId, ?Carbon $now = null): string
    {
        $now ??= now('Asia/Jakarta');
        $period = $now->format('Ym');       // 202510
        $prefix = 'SLV';                    // atau ambil dari setting

        return DB::transaction(function () use ($branchId, $period, $prefix) {
            // lock baris counter per cabang+periode
            $row = InvoiceCounter::query()
                ->where('branch_id', $branchId)
                ->where('period', $period)
                ->lockForUpdate()
                ->first();

            if (!$row) {
                // buat dulu lalu lock lagi untuk mencegah race
                $row = InvoiceCounter::create([
                    'branch_id' => $branchId,
                    'period' => $period,   // CHAR(6) 'YYYYMM'
                    'prefix' => $prefix,
                    'last_no' => 0,
                ]);

                $row = InvoiceCounter::query()
                    ->whereKey($row->getKey())
                    ->lockForUpdate()
                    ->first();
            }

            $row->last_no = (int) $row->last_no + 1;
            $row->save();

            $seq = str_pad((string) $row->last_no, 6, '0', STR_PAD_LEFT);
            // Format: SLV-202510-000123
            return "{$row->prefix}-{$row->period}-{$seq}";
        });
    }
}

