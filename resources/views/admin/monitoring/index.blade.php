@extends('layouts.admin')
@section('title', 'Dashboard Monitoring')
@section('page-title', 'Dashboard Monitoring')
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4/dist/chart.umd.min.js"></script>
@endpush
@section('content')

{{-- Period selector + export --}}
<div class="flex flex-wrap items-end gap-3 mb-5">
    <form method="GET" class="flex items-end gap-2">
        <div>
            <label class="block text-xs text-gray-500 mb-1">Pilih Periode</label>
            <select name="period_id" onchange="this.form.submit()"
                    class="px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-[#2d6a4f]/30 focus:outline-none">
                @foreach ($periods as $p)
                <option value="{{ $p->id }}" {{ $period?->id == $p->id ? 'selected' : '' }}>
                    {{ $p->name }}
                </option>
                @endforeach
            </select>
        </div>
    </form>
    @if ($period)
    <a href="{{ route('admin.monitoring.export', $period->id) }}"
       class="inline-flex items-center gap-1.5 px-3 py-2 text-sm border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
        ↓ Export CSV
    </a>
    @endif
</div>

@if (!$period)
<div class="bg-white rounded-xl border shadow-sm p-10 text-center text-gray-400 text-sm">
    Pilih periode program untuk melihat data monitoring.
</div>
@else

{{-- Summary cards --}}
<div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-5">
    <div class="bg-white rounded-xl border shadow-sm p-4">
        <p class="text-xs text-gray-400 mb-1">Total Peserta Hadir</p>
        <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</p>
    </div>
    <div class="bg-green-50 rounded-xl border border-green-100 shadow-sm p-4">
        <p class="text-xs text-green-600 mb-1">🟢 Stabil</p>
        <p class="text-2xl font-bold text-green-700">{{ $stats['green'] }}</p>
    </div>
    <div class="bg-yellow-50 rounded-xl border border-yellow-100 shadow-sm p-4">
        <p class="text-xs text-yellow-600 mb-1">🟡 Perlu Perhatian</p>
        <p class="text-2xl font-bold text-yellow-700">{{ $stats['yellow'] }}</p>
    </div>
    <div class="bg-red-50 rounded-xl border border-red-100 shadow-sm p-4">
        <p class="text-xs text-red-600 mb-1">🔴 Perlu Pendampingan</p>
        <p class="text-2xl font-bold text-red-700">{{ $stats['red'] }}</p>
    </div>
</div>

@if ($stats['need_attention'] > 0)
<div class="mb-5 p-3 bg-red-50 border border-red-200 rounded-xl text-sm text-red-700 flex items-center gap-2">
    ⚠️ <strong>{{ $stats['need_attention'] }} peserta</strong> membutuhkan pendampingan khusus — cek baris merah di tabel di bawah.
</div>
@endif

