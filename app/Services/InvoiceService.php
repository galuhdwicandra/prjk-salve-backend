<?php

namespace App\Services;

use App\Models\Branch;
use App\Models\InvoiceCounter;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class InvoiceService
{
    /**
     * Generate nomor faktur format: {PREFIX}-{YYYYMM}-{SEQ6}
     * Reset bulanan jika reset_policy = 'monthly'.
     */
        public function generate(string $branchId): string
    {
        $ids = $this->generatePair($branchId);
        return $ids['number'];
    }

    /**
     * Generate dua nomor sekaligus dalam satu transaksi DB:
     * - number     : {PREFIX}-{YYYYMM}-{SEQ6}  (tetap untuk kompatibilitas)
     * - invoice_no : INV-{DD}-{MM}-{####}      (untuk ditampilkan ke user/struk)
     */
    public function generatePair(string $branchId, ?Carbon $now = null): array
    {
        // Pastikan branch ada
        $branch = Branch::query()->find($branchId);
        if (!$branch) {
            throw new ModelNotFoundException('Branch not found');
        }

        $now    = $now ?: Carbon::now('Asia/Jakarta');
        $nowYm  = $now->format('Ym'); // contoh: 202511
        $dd     = $now->format('d');  // 25
        $mm     = $now->format('m');  // 11
        $prefix = $branch->invoice_prefix ?? 'SLV';

        return DB::transaction(function () use ($branch, $prefix, $nowYm, $dd, $mm) {
            // Lock row counter by (branch_id, prefix)
            $counter = InvoiceCounter::query()
                ->where('branch_id', $branch->id)
                ->where('prefix', $prefix)
                ->lockForUpdate()
                ->first();

            if (!$counter) {
                $counter = new InvoiceCounter([
                    'branch_id' => $branch->id,
                    'prefix'    => $prefix,
                    'seq'       => 0,
                    'reset_policy'     => $branch->reset_policy ?? 'monthly',
                    'last_reset_month' => null,
                ]);
                $counter->save();
                $counter->refresh();
            }

            // Reset jika perlu (monthly)
            if ($counter->reset_policy === 'monthly') {
                if ($counter->last_reset_month !== $nowYm) {
                    $counter->seq = 0;
                    $counter->last_reset_month = $nowYm;
                }
            }

            // Naikkan sequence
            $counter->seq = (int) $counter->seq + 1;
            $counter->save();

            // number: PREFIX-YYYYMM-SEQ6
            $seq6   = str_pad((string) $counter->seq, 6, '0', STR_PAD_LEFT);
            $number = "{$counter->prefix}-{$nowYm}-{$seq6}";

            // invoice_no: INV-DD-MM-#### (gunakan 4 digit terakhir seq)
            $seq4      = substr(str_pad((string) $counter->seq, 4, '0', STR_PAD_LEFT), -4);
            $invoiceNo = "INV-{$dd}-{$mm}-{$seq4}";

            return ['number' => $number, 'invoice_no' => $invoiceNo];
        });
    }
}
