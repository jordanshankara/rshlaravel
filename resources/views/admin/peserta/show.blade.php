@extends('layouts.admin')
@section('title', $registration->full_name . ' — Peserta')
@section('page-title', 'Detail Peserta')
@section('header-actions')
<a href="{{ route('admin.peserta.index') }}" class="text-sm text-gray-500 hover:text-gray-700">← Kembali</a>
@endsection
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4/dist/chart.umd.min.js"></script>
@endpush
@section('content')

@if (session('success'))
<div class="mb-4 p-3 bg-green-50 border border-green-200 rounded-lg text-sm text-green-700">
    {{ session('success') }}
</div>
@endif

@if (session('reregLink'))
<div class="mb-4 p-4 bg-blue-50 border border-blue-200 rounded-lg" x-data="{ copied: false }">
    <p class="text-sm font-medium text-blue-800 mb-2">🔗 Link Re-Registrasi Berhasil Dibuat</p>
    <div class="flex items-center gap-2">
        <input type="text" value="{{ session('reregLink') }}" readonly
               class="flex-1 text-xs bg-white border rounded px-2 py-1.5 font-mono text-gray-700"
               id="reregLinkInput">
        <button @click="navigator.clipboard.writeText('{{ session('reregLink') }}'); copied=true; setTimeout(()=>copied=false,2000)"
                class="px-3 py-1.5 text-xs bg-blue-600 text-white rounded hover:bg-blue-700">
            <span x-show="!copied">Salin</span>
            <span x-show="copied">✓ Disalin!</span>
        </button>
    </div>
    <p class="text-xs text-blue-600 mt-1">Link berlaku 30 hari. Kirim ke peserta via WA.</p>
