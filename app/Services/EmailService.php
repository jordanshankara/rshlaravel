<?php

namespace App\Services;

use App\Models\Registration;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class EmailService
{
    /**
     * Send new-registration notification to CS staff.
     * Mirrors the Next.js /api/program/7-hari/registrasi email behaviour.
     */
    public function sendNewRegistrationNotification(Registration $registration): void
    {
        $registration->loadMissing('programPeriod', 'invoice');

        $recipients = $this->resolveRecipients();
        if (empty($recipients)) return;

        $subject = '[Pendaftaran] ' . $registration->registration_code
            . ' — ' . $registration->full_name
            . ' — ' . ($registration->programPeriod?->name ?? '-');

        $html = $this->buildHtml($registration);

        try {
            Mail::html($html, function ($message) use ($recipients, $subject) {
                $message->to($recipients)->subject($subject);
            });

            $registration->update(['email_sent' => true]);
        } catch (\Throwable $e) {
            Log::warning('Registration email failed: ' . $e->getMessage());
        }
    }

    // ── Helpers ──────────────────────────────────────────────────────────────

    private function resolveRecipients(): array
    {
        // Try site settings first, then env fallback
        $raw = SiteSetting::get('notification_emails', env('EMAIL_CS', ''));
        if (!$raw) return [];

        return array_values(array_filter(array_map('trim', explode(',', $raw))));
    }

    private function buildHtml(Registration $registration): string
    {
        $period  = $registration->programPeriod;
        $invoice = $registration->invoice;

        $code    = e($registration->registration_code);
        $name    = e($registration->full_name);
        $birth   = $registration->birth_date?->format('d F Y') ?? '-';
        $occ     = e($registration->occupation);
        $wa      = e($registration->whatsapp);
        $addr    = e($registration->address);
        $hw      = e($registration->height_weight);
        $bmi     = $registration->bmi ? number_format($registration->bmi, 1) : '-';
        $ts      = $registration->submitted_at?->setTimezone('Asia/Jakarta')->format('d F Y, H:i') . ' WIB';

        $periodName  = e($period?->name ?? '-');
        $periodDates = $period
            ? $period->start_date->format('d M Y') . ' – ' . $period->end_date->format('d M Y')
            : '-';
        $price    = $period ? 'Rp ' . number_format($period->price, 0, ',', '.') : '-';
        $dp       = $period ? 'Rp ' . number_format($period->dp_amount, 0, ',', '.') : '-';

        $complaints = e($registration->health_complaints);
        $clinical   = e($registration->clinical_details ?: '-');
        $emotion    = e($registration->emotion_state);
        $allergies  = e($registration->food_allergies);
        $history    = e($registration->treatment_history);
        $meds       = e($registration->current_meds);
        $confidence = $registration->confidence_level . ' / 10';

        $invoiceNum = $invoice ? e($invoice->invoice_number) : '-';
        $invoiceAmt = $invoice ? 'Rp ' . number_format($invoice->total_amount, 0, ',', '.') : '-';

        return <<<HTML
<!DOCTYPE html>
<html lang="id">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"></head>
<body style="margin:0;padding:0;background:#f4f7f4;font-family:Arial,sans-serif;color:#1a1a1a">
<table width="100%" cellpadding="0" cellspacing="0" style="background:#f4f7f4;padding:32px 16px">
<tr><td align="center">
<table width="600" cellpadding="0" cellspacing="0" style="background:#ffffff;border-radius:12px;overflow:hidden;max-width:600px;width:100%">

  {{-- Header --}}
  <tr>
    <td style="background:linear-gradient(135deg,#166534,#15803d);padding:32px 40px;text-align:center">
      <p style="color:#86efac;font-size:12px;font-weight:bold;letter-spacing:2px;margin:0 0 8px">PENDAFTARAN BARU</p>
      <h1 style="color:#ffffff;font-size:22px;margin:0">RSH Satu Bumi</h1>
      <p style="color:#bbf7d0;font-size:13px;margin:8px 0 0">Program 7 Hari Menuju Sehat Raga &amp; Jiwa</p>
    </td>
  </tr>

  {{-- Registration Code --}}
  <tr>
    <td style="padding:32px 40px 0;text-align:center">
      <p style="color:#6b7280;font-size:12px;font-weight:bold;letter-spacing:1px;text-transform:uppercase;margin:0 0 10px">Kode Pendaftaran</p>
      <div style="display:inline-block;background:#f0fdf4;border:2px solid #86efac;border-radius:10px;padding:14px 40px">
        <span style="font-size:28px;font-weight:bold;color:#166534;letter-spacing:4px">{$code}</span>
      </div>
      <p style="color:#9ca3af;font-size:12px;margin:10px 0 0">{$ts}</p>
    </td>
  </tr>

  {{-- Personal Data --}}
  <tr>
    <td style="padding:28px 40px 0">
      <p style="font-size:11px;font-weight:bold;color:#6b7280;text-transform:uppercase;letter-spacing:1px;border-bottom:2px solid #e5e7eb;padding-bottom:8px;margin:0 0 16px">Data Diri</p>
      {$this->row('Nama Lengkap', $name)}
      {$this->row('Tanggal Lahir', $birth)}
      {$this->row('Pekerjaan', $occ)}
      {$this->row('WhatsApp', $wa)}
      {$this->row('Alamat', $addr)}
      {$this->row('Tinggi / Berat', $hw)}
      {$this->row('BMI', $bmi)}
    </td>
  </tr>

  {{-- Program --}}
  <tr>
    <td style="padding:24px 40px 0">
      <p style="font-size:11px;font-weight:bold;color:#6b7280;text-transform:uppercase;letter-spacing:1px;border-bottom:2px solid #e5e7eb;padding-bottom:8px;margin:0 0 16px">Program</p>
      {$this->row('Periode', $periodName)}
      {$this->row('Tanggal', $periodDates)}
      {$this->row('Harga Program', $price)}
      {$this->row('DP (50%)', $dp)}
      {$this->row('Nomor Invoice', $invoiceNum)}
      {$this->row('Total Tagihan', $invoiceAmt)}
    </td>
  </tr>

  {{-- Health Data --}}
  <tr>
    <td style="padding:24px 40px 0">
      <p style="font-size:11px;font-weight:bold;color:#6b7280;text-transform:uppercase;letter-spacing:1px;border-bottom:2px solid #e5e7eb;padding-bottom:8px;margin:0 0 16px">Data Kesehatan</p>
      {$this->row('Keluhan Utama', $complaints)}
      {$this->row('Data Klinis', $clinical)}
      {$this->row('Kondisi Emosi', $emotion)}
      {$this->row('Alergi / Pantangan', $allergies)}
      {$this->row('Riwayat Pengobatan', $history)}
      {$this->row('Obat / Suplemen', $meds)}
      {$this->row('Keyakinan Pulih', $confidence)}
    </td>
  </tr>

  {{-- Footer --}}
  <tr>
    <td style="padding:32px 40px;text-align:center;border-top:1px solid #e5e7eb;margin-top:28px">
      <p style="color:#9ca3af;font-size:12px;margin:0">Email ini dibuat otomatis oleh sistem RSH Satu Bumi.</p>
      <p style="color:#9ca3af;font-size:12px;margin:4px 0 0">Jangan balas email ini.</p>
    </td>
  </tr>

</table>
</td></tr>
</table>
</body>
</html>
HTML;
    }

    private function row(string $label, string $value): string
    {
        return '<table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:10px">'
            . '<tr>'
            . '<td style="width:40%;vertical-align:top;font-size:13px;color:#6b7280;padding-right:8px">' . e($label) . '</td>'
            . '<td style="font-size:13px;color:#111827;font-weight:500">' . $value . '</td>'
            . '</tr></table>';
    }
}
