@extends('layouts.admin')
@section('title', 'Registrasi')
@section('page-title', 'Registrasi')

@section('content')
{{-- Filter tabs --}}
<div class="flex flex-wrap gap-2 mb-4">
    @foreach([''=>'Semua ('.$counts['all'].')','PENDING_PAYMENT'=>'Menunggu ('.$counts['PENDING_PAYMENT'].')','CONFIRMED'=>'Dikonfirmasi ('.$counts['CONFIRMED'].')','FULLY_PAID'=>'Lunas ('.$counts['FULLY_PAID'].')','CANCELLED'=>'Batal ('.$counts['CANCELLED'].')'] as $key => $label)
    <a href="{{ request()->fullUrlWithQuery(['status' => $key, 'page' => null]) }}"
       class="px-3 py-1.5 text-xs font-medium rounded-full border {{ request('status', '') === $key ? 'bg-emerald-600 text-white border-emerald-600' : 'bg-white text-gray-600 border-gray-200 hover:border-emerald-400' }}">
        {{ $label }}
    </a>
    @endforeach
</div>

{{-- Search & filter --}}
<form method="GET" class="flex gap-2 mb-4">
    <input type="hidden" name="status" value="{{ request('status') }}">
    <input type="text" name="search" value="{{ request('search') }}"
           placeholder="Cari nama, kode, WhatsApp..."
           class="flex-1 px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none">
    <select name="period_id" class="px-3 py-2 border rounded-lg text-sm focus:outline-none">
        <option value="">Semua Periode</option>
        @foreach($periods as $period)
        <option value="{{ $period->id }}" {{ request('period_id') == $period->id ? 'selected' : '' }}>{{ $period->name }}</option>
        @endforeach
    </select>
    <button type="submit" class="px-4 py-2 bg-emerald-600 text-white text-sm rounded-lg">Cari</button>
</form>

<div class="bg-white rounded-xl border shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="px-4 py-3 text-left font-semibold text-gray-600">Kode</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-600">Nama</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-600">Periode</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-600">Status</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-600">Tanggal</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-600">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($registrations as $reg)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 font-mono text-xs text-gray-500">{{ $reg->registration_code }}</td>
                    <td class="px-4 py-3">
                        <div class="font-medium">{{ $reg->full_name }}</div>
                        <div class="text-xs text-gray-400">{{ $reg->whatsapp }}</div>
                    </td>
                    <td class="px-4 py-3 text-xs text-gray-600">{{ $reg->programPeriod?->name }}</td>
                    <td class="px-4 py-3">
                        <span class="text-xs px-2 py-0.5 rounded-full font-medium
                            {{ $reg->status === 'FULLY_PAID' ? 'bg-green-100 text-green-700' :
                               ($reg->status === 'CONFIRMED' ? 'bg-emerald-100 text-emerald-700' :
                               ($reg->status === 'CANCELLED' ? 'bg-gray-100 text-gray-500' :
                               'bg-amber-100 text-amber-700')) }}">
                            {{ str_replace('_', ' ', $reg->status) }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-xs text-gray-500">{{ $reg->submitted_at->format('d M Y') }}</td>
                    <td class="px-4 py-3">
                        <a href="{{ route('admin.registrasi.show', $reg) }}" class="text-emerald-600 hover:underline text-xs font-medium">Detail</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-4 py-8 text-center text-gray-400">Tidak ada data.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($registrations->hasPages())
    <div class="px-4 py-3 border-t">{{ $registrations->links() }}</div>
    @endif
</div>
@endsection
