{{-- resources/views/orders/receipt.blade.php --}}
<!doctype html>
<html lang="id" data-theme="light">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1" />

  @php
  // Logika asli dipertahankan
  $sisa = max((float) $order->grand_total - (float) $order->paid_amount, 0);
  $isLunas = $sisa <= 0 && $order->payment_status === 'PAID';
    $docTitle = $isLunas ? 'KUITANSI PEMBAYARAN' : 'TAGIHAN / INVOICE';
    @endphp

    <title>{{ $docTitle }} {{ $order->invoice_no ?? $order->number }}</title>

    <style>
      /* ========= Design tokens SALVE (screen view) ========= */
      :root {
        --brand: #0000FF;
        --on-brand: #FFFFFF;
        --text: #0B1220;
        --surface: #F5F7FB;
        --border: #E6EAF2;
        --success: #11A362;
        --warning: #EF9300;
        --danger: #D92D20;
        --info: #2E7CF6;
        --radius-sm: 8px;
        --radius-md: 12px;
        --radius-lg: 16px;
        --shadow-1: 0 1px 2px rgba(16, 24, 40, .06), 0 1px 3px rgba(16, 24, 40, .10);
        --shadow-2: 0 8px 16px rgba(16, 24, 40, .10), 0 2px 6px rgba(16, 24, 40, .08);
        --focus: 0 0 0 3px rgba(0, 0, 255, .25);
      }

      [data-theme="dark"] {
        --text: #F5F7FB;
        --surface: #0F172A;
        --border: #1E293B;
      }

      @media (prefers-color-scheme:dark) {
        html:not([data-theme="light"]) {
          --text: #F5F7FB;
          --surface: #0F172A;
          --border: #1E293B;
        }
      }

      /* ========= Base ========= */
      * {
        box-sizing: border-box;
      }

      html,
      body {
        height: 100%;
      }

      body {
        margin: 0;
        font-family: ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, "Helvetica Neue", Arial, "Noto Sans", "Apple Color Emoji", "Segoe UI Emoji";
        color: var(--text);
        background: var(--surface);
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
      }

      a {
        color: var(--brand);
        text-decoration: none;
      }

      a:hover {
        text-decoration: underline;
      }

      :focus-visible {
        outline: 0;
        box-shadow: var(--focus);
        border-radius: 6px;
      }

      /* ========= Layout ========= */
      .container {
        max-width: 720px;
        margin-inline: auto;
        padding: 24px;
        display: grid;
        gap: 16px;
      }

      .header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 12px;
      }

      .brand {
        font-weight: 700;
        font-size: 18px;
        line-height: 1.2;
      }

      .doctype {
        font-weight: 700;
        font-size: 12px;
        color: #1A4BFF;
        background: #E6EDFF;
        padding: 6px 10px;
        border-radius: 999px;
      }

      .card {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-1);
      }

      .section {
        padding: 16px;
      }

      .section+.section {
        border-top: 1px solid var(--border);
      }

      /* ========= Meta grid ========= */
      .meta {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
        font-size: 14px;
      }

      .meta dt {
        color: #475569;
        font-weight: 500;
      }

      .meta dd {
        margin: 0;
      }

      /* ========= Badge status ========= */
      .badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 6px 10px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 600;
      }

      .badge--paid {
        color: #065F46;
        background: rgba(17, 163, 98, .12);
      }

      .badge--unpaid {
        color: #7C2D12;
        background: rgba(217, 45, 32, .12);
      }

      /* ========= Table items ========= */
      .table-wrap {
        overflow: auto;
        border: 1px solid var(--border);
        border-radius: var(--radius-md);
      }

      table {
        border-collapse: collapse;
        width: 100%;
        font-size: 14px;
        background: #fff;
      }

      thead th {
        text-align: left;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: .04em;
        color: #334155;
        background: #E6EDFF;
        padding: 10px 12px;
        position: sticky;
        top: 0;
        z-index: 1;
      }

      tbody td {
        padding: 12px;
        border-top: 1px solid var(--border);
        vertical-align: top;
      }

      tbody tr:hover {
        background: rgba(0, 0, 0, .04);
      }

      .right {
        text-align: right;
      }

      .muted {
        color: #475569;
      }

      .bold {
        font-weight: 700;
      }

      /* ========= Totals ========= */
      .totals {
        display: grid;
        gap: 8px;
      }

      .row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
      }

      .row--strong .label {
        font-weight: 700;
      }

      .row--strong .value {
        font-weight: 800;
        font-size: 18px;
      }

      /* ========= QRIS ========= */
      .qris-box {
        display: grid;
        justify-items: center;
        gap: 8px;
        text-align: center;
      }

      .qris {
        width: 100%;
        max-width: 220px;
        height: auto;
        image-rendering: -webkit-optimize-contrast;
        border-radius: var(--radius-sm);
        box-shadow: var(--shadow-1);
      }

      .qris-caption {
        font-size: 12px;
        color: #334155;
      }

      /* ========= Footer ========= */
      .footer {
        font-size: 12px;
        color: #64748B;
      }

      /* ========= Stamp Loyalty ========= */
      .stamps {
        display: grid;
        grid-template-columns: repeat(10, 1fr);
        gap: 6px;
        margin-top: 8px;
      }

      .stamp {
        height: 22px;
        border: 1px dashed var(--border);
        border-radius: var(--radius-sm);
        background: #fff;
        box-shadow: var(--shadow-1);
      }

      .stamp--filled {
        border-style: solid;
        border-color: #1A4BFF;
        background: #E6EDFF;
      }

      .stamp--milestone {
        position: relative;
      }

      .stamp--milestone::after {
        content: attr(data-m);
        position: absolute;
        inset: 0;
        display: grid;
        place-items: center;
        font-size: 10px;
        font-weight: 700;
        color: #1A4BFF;
      }

      /* Card helpers */
      .stack {
        display: grid;
        gap: 12px;
      }

      .split {
        display: grid;
        gap: 16px;
        grid-template-columns: 1fr;
      }

      @media (min-width:720px) {
        .split {
          grid-template-columns: 1.2fr .8fr;
        }
      }
    </style>
