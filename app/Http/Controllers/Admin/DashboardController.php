<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use App\Models\Invoice;
use App\Models\ProgramPeriod;
use App\Models\Article;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_registrations'  => Registration::count(),
            'pending_payment'      => Registration::where('status', 'PENDING_PAYMENT')->count(),
            'confirmed'            => Registration::where('status', 'CONFIRMED')->count(),
            'fully_paid'           => Registration::where('status', 'FULLY_PAID')->count(),
            'cancelled'            => Registration::where('status', 'CANCELLED')->count(),
            'unpaid_invoices'      => Invoice::where('payment_status', 'BELUM_LUNAS')->count(),
            'active_periods'       => ProgramPeriod::where('is_active', true)->count(),
            'published_articles'   => Article::where('status', 'PUBLISHED')->count(),
        ];

        $recentRegistrations = Registration::with('programPeriod')
            ->orderByDesc('submitted_at')
            ->limit(5)
            ->get();

        $activePeriods = ProgramPeriod::where('is_active', true)
            ->orderBy('start_date')
            ->get()
            ->map(function ($p) {
                $p->filled = $p->registrations()->whereNotIn('status', ['CANCELLED'])->count();
                return $p;
            });

        return view('admin.dashboard', compact('stats', 'recentRegistrations', 'activePeriods'));
    }
}
