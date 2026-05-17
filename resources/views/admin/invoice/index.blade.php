@extends('layouts.admin')
@section('title', 'Invoice')
@section('page-title', 'Invoice')

@section('header-actions')
<a href="{{ route('admin.invoice.create') }}" class="px-4 py-2 bg-[#2d6a4f] text-white text-sm font-semibold rounded-lg hover:bg-[#1a5a3f] transition-colors">+ Invoice Baru</a>
@endsection

@section('content')
{{-- Stat cards --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-xl border shadow-sm p-4 flex items-center gap-4">
        <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        </div>
        <div>
            <p class="text-xs text-gray-500">Total Invoice</p>
            <p class="text-2xl font-bold text-gray-800">{{ $totalCount }}</p>
        </div>
    </div>
    <div class="bg-white rounded-xl border shadow-sm p-4 flex items-center gap-4">
        <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div>
            <p class="text-xs text-gray-500">Total Pendapatan</p>
            <p class="text-xl font-bold text-gray-800">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
        </div>
    </div>
    <div class="bg-white rounded-xl border shadow-sm p-4 flex items-center gap-4">
        <div class="w-10 h-10 rounded-full bg-amber-100 flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div>
            <p class="text-xs text-gray-500">Belum Lunas</p>
            <p class="text-2xl font-bold text-gray-800">{{ $unpaidCount }}</p>
        </div>
    </div>
</div>

<div class="flex flex-wrap gap-2 mb-4">
    @foreach([''=>'Semua ('.$counts['all'].')','BELUM_LUNAS'=>'Belum Lunas ('.$counts['BELUM_LUNAS'].')','LUNAS'=>'Lunas ('.$counts['LUNAS'].')','DIBATALKAN'=>'Dibatalkan ('.$counts['DIBATALKAN'].')'] as $key => $label)
    <a href="{{ request()->fullUrlWithQuery(['status' => $key, 'page' => null]) }}"
       class="px-3 py-1.5 text-xs font-medium rounded-full border transition-colors {{ request('status', '') === $key ? 'bg-[#2d6a4f] text-white border-[#2d6a4f]' : 'bg-white text-gray-600 border-gray-200 hover:border-[#2d6a4f] hover:text-[#2d6a4f]' }}">
        {{ $label }}
    </a>
    @endforeach
</div>

<form method="GET" class="flex gap-2 mb-4">
    <input type="hidden" name="status" value="{{ request('status') }}">
    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nomor, nama klien..."
           class="flex-1 px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-[#2d6a4f]/30 focus:outline-none">
    <button type="submit" class="px-4 py-2 bg-[#2d6a4f] text-white text-sm rounded-lg hover:bg-[#1a5a3f] transition-colors">Cari</button>
</form>

<div class="bg-white rounded-xl border shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="px-4 py-3 text-left font-semibold text-gray-600">No. Invoice</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-600">Klien</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-600">Tanggal</th>
                    <th class="px-4 py-3 text-right font-semibold text-gray-600">Total</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-600">Status</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-600">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($invoices as $inv)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 font-mono text-xs text-gray-500">{{ $inv->invoice_number }}</td>
                    <td class="px-4 py-3 font-medium">{{ $inv->client_name }}</td>
                    <td class="px-4 py-3 text-xs text-gray-500">{{ $inv->invoice_date->format('d M Y') }}</td>
                    <td class="px-4 py-3 text-right font-semibold text-gray-800">Rp {{ number_format($inv->total_amount, 0, ',', '.') }}</td>
                    <td class="px-4 py-3">
                        <x-invoice-status-badge :status="$inv->payment_status" />
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-1">
                            <a href="{{ route('admin.invoice.show', $inv) }}" title="Detail"
                               class="p-1.5 rounded-lg text-gray-400 hover:text-[#2d6a4f] hover:bg-[#2d6a4f]/10 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </a>
                            <a href="{{ route('admin.invoice.pdf', $inv) }}" title="Unduh PDF" target="_blank"
                               class="p-1.5 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-4 py-8 text-center text-gray-400">Tidak ada invoice.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($invoices->hasPages())
    <div class="px-4 py-3 border-t">{{ $invoices->links() }}</div>
    @endif
</div>
@endsection
