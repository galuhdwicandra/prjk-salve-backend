<?php
namespace App\Services;

use App\Models\Branch;
use App\Models\InvoiceCounter;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class InvoiceService
{
    public function __construct(private DocumentNumberService $numbers)
    {
    }
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
        if (! $branch) {
            throw new ModelNotFoundException('Branch not found');
        }

        $now = $now ?: Carbon::now('Asia/Jakarta');

        return DB::transaction(function () use ($branch, $now) {
            // Lock row counter by (branch_id, prefix)
            $counter = InvoiceCounter::query()
                ->where('branch_id', $branch->id)
                ->where('doc_key', DocumentNumberService::ORDER)
                ->lockForUpdate()
                ->first();

            if (! $counter) {
                $created = InvoiceCounter::query()->create(
                    $this->numbers->defaultsFor($branch, DocumentNumberService::ORDER)
                );

                $counter = InvoiceCounter::query()
                    ->whereKey($created->getKey())
                    ->lockForUpdate()
                    ->first();
            }

            // Reset jika perlu (monthly)
            $period = $this->numbers->periodKey($counter->reset_policy, $now);
            if ($period !== null && $counter->last_reset_month !== $period) {
                $counter->seq              = 0;
                $counter->last_reset_month = $period;
            }

            // Naikkan sequence
            $counter->seq = (int) $counter->seq + 1;
            $counter->save();

            // invoice_no: INV-DD-MM-#### (gunakan 4 digit terakhir seq)
            $seq4 = substr(str_pad((string) $counter->seq, 4, '0', STR_PAD_LEFT), -4);

            return [
                'number'     => $this->numbers->render(
                    $counter->format ?: DocumentNumberService::DOCUMENTS[DocumentNumberService::ORDER]['format'],
                    (int) $counter->seq,
                    $branch->invoice_prefix ?? 'SLV',
                    $now
                ),
                'invoice_no' => "INV-{$now->format('d')}-{$now->format('m')}-{$seq4}",
            ];
        });
    }

    public function preview(string $branchId, ?Carbon $now = null): array
    {
        $now ??= now('Asia/Jakarta');
        $dd = $now->format('d');
        $mm = $now->format('m');
        $ym = $now->format('Ym');

        /** @var Branch $branch */
        $branch = Branch::query()->findOrFail($branchId);
        $prefix = $branch->invoice_prefix ?? 'SLV';

        /** @var InvoiceCounter|null $counter */
        $counter = InvoiceCounter::query()
            ->where('branch_id', $branch->id)
            ->where('prefix', $prefix)
            ->first();

        $seq = $counter?->seq ?? 0;

        // Jika kebijakan reset bulanan aktif dan bulan terakhir berbeda, maka preview mulai dari 0 lagi
        if (($counter?->reset_policy ?? $branch->reset_policy ?? 'monthly') === 'monthly') {
            if (($counter?->last_reset_month) !== $ym) {
                $seq = 0;
            }
        }

        $next = $seq + 1;
        $seq6 = str_pad((string) $next, 6, '0', STR_PAD_LEFT);
        $seq4 = substr(str_pad((string) $next, 4, '0', STR_PAD_LEFT), -4);

        return [
            'number' => "{$prefix}-{$ym}-{$seq6}",
            'invoice_no' => "INV-{$dd}-{$mm}-{$seq4}",
        ];
    }
}
