{{-- resources/views/orders/receipt.blade.php --}}
<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    @php
        // Hitung sisa, lalu tentukan apakah sudah lunas atau belum
        $sisa = max((float) $order->grand_total - (float) $order->paid_amount, 0);
        $isLunas = $sisa <= 0 && $order->payment_status === 'PAID';
        $docTitle = $isLunas ? 'KUITANSI PEMBAYARAN' : 'TAGIHAN / INVOICE';
    @endphp
    <title>{{ $docTitle }} {{ $order->invoice_no ?? $order->number }}</title>
    <style>
        * {
            font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, monospace;
        }

        body {
            width: 280px;
            margin: 0;
        }

        h1 {
            font-size: 14px;
            margin: 0 0 4px;
            text-align: center;
        }

        .doc-type {
            font-size: 12px;
            text-align: center;
            font-weight: bold;
            margin-bottom: 4px;
        }

        .meta,
        .totals {
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }

        td {
            padding: 2px 0;
            vertical-align: top;
        }

        .right {
            text-align: right;
        }

        .sep {
            border-top: 1px dashed #000;
            margin: 6px 0;
        }
    </style>
</head>

<body>
    <h1>{{ $branch?->name ?? 'Salve Laundry' }}</h1>
    <div class="doc-type">
        {{ $docTitle }}
    </div>
    <div class="meta">
        No: {{ $order->invoice_no ?? $order->number }}<br>
        Tgl: {{ \Illuminate\Support\Carbon::parse($order->created_at)->format('d/m/Y H:i') }}<br>
        Status Bayar: {{ $order->payment_status }}
    </div>
    <div class="sep"></div>
    <table>
        <tbody>
            @foreach($order->items as $it)
                <tr>
                    <td>{{ $it->service->name ?? 'Layanan' }} x{{ (float) $it->qty }}</td>
                    <td class="right">{{ number_format((float) $it->total, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="sep"></div>
    <table class="totals">
        <tr>
            <td>Subtotal</td>
            <td class="right">{{ number_format((float) $order->subtotal, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Diskon</td>
            <td class="right">{{ number_format((float) $order->discount, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td><strong>Grand Total</strong></td>
            <td class="right"><strong>{{ number_format((float) $order->grand_total, 0, ',', '.') }}</strong></td>
        </tr>
        <tr>
            <td>Dibayar</td>
            <td class="right">{{ number_format((float) $order->paid_amount, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>{{ $isLunas ? 'Sisa' : 'Sisa Tagihan' }}</td>
            <td class="right">
                {{ number_format($sisa, 0, ',', '.') }}
            </td>
        </tr>
    </table>
    <div class="sep"></div>
    <div class="meta">Dicetak: {{ $printedAt->format('d/m/Y H:i') }}</div>
</body>

</html>
