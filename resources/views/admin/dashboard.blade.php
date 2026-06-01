@extends('layouts.admin')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
{{-- Brand banner --}}
<div class="bg-white rounded-2xl px-6 py-5 mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 border border-gray-200 shadow-sm">
    <img src="{{ asset('assets/logo/logo-rec-colored.png') }}" alt="Rumah Sehat Holistik Satu Bumi" class="h-14 w-auto max-w-[200px]">
    <div class="sm:text-right">
        <p class="text-xs text-gray-400 uppercase tracking-wider font-semibold">Panel Admin</p>
        <p class="text-sm text-gray-600 mt-0.5">Selamat datang, <span class="font-semibold text-[#2d6a4f]">{{ auth()->user()->name }}</span></p>
    </div>
</div>

{{-- Stat cards --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    @foreach([
        ['label'=>'Total Pendaftar','value'=>$stats['total_registrations'],'icon'=>'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4','color'=>'blue'],
        ['label'=>'Menunggu Bayar','value'=>$stats['pending_payment'],'icon'=>'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z','color'=>'amber'],
        ['label'=>'Dikonfirmasi','value'=>$stats['confirmed'],'icon'=>'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z','color'=>'emerald'],
        ['label'=>'Lunas','value'=>$stats['fully_paid'],'icon'=>'M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z','color'=>'green'],
    ] as $card)
    <div class="bg-white rounded-xl p-5 border border-gray-200 shadow-sm">
        <div class="flex items-center justify-between mb-3">
            <div class="text-xs font-medium text-gray-500 uppercase tracking-wide">{{ $card['label'] }}</div>
            <div class="w-8 h-8 rounded-lg flex items-center justify-center
                {{ $card['color'] === 'blue' ? 'bg-blue-50' : ($card['color'] === 'amber' ? 'bg-amber-50' : ($card['color'] === 'emerald' ? 'bg-emerald-50' : 'bg-green-50')) }}">
                <svg class="w-4 h-4 {{ $card['color'] === 'blue' ? 'text-blue-600' : ($card['color'] === 'amber' ? 'text-amber-600' : ($card['color'] === 'emerald' ? 'text-[#2d6a4f]' : 'text-green-600')) }}"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="{{ $card['icon'] }}"/>
                </svg>
            </div>
        </div>
        <div class="text-3xl font-bold text-gray-900">{{ $card['value'] }}</div>
    </div>
    @endforeach
</div>

{{-- Charts --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    {{-- Trend line chart (2/3 width) --}}
    <div class="lg:col-span-2 bg-white rounded-xl border border-gray-200 shadow-sm p-5">
        <h2 class="font-semibold text-gray-800 mb-4">Tren 6 Bulan Terakhir</h2>
        <div class="relative" style="height:220px">
            <canvas id="trendChart"></canvas>
        </div>
    </div>

    {{-- Status donut chart (1/3 width) --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
        <h2 class="font-semibold text-gray-800 mb-4">Status Pendaftaran</h2>
        <div class="relative flex items-center justify-center" style="height:160px">
            <canvas id="statusChart"></canvas>
        </div>
        <div class="mt-4 space-y-1.5">
            @foreach([
                ['Pending','bg-amber-400',$stats['pending_payment']],
                ['DP / Konfirmasi','bg-emerald-500',$stats['confirmed']],
                ['Lunas','bg-green-600',$stats['fully_paid']],
                ['Dibatalkan','bg-gray-300',$stats['cancelled']],
            ] as [$lbl,$clr,$val])
            <div class="flex items-center justify-between text-xs">
                <div class="flex items-center gap-1.5">
                    <span class="w-2.5 h-2.5 rounded-full {{ $clr }} flex-shrink-0"></span>
                    <span class="text-gray-600">{{ $lbl }}</span>
                </div>
                <span class="font-semibold text-gray-700">{{ $val }}</span>
            </div>
            @endforeach
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    {{-- Recent Registrations --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
        <div class="px-5 py-4 border-b border-gray-200 flex items-center justify-between">
            <h2 class="font-semibold text-gray-800">Pendaftaran Terbaru</h2>
            <a href="{{ route('admin.registrasi.index') }}" class="text-xs text-[#2d6a4f] font-medium hover:underline">Lihat semua →</a>
        </div>
        <div class="divide-y divide-gray-100">
            @forelse($recentRegistrations as $reg)
            <a href="{{ route('admin.registrasi.show', $reg->id) }}"
               class="px-5 py-3.5 flex items-center justify-between hover:bg-gray-50 transition-colors">
                <div>
                    <div class="text-sm font-medium text-gray-800">{{ $reg->full_name }}</div>
                    <div class="text-xs text-gray-400 mt-0.5">{{ $reg->registration_code }} · {{ $reg->programPeriod?->name }}</div>
                </div>
                <div class="text-right flex-shrink-0 ml-2">
                    <span class="text-xs px-2 py-1 rounded-full font-medium inline-block max-w-[100px] truncate
                        {{ $reg->status === 'FULLY_PAID' ? 'bg-green-100 text-green-700' :
                           ($reg->status === 'CONFIRMED' ? 'bg-emerald-100 text-emerald-700' :
                           ($reg->status === 'CANCELLED' ? 'bg-gray-100 text-gray-500' :
                           'bg-amber-100 text-amber-700')) }}"
                        title="{{ str_replace('_', ' ', $reg->status) }}">
                        {{ str_replace('_', ' ', $reg->status) }}
                    </span>
                    <div class="text-xs text-gray-400 mt-1">{{ $reg->submitted_at->diffForHumans() }}</div>
                </div>
            </a>
            @empty
            <div class="px-5 py-8 text-center">
                <div class="text-3xl mb-2">📋</div>
                <p class="text-sm text-gray-400">Belum ada pendaftaran.</p>
            </div>
            @endforelse
        </div>
    </div>

    {{-- Active Periods --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
        <div class="px-5 py-4 border-b border-gray-200 flex items-center justify-between">
            <h2 class="font-semibold text-gray-800">Periode Aktif</h2>
            <a href="{{ route('admin.program.index') }}" class="text-xs text-[#2d6a4f] font-medium hover:underline">Kelola →</a>
        </div>
        <div class="divide-y divide-gray-100">
            @forelse($activePeriods as $period)
            <div class="px-5 py-4">
                <div class="flex justify-between items-start mb-2">
                    <div class="text-sm font-medium text-gray-800">{{ $period->name }}</div>
                    <div class="text-xs font-medium text-gray-500">{{ $period->filled }}/{{ $period->quota }}</div>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-1.5 mb-2">
                    <div class="bg-[#2d6a4f] h-1.5 rounded-full transition-all"
                         style="width: {{ $period->quota > 0 ? min(100, round($period->filled / $period->quota * 100)) : 0 }}%"></div>
                </div>
                <div class="text-xs text-gray-400">
                    {{ $period->start_date->format('d M Y') }} – {{ $period->end_date->format('d M Y') }}
                </div>
            </div>
            @empty
            <div class="px-5 py-8 text-center">
                <div class="text-3xl mb-2">📅</div>
                <p class="text-sm text-gray-400">Tidak ada periode aktif.</p>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4/dist/chart.umd.min.js"></script>
<script>
(function () {
    const labels      = @json($chartLabels);
    const regData     = @json($chartReg);
    const revData     = @json($chartRevenue);
    @php $statusArr = [$stats['pending_payment'], $stats['confirmed'], $stats['fully_paid'], $stats['cancelled']]; @endphp
    const statusData  = @json($statusArr);

    // ── Trend line chart ────────────────────────────────────────
    new Chart(document.getElementById('trendChart'), {
        type: 'line',
        data: {
            labels,
            datasets: [
                {
                    label: 'Pendaftar',
                    data: regData,
                    borderColor: '#2d6a4f',
                    backgroundColor: 'rgba(45,106,79,0.08)',
                    borderWidth: 2,
                    pointBackgroundColor: '#2d6a4f',
                    pointRadius: 4,
                    tension: 0.35,
                    yAxisID: 'yReg',
                    fill: true,
                },
                {
                    label: 'Pendapatan (Rp)',
                    data: revData,
                    borderColor: '#f59e0b',
                    backgroundColor: 'rgba(245,158,11,0.06)',
                    borderWidth: 2,
                    pointBackgroundColor: '#f59e0b',
                    pointRadius: 4,
                    tension: 0.35,
                    yAxisID: 'yRev',
                    fill: true,
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: { position: 'top', labels: { font: { size: 11 }, boxWidth: 12 } },
                tooltip: {
                    callbacks: {
                        label: function (ctx) {
                            if (ctx.datasetIndex === 1) {
                                return ' Rp ' + ctx.parsed.y.toLocaleString('id-ID');
                            }
                            return ' ' + ctx.parsed.y + ' orang';
                        },
                    },
                },
            },
            scales: {
                yReg: {
                    type: 'linear', position: 'left',
                    ticks: { stepSize: 1, font: { size: 10 } },
                    grid: { color: 'rgba(0,0,0,0.05)' },
                    beginAtZero: true,
                    title: { display: true, text: 'Pendaftar', font: { size: 10 } },
                },
                yRev: {
                    type: 'linear', position: 'right',
                    ticks: {
                        font: { size: 10 },
                        callback: v => 'Rp ' + (v >= 1000000 ? (v / 1000000).toFixed(1) + 'jt' : v.toLocaleString('id-ID')),
                    },
                    grid: { drawOnChartArea: false },
                    beginAtZero: true,
                    title: { display: true, text: 'Pendapatan', font: { size: 10 } },
                },
                x: { ticks: { font: { size: 10 } }, grid: { display: false } },
            },
        },
    });

    // ── Status donut chart ──────────────────────────────────────
    new Chart(document.getElementById('statusChart'), {
        type: 'doughnut',
        data: {
            labels: ['Pending', 'DP / Konfirmasi', 'Lunas', 'Dibatalkan'],
            datasets: [{
                data: statusData,
                backgroundColor: ['#fbbf24', '#10b981', '#16a34a', '#d1d5db'],
                borderWidth: 2,
                borderColor: '#fff',
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '68%',
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: ctx => ' ' + ctx.label + ': ' + ctx.parsed,
                    },
                },
            },
        },
    });
})();
</script>
@endpush
