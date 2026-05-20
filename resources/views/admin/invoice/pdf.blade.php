<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<style>
* { margin: 0; padding: 0; box-sizing: border-box; }
body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #1a1a1a; background: #fff; }
table { border-collapse: collapse; }
</style>
</head>
<body>

{{-- ── HEADER ─────────────────────────────────────────── --}}
<table width="100%" style="background-color:#065f46;">
  <tr>
    <td style="padding:18px 30px; vertical-align:middle;">
      @if(!empty($logoData))
        <img src="{{ $logoData }}" alt="RSH Satu Bumi" style="height:36px; display:block;">
      @else
        <div style="font-size:18px;font-weight:700;color:#fff;letter-spacing:-0.5px;">RSH Satu Bumi</div>
      @endif
    </td>
    <td style="padding:18px 30px; text-align:right; vertical-align:middle;">
      <div style="font-size:9px;color:#a7f3d0;text-transform:uppercase;letter-spacing:1.5px;margin-bottom:3px;">Invoice</div>
      <div style="font-size:16px;font-weight:700;color:#fff;letter-spacing:0.5px;">{{ $invoice->invoice_number }}</div>
    </td>
  </tr>
</table>

{{-- ── ADDRESS BAR ─────────────────────────────────────── --}}
<table width="100%" style="background-color:#1a4731;">
  <tr>
    <td style="padding:5px 30px; color:#6ee7b7; font-size:9px; letter-spacing:0.3px;">
      {{ $settings['site_address'] ?? 'Jl. Bukit Pelangi KM 2, Bogor' }}
      &nbsp;·&nbsp;
      {{ $settings['site_phone'] ?? '' }}
      @if(!empty($settings['site_email']))
        &nbsp;·&nbsp; {{ $settings['site_email'] }}
      @endif
    </td>
  </tr>
</table>

{{-- ── BODY ────────────────────────────────────────────── --}}
<div style="padding:24px 30px;">
@php
    $period    = $invoice->registration?->programPeriod;
    $isDP      = $period
                 && $invoice->payment_status !== 'DIBATALKAN'
                 && $invoice->total_amount < $period->price;
    $remaining = ($isDP && $period) ? ($period->price - $invoice->total_amount) : 0;
