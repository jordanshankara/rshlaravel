<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProgramPeriod;
use App\Models\Registration;
use App\Models\ReregistrationToken;
use App\Services\MonitoringService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PesertaController extends Controller
{
    public function __construct(private MonitoringService $monitoringService) {}

    public function index(Request $request)
    {
        $query = Registration::with('programPeriod')
            ->whereNotIn('status', ['PENDING_PAYMENT', 'CANCELLED']);

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('registration_code', 'like', "%{$search}%");
            });
        }

        if ($periodId = $request->get('period_id')) {
            $query->where('program_period_id', $periodId);
        }

        if ($request->filled('hadir')) {
            $query->where('is_present', (bool) $request->get('hadir'));
        }

        $peserta = $query->orderByDesc('confirmed_at')->paginate(25)->withQueryString();
        $periods = ProgramPeriod::orderByDesc('start_date')->get();

        return view('admin.peserta.index', compact('peserta', 'periods'));
    }

    public function show(int $id)
    {
        $registration = Registration::with([
            'programPeriod',
            'monitoringTokens.responses',
            'reregistrationToken',
        ])->findOrFail($id);

        $tokensByDay = $registration->monitoringTokens->keyBy('day_number');
        $days        = config('monitoring.days', 7);

        return view('admin.peserta.show', compact('registration', 'tokensByDay', 'days'));
    }

    public function markPresent(int $id)
    {
        $registration = Registration::findOrFail($id);

        if (!$registration->is_present) {
            $registration->update([
                'is_present' => true,
                'present_at' => now(),
            ]);

            // Generate the 7 monitoring tokens
            $this->monitoringService->generateTokens($registration);
        }

        return back()->with('success', '✅ Kehadiran ditandai dan 7 link monitoring berhasil dibuat.');
    }

    public function resetDay(int $id, int $day)
    {
        $registration = Registration::findOrFail($id);

        $token = $registration->monitoringTokens()
            ->where('day_number', $day)
            ->first();

        if ($token) {
            $token->responses()->delete();
            $token->update(['completed_at' => null]);
        }

        return back()->with('success', "Link monitoring hari ke-{$day} berhasil direset.");
    }

    public function createReregLink(int $id)
    {
        $registration = Registration::findOrFail($id);

        // Revoke any existing unused token first
        $registration->reregistrationToken?->delete();

        $token = ReregistrationToken::create([
            'registration_id' => $registration->id,
            'token'           => Str::random(48),
            'expires_at'      => now()->addDays(30),
        ]);

        $link = route('pendaftaran.reregister', $token->token);

        return back()->with('reregLink', $link);
    }
}
