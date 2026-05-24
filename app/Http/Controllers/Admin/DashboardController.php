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
            ->withCount(['registrations as filled' => fn($q) => $q->whereNotIn('status', ['CANCELLED'])])
            ->orderBy('start_date')
            ->get();

        // ── Chart data: last 6 months ────────────────────────────────────────
        $months = collect(range(5, 0))->map(fn($i) => now()->startOfMonth()->subMonths($i));

        $regByMonth = Registration::selectRaw('YEAR(submitted_at) y, MONTH(submitted_at) m, COUNT(*) n')
            ->where('submitted_at', '>=', now()->subMonths(5)->startOfMonth())
            ->groupBy('y', 'm')
            ->get()
            ->keyBy(fn($r) => "{$r->y}-{$r->m}");

        $revenueByMonth = Invoice::selectRaw('YEAR(invoice_date) y, MONTH(invoice_date) m, SUM(total_amount) total')
            ->where('payment_status', 'LUNAS')
            ->where('invoice_date', '>=', now()->subMonths(5)->startOfMonth())
            ->groupBy('y', 'm')
            ->get()
            ->keyBy(fn($r) => "{$r->y}-{$r->m}");

        $indonesianMonths = ['', 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

        $chartLabels  = $months->map(fn($d) => $indonesianMonths[$d->month] . ' ' . $d->year)->values();
        $chartReg     = $months->map(fn($d) => (int) ($regByMonth["{$d->year}-{$d->month}"]->n ?? 0))->values();
        $chartRevenue = $months->map(fn($d) => (float) ($revenueByMonth["{$d->year}-{$d->month}"]->total ?? 0))->values();

        return view('admin.dashboard', compact(
            'stats', 'recentRegistrations', 'activePeriods',
            'chartLabels', 'chartReg', 'chartRevenue'
        ));
    }
}
