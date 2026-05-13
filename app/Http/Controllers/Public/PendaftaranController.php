<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\ProgramPeriod;
use App\Models\Registration;
use App\Models\SiteSetting;
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
    ) {}

    public function index()
    {
        $periods = ProgramPeriod::where('is_active', true)
            ->orderBy('start_date')
            ->get()
            ->map(function ($p) {
                $p->filled = $p->registrations()->whereNotIn('status', ['CANCELLED'])->count();
                return $p;
            });

        $settings = SiteSetting::getMany(['turnstile_site_key', 'bank_name', 'bank_account_number', 'bank_account_name']);
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
            'clinical_details'  => 'required|string',
            'bmi'               => 'nullable|numeric',
            'emotion_state'     => 'required|string',
            'food_allergies'    => 'required|string',
            'treatment_history' => 'required|string',
            'current_meds'      => 'required|string',
            'confidence_level'  => 'required|integer|min:1|max:10',
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

            // Sync invoice (outside transaction, non-critical)
            try {
                $this->invoiceService->syncInvoice($registration->load('programPeriod', 'invoice'));
            } catch (\Throwable) {}

            // Sync Google Sheets (non-critical)
            try {
                $rowId = $this->sheetsService->appendRegistration($registration);
                if ($rowId) {
                    $registration->update(['sheets_row_id' => $rowId]);
                }
            } catch (\Throwable) {}

            RateLimiter::clear($rateLimitKey);

            return response()->json([
                'success' => true,
                'code'    => $registration->registration_code,
                'message' => 'Pendaftaran berhasil! Kode Anda: ' . $registration->registration_code,
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

    private function generateCode(): string
    {
        do {
            $code = 'RSH' . strtoupper(substr(uniqid(), -7));
        } while (Registration::where('registration_code', $code)->exists());
        return $code;
    }
}
