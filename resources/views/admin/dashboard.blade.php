@extends('layouts.admin')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
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
                <div class="text-right">
                    <span class="text-xs px-2 py-1 rounded-full font-medium
                        {{ $reg->status === 'FULLY_PAID' ? 'bg-green-100 text-green-700' :
                           ($reg->status === 'CONFIRMED' ? 'bg-emerald-100 text-emerald-700' :
                           ($reg->status === 'CANCELLED' ? 'bg-gray-100 text-gray-500' :
                           'bg-amber-100 text-amber-700')) }}">
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
