<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\PaymentDetail;
use App\Models\Registration;
use Illuminate\Support\Facades\DB;

class RegistrationInvoiceService
{
    private const VALID_TRANSITIONS = [
        'PENDING_PAYMENT' => ['CONFIRMED', 'CANCELLED'],
        'CONFIRMED'       => ['FULLY_PAID', 'CANCELLED'],
        'FULLY_PAID'      => [],
        'CANCELLED'       => [],
    ];

    public function isValidTransition(string $from, string $to): bool
    {
        return in_array($to, self::VALID_TRANSITIONS[$from] ?? []);
    }

    public function syncInvoice(Registration $registration): void
    {
        $registration->load('programPeriod', 'invoice');
        $period = $registration->programPeriod;

        $isFullyPaid = $registration->status === 'FULLY_PAID';
        $isPaid = in_array($registration->status, ['CONFIRMED', 'FULLY_PAID']);
        $isCancelled = $registration->status === 'CANCELLED';
        $isPending = $registration->status === 'PENDING_PAYMENT';

        $amount = $isFullyPaid ? $period->price : $period->dp_amount;
        $paymentStatus = $isCancelled ? 'DIBATALKAN' : ($isPaid ? 'LUNAS' : 'BELUM_LUNAS');
        $invoiceNumber = 'INV-' . $registration->registration_code;

        $periodLabel = $period->name . ' · ' . $this->fmtDate($period->start_date) . ' – ' . $this->fmtDate($period->end_date);
        $description = $isFullyPaid
            ? "Program 7 Hari Menuju Sehat Raga & Jiwa\n{$periodLabel}"
            : "DP (50%) Program 7 Hari Menuju Sehat Raga & Jiwa\n{$periodLabel}";

        $notes = $isCancelled
            ? 'Pendaftaran dibatalkan.'
            : (!$isPaid ? 'Sisa pelunasan ' . $this->formatRupiah($period->price - $period->dp_amount) . ' dibayarkan saat check-in.' : null);

        $defaultPayment = $isPending ? PaymentDetail::where('is_default', true)->first() : null;
        $paymentDetailId = $defaultPayment?->id;

        DB::transaction(function () use ($registration, $invoiceNumber, $amount, $paymentStatus, $notes, $paymentDetailId, $description) {
            if ($registration->invoice) {
                $invoice = $registration->invoice;
                InvoiceItem::where('invoice_id', $invoice->id)->delete();
                $invoice->update([
                    'client_name'       => $registration->full_name,
                    'payment_status'    => $paymentStatus,
                    'total_amount'      => $amount,
                    'notes'             => $notes,
                    'payment_detail_id' => $paymentDetailId,
                ]);
                InvoiceItem::create([
                    'invoice_id'  => $invoice->id,
                    'description' => $description,
                    'quantity'    => 1,
                    'price'       => $amount,
                    'discount'    => 0,
                ]);
            } else {
                $invoice = Invoice::create([
                    'invoice_number'       => $invoiceNumber,
                    'client_name'          => $registration->full_name,
                    'invoice_date'         => $registration->submitted_at->toDateString(),
                    'payment_status'       => $paymentStatus,
                    'total_amount'         => $amount,
                    'notes'                => $notes,
                    'author_id'            => null,
                    'is_system_generated'  => true,
                    'registration_id'      => $registration->id,
                    'payment_detail_id'    => $paymentDetailId,
                ]);
                InvoiceItem::create([
                    'invoice_id'  => $invoice->id,
                    'description' => $description,
                    'quantity'    => 1,
                    'price'       => $amount,
                    'discount'    => 0,
                ]);
            }
        });
    }

    private function fmtDate($date): string
    {
        if (!$date) return '';
        $d = $date instanceof \Carbon\Carbon ? $date : \Carbon\Carbon::parse($date);
        $months = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                   'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        return $d->day . ' ' . $months[$d->month] . ' ' . $d->year;
    }

    public static function formatRupiah(int|float $amount): string
    {
        return 'Rp ' . number_format($amount, 0, ',', '.');
    }
}
