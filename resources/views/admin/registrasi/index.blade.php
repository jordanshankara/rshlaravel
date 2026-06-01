@extends('layouts.admin')
@section('title', 'Registrasi')
@section('page-title', 'Registrasi')

@section('content')
@php
$statusLabels = [
    'PENDING_PAYMENT' => 'Pending Payment',
    'CONFIRMED'       => 'DP',
    'FULLY_PAID'      => 'Full Paid',
    'CANCELLED'       => 'Dibatalkan',
];
@endphp
{{-- Filter tabs --}}
<div class="flex flex-wrap gap-2 mb-4">
    @foreach(['' => 'Semua ('.$counts['all'].')', 'PENDING_PAYMENT' => 'Pending Payment ('.$counts['PENDING_PAYMENT'].')', 'CONFIRMED' => 'DP ('.$counts['CONFIRMED'].')', 'FULLY_PAID' => 'Full Paid ('.$counts['FULLY_PAID'].')', 'CANCELLED' => 'Dibatalkan ('.$counts['CANCELLED'].')'] as $key => $label)
    <a href="{{ request()->fullUrlWithQuery(['status' => $key, 'page' => null]) }}"
       class="px-3 py-1.5 text-[11px] sm:text-xs font-medium rounded-full border transition-colors shrink-0 {{ request('status', '') === $key ? 'bg-[#2d6a4f] text-white border-[#2d6a4f]' : 'bg-white text-gray-600 border-gray-200 hover:border-[#2d6a4f] hover:text-[#2d6a4f]' }}">
        {{ $label }}
    </a>
    @endforeach
</div>

{{-- Search & filter --}}
<form method="GET" class="flex flex-col sm:flex-row gap-2 mb-4">
    <input type="hidden" name="status" value="{{ request('status') }}">
    <input type="text" name="search" value="{{ request('search') }}"
           placeholder="Cari nama, kode, WhatsApp..."
           class="flex-1 px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-[#2d6a4f]/30 focus:border-[#2d6a4f] focus:outline-none">
    <div class="flex gap-2">
        <select name="period_id" class="flex-1 sm:flex-none px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-[#2d6a4f]">
            <option value="">Semua Periode</option>
            @foreach($periods as $period)
            <option value="{{ $period->id }}" {{ request('period_id') == $period->id ? 'selected' : '' }}>{{ $period->name }}</option>
            @endforeach
        </select>
        <button type="submit" class="px-4 py-2 bg-[#2d6a4f] text-white text-sm rounded-lg hover:bg-[#1a5a3f] transition-colors whitespace-nowrap flex-shrink-0">Cari</button>
        <a href="{{ route('admin.registrasi.export', request()->query()) }}"
           title="Export data yang tampil ke CSV"
           class="px-3 py-2 border border-gray-300 rounded-lg text-sm text-gray-600 hover:bg-gray-50 hover:border-gray-400 transition-colors whitespace-nowrap flex-shrink-0 inline-flex items-center gap-1.5">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
            </svg>
            CSV
        </a>
    </div>
</form>

<div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Kode</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Nama</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Periode</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Tanggal</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($registrations as $reg)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-4 py-3 font-mono text-xs text-gray-400">{{ $reg->registration_code }}</td>
                    <td class="px-4 py-3">
                        <div class="font-medium text-gray-800">{{ $reg->full_name }}</div>
                        <div class="text-xs text-gray-400 mt-0.5">{{ $reg->whatsapp }}</div>
                    </td>
                    <td class="px-4 py-3 text-xs text-gray-600">{{ $reg->programPeriod?->name }}</td>
                    <td class="px-4 py-3">
                        <span class="text-xs px-2.5 py-1 rounded-full font-medium
                            {{ $reg->status === 'FULLY_PAID' ? 'bg-green-100 text-green-700' :
                               ($reg->status === 'CONFIRMED' ? 'bg-emerald-100 text-emerald-700' :
                               ($reg->status === 'CANCELLED' ? 'bg-gray-100 text-gray-500' :
                               'bg-amber-100 text-amber-700')) }}">
                            {{ $statusLabels[$reg->status] ?? $reg->status }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-xs text-gray-500">{{ $reg->submitted_at->format('d M Y') }}</td>
                    <td class="px-4 py-3">
                        <a href="{{ route('admin.registrasi.show', $reg) }}" title="Detail"
                           class="p-1.5 rounded-lg text-gray-400 hover:text-[#2d6a4f] hover:bg-[#2d6a4f]/10 transition-colors inline-flex">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-4 py-12 text-center">
                        <div class="text-3xl mb-2">📋</div>
                        <p class="text-sm text-gray-400">Tidak ada data pendaftaran.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($registrations->hasPages())
    <div class="px-4 py-3 border-t border-gray-100">{{ $registrations->links() }}</div>
    @endif
</div>
@endsection
