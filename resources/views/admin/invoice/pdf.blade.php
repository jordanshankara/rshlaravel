<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<style>
  * { margin: 0; padding: 0; box-sizing: border-box; }
  body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #1a1a1a; background: #fff; }
  .page { padding: 40px; }
  .header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 30px; }
  .company-name { font-size: 20px; font-weight: 700; color: #065f46; }
  .invoice-meta { text-align: right; }
  .invoice-meta h1 { font-size: 22px; font-weight: 700; color: #374151; letter-spacing: 2px; }
  .invoice-meta p { color: #6b7280; margin-top: 2px; }
  .divider { border: none; border-top: 2px solid #d1fae5; margin: 20px 0; }
  .info-grid { display: flex; gap: 40px; margin-bottom: 24px; }
  .info-block { flex: 1; }
  .info-label { font-size: 9px; text-transform: uppercase; color: #9ca3af; letter-spacing: 1px; margin-bottom: 4px; }
  .info-value { font-size: 12px; font-weight: 600; color: #111827; }
  .info-sub { font-size: 10px; color: #6b7280; margin-top: 1px; }
  table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
  th { background: #ecfdf5; color: #065f46; font-size: 10px; text-transform: uppercase; padding: 8px 10px; text-align: left; letter-spacing: 0.5px; }
  td { padding: 8px 10px; border-bottom: 1px solid #f3f4f6; font-size: 11px; }
  .text-right { text-align: right; }
  .total-row td { font-weight: 700; font-size: 13px; border-top: 2px solid #d1fae5; border-bottom: none; }
  .status-badge { display: inline-block; padding: 4px 12px; border-radius: 20px; font-size: 10px; font-weight: 700; letter-spacing: 0.5px; }
  .status-lunas { background: #d1fae5; color: #065f46; }
  .status-belum { background: #fef3c7; color: #92400e; }
  .status-batal { background: #f3f4f6; color: #6b7280; }
  .notes { background: #f9fafb; border-left: 3px solid #d1fae5; padding: 10px 14px; border-radius: 4px; font-size: 10px; color: #6b7280; }
  .payment-box { border: 1px solid #d1fae5; border-radius: 6px; padding: 14px; margin-top: 16px; background: #f0fdf4; }
  .payment-box .title { font-size: 10px; text-transform: uppercase; color: #065f46; font-weight: 700; letter-spacing: 0.5px; margin-bottom: 8px; }
  .footer { margin-top: 40px; text-align: center; font-size: 9px; color: #9ca3af; }
</style>
</head>
<body>
<div class="page">
  <div class="header">
    <div>
      <div class="company-name">RSH Satu Bumi</div>
      <div style="font-size:10px;color:#6b7280;margin-top:4px;">{{ $settings['site_address'] ?? '' }}</div>
      <div style="font-size:10px;color:#6b7280;">{{ $settings['site_phone'] ?? '' }}</div>
    </div>
    <div class="invoice-meta">
      <h1>INVOICE</h1>
      <p>{{ $invoice->invoice_number }}</p>
      <p>{{ $invoice->invoice_date->format('d M Y') }}</p>
      <div style="margin-top:8px;">
        <span class="status-badge {{ $invoice->payment_status === 'LUNAS' ? 'status-lunas' : ($invoice->payment_status === 'DIBATALKAN' ? 'status-batal' : 'status-belum') }}">
          {{ $invoice->payment_status }}
        </span>
      </div>
    </div>
  </div>

  <hr class="divider">

  <div class="info-grid">
    <div class="info-block">
      <div class="info-label">Tagihan Kepada</div>
      <div class="info-value">{{ $invoice->client_name }}</div>
    </div>
    <div class="info-block">
      <div class="info-label">Tanggal Invoice</div>
      <div class="info-value">{{ $invoice->invoice_date->format('d M Y') }}</div>
    </div>
  </div>

  <table>
    <thead>
      <tr>
        <th>Deskripsi</th>
        <th class="text-right" style="width:60px;">Qty</th>
        <th class="text-right" style="width:120px;">Harga</th>
        <th class="text-right" style="width:60px;">Disc%</th>
        <th class="text-right" style="width:130px;">Subtotal</th>
      </tr>
    </thead>
    <tbody>
      @foreach($invoice->items as $item)
      <tr>
        <td>{{ $item->description }}</td>
        <td class="text-right">{{ $item->quantity }}</td>
        <td class="text-right">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
        <td class="text-right">{{ $item->discount > 0 ? $item->discount.'%' : '-' }}</td>
        <td class="text-right">Rp {{ number_format($item->price * $item->quantity * (1 - $item->discount / 100), 0, ',', '.') }}</td>
      </tr>
      @endforeach
    </tbody>
    <tfoot>
      <tr class="total-row">
        <td colspan="4">TOTAL</td>
        <td class="text-right">Rp {{ number_format($invoice->total_amount, 0, ',', '.') }}</td>
      </tr>
    </tfoot>
  </table>

  @if($invoice->notes)
  <div class="notes">{{ $invoice->notes }}</div>
  @endif

  @if($invoice->paymentDetail && $invoice->payment_status === 'BELUM_LUNAS')
  <div class="payment-box">
    <div class="title">Informasi Pembayaran</div>
    <table style="margin:0;">
      <tr><td style="padding:2px 0;color:#6b7280;width:120px;">Bank</td><td style="padding:2px 0;font-weight:600;">{{ $invoice->paymentDetail->bank_name }}</td></tr>
      <tr><td style="padding:2px 0;color:#6b7280;">No. Rekening</td><td style="padding:2px 0;font-weight:600;">{{ $invoice->paymentDetail->account_number }}</td></tr>
      <tr><td style="padding:2px 0;color:#6b7280;">Atas Nama</td><td style="padding:2px 0;font-weight:600;">{{ $invoice->paymentDetail->account_name }}</td></tr>
    </table>
  </div>
  @endif

  <div class="footer">Terima kasih atas kepercayaan Anda. · RSH Satu Bumi</div>
</div>
</body>
</html>
