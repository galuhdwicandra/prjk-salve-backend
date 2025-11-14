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
        // Pastikan branch ada
        $branch = Branch::query()->find($branchId);
        if (!$branch) {
            throw new ModelNotFoundException('Branch not found');
        }

        $prefix = $branch->invoice_prefix ?? 'SLV';
        $nowYm = Carbon::now()->format('Ym');

        return DB::transaction(function () use ($branch, $prefix, $nowYm) {
            // Lock row counter by (branch_id, prefix)
            $counter = InvoiceCounter::query()
                ->where('branch_id', $branch->id)
                ->where('prefix', $prefix)
                ->lockForUpdate()
                ->first();

            if (!$counter) {
                $counter = new InvoiceCounter([
                    'branch_id' => $branch->id,
                    'prefix' => $prefix,
                    'seq' => 0,
                    'reset_policy' => $branch->reset_policy ?? 'monthly',
                    'last_reset_month' => null,
                ]);
                $counter->save();
                $counter->refresh();
            }

            // Reset jika perlu
            if ($counter->reset_policy === 'monthly') {
                if ($counter->last_reset_month !== $nowYm) {
                    $counter->seq = 0;
                    $counter->last_reset_month = $nowYm;
                }
            }

            // Naikkan sequence
            $counter->seq = (int) $counter->seq + 1;
            $counter->save();

            // Format A: PREFIX-YYYYMM-SEQ6
            $seq6 = str_pad((string) $counter->seq, 6, '0', STR_PAD_LEFT);
            $invoiceNo = "{$counter->prefix}-{$nowYm}-{$seq6}";

            return $invoiceNo;
        });
    }
}
