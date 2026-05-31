@extends('layouts.admin')
@section('title', 'Program Periode')
@section('page-title', 'Program 7 Hari')
@section('header-actions')
<a href="{{ route('admin.program.create') }}" class="px-4 py-2 bg-[#2d6a4f] text-white text-sm font-semibold rounded-lg hover:bg-[#1a5a3f] transition-colors">+ Periode Baru</a>
@endsection
@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
    @forelse($periods as $period)
    <div class="bg-white rounded-xl border shadow-sm p-5">
        <div class="flex items-start justify-between mb-3">
            <div class="font-semibold text-gray-800">{{ $period->name }}</div>
            <span class="text-xs px-2 py-0.5 rounded-full {{ $period->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                {{ $period->is_active ? 'Aktif' : 'Nonaktif' }}
            </span>
        </div>
        <div class="text-xs text-gray-500 mb-2">{{ $period->start_date->format('d M Y') }} – {{ $period->end_date->format('d M Y') }}</div>
        <div class="mb-3">
            <div class="flex justify-between text-xs mb-1">
                <span class="text-gray-500">Kuota</span>
                <span>{{ $period->filled }}/{{ $period->quota }}</span>
            </div>
            <div class="w-full bg-gray-100 rounded-full h-1.5">
                <div class="bg-[#2d6a4f] h-1.5 rounded-full" style="width: {{ $period->quota > 0 ? min(100, round($period->filled / $period->quota * 100)) : 0 }}%"></div>
            </div>
        </div>
        <div class="text-sm font-semibold text-gray-700 mb-3">
            Rp {{ number_format($period->price, 0, ',', '.') }}
            <span class="text-xs text-gray-400 font-normal ml-1">(DP: Rp {{ number_format($period->dp_amount, 0, ',', '.') }})</span>
        </div>
        <div class="flex gap-1 justify-end">
            <a href="{{ route('admin.program.edit', $period) }}" title="Edit"
               class="p-1.5 rounded-lg text-gray-400 hover:text-[#2d6a4f] hover:bg-[#2d6a4f]/10 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
            </a>
            <form method="POST" action="{{ route('admin.program.destroy', $period) }}" @submit.prevent="adminConfirm('Hapus Periode', 'Periode program ini akan dihapus. Pastikan tidak ada pendaftar aktif.', {danger:true, okLabel:'Ya, Hapus'}).then(ok => ok && $el.submit())">
                @csrf @method('DELETE')
                <button type="submit" title="Hapus"
                        class="p-1.5 rounded-lg text-gray-400 hover:text-red-500 hover:bg-red-50 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </button>
            </form>
        </div>
    </div>
    @empty
    <div class="col-span-3 text-center py-12 text-gray-400">Belum ada periode program.</div>
    @endforelse
</div>
@endsection
