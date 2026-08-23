<?php
namespace App\Services;

use App\Models\Branch;
use App\Models\InvoiceCounter;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DocumentNumberService
{
    public const ORDER    = 'order';
    public const CASH_IN  = 'cashin';
    public const CASH_OUT = 'cashout';
    public const TRANSFER = 'transfer';

    public const DOCUMENTS = [
        'order'      => [
            'group'        => 'Penjualan',
            'label'        => 'Order Penjualan',
            'status'       => 'aktif',
            'format'       => '[OUTLET]-[YYYY][MM]-[NUMBER:6]',
            'reset_policy' => 'monthly',
        ],
        'cashin'     => [
            'group'        => 'Transaksi Bank',
            'label'        => 'Uang Masuk',
            'status'       => 'aktif',
            'format'       => 'IN[YY][MM]-[NUMBER]',
            'reset_policy' => 'monthly',
        ],
        'cashout'    => [
            'group'        => 'Transaksi Bank',
            'label'        => 'Uang Keluar',
            'status'       => 'aktif',
            'format'       => 'OUT[YY][MM]-[NUMBER]',
            'reset_policy' => 'monthly',
        ],
        'transfer'   => [
            'group'        => 'Transaksi Bank',
            'label'        => 'Pindah Dana',
            'status'       => 'aktif',
            'format'       => 'TRF-[YY][MM]-[NUMBER]',
            'reset_policy' => 'monthly',
        ],
        'suratjalan' => [
            'group'        => 'Operasional',
            'label'        => 'Surat Jalan',
            'status'       => 'aktif',
            'format'       => 'SJ-[OUTLET]-[YY][MM]-[NUMBER]',
            'reset_policy' => 'monthly',
        ],
        'delivery'   => [
            'group'        => 'Operasional',
            'label'        => 'Pengantaran Kurir',
            'status'       => 'aktif',
            'format'       => 'DLV-[OUTLET]-[YY][MM]-[NUMBER]',
            'reset_policy' => 'monthly',
        ],
        'contact'    => [
            'group'        => 'Kontak',
            'label'        => 'Kontak / Subcon ID',
            'status'       => 'rencana',
            'format'       => 'VND-[NUMBER]',
            'reset_policy' => 'never',
        ],
    ];

    public function periodKey(string $resetPolicy, Carbon $now): ?string
    {
        return match ($resetPolicy) {
            'monthly' => $now->format('Ym'),
            'yearly'  => $now->format('Y'),
            default   => null,
        };
    }

    public function next(Branch $branch, string $docKey, ?Carbon $now = null): string
    {
        $now ??= Carbon::now('Asia/Jakarta');

        return DB::transaction(function () use ($branch, $docKey, $now) {
            $counter = InvoiceCounter::query()
                ->where('branch_id', $branch->id)
                ->where('doc_key', $docKey)
                ->lockForUpdate()
                ->first();

            if (! $counter) {
                $created = InvoiceCounter::query()->create($this->defaultsFor($branch, $docKey));

                $counter = InvoiceCounter::query()
                    ->whereKey($created->getKey())
                    ->lockForUpdate()
                    ->first();
            }
            $period = $this->periodKey($counter->reset_policy, $now);
            if ($period !== null && $counter->last_reset_month !== $period) {
                $counter->seq              = 0;
                $counter->last_reset_month = $period;
            }

            $counter->seq = (int) $counter->seq + 1;
            $counter->save();

            return $this->render(
                $counter->format ?: self::DOCUMENTS[$docKey]['format'],
                (int) $counter->seq,
                $branch->invoice_prefix ?? 'SLV',
                $now
            );
        });
    }

    public function render(string $format, int $seq, string $outlet, Carbon $now): string
    {
        $rendered = strtr($format, [
            '[YYYY]'   => $now->format('Y'),
            '[YY]'     => $now->format('y'),
            '[MM]'     => $now->format('m'),
            '[DD]'     => $now->format('d'),
            '[OUTLET]' => $outlet,
        ]);

        return (string) preg_replace_callback(
            '/\[NUMBER(?::(\d+))?\]/',
            fn(array $matches) => str_pad((string) $seq, (int) ($matches[1] ?? 4), '0', STR_PAD_LEFT),
            $rendered
        );
    }

    public function defaultsFor(Branch $branch, string $docKey): array
    {
        $doc = self::DOCUMENTS[$docKey];

        return [
            'branch_id'        => $branch->id,
            'doc_key'          => $docKey,
            'prefix'           => $branch->invoice_prefix ?? 'SLV',
            'format'           => $doc['format'],
            'seq'              => 0,
            'reset_policy'     => $doc['reset_policy'],
            'last_reset_month' => null,
        ];
    }

    public function preview(Branch $branch, ?Carbon $now = null): array
    {
        $now ??= Carbon::now('Asia/Jakarta');
        $outlet = $branch->invoice_prefix ?? 'SLV';

        $rows = InvoiceCounter::query()
            ->where('branch_id', $branch->id)
            ->whereNotNull('doc_key')
            ->get()
            ->keyBy('doc_key');

        $documents = [];

        foreach (self::DOCUMENTS as $key => $doc) {
            $row    = $rows->get($key);
            $format = $row?->format ?: $doc['format'];
            $policy = $row?->reset_policy ?: $doc['reset_policy'];
            $period = $this->periodKey($policy, $now);

            $seq = (int) ($row?->seq ?? 0);
            if ($period !== null && $row?->last_reset_month !== $period) {
                $seq = 0;
            }

            $documents[] = [
                'id'           => $row?->id,
                'key'          => $key,
                'group'        => $doc['group'],
                'label'        => $doc['label'],
                'status'       => $doc['status'],
                'format'       => $format,
                'reset_policy' => $policy,
                'seq'          => $seq,
                'next'         => $this->render($format, $seq + 1, $outlet, $now),
            ];
        }

        return $documents;
    }
}
