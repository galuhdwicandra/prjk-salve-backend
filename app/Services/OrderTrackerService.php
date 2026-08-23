<?php
namespace App\Services;

use App\Models\Delivery;
use App\Models\Order;
use App\Models\OrderAuditLog;
use App\Models\PaymentMethod;
use App\Models\ProductionTaskLog;
use DateTimeInterface;
use Illuminate\Support\Str;

class OrderTrackerService
{
    private const WORK_LABELS = [
        'QUEUE'   => 'Masuk antrean',
        'WASHING' => 'Persiapan dimulai',
        'DRYING'  => 'Pengeringan',
        'IRONING' => 'Finishing',
        'READY'   => 'Selesai dikerjakan',
    ];

    private const POSITION_WORK = [
        'QUEUE'   => 'Menunggu proses',
        'WASHING' => 'Persiapan',
        'DRYING'  => 'Pengeringan',
        'IRONING' => 'Finishing',
        'READY'   => 'Selesai',
    ];

    private const AUDIT_LABELS = [
        'SORT'                     => 'Disortir',
        'DELIVERY_NOTE'            => 'Surat jalan dibuat',
        'SHIPPED'                  => 'Surat jalan selesai',
        'PICKUP_NOTE'              => 'Surat jalan ambil dibuat',
        'SUBCON_PICKED'            => 'Diambil dari subcon',
        'SUBCON_ARRIVED'           => 'Tiba dari subcon',
        'HANDOVER'                 => 'Diambil pelanggan',
        'COURIER'                  => 'Dikirim via kurir',
        'ORDER_VOIDED'             => 'Di-VOID',
        'VOID'                     => 'Di-VOID',
        'PAYMENT_RESET_TO_PENDING' => 'Pembayaran direset',
    ];

    public function stage(Order $order): array
    {
        return match (true) {
            $order->status === 'CANCELED'                                         => ['index' => -1, 'label' => 'Dibatalkan'],
            $order->status === 'PICKED_UP'                                        => ['index' => 3, 'label' => 'Selesai'],
            $order->status === 'DELIVERING'                                       => ['index' => 2, 'label' => 'Dalam pengantaran'],
            $order->status === 'READY'                                            => ['index' => 2, 'label' => 'Siap diambil'],
            $order->status === 'QUEUE' && $order->processing_destination === null => ['index' => 0, 'label' => 'Diterima'],
            default                                                               => ['index' => 1, 'label' => 'Sedang dikerjakan'],
        };
    }

    public function position(Order $order): array
    {
        if ($order->status === 'CANCELED') {
            return $this->chip('Dibatalkan (void)', 'c-belum');
        }

        if ($order->status === 'PICKED_UP') {
            return $this->chip('Sudah diambil pelanggan', 'c-diambil');
        }

        if ($order->status === 'DELIVERING') {
            return $this->chip('Sedang diantar kurir', 'c-proses');
        }

        if ($order->status === 'READY') {
            return $this->chip('Selesai · siap diambil', 'c-ready');
        }

        if ($order->processing_destination === null) {
            return $this->chip('Belum disortir', 'c-masuk', 'di outlet ' . ($order->branch?->code ?? '-'));
        }

        if ($order->processing_destination === 'vendor') {
            if ($order->deliveryNotes->firstWhere('kind', 'ambil')) {
                return $this->chip('Kembali dari subcon', 'c-proses');
            }

            $sent = $order->deliveryNotes->where('kind', 'kirim')->firstWhere('status', 'COMPLETED');

            return $sent
                ? $this->chip('Di subcon: ' . ($order->destinationContact?->name ?? '-'), 'c-proses')
                : $this->chip('Menunggu surat jalan ke subcon', 'c-masuk');
        }

        if ($order->destination_branch_id === null) {
            return $this->chip('Menunggu surat jalan ke workshop', 'c-masuk');
        }

        $work = self::POSITION_WORK[$order->productionTask?->current_status ?? ''] ?? 'Menunggu proses';

        return $this->chip('Di Workshop ' . ($order->destinationBranch?->name ?? '-') . ' · ' . $work, 'c-proses');
    }

    private function chip(string $label, string $tone, ?string $suffix = null): array
    {
        return ['label' => $label, 'tone' => $tone, 'suffix' => $suffix];
    }