</div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

    {{-- Left: Profile + Period --}}
    <div class="space-y-4">

        {{-- Profile card --}}
        <div class="bg-white rounded-xl border shadow-sm p-5">
            <h3 class="text-sm font-semibold text-gray-700 mb-3">Profil Peserta</h3>
            <div class="space-y-2 text-sm">
                <div>
                    <p class="text-xs text-gray-400">Nama Lengkap</p>
                    <p class="font-medium text-gray-900">{{ $registration->full_name }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400">Kode Registrasi</p>
                    <p class="font-mono text-gray-700">{{ $registration->registration_code }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400">WhatsApp</p>
                    <p class="text-gray-700">{{ $registration->whatsapp }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400">Tanggal Lahir</p>
                    <p class="text-gray-700">{{ $registration->birth_date?->format('d M Y') }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400">Pekerjaan</p>
                    <p class="text-gray-700">{{ $registration->occupation }}</p>
                </div>
                @if($registration->bmi)
                <div>
                    <p class="text-xs text-gray-400">BMI</p>
                    <p class="text-gray-700">{{ number_format($registration->bmi, 1) }}
                        @php
                            $bmi = (float)$registration->bmi;
                            [$bmiLabel, $bmiClass] = match(true) {
                                $bmi < 18.5 => ['Underweight', 'bg-blue-100 text-blue-700'],
                                $bmi < 25.0 => ['Normal',      'bg-green-100 text-green-700'],
                                $bmi < 30.0 => ['Overweight',  'bg-orange-100 text-orange-700'],
                                default     => ['Obese',       'bg-red-100 text-red-700'],
                            };
                        @endphp
                        <span class="inline-flex px-1.5 py-0.5 rounded text-xs font-medium {{ $bmiClass }}">{{ $bmiLabel }}</span>
                    </p>
                </div>
                @endif
            </div>
        </div>

        {{-- Period + Attendance card --}}
        <div class="bg-white rounded-xl border shadow-sm p-5">
            <h3 class="text-sm font-semibold text-gray-700 mb-3">Program & Kehadiran</h3>
            @if($registration->programPeriod)
            <div class="space-y-1.5 text-sm mb-4">
                <p class="font-medium text-gray-900">{{ $registration->programPeriod->name }}</p>
                <p class="text-xs text-gray-500">
                    {{ $registration->programPeriod->start_date->format('d M') }} –
                    {{ $registration->programPeriod->end_date->format('d M Y') }}
                </p>
            </div>
            @endif

            @if ($registration->is_present)
                <div class="flex items-center gap-2 text-sm text-green-700 bg-green-50 rounded-lg px-3 py-2 mb-3">
                    ✅ Hadir sejak {{ $registration->present_at?->format('d M Y, H:i') }}
                </div>
            @else
                <form method="POST" action="{{ route('admin.peserta.hadir', $registration->id) }}" class="mb-3"
                      @submit.prevent="adminConfirm('Tandai Hadir', 'Tandai peserta sebagai hadir dan generate 7 link Energy Level?', {okLabel:'Ya, Tandai'}).then(ok => ok && $el.submit())">
                    @csrf
                    <button type="submit"
                            class="w-full py-2.5 border-2 border-dashed border-gray-300 rounded-xl text-sm text-gray-500 hover:border-green-400 hover:text-green-600 hover:bg-green-50 transition-colors">
                        ☑ Tandai Hadir
                    </button>
                </form>
            @endif

            {{-- Re-registration link --}}
            <form method="POST" action="{{ route('admin.peserta.reregister-link', $registration->id) }}"
                  @submit.prevent="adminConfirm('Buat Link Daftar Ulang', 'Buat link re-registrasi untuk {{ addslashes($registration->full_name) }}? Link lama akan diganti.', {okLabel:'Buat Link'}).then(ok => ok && $el.submit())">
                @csrf
                <button type="submit"
                        class="w-full py-2 text-xs border border-gray-200 rounded-lg text-gray-500 hover:bg-gray-50 transition-colors">
                    🔗 Buat Link Daftar Ulang
                </button>
            </form>
        </div>

    </div>

    {{-- Right: Monitoring links + Chart --}}
    <div class="lg:col-span-2 space-y-4">

        {{-- 7 monitoring links --}}
        <div class="bg-white rounded-xl border shadow-sm p-5">
            <h3 class="text-sm font-semibold text-gray-700 mb-4">
                Link Energy Level Harian
                @if($registration->is_present)
                <span class="ml-2 text-xs font-normal text-gray-400">{{ $registration->monitoringProgress() }} hari diisi</span>
                @endif
            </h3>

            @if (!$registration->is_present)
            <p class="text-sm text-gray-400 py-4 text-center">
                Tandai peserta sebagai hadir terlebih dahulu untuk membuat link Energy Level.
            </p>
            @else
            <div class="space-y-2">
                @for ($day = 1; $day <= $days; $day++)
                @php $token = $tokensByDay->get($day); @endphp
                <div class="flex items-center gap-2 sm:gap-3 p-3 rounded-lg {{ $token?->isCompleted() ? 'bg-green-50 border border-green-100' : 'bg-gray-50 border border-gray-100' }}">
                    <div class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0
                                {{ $token?->isCompleted() ? 'bg-green-500 text-white' : 'bg-gray-200 text-gray-500' }}">
                        {{ $day }}
                    </div>
                    <div class="flex-1 min-w-0 overflow-hidden">
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-medium text-gray-700">Hari ke-{{ $day }}</span>
                            @if ($token?->isCompleted())
                            @php
                                $eScore = $token->emosiScore();
                                $fScore = $token->fisikScore();
                                $eLevel = $token->emosiLevel();
                                $fLevel = $token->fisikLevel();
                                $levelColor = fn($l) => match($l['color']) {
                                    'green'  => 'text-green-600',
                                    'yellow' => 'text-yellow-600',
                                    default  => 'text-red-600',
                                };
                            @endphp
                            <span class="text-xs {{ $levelColor($eLevel) }}">E:{{ $eScore }}/16</span>
                            <span class="text-xs {{ $levelColor($fLevel) }}">F:{{ $fScore }}/16</span>
                            @else
                            <span class="text-xs text-gray-400">Belum diisi</span>
                            @endif
                        </div>
                        @if ($token)
                        <p class="text-xs text-gray-400 font-mono truncate mt-0.5 min-w-0">{{ $token->friendlyUrl() }}</p>
                        @endif
                    </div>
                    @if ($token)
                    <div class="flex items-center gap-1 flex-shrink-0 ml-auto" x-data="{ copied{{ $day }}: false }">
                        <button type="button"
                                @click="navigator.clipboard.writeText('{{ $token->friendlyUrl() }}'); copied{{ $day }}=true; setTimeout(()=>copied{{ $day }}=false,2000)"
                                class="p-1.5 text-gray-400 hover:text-[#2d6a4f] rounded transition-colors"
                                title="Salin link">
                            <span x-show="!copied{{ $day }}">📋</span>
                            <span x-show="copied{{ $day }}" class="text-green-600 text-xs">✓</span>
                        </button>
                        @if ($token->isCompleted())
                        <form method="POST" action="{{ route('admin.peserta.reset-day', [$registration->id, $day]) }}"
                              @submit.prevent="adminConfirm('Reset Hari ke-{{ $day }}', 'Jawaban Energy Level hari ke-{{ $day }} akan dihapus. Peserta bisa mengisi ulang.', {danger:true, okLabel:'Reset'}).then(ok => ok && $el.submit())">
                            @csrf @method('DELETE')
                            <button type="submit" class="p-1.5 text-gray-300 hover:text-red-500 rounded transition-colors text-xs" title="Reset">↺</button>
                        </form>
                        @endif
                    </div>
                    @endif
                </div>
                @endfor
            </div>
            @endif
        </div>

        {{-- Trend chart --}}
        @if ($registration->is_present && $tokensByDay->where('completed_at', '!=', null)->count() > 0)
        <div class="bg-white rounded-xl border shadow-sm p-5">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-gray-700">Tren Score</h3>
                <button onclick="downloadChart()" class="text-xs px-3 py-1.5 border border-gray-200 rounded-lg text-gray-500 hover:bg-gray-50 transition-colors">
                    ↓ Download PNG
                </button>
            </div>
            <canvas id="trendChart" height="200"></canvas>
        </div>

        @push('scripts')
        <script>
        (function() {
            @php
                $chartData = $tokensByDay->filter(fn($t) => $t->completed_at)->map(fn($t) => [
                    'day'   => $t->day_number,
                    'emosi' => $t->emosiScore(),
                    'fisik' => $t->fisikScore(),
                ])->values();
            @endphp
            const tokenData = @json($chartData);

            const labels = tokenData.map(d => 'Hari ' + d.day);
            const emosiData = tokenData.map(d => d.emosi);
            const fisikData = tokenData.map(d => d.fisik);

            const ctx = document.getElementById('trendChart').getContext('2d');
            const chart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels,
                    datasets: [
                        {
                            label: 'Emosi & Pikiran',
                            data: emosiData,
                            borderColor: '#7c3aed',
                            backgroundColor: 'rgba(124,58,237,0.08)',
                            tension: 0.3,
                            fill: true,
                            pointRadius: 5,
                        },
                        {
                            label: 'Kondisi Fisik',
                            data: fisikData,
                            borderColor: '#2d6a4f',
                            backgroundColor: 'rgba(45,106,79,0.08)',
                            tension: 0.3,
                            fill: true,
                            pointRadius: 5,
                        },
                    ],
                },
                options: {
                    responsive: true,
                    scales: {
                        y: { min: 0, max: 16, ticks: { stepSize: 4 } }
                    },
                    plugins: {
                        legend: { position: 'bottom' },
                        tooltip: {
                            callbacks: {
                                label: ctx => ctx.dataset.label + ': ' + ctx.parsed.y + '/16',
                            }
                        }
                    },
                }
            });

            window.downloadChart = function () {
                const a = document.createElement('a');
                a.href = chart.toBase64Image();
                a.download = 'monitoring_{{ Str::slug($registration->full_name) }}.png';
                a.click();
            };
        })();
        </script>
        @endpush
        @endif

    </div>
</div>

@endsection