@endphp

  {{-- Bill-To / Date --}}
  <table width="100%" style="margin-bottom:18px;">
    <tr>
      <td style="width:55%; vertical-align:top; padding-right:20px;">
        <div style="font-size:8px;text-transform:uppercase;color:#9ca3af;letter-spacing:1px;margin-bottom:5px;">Tagihan Kepada</div>
        <div style="font-size:13px;font-weight:700;color:#111827;">{{ $invoice->client_name }}</div>
        @if($invoice->registration?->programPeriod)
        <div style="font-size:10px;color:#6b7280;margin-top:3px;">{{ $invoice->registration->programPeriod->name }}</div>
        @endif
      </td>
      <td style="width:45%; text-align:right; vertical-align:top;">
        <div style="font-size:8px;text-transform:uppercase;color:#9ca3af;letter-spacing:1px;margin-bottom:5px;">Tanggal Invoice</div>
        <div style="font-size:12px;font-weight:600;color:#111827;">{{ $invoice->invoice_date->format('d F Y') }}</div>
        <div style="margin-top:7px;">
          @if($isDP)
            <span style="display:inline-block;padding:3px 12px;border-radius:20px;font-size:9px;font-weight:700;background:#fef9c3;color:#854d0e;">DOWN PAYMENT</span>
          @elseif($invoice->payment_status === 'LUNAS')
            <span style="display:inline-block;padding:3px 12px;border-radius:20px;font-size:9px;font-weight:700;background:#d1fae5;color:#065f46;">LUNAS</span>
          @elseif($invoice->payment_status === 'DIBATALKAN')
            <span style="display:inline-block;padding:3px 12px;border-radius:20px;font-size:9px;font-weight:700;background:#f3f4f6;color:#6b7280;">DIBATALKAN</span>
          @else
            <span style="display:inline-block;padding:3px 12px;border-radius:20px;font-size:9px;font-weight:700;background:#fef3c7;color:#92400e;">BELUM LUNAS</span>
          @endif
        </div>
      </td>
    </tr>
  </table>

  {{-- Divider --}}
  <div style="border-top:1px solid #d1fae5; margin-bottom:16px;"></div>

  {{-- Items Table --}}
  <table width="100%">
    <thead>
      <tr style="background-color:#ecfdf5;">
        <th style="padding:8px 10px; text-align:left; font-size:9px; color:#065f46; text-transform:uppercase; letter-spacing:0.5px;">Deskripsi</th>
        <th style="padding:8px 10px; text-align:center; font-size:9px; color:#065f46; text-transform:uppercase; letter-spacing:0.5px; width:50px;">Qty</th>
        <th style="padding:8px 10px; text-align:right; font-size:9px; color:#065f46; text-transform:uppercase; letter-spacing:0.5px; width:115px;">Harga</th>
        <th style="padding:8px 10px; text-align:right; font-size:9px; color:#065f46; text-transform:uppercase; letter-spacing:0.5px; width:55px;">Disc%</th>
        <th style="padding:8px 10px; text-align:right; font-size:9px; color:#065f46; text-transform:uppercase; letter-spacing:0.5px; width:125px;">Subtotal</th>
      </tr>
    </thead>
    <tbody>
      @foreach($invoice->items as $item)
      <tr style="border-bottom:1px solid #f3f4f6;">
        <td style="padding:9px 10px; font-size:11px; color:#374151;">{{ $item->description }}</td>
        <td style="padding:9px 10px; text-align:center; font-size:11px; color:#374151;">{{ $item->quantity }}</td>
        <td style="padding:9px 10px; text-align:right; font-size:11px; color:#374151;">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
        <td style="padding:9px 10px; text-align:right; font-size:11px; color:#9ca3af;">{{ $item->discount > 0 ? $item->discount.'%' : '-' }}</td>
        <td style="padding:9px 10px; text-align:right; font-size:11px; font-weight:600; color:#111827;">Rp {{ number_format($item->price * $item->quantity * (1 - $item->discount / 100), 0, ',', '.') }}</td>
      </tr>
      @endforeach
      <tr style="background-color:#f0fdf4; border-top:2px solid #065f46;">
        <td colspan="4" style="padding:11px 10px; font-size:10px; font-weight:700; color:#065f46; text-transform:uppercase; letter-spacing:0.5px;">Total Tagihan</td>
        <td style="padding:11px 10px; text-align:right; font-size:16px; font-weight:700; color:#065f46;">Rp {{ number_format($invoice->total_amount, 0, ',', '.') }}</td>
      </tr>
    </tbody>
  </table>

  {{-- Payment Info (only if BELUM_LUNAS) --}}
  @if($invoice->paymentDetail && $invoice->payment_status === 'BELUM_LUNAS')
  <div style="border:1px solid #d1fae5; border-radius:6px; padding:14px; margin-top:18px; background:#f0fdf4;">
    <div style="font-size:9px; text-transform:uppercase; color:#065f46; font-weight:700; letter-spacing:0.5px; margin-bottom:9px;">Informasi Pembayaran</div>
    <table style="margin:0;">
      <tr>
        <td style="padding:3px 0; color:#6b7280; width:120px; font-size:10px;">Bank</td>
        <td style="padding:3px 0; font-weight:600; font-size:11px;">{{ $invoice->paymentDetail->bank_name }}</td>
      </tr>
      <tr>
        <td style="padding:3px 0; color:#6b7280; font-size:10px;">No. Rekening</td>
        <td style="padding:3px 0; font-weight:600; font-size:11px;">{{ $invoice->paymentDetail->account_number }}</td>
      </tr>
      <tr>
        <td style="padding:3px 0; color:#6b7280; font-size:10px;">Atas Nama</td>
        <td style="padding:3px 0; font-weight:600; font-size:11px;">{{ $invoice->paymentDetail->account_name }}</td>
      </tr>
    </table>
  </div>
  @endif

  {{-- DP Kekurangan / Notes --}}
  @if($isDP && $remaining > 0)
  <div style="border:1px solid #fde68a; border-radius:6px; padding:14px; margin-top:18px; background:#fefce8;">
    <div style="font-size:9px; text-transform:uppercase; color:#854d0e; font-weight:700; letter-spacing:0.5px; margin-bottom:9px;">Perhatian — Invoice Down Payment (DP)</div>
    <table style="margin:0; width:100%; border-collapse:collapse;">
      <tr>
        <td style="padding:3px 0; color:#6b7280; width:60%; font-size:10px;">Harga Program</td>
        <td style="padding:3px 0; font-weight:600; font-size:11px; text-align:right;">Rp {{ number_format($period->price, 0, ',', '.') }}</td>
      </tr>
      <tr>
        <td style="padding:3px 0; color:#6b7280; font-size:10px;">DP Dibayar</td>
        <td style="padding:3px 0; font-weight:600; font-size:11px; text-align:right;">Rp {{ number_format($invoice->total_amount, 0, ',', '.') }}</td>
      </tr>
      <tr style="border-top:1px solid #fde68a;">
        <td style="padding:5px 0 3px; color:#854d0e; font-size:10px; font-weight:700;">Nominal Kekurangan yang Harus Dibayar</td>
        <td style="padding:5px 0 3px; font-weight:700; font-size:13px; color:#854d0e; text-align:right;">Rp {{ number_format($remaining, 0, ',', '.') }}</td>
      </tr>
    </table>
  </div>
  @elseif($invoice->notes)
  <div style="background:#f9fafb; border-left:3px solid #d1fae5; padding:10px 14px; border-radius:4px; font-size:10px; color:#6b7280; margin-top:16px;">
    {{ $invoice->notes }}
  </div>
  @endif

  {{-- Footer --}}
  <div style="margin-top:40px; text-align:center; font-size:9px; color:#9ca3af; border-top:1px solid #f3f4f6; padding-top:16px;">
    Terima kasih atas kepercayaan Anda &nbsp;·&nbsp; Rumah Sehat Holistik Satu Bumi
  </div>

</div>
</body>
</html>
