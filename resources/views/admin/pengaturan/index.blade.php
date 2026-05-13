@extends('layouts.admin')
@section('title', 'Pengaturan')
@section('page-title', 'Pengaturan')
@section('content')
<div class="max-w-2xl space-y-6">
    <div class="bg-white rounded-xl border shadow-sm p-6">
        <h2 class="font-semibold mb-4">Informasi Situs</h2>
        <form method="POST" action="{{ route('admin.pengaturan.update') }}" class="space-y-4">
            @csrf
            @foreach(['site_name'=>'Nama Situs','site_tagline'=>'Tagline','site_address'=>'Alamat','site_phone'=>'Telepon','site_email'=>'Email','whatsapp_number'=>'Nomor WhatsApp'] as $key => $label)
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ $label }}</label>
                <input type="{{ $key === 'site_email' ? 'email' : 'text' }}" name="{{ $key }}"
                       value="{{ old($key, $settings[$key] ?? '') }}"
                       class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-[#2d6a4f]/30 focus:outline-none">
            </div>
            @endforeach
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Deskripsi Program</label>
                <textarea name="program_description" rows="3"
                          class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-[#2d6a4f]/30 focus:outline-none resize-none">{{ old('program_description', $settings['program_description'] ?? '') }}</textarea>
            </div>
            <button type="submit" class="px-5 py-2 bg-[#2d6a4f] text-white text-sm font-semibold rounded-lg hover:bg-[#1a5a3f] transition-colors">Simpan</button>
        </form>
    </div>

    <div class="bg-white rounded-xl border shadow-sm p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="font-semibold">Detail Pembayaran</h2>
        </div>
        <div class="space-y-3 mb-6">
            @forelse($paymentDetails as $pd)
            <div class="flex items-center justify-between p-3 border rounded-lg">
                <div>
                    <div class="text-sm font-medium">{{ $pd->bank_name }} — {{ $pd->account_number }}</div>
                    <div class="text-xs text-gray-500">{{ $pd->account_name }}{{ $pd->is_default ? ' · Default' : '' }}</div>
                </div>
                <div class="flex gap-2 items-center">
                    <form method="POST" action="{{ route('admin.pengaturan.payment-detail.destroy', $pd) }}" onsubmit="return confirm('Hapus?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-xs text-red-500 hover:underline">Hapus</button>
                    </form>
                </div>
            </div>
            @empty
            <p class="text-sm text-gray-400">Belum ada detail pembayaran.</p>
            @endforelse
        </div>

        <form method="POST" action="{{ route('admin.pengaturan.payment-detail.store') }}" class="space-y-3">
            @csrf
            <h3 class="text-sm font-semibold text-gray-700">Tambah Rekening</h3>
            <div class="grid grid-cols-2 gap-3">
                <input type="text" name="bank_name" placeholder="Nama Bank" required class="px-3 py-2 border rounded-lg text-sm focus:outline-none">
                <input type="text" name="account_number" placeholder="No. Rekening" required class="px-3 py-2 border rounded-lg text-sm focus:outline-none">
            </div>
            <input type="text" name="account_name" placeholder="Nama Pemilik Rekening" required class="w-full px-3 py-2 border rounded-lg text-sm focus:outline-none">
            <label class="flex items-center gap-2 text-sm">
                <input type="checkbox" name="is_default" value="1" class="rounded border-gray-300 text-[#2d6a4f]">
                Jadikan Default
            </label>
            <button type="submit" class="px-4 py-2 bg-[#2d6a4f] text-white text-sm font-semibold rounded-lg hover:bg-[#1a5a3f] transition-colors">Tambah</button>
        </form>
    </div>
</div>
@endsection
