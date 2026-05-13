@extends('layouts.admin')
@section('title', 'Edit Periode')
@section('page-title', 'Edit Periode')
@section('header-actions')
<a href="{{ route('admin.program.index') }}" class="text-sm text-gray-500 hover:text-gray-700">← Kembali</a>
@endsection
@section('content')
<div class="max-w-lg">
    <div class="bg-white rounded-xl border shadow-sm p-6">
        @if($program->filled > 0)
        <div class="mb-4 p-3 bg-amber-50 border border-amber-200 rounded-lg text-xs text-amber-700">
            Periode ini memiliki {{ $program->filled }} pendaftar aktif.
        </div>
        @endif
        <form method="POST" action="{{ route('admin.program.update', $program) }}" class="space-y-4">
            @csrf @method('PUT')
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Periode</label>
                <input type="text" name="name" value="{{ old('name', $program->name) }}" required class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Tanggal Mulai</label>
                    <input type="date" name="start_date" value="{{ old('start_date', $program->start_date->format('Y-m-d')) }}" required class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Tanggal Selesai</label>
                    <input type="date" name="end_date" value="{{ old('end_date', $program->end_date->format('Y-m-d')) }}" required class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Harga (Rp)</label>
                    <input type="number" name="price" value="{{ old('price', $program->price) }}" required min="0" class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">DP (Rp)</label>
                    <input type="number" name="dp_amount" value="{{ old('dp_amount', $program->dp_amount) }}" required min="0" class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Kuota</label>
                    <input type="number" name="quota" value="{{ old('quota', $program->quota) }}" required min="{{ $program->filled }}" class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                </div>
                <div class="flex items-end pb-2">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ $program->is_active ? 'checked' : '' }} class="rounded border-gray-300 text-emerald-600">
                        <span class="text-sm font-medium text-gray-700">Aktif</span>
                    </label>
                </div>
            </div>
            <button type="submit" class="w-full py-2.5 bg-emerald-600 text-white font-semibold rounded-lg hover:bg-emerald-700 text-sm">Simpan Perubahan</button>
        </form>
    </div>
</div>
@endsection
