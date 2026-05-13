@extends('layouts.admin')
@section('title', 'Invoice')
@section('page-title', 'Invoice')

@section('header-actions')
<a href="{{ route('admin.invoice.create') }}" class="px-4 py-2 bg-[#2d6a4f] text-white text-sm font-semibold rounded-lg hover:bg-[#1a5a3f] transition-colors">+ Invoice Baru</a>
@endsection

@section('content')
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
                        <span class="text-xs px-2 py-0.5 rounded-full font-medium
                            {{ $inv->payment_status === 'LUNAS' ? 'bg-green-100 text-green-700' :
                               ($inv->payment_status === 'DIBATALKAN' ? 'bg-gray-100 text-gray-500' :
                               'bg-amber-100 text-amber-700') }}">
                            {{ $inv->payment_status }}
                        </span>
                    </td>
                    <td class="px-4 py-3 flex items-center gap-2">
                        <a href="{{ route('admin.invoice.show', $inv) }}" class="text-[#2d6a4f] hover:underline text-xs font-semibold">Detail →</a>
                        <a href="{{ route('admin.invoice.pdf', $inv) }}" class="text-gray-500 hover:underline text-xs">PDF</a>
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