</head>

<body>
  <main class="container" role="main">
    <!-- Header -->
    <div class="header">
      <div class="stack">
        <div class="brand">{{ $branch?->name ?? 'Salve Laundry' }}</div>
        <div class="doctype">{{ $docTitle }}</div>
      </div>
      <div>
        <span class="badge {{ $isLunas ? 'badge--paid' : 'badge--unpaid' }}">
          {{ $isLunas ? 'Lunas' : 'Belum Lunas' }}
        </span>
      </div>
    </div>

    <!-- Identitas & Status -->
    <section class="card">
      <div class="section">
        <dl class="meta">
          <div>
            <dt>No. Dokumen</dt>
            <dd>{{ $order->invoice_no ?? $order->number }}</dd>
          </div>
          <div>
            <dt>Tgl Dibuat</dt>
            <dd>{{ \Illuminate\Support\Carbon::parse($order->created_at)->format('d/m/Y H:i') }}</dd>
          </div>
          <div>
            <dt>Status Bayar</dt>
            <dd>{{ $order->payment_status }}</dd>
          </div>
          <div>
            <dt>Pelanggan</dt>
            <dd>{{ $order->customer->name ?? '—' }}</dd>
          </div>
          <div>
            <dt>Tgl Masuk</dt>
            <dd>
              {{ $order->received_at ? \Illuminate\Support\Carbon::parse($order->received_at)->format('d/m/Y H:i') : '—' }}
            </dd>
          </div>
          <div>
            <dt>Tgl Selesai</dt>
            <dd>
              {{ $order->ready_at ? \Illuminate\Support\Carbon::parse($order->ready_at)->format('d/m/Y H:i') : '—' }}
            </dd>
          </div>
        </dl>
      </div>
    </section>

    <!-- Items + Ringkasan -->
    <section class="split">
      <!-- Items -->
      <div class="card">
        <div class="section">
          <div class="muted" style="font-size:12px; margin-bottom:8px;">Rincian Layanan</div>
          <div class="table-wrap">
            <table aria-label="Tabel item layanan">
              <thead>
                <tr>
                  <th>Layanan</th>
                  <th class="right">Qty</th>
                  <th class="right">Subtotal</th>
                </tr>
              </thead>
              <tbody>
                @foreach($order->items as $it)
                <tr>
                  <td>
                    <div class="bold">{{ $it->service->name ?? 'Layanan' }}</div>
                  </td>
                  <td class="right">{{ (float) $it->qty }}</td>
                  <td class="right">{{ number_format((float) $it->total, 0, ',', '.') }}</td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- Ringkasan Pembayaran -->
      <div class="card">
        <div class="section stack">
          <div class="muted" style="font-size:12px;">Ringkasan Pembayaran</div>
          <div class="totals">
            <div class="row">
              <div class="label">Subtotal</div>
              <div class="value">{{ number_format((float) $order->subtotal, 0, ',', '.') }}</div>
            </div>
            <div class="row">
              <div class="label">Diskon</div>
              <div class="value">{{ number_format((float) $order->discount, 0, ',', '.') }}</div>
            </div>
            <div class="row row--strong">
              <div class="label">Grand Total</div>
              <div class="value">{{ number_format((float) $order->grand_total, 0, ',', '.') }}</div>
            </div>
            <div class="row">
              <div class="label">Dibayar</div>
              <div class="value">{{ number_format((float) $order->paid_amount, 0, ',', '.') }}</div>
            </div>
            <div class="row">
              <div class="label">{{ $isLunas ? 'Sisa' : 'Sisa Tagihan' }}</div>
              <div class="value">{{ number_format($sisa, 0, ',', '.') }}</div>
            </div>
          </div>
        </div>

        {{-- === Stamp Loyalty (tampil hanya jika data tersedia dari controller) === --}}
        @if(isset($loy) && $loy)
        <div class="section">
          <div class="muted" style="font-size:12px; margin-bottom:8px;">Stamp Loyalty</div>

          <div class="row">
            <div class="label">Progress</div>
            <div class="value">{{ $loy['stamps'] }} / {{ $loy['cycle'] }}</div>
          </div>

          @php
          $cycle = (int)($loy['cycle'] ?? 10);
          $stamps = (int)($loy['stamps'] ?? 0);
          $filled = $cycle > 0 ? ($stamps % $cycle) : 0;
          // Jika tepat jatuh reward pada transaksi ini (mod 0), tampilkan penuh:
          if ($filled === 0 && !empty($order->loyalty_reward)) {
          $filled = $cycle;
          }
          @endphp
          <div class="stamps" role="list" aria-label="Progres stamp loyalty">
            @for ($i = 1; $i <= $cycle; $i++)
              @php
              $isFilled=$i <=$filled;
              $milestone=($i===5 || $i===10) ? $i : null; // penanda 5 & 10
              @endphp
              <div
              role="listitem"
              class="stamp {{ $isFilled ? 'stamp--filled' : '' }} {{ $milestone ? 'stamp--milestone' : '' }}"
              @if($milestone) data-m="{{ $milestone }}" @endif>
          </div>
          @endfor
        </div>

        @if(!empty($order->loyalty_reward))
        <div style="font-size:12px; margin:6px 0 8px 0;">
          Reward diterapkan pada transaksi ini:
          @if($order->loyalty_reward === 'DISC25') <strong>Diskon 25%</strong>.
          @elseif($order->loyalty_reward === 'FREE100') <strong>Gratis 100%</strong>.
          @else <strong>{{ $order->loyalty_reward }}</strong>.
          @endif
        </div>
        @endif

        <div class="row">
          <div class="label">Sisa ke Diskon 25%</div>
          <div class="value">{{ $loy['sisa25'] }}</div>
        </div>
        <div class="row">
          <div class="label">Sisa ke Gratis 100%</div>
          <div class="value">{{ $loy['sisa100'] }}</div>
        </div>
      </div>
      @endif

      @php
      $qrisPath = 'qris.png';
      $hasQris = \Illuminate\Support\Facades\Storage::disk('public')->exists($qrisPath);
      @endphp

      @if(!$isLunas && $hasQris)
      <div class="section qris-box">
        <div class="qris-caption">Scan untuk bayar (QRIS)</div>
        <img class="qris" src="{{ asset('storage/'.$qrisPath) }}" alt="QRIS">
      </div>
      @endif
      </div>
    </section>

    <!-- Footer -->
    <section class="card">
      <div class="section footer">
        Dicetak: {{ $printedAt->format('d/m/Y H:i') }}
      </div>
    </section>
  </main>
</body>

</html>