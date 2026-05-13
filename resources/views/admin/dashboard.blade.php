@extends('layouts.admin')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
    @foreach([
        ['label'=>'Total Pendaftar','value'=>$stats['total_registrations'],'color'=>'blue'],
        ['label'=>'Menunggu Bayar','value'=>$stats['pending_payment'],'color'=>'amber'],
        ['label'=>'Dikonfirmasi','value'=>$stats['confirmed'],'color'=>'emerald'],
        ['label'=>'Lunas','value'=>$stats['fully_paid'],'color'=>'green'],
    ] as $card)
    <div class="bg-white rounded-xl p-5 border shadow-sm">
        <div class="text-sm text-gray-500 mb-1">{{ $card['label'] }}</div>
        <div class="text-3xl font-bold text-gray-800">{{ $card['value'] }}</div>
    </div>
    @endforeach
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white rounded-xl border shadow-sm">
        <div class="px-5 py-4 border-b font-semibold text-gray-700">Pendaftaran Terbaru</div>
        <div class="divide-y">
            @forelse($recentRegistrations as $reg)
            <div class="px-5 py-3 flex items-center justify-between">
                <div>
                    <div class="text-sm font-medium">{{ $reg->full_name }}</div>
                    <div class="text-xs text-gray-500">{{ $reg->registration_code }} · {{ $reg->programPeriod?->name }}</div>
                </div>
                <div class="text-right">
                    <span class="text-xs px-2 py-0.5 rounded-full font-medium
                        {{ $reg->status === 'FULLY_PAID' ? 'bg-green-100 text-green-700' :
                           ($reg->status === 'CONFIRMED' ? 'bg-emerald-100 text-emerald-700' :
                           ($reg->status === 'CANCELLED' ? 'bg-gray-100 text-gray-500' :
                           'bg-amber-100 text-amber-700')) }}">
                        {{ str_replace('_', ' ', $reg->status) }}
                    </span>
                    <div class="text-xs text-gray-400 mt-1">{{ $reg->submitted_at->diffForHumans() }}</div>
                </div>
            </div>
            @empty
            <div class="px-5 py-4 text-sm text-gray-400">Belum ada pendaftaran.</div>
            @endforelse
        </div>
        <div class="px-5 py-3 border-t">
            <a href="{{ route('admin.registrasi.index') }}" class="text-sm text-emerald-600 font-medium hover:underline">Lihat semua →</a>
        </div>
    </div>

    <div class="bg-white rounded-xl border shadow-sm">
        <div class="px-5 py-4 border-b font-semibold text-gray-700">Periode Aktif</div>
        <div class="divide-y">
            @forelse($activePeriods as $period)
            <div class="px-5 py-3">
                <div class="flex justify-between items-start mb-2">
                    <div class="text-sm font-medium">{{ $period->name }}</div>
                    <div class="text-xs text-gray-500">{{ $period->filled }}/{{ $period->quota }}</div>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-1.5">
                    <div class="bg-emerald-500 h-1.5 rounded-full"
                         style="width: {{ $period->quota > 0 ? min(100, round($period->filled / $period->quota * 100)) : 0 }}%"></div>
                </div>
                <div class="text-xs text-gray-400 mt-1">
                    {{ $period->start_date->format('d M Y') }} – {{ $period->end_date->format('d M Y') }}
                </div>
            </div>
            @empty
            <div class="px-5 py-4 text-sm text-gray-400">Tidak ada periode aktif.</div>
            @endforelse
        </div>
    </div>
</div>
@endsection
