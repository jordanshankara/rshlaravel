<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProgramPeriod;
use App\Models\Registration;
use App\Services\MonitoringService;
use Illuminate\Http\Request;
use Illuminate\Http\StreamedResponse;

class MonitoringDashboardController extends Controller
{
    public function __construct(private MonitoringService $service) {}

    public function index(Request $request)
    {
        $periods = ProgramPeriod::orderByDesc('start_date')->get();
        $selectedId = $request->get('period_id', $periods->first()?->id);
        $period = $selectedId ? ProgramPeriod::find($selectedId) : null;

        $summary = [];
        $aggregates = [];
        $stats = [
            'total'       => 0,
            'green'       => 0,
            'yellow'      => 0,
            'red'         => 0,
            'need_attention' => 0,
        ];

        if ($period) {
            $summary    = $this->service->getPeriodSummary($period);
            $aggregates = $this->service->getPeriodAggregates($period);
            $stats['total'] = count($summary);

            foreach ($summary as $row) {
                // Use latest completed day to classify the participant
                $latestDay = null;
                foreach (array_reverse($row['days']) as $dayData) {
                    if ($dayData['completed']) {
                        $latestDay = $dayData;
                        break;
                    }
                }
                if ($latestDay) {
                    $worstLevel = $latestDay['emosi_level']['color'] === 'red' || $latestDay['fisik_level']['color'] === 'red'
                        ? 'red'
                        : ($latestDay['emosi_level']['color'] === 'yellow' || $latestDay['fisik_level']['color'] === 'yellow'
                            ? 'yellow' : 'green');

                    $stats[$worstLevel]++;
                    if ($worstLevel === 'red') $stats['need_attention']++;
                }
            }
        }

        $days = config('monitoring.days', 7);

        return view('admin.monitoring.index', compact(
            'periods', 'period', 'summary', 'aggregates', 'stats', 'days'
        ));
    }

    public function export(int $periodId): StreamedResponse
    {
        $period  = ProgramPeriod::findOrFail($periodId);
        $summary = $this->service->getPeriodSummary($period);
        $days    = config('monitoring.days', 7);

        $filename = 'monitoring_' . str_replace(' ', '_', $period->name) . '_' . now()->format('Ymd') . '.csv';

        return response()->streamDownload(function () use ($summary, $days) {
            $handle = fopen('php://output', 'w');
            fputs($handle, "\xEF\xBB\xBF");

            // Header row
            $header = ['Nama', 'Kode', 'WA', 'Hadir'];
            for ($d = 1; $d <= $days; $d++) {
                $header[] = "H{$d} Emosi";
                $header[] = "H{$d} Fisik";
            }
            fputcsv($handle, $header);

            foreach ($summary as $row) {
                $reg  = $row['registration'];
                $line = [$reg->full_name, $reg->registration_code, $reg->whatsapp, $reg->is_present ? 'Ya' : 'Tidak'];
                for ($d = 1; $d <= $days; $d++) {
                    $day = $row['days'][$d] ?? null;
                    if ($day && $day['completed']) {
                        $line[] = $day['emosi_score'] . '/16';
                        $line[] = $day['fisik_score'] . '/16';
                    } else {
                        $line[] = '-';
                        $line[] = '-';
                    }
                }
                fputcsv($handle, $line);
            }
            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv']);
    }
}
