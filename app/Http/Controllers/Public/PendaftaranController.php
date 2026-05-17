<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\ProgramPeriod;
use App\Models\Registration;
use App\Models\SiteSetting;
use App\Services\EmailService;
use App\Services\RegistrationInvoiceService;
use App\Services\SheetsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\RateLimiter;

class PendaftaranController extends Controller
{
    public function __construct(
        private RegistrationInvoiceService $invoiceService,
        private SheetsService $sheetsService,
        private EmailService $emailService,
    ) {}

    public function index()
    {
        $periods = ProgramPeriod::where('is_active', true)
            ->withCount(['registrations as filled' => fn($q) => $q->whereNotIn('status', ['CANCELLED'])])
            ->orderBy('start_date')
            ->get();

        $settings = SiteSetting::getMany(['bank_name', 'bank_account_number', 'bank_account_name']);
        $settings['turnstile_site_key'] = config('services.turnstile.site_key', '');
        return view('public.pendaftaran.form', compact('periods', 'settings'));
    }

    public function store(Request $request)
    {
        $ip = $request->ip();
        $rateLimitKey = 'registration:' . $ip;

        if (RateLimiter::tooManyAttempts($rateLimitKey, 3)) {
            return response()->json(['error' => 'Terlalu banyak permintaan. Coba lagi nanti.'], 429);
        }

        // Verify Turnstile
        $turnstileSecret = config('services.turnstile.secret_key', '');
        if ($turnstileSecret) {
            $token = $request->input('cf-turnstile-response');
            if (!$token) {
                return response()->json(['error' => 'Verifikasi CAPTCHA diperlukan.'], 422);
            }
            $verify = Http::asForm()->post('https://challenges.cloudflare.com/turnstile/v0/siteverify', [
                'secret'   => $turnstileSecret,
                'response' => $token,
                'remoteip' => $ip,
            ]);
            if (!($verify->json('success') ?? false)) {
                return response()->json(['error' => 'Verifikasi CAPTCHA gagal.'], 422);
            }
        }

        $request->validate([
            'full_name'        => 'required|string|max:255',
            'birth_date'       => 'required|date',
            'occupation'       => 'required|string|max:255',
            'whatsapp'         => 'required|string|max:30',
            'address'          => 'required|string',
            'height_weight'    => 'required|string|max:100',
            'program_period_id' => 'required|exists:program_periods,id',
            'health_complaints' => 'required|string',
            'clinical_details'  => 'nullable|string',
            'bmi'               => 'nullable|numeric',
            'emotion_state'     => 'required|string',
            'food_allergies'    => 'required|string',
            'treatment_history' => 'required|string',
            'current_meds'      => 'required|string',
            'confidence_level'  => 'required|integer|min:1|max:5',
        ]);

        RateLimiter::hit($rateLimitKey, 60 * 60);

        try {
            $registration = DB::transaction(function () use ($request) {
                $period = ProgramPeriod::lockForUpdate()->findOrFail($request->program_period_id);

                if (!$period->is_active) {
                    throw new \Exception('PERIOD_INACTIVE');
                }

                $filled = $period->registrations()->whereNotIn('status', ['CANCELLED'])->count();
                if ($filled >= $period->quota) {
                    throw new \Exception('QUOTA_FULL');
                }

                // Check duplicate by whatsapp + period
                $exists = Registration::where('whatsapp', $request->whatsapp)
                    ->where('program_period_id', $request->program_period_id)
                    ->whereNotIn('status', ['CANCELLED'])
                    ->exists();
                if ($exists) {
                    throw new \Exception('DUPLICATE');
                }

                $code = $this->generateCode();

                $reg = Registration::create([
                    'registration_code' => $code,
                    'full_name'         => $request->full_name,
                    'birth_date'        => $request->birth_date,
                    'occupation'        => $request->occupation,
                    'whatsapp'          => $request->whatsapp,
                    'address'           => $request->address,
                    'height_weight'     => $request->height_weight,
                    'program_period_id' => $request->program_period_id,
                    'health_complaints' => $request->health_complaints,
                    'clinical_details'  => $request->clinical_details,
                    'bmi'               => $request->bmi,
                    'emotion_state'     => $request->emotion_state,
                    'food_allergies'    => $request->food_allergies,
                    'treatment_history' => $request->treatment_history,
                    'current_meds'      => $request->current_meds,
                    'confidence_level'  => $request->confidence_level,
                    'status'            => 'PENDING_PAYMENT',
                    'submitted_at'      => now(),
                ]);

                return $reg;
            });

            // Sync invoice (non-critical)
            try {
                $this->invoiceService->syncInvoice($registration->load('programPeriod', 'invoice'));
            } catch (\Throwable) {}

            // Send CS notification email (non-critical)
            try {
                $this->emailService->sendNewRegistrationNotification($registration);
            } catch (\Throwable) {}

            // Sync Google Sheets (non-critical)
            try {
                $rowId = $this->sheetsService->appendRegistration($registration);
                if ($rowId) {
                    $registration->update(['sheets_row_id' => $rowId]);
                }
            } catch (\Throwable) {}

            RateLimiter::clear($rateLimitKey);

            // Reload with all relations needed for the response
            $registration->load('programPeriod', 'invoice.items', 'invoice.paymentDetail');
            $invoice = $registration->invoice;
            $period  = $registration->programPeriod;

            return response()->json([
                'success'   => true,
                'code'      => $registration->registration_code,
                'full_name' => $registration->full_name,
                'period' => $period ? [
                    'name' => $period->name,
                ] : null,
                'invoice' => $invoice ? [
                    'invoice_number'  => $invoice->invoice_number,
                    'invoice_date'    => $invoice->invoice_date?->format('d F Y'),
                    'payment_status'  => $invoice->payment_status,
                    'total_amount'    => (float) $invoice->total_amount,
                    'notes'           => $invoice->notes,
                    'items'           => $invoice->items->map(fn ($item) => [
                        'id'          => $item->id,
                        'description' => $item->description,
                        'quantity'    => (int) $item->quantity,
                        'price'       => (float) $item->price,
                        'discount'    => (float) $item->discount,
                        'subtotal'    => (float) ($item->price * $item->quantity * (1 - $item->discount / 100)),
                    ])->values(),
                ] : null,
                'payment_detail' => $invoice?->paymentDetail ? [
                    'bank_name'      => $invoice->paymentDetail->bank_name,
                    'account_number' => $invoice->paymentDetail->account_number,
                    'account_name'   => $invoice->paymentDetail->account_name,
                ] : null,
            ]);
        } catch (\Exception $e) {
            return match ($e->getMessage()) {
                'QUOTA_FULL'       => response()->json(['error' => 'Kuota periode ini sudah penuh.'], 409),
                'DUPLICATE'        => response()->json(['error' => 'Nomor WhatsApp Anda sudah terdaftar di periode ini.'], 409),
                'PERIOD_INACTIVE'  => response()->json(['error' => 'Periode program tidak aktif.'], 400),
                default            => response()->json(['error' => 'Terjadi kesalahan. Silakan coba lagi.'], 500),
            };
        }
    }

