@extends('layouts.admin')
@section('title', 'Registrasi')
@section('page-title', 'Registrasi')

@section('content')
{{-- Filter tabs --}}
<div class="flex flex-wrap gap-2 mb-4">
    @foreach([''=>'Semua ('.$counts['all'].')','PENDING_PAYMENT'=>'Menunggu ('.$counts['PENDING_PAYMENT'].')','CONFIRMED'=>'Dikonfirmasi ('.$counts['CONFIRMED'].')','FULLY_PAID'=>'Lunas ('.$counts['FULLY_PAID'].')','CANCELLED'=>'Batal ('.$counts['CANCELLED'].')'] as $key => $label)
    <a href="{{ request()->fullUrlWithQuery(['status' => $key, 'page' => null]) }}"
       class="px-3 py-1.5 text-xs font-medium rounded-full border transition-colors {{ request('status', '') === $key ? 'bg-[#2d6a4f] text-white border-[#2d6a4f]' : 'bg-white text-gray-600 border-gray-200 hover:border-[#2d6a4f] hover:text-[#2d6a4f]' }}">
        {{ $label }}
    </a>
    @endforeach
</div>

{{-- Search & filter --}}
<form method="GET" class="flex gap-2 mb-4">
    <input type="hidden" name="status" value="{{ request('status') }}">
    <input type="text" name="search" value="{{ request('search') }}"
           placeholder="Cari nama, kode, WhatsApp..."
           class="flex-1 px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-[#2d6a4f]/30 focus:border-[#2d6a4f] focus:outline-none">
    <select name="period_id" class="px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-[#2d6a4f]">
        <option value="">Semua Periode</option>
        @foreach($periods as $period)
        <option value="{{ $period->id }}" {{ request('period_id') == $period->id ? 'selected' : '' }}>{{ $period->name }}</option>
        @endforeach
    </select>
    <button type="submit" class="px-4 py-2 bg-[#2d6a4f] text-white text-sm rounded-lg hover:bg-[#1a5a3f] transition-colors">Cari</button>
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
                            {{ str_replace('_', ' ', $reg->status) }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-xs text-gray-500">{{ $reg->submitted_at->format('d M Y') }}</td>
                    <td class="px-4 py-3">
                        <a href="{{ route('admin.registrasi.show', $reg) }}"
                           class="text-[#2d6a4f] hover:text-[#1a5a3f] text-xs font-semibold hover:underline">Detail →</a>
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