{{-- Matrix table --}}
<div class="bg-white rounded-xl border shadow-sm overflow-hidden mb-5">
    <div class="px-5 py-3 border-b bg-gray-50">
        <h3 class="text-sm font-semibold text-gray-700">Matrix Peserta × Hari</h3>
        <p class="text-xs text-gray-400 mt-0.5">Hover cell untuk detail score Emosi & Fisik</p>
    </div>
    @if (empty($summary))
    <div class="p-8 text-center text-gray-400 text-sm">Belum ada peserta yang hadir di periode ini.</div>
    @else
    <div class="overflow-x-auto">
        <table class="text-xs w-full">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="text-left px-4 py-2.5 font-medium text-gray-500 sticky left-0 bg-gray-50 min-w-40">Peserta</th>
                    @for ($d = 1; $d <= $days; $d++)
                    <th class="text-center px-3 py-2.5 font-medium text-gray-500 w-14">H{{ $d }}</th>
                    @endfor
                    <th class="text-center px-3 py-2.5 font-medium text-gray-500">Tren</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach ($summary as $row)
                @php
                    $reg = $row['registration'];
                    // Compute trend: compare last 2 completed days
                    $completedDays = collect($row['days'])->filter(fn($d) => $d['completed']);
                    $trend = '—';
                    if ($completedDays->count() >= 2) {
                        $vals = $completedDays->values();
                        $last  = ($vals->last()['emosi_score']  + $vals->last()['fisik_score']);
                        $prev  = ($vals->slice(-2, 1)->first()['emosi_score'] + $vals->slice(-2, 1)->first()['fisik_score']);
                        $trend = $last > $prev ? '↗️' : ($last < $prev ? '↘️' : '→');
                    }
                @endphp
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-4 py-2.5 sticky left-0 bg-white hover:bg-gray-50">
                        <a href="{{ route('admin.peserta.show', $reg->id) }}"
                           class="font-medium text-gray-800 hover:text-[#2d6a4f] truncate block max-w-[140px]">
                            {{ $reg->full_name }}
                        </a>
                    </td>
                    @for ($d = 1; $d <= $days; $d++)
                    @php $day = $row['days'][$d] ?? null; @endphp
                    <td class="px-2 py-2.5 text-center">
                        @if ($day && $day['completed'])
                        @php
                            $worstColor = ($day['emosi_level']['color'] === 'red' || $day['fisik_level']['color'] === 'red') ? 'red'
                                : (($day['emosi_level']['color'] === 'yellow' || $day['fisik_level']['color'] === 'yellow') ? 'yellow' : 'green');
                            $emoji = match($worstColor) { 'green' => '🟢', 'yellow' => '🟡', default => '🔴' };
                            $tipClass = match($worstColor) {
                                'green'  => 'bg-green-600',
                                'yellow' => 'bg-yellow-500',
                                default  => 'bg-red-600',
                            };
                        @endphp
                        <div class="relative group cursor-default inline-block">
                            <span class="text-base">{{ $emoji }}</span>
                            <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-1 hidden group-hover:block z-20
                                        {{ $tipClass }} text-white text-xs rounded px-2 py-1 whitespace-nowrap shadow-lg pointer-events-none">
                                E:{{ $day['emosi_score'] }}/16 · F:{{ $day['fisik_score'] }}/16
                            </div>
                        </div>
                        @else
                        <span class="text-gray-200 text-base">○</span>
                        @endif
                    </td>
                    @endfor
                    <td class="px-3 py-2.5 text-center text-base">{{ $trend }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>

{{-- Aggregate trend chart --}}
@if (!empty(array_filter($aggregates, fn($a) => $a['count'] > 0)))
<div class="bg-white rounded-xl border shadow-sm p-5">
    <div class="flex items-center justify-between mb-4">
        <div>
            <h3 class="text-sm font-semibold text-gray-700">Tren Rata-Rata Kelompok</h3>
            <p class="text-xs text-gray-400 mt-0.5">Score rata-rata semua peserta per hari</p>
        </div>
        <button onclick="downloadGroupChart()" class="text-xs px-3 py-1.5 border border-gray-200 rounded-lg text-gray-500 hover:bg-gray-50 transition-colors">
            ↓ Download PNG
        </button>
    </div>
    <canvas id="groupChart" height="160"></canvas>
</div>

<script>
(function() {
    const agg = @json($aggregates);
    const days = @json($days);
    const labels = [], emosiData = [], fisikData = [];

    for (let d = 1; d <= days; d++) {
        labels.push('Hari ' + d);
        emosiData.push(agg[d]?.emosi_avg ?? null);
        fisikData.push(agg[d]?.fisik_avg ?? null);
    }

    const ctx = document.getElementById('groupChart').getContext('2d');
    const chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels,
            datasets: [
                {
                    label: 'Rata-rata Emosi',
                    data: emosiData,
                    borderColor: '#7c3aed',
                    backgroundColor: 'rgba(124,58,237,0.07)',
                    tension: 0.3,
                    fill: true,
                    spanGaps: true,
                    pointRadius: 5,
                },
                {
                    label: 'Rata-rata Fisik',
                    data: fisikData,
                    borderColor: '#2d6a4f',
                    backgroundColor: 'rgba(45,106,79,0.07)',
                    tension: 0.3,
                    fill: true,
                    spanGaps: true,
                    pointRadius: 5,
                },
            ],
        },
        options: {
            responsive: true,
            scales: { y: { min: 0, max: 16, ticks: { stepSize: 4 } } },
            plugins: {
                legend: { position: 'bottom' },
                tooltip: {
                    callbacks: {
                        label: ctx => ctx.dataset.label + ': ' + (ctx.parsed.y !== null ? ctx.parsed.y.toFixed(1) + '/16' : '—'),
                    }
                }
            },
        }
    });

    window.downloadGroupChart = function () {
        const a = document.createElement('a');
        a.href = chart.toBase64Image();
        a.download = 'monitoring_kelompok_{{ Str::slug($period->name) }}.png';
        a.click();
    };
})();
</script>
@endif

@endif {{-- end if period --}}
@endsection
