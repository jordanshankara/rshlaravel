@extends('layouts.admin')
@section('title', $contact->exists ? 'Edit Kontak' : 'Tambah Kontak')
@section('page-title', $contact->exists ? 'Edit Kontak' : 'Tambah Kontak')
@section('header-actions')
<a href="{{ route('admin.kontak.index') }}" class="text-sm text-gray-500 hover:text-gray-700">← Kembali</a>
@endsection
@section('content')

<div class="max-w-lg">
    <div class="bg-white rounded-xl border shadow-sm p-6">

        @if ($errors->any())
        <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg text-sm text-red-700 space-y-1">
            @foreach ($errors->all() as $e)
            <p>• {{ $e }}</p>
            @endforeach
        </div>
        @endif

        <form method="POST"
              action="{{ $contact->exists ? route('admin.kontak.update', $contact) : route('admin.kontak.store') }}"
              class="space-y-4">
            @csrf
            @if ($contact->exists) @method('PUT') @endif

            <div class="grid grid-cols-2 gap-4">
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $contact->name) }}" required
                           class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-[#2d6a4f]/30 focus:outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">No. Telepon / WA <span class="text-red-500">*</span></label>
                    <input type="text" name="phone" value="{{ old('phone', $contact->phone) }}" required
                           placeholder="6281234567890"
                           class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-[#2d6a4f]/30 focus:outline-none font-mono">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
                    <input type="email" name="email" value="{{ old('email', $contact->email) }}"
                           class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-[#2d6a4f]/30 focus:outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Jenis Kelamin</label>
                    <select name="gender" class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-[#2d6a4f]/30 focus:outline-none">
                        <option value="">—</option>
                        <option value="Perempuan" {{ old('gender', $contact->gender) === 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                        <option value="Laki-laki" {{ old('gender', $contact->gender) === 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Usia</label>
                    <input type="number" name="age" value="{{ old('age', $contact->age) }}" min="1" max="120"
                           class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-[#2d6a4f]/30 focus:outline-none">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Penyakit / Keluhan</label>
                <input type="text" name="health_complaint" value="{{ old('health_complaint', $contact->health_complaint) }}"
                       placeholder="Diabetes, Hipertensi, dll."
                       class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-[#2d6a4f]/30 focus:outline-none">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Alamat</label>
                <textarea name="address" rows="2"
                          class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-[#2d6a4f]/30 focus:outline-none resize-none">{{ old('address', $contact->address) }}</textarea>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Sumber Info</label>
                    <input type="text" name="info_source" value="{{ old('info_source', $contact->info_source) }}"
                           placeholder="Instagram, Rekomendasi, dll."
                           class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-[#2d6a4f]/30 focus:outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Asal File</label>
                    <input type="text" name="source_file" value="{{ old('source_file', $contact->source_file) }}"
                           placeholder="Webinar, Talkshow, dll."
                           class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-[#2d6a4f]/30 focus:outline-none">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Catatan Internal</label>
                <textarea name="notes" rows="2" placeholder="Catatan untuk admin, tidak dilihat peserta…"
                          class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-[#2d6a4f]/30 focus:outline-none resize-none">{{ old('notes', $contact->notes) }}</textarea>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="flex-1 py-2.5 bg-[#2d6a4f] text-white font-semibold rounded-lg hover:bg-[#1a5a3f] text-sm">
                    {{ $contact->exists ? 'Simpan Perubahan' : 'Tambah Kontak' }}
                </button>
                <a href="{{ route('admin.kontak.index') }}"
                   class="px-5 py-2.5 border border-gray-300 text-gray-600 rounded-lg text-sm hover:bg-gray-50 text-center">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

@endsection