    public function timeline(Order $order, bool $internal = true): array
    {
        $events = [$this->event($order->created_at, 'Order dibuat', $this->createdDetail($order, $internal))];

        $methods = PaymentMethod::query()->pluck('name', 'code');

        foreach ($order->payments as $payment) {
            $events[] = $this->event(
                $payment->paid_at ?? $payment->created_at,
                'Pembayaran',
                'Rp ' . number_format((float) $payment->amount, 0, ',', '.')
                . ' via ' . ($methods[$payment->method] ?? $payment->method),
            );
        }

        if ($order->productionTask) {
            $events[] = $this->event(
                $order->productionTask->created_at,
                'Masuk antrean pengerjaan',
                $order->processing_destination,
            );
        }

        $logs = ProductionTaskLog::query()
            ->with('user:id,name')
            ->where('order_id', $order->getKey())
            ->get();

        foreach ($logs as $log) {
            $events[] = $this->event(
                $log->created_at,
                self::WORK_LABELS[$log->to_status] ?? $log->to_status,
                $log->note,
                $internal ? $log->user?->name : null,
            );
        }

        $deliveries = Delivery::query()
            ->with(['events', 'courier:id,name'])
            ->where('order_id', $order->getKey())
            ->get();

        foreach ($deliveries as $delivery) {
            $courier = $internal ? $delivery->courier?->name : null;

            $events[] = $this->event($delivery->created_at, 'Pengiriman dibuat', $delivery->type, $courier);

            foreach ($delivery->events as $event) {
                $events[] = $this->event(
                    $event->created_at,
                    'Pengiriman: ' . $event->status,
                    $event->note,
                    $courier,
                );
            }
        }

        if ($internal) {
            $audits = OrderAuditLog::query()
                ->with('actor:id,name')
                ->where('order_id', $order->getKey())
                ->get();

            foreach ($audits as $audit) {
                $events[] = $this->event(
                    $audit->created_at,
                    self::AUDIT_LABELS[$audit->action] ?? $audit->action,
                    $this->auditDetail($audit),
                    $audit->actor?->name,
                    str_contains($audit->action, 'VOID'),
                );
            }
        }

        $events = array_values(array_filter(
            $events,
            static fn(array $event): bool => $event['at'] instanceof DateTimeInterface,
        ));

        usort($events, static fn(array $a, array $b): int => $a['at'] <=> $b['at']);

        return array_map(static fn(array $event): array=> [
            'at'     => $event['at']->format(DateTimeInterface::ATOM),
            'title'  => $event['title'],
            'detail' => $event['detail'],
            'actor'  => $event['actor'],
            'danger' => $event['danger'],
        ], $events);
    }

    public function photos(Order $order): array
    {
        return $order->photos
            ->where('kind', 'before')
            ->concat($order->photos->where('kind', 'after'))
            ->take(6)
            ->map(static fn($photo): array=> [
                'group' => $photo->kind === 'before' ? 'Sebelum pengerjaan' : 'Serah-terima',
                'path'  => $photo->path,
            ])
            ->values()
            ->all();
    }

    public function issueToken(Order $order, int $days = 90): Order
    {
        $order->tracker_token            = Str::random(48);
        $order->tracker_token_expires_at = now()->addDays($days);
        $order->save();

        return $order;
    }

    public function revokeToken(Order $order): Order
    {
        $order->tracker_token            = null;
        $order->tracker_token_expires_at = null;
        $order->save();

        return $order;
    }

    public function publicUrl(Order $order): ?string
    {
        if (! $order->tracker_token) {
            return null;
        }

        return rtrim((string) config('app.frontend_url'), '/') . '/t/' . $order->tracker_token;
    }

    private function event(
        ?DateTimeInterface $at,
        string $title,
        ?string $detail = null,
        ?string $actor = null,
        bool $danger = false,
    ): array {
        return [
            'at'     => $at,
            'title'  => $title,
            'detail' => $detail,
            'actor'  => $actor,
            'danger' => $danger,
        ];
    }

        private function createdDetail(Order $order, bool $internal): string
    {
        $parts = [];

        if ($order->branch) {
            $parts[] = 'Outlet ' . $order->branch->name
                . ' (' . $order->branch->code . ' · ' . $order->branch->type . ')';
        }

        if ($internal && $order->creator) {
            $parts[] = 'oleh ' . $order->creator->name;
        }

        $parts[] = (string) (float) $order->items->sum('qty') . ' pasang';

        $before = $order->photos->where('kind', 'before')->count();

        if ($before > 0) {
            $parts[] = $before . ' foto before';
        }

        return implode(' · ', $parts);
    }

    private function auditDetail(OrderAuditLog $audit): ?string
    {
        $snapshot = $audit->snapshot ?? [];

        $detail = match ($audit->action) {
            'SORT'          => 'Tujuan: ' . (($snapshot['processing_destination'] ?? null) === 'vendor' ? 'Subcon' : 'Workshop'),
            'DELIVERY_NOTE',
            'PICKUP_NOTE',
            'SHIPPED',
            'SUBCON_PICKED' => $snapshot['number'] ?? null,
            'COURIER'       => $snapshot['delivery_number'] ?? null,
            default         => null,
        };

        return $audit->reason ?: $detail;
    }
}