    public function confirm(Request $request)
    {
        return view('public.pendaftaran.confirm');
    }

    public function checkCode(Request $request)
    {
        $ip = $request->ip();
        $key = 'confirm:' . $ip;

        if (RateLimiter::tooManyAttempts($key, 10)) {
            return response()->json(['error' => 'Terlalu banyak percobaan. Coba lagi nanti.'], 429);
        }

        $request->validate(['code' => 'required|string|max:20']);

        $registration = Registration::where('registration_code', strtoupper(trim($request->code)))
            ->with('programPeriod', 'invoice.paymentDetail')
            ->first();

        if (!$registration) {
            RateLimiter::hit($key, 15 * 60);
            return response()->json(['error' => 'Kode pendaftaran tidak ditemukan.'], 404);
        }

        RateLimiter::clear($key);

        $invoice = $registration->invoice;
        $period = $registration->programPeriod;

        return response()->json([
            'registration' => [
                'code'       => $registration->registration_code,
                'full_name'  => $registration->full_name,
                'status'     => $registration->status,
                'period'     => $period?->name,
                'start_date' => $period?->start_date?->format('d M Y'),
                'end_date'   => $period?->end_date?->format('d M Y'),
            ],
            'invoice' => $invoice ? [
                'invoice_number'  => $invoice->invoice_number,
                'payment_status'  => $invoice->payment_status,
                'total_amount'    => $invoice->total_amount,
                'bank_name'       => $invoice->paymentDetail?->bank_name,
                'account_number'  => $invoice->paymentDetail?->account_number,
                'account_name'    => $invoice->paymentDetail?->account_name,
            ] : null,
        ]);
    }

    public function konfirmasi(Request $request)
    {
        $ip = $request->ip();
        $key = 'konfirmasi:' . $ip;

        if (RateLimiter::tooManyAttempts($key, 10)) {
            return response()->json(['error' => 'Terlalu banyak percobaan. Coba lagi nanti.'], 429);
        }

        $request->validate(['code' => 'required|string|max:30']);

        $registration = Registration::where('registration_code', strtoupper(trim($request->code)))
            ->first();

        if (!$registration) {
            RateLimiter::hit($key, 15 * 60);
            return response()->json(['confirmed' => false, 'error' => 'Kode tidak ditemukan.'], 404);
        }

        if (in_array($registration->status, ['CONFIRMED', 'FULLY_PAID'])) {
            RateLimiter::clear($key);
            return response()->json(['confirmed' => true]);
        }

        RateLimiter::hit($key, 60);
        return response()->json([
            'confirmed' => false,
            'error' => 'Pembayaran belum dikonfirmasi oleh admin. Status saat ini: ' . $registration->status,
        ]);
    }

    public function downloadInvoice(string $code)
    {
        $dlKey = 'invoice-dl:' . request()->ip();
        if (RateLimiter::tooManyAttempts($dlKey, 5)) {
            abort(429, 'Terlalu banyak permintaan. Coba lagi nanti.');
        }
        RateLimiter::hit($dlKey, 60);

        $registration = Registration::where('registration_code', strtoupper(trim($code)))
            ->with('invoice.items', 'invoice.paymentDetail')
            ->firstOrFail();

        $invoice = $registration->invoice;
        if (!$invoice) {
            abort(404, 'Invoice tidak ditemukan.');
        }

        $invoice->load('items', 'paymentDetail', 'registration.programPeriod');
        $settings = \App\Models\SiteSetting::getMany(['site_name', 'site_address', 'site_phone', 'site_email']);

        $logoPath = public_path('assets/logo/logo-rec-white.png');
        $logoData = file_exists($logoPath) ? 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath)) : null;

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.invoice.pdf', compact('invoice', 'settings', 'logoData'));
        $pdf->setPaper('a4', 'portrait');

        $statusLabel = match($invoice->payment_status) {
            'LUNAS'      => 'LUNAS',
            'DIBATALKAN' => 'DIBATALKAN',
            default      => 'BELUM-LUNAS',
        };
        return $pdf->download("Invoice-{$invoice->invoice_number}-{$statusLabel}.pdf");
    }

    private function generateCode(): string
    {
        do {
            $code = 'RSH' . strtoupper(\Illuminate\Support\Str::random(9));
        } while (Registration::where('registration_code', $code)->exists());
        return $code;
    }
}
