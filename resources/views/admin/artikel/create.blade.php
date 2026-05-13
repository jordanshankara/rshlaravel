@extends('layouts.admin')
@section('title', 'Tulis Artikel')
@section('page-title', 'Tulis Artikel')
@section('header-actions')
<a href="{{ route('admin.artikel.index') }}" class="text-sm text-gray-500 hover:text-gray-700">← Kembali</a>
@endsection
@section('content')
<div class="max-w-3xl">
    <div class="bg-white rounded-xl border shadow-sm p-6">
        <form method="POST" action="{{ route('admin.artikel.store') }}" enctype="multipart/form-data" class="space-y-5">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Judul</label>
                <input type="text" name="title" value="{{ old('title') }}" required class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Ringkasan</label>
                <textarea name="excerpt" rows="2" required class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none resize-none">{{ old('excerpt') }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Konten</label>
                <textarea name="content" rows="12" required class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none font-mono">{{ old('content') }}</textarea>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Cover Image</label>
                    <input type="file" name="cover_image" accept="image/*" class="w-full text-sm text-gray-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Status</label>
                    <select name="status" class="w-full px-3 py-2 border rounded-lg text-sm focus:outline-none">
                        <option value="DRAFT" {{ old('status') === 'DRAFT' ? 'selected' : '' }}>DRAFT</option>
                        <option value="PUBLISHED" {{ old('status') === 'PUBLISHED' ? 'selected' : '' }}>PUBLISHED</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                <div class="flex flex-wrap gap-2">
                    @foreach($categories as $cat)
                    <label class="flex items-center gap-1.5 text-sm cursor-pointer">
                        <input type="checkbox" name="categories[]" value="{{ $cat->id }}"
                               {{ in_array($cat->id, old('categories', [])) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-emerald-600">
                        {{ $cat->name }}
                    </label>
                    @endforeach
                </div>
            </div>
            <button type="submit" class="px-6 py-2.5 bg-emerald-600 text-white font-semibold rounded-lg hover:bg-emerald-700 text-sm">Simpan Artikel</button>
        </form>
    </div>
</div>
@endsection
