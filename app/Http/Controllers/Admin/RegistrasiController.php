<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use App\Models\ProgramPeriod;
use App\Services\RegistrationInvoiceService;
use App\Services\SheetsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RegistrasiController extends Controller
{
    public function __construct(
        private RegistrationInvoiceService $invoiceService,
        private SheetsService $sheetsService,
    ) {}

    public function index(Request $request)
    {
        $query = Registration::with('programPeriod')->orderByDesc('submitted_at');

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('registration_code', 'like', "%{$search}%")
                  ->orWhere('whatsapp', 'like', "%{$search}%");
            });
        }
        if ($periodId = $request->get('period_id')) {
            $query->where('program_period_id', $periodId);
        }

        $registrations = $query->paginate(20)->withQueryString();
        $periods = ProgramPeriod::orderByDesc('start_date')->get();
        $counts = [
            'all'             => Registration::count(),
            'PENDING_PAYMENT' => Registration::where('status', 'PENDING_PAYMENT')->count(),
            'CONFIRMED'       => Registration::where('status', 'CONFIRMED')->count(),
            'FULLY_PAID'      => Registration::where('status', 'FULLY_PAID')->count(),
            'CANCELLED'       => Registration::where('status', 'CANCELLED')->count(),
        ];

        return view('admin.registrasi.index', compact('registrations', 'periods', 'counts'));
    }

    public function show(Registration $registration)
    {
        $registration->load('programPeriod', 'invoice.paymentDetail', 'invoice.items');
        return view('admin.registrasi.show', compact('registration'));
    }

    public function updateStatus(Request $request, Registration $registration)
    {
        $request->validate(['status' => 'required|in:PENDING_PAYMENT,CONFIRMED,FULLY_PAID,CANCELLED']);
        $newStatus = $request->status;

        if (!$this->invoiceService->isValidTransition($registration->status, $newStatus)) {
            return back()->withErrors(['status' => 'Transisi status tidak valid.']);
        }

        DB::transaction(function () use ($registration, $newStatus, $request) {
            $data = ['status' => $newStatus];
            if ($newStatus === 'CONFIRMED') {
                $data['confirmed_at'] = now();
            }
            if ($note = $request->get('payment_note')) {
                $data['payment_note'] = $note;
            }
            $registration->update($data);
            $this->invoiceService->syncInvoice($registration->fresh(['programPeriod', 'invoice']));
        });

        if ($registration->sheets_row_id) {
            $this->sheetsService->updateStatus($registration->sheets_row_id, $newStatus);
        }

        return back()->with('success', 'Status berhasil diperbarui.');
    }

    public function reschedule(Request $request, Registration $registration)
    {
        $request->validate(['program_period_id' => 'required|exists:program_periods,id']);

        if ($registration->status === 'CANCELLED') {
            return back()->withErrors(['program_period_id' => 'Pendaftaran yang dibatalkan tidak dapat dijadwalkan ulang.']);
        }

        $newPeriod = ProgramPeriod::findOrFail($request->program_period_id);

        if ($newPeriod->id === $registration->program_period_id) {
            return back()->withErrors(['program_period_id' => 'Peserta sudah terdaftar di periode ini.']);
        }
        if (!$newPeriod->is_active) {
            return back()->withErrors(['program_period_id' => 'Periode yang dipilih tidak aktif.']);
        }

        $filled = $newPeriod->registrations()->whereNotIn('status', ['CANCELLED'])->count();
        if ($filled >= $newPeriod->quota) {
            return back()->withErrors(['program_period_id' => 'Kuota periode ini sudah penuh.']);
        }

        DB::transaction(function () use ($registration, $newPeriod) {
            $registration->update(['program_period_id' => $newPeriod->id]);
            $this->invoiceService->syncInvoice($registration->fresh(['programPeriod', 'invoice']));
        });

        return back()->with('success', 'Jadwal berhasil diperbarui ke periode ' . $newPeriod->name . '.');
    }

    public function getAvailablePeriods(Registration $registration)
    {
        $periods = ProgramPeriod::where('is_active', true)
            ->where('id', '!=', $registration->program_period_id)
            ->orderBy('start_date')
            ->get()
            ->map(function ($p) {
                $filled = $p->registrations()->whereNotIn('status', ['CANCELLED'])->count();
                return [
                    'id'        => $p->id,
                    'name'      => $p->name,
                    'startDate' => $p->start_date->format('Y-m-d'),
                    'endDate'   => $p->end_date->format('Y-m-d'),
                    'price'     => $p->price,
                    'dpAmount'  => $p->dp_amount,
                    'quota'     => $p->quota,
                    'filled'    => $filled,
                    'available' => $p->quota - $filled,
                ];
            })
            ->filter(fn($p) => $p['available'] > 0)
            ->values();

        return response()->json(['periods' => $periods]);
    }
}
