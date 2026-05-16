@extends('layouts.admin')
@section('title', 'Produk Invoice')
@section('page-title', 'Produk Invoice')

@section('header-actions')
<button @click="open = true; editing = null; form = { name: '', category: '', description: '', price: '', is_active: true }"
        class="px-4 py-2 bg-[#2d6a4f] text-white text-sm font-semibold rounded-lg hover:bg-[#1a5a3f] transition-colors">
    + Produk Baru
</button>
@endsection

@section('content')
<div x-data="{
    open: false,
    editing: null,
    form: { name: '', category: '', description: '', price: '', is_active: true },
    openEdit(p) {
        this.editing = p;
        this.form = { name: p.name, category: p.category ?? '', description: p.description ?? '', price: p.price, is_active: p.is_active };
        this.open = true;
    }
}">

@if(session('success'))
<div class="mb-4 px-4 py-3 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm">{{ session('success') }}</div>
@endif

@forelse($products->groupBy('category') as $category => $items)
<div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mb-4">
    <div class="px-5 py-3 bg-gray-50 border-b border-gray-200">
        <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider">{{ $category ?: 'Umum' }}</h3>
    </div>
    <div class="divide-y divide-gray-100">
        @foreach($items as $product)
        <div class="px-5 py-4 flex items-center justify-between hover:bg-gray-50">
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2">
                    <span class="text-sm font-medium text-gray-800">{{ $product->name }}</span>
                    <span class="text-xs px-2 py-0.5 rounded-full font-medium {{ $product->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                        {{ $product->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </div>
                @if($product->description)
                <p class="text-xs text-gray-400 mt-0.5 truncate max-w-xs">{{ $product->description }}</p>
                @endif
            </div>
            <div class="flex items-center gap-4 ml-4">
                <span class="text-sm font-semibold text-gray-700">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                <div class="flex items-center gap-2">
                    <form method="POST" action="{{ route('admin.invoice-product.toggle', $product) }}">
                        @csrf @method('PATCH')
                        <button type="submit" class="text-xs {{ $product->is_active ? 'text-amber-600' : 'text-green-600' }} hover:underline">
                            {{ $product->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                        </button>
                    </form>
                    <button @click="openEdit({{ $product->toJson() }})"
                            class="text-xs text-[#2d6a4f] font-medium hover:underline">Edit</button>
                    <form method="POST" action="{{ route('admin.invoice-product.destroy', $product) }}" onsubmit="return confirm('Hapus produk ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-xs text-red-500 hover:underline">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@empty
<div class="bg-white rounded-xl border border-gray-200 shadow-sm px-5 py-10 text-center text-gray-400 text-sm">
    Belum ada produk invoice.
</div>
@endforelse

{{-- Modal --}}
<div x-show="open" class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display:none">
    <div class="absolute inset-0 bg-black/40" @click="open = false"></div>
    <div class="relative bg-white rounded-xl shadow-xl w-full max-w-md p-6 max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between mb-5">
            <h2 class="font-semibold text-gray-800" x-text="editing ? 'Edit Produk' : 'Tambah Produk'"></h2>
            <button @click="open = false" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Add Form --}}
        <form x-show="!editing" method="POST" action="{{ route('admin.invoice-product.store') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Produk <span class="text-red-500">*</span></label>
                <input type="text" name="name" x-model="form.name" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#2d6a4f]/30 focus:outline-none">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                <input type="text" name="category" x-model="form.category" placeholder="Terapi, Konsultasi, Produk..."
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#2d6a4f]/30 focus:outline-none">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                <textarea name="description" x-model="form.description" rows="2"
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#2d6a4f]/30 focus:outline-none resize-none"></textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Harga (Rp) <span class="text-red-500">*</span></label>
                <input type="number" name="price" x-model="form.price" required min="0" step="1000"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#2d6a4f]/30 focus:outline-none">
            </div>
            <label class="flex items-center gap-2 text-sm">
                <input type="checkbox" name="is_active" value="1" x-model="form.is_active" class="rounded border-gray-300 text-[#2d6a4f]" checked>
                Produk aktif
            </label>
            <div class="flex gap-3 pt-2">
                <button type="button" @click="open = false" class="flex-1 px-4 py-2 border border-gray-200 text-gray-600 text-sm rounded-lg hover:bg-gray-50">Batal</button>
                <button type="submit" class="flex-1 px-4 py-2 bg-[#2d6a4f] text-white text-sm rounded-lg hover:bg-[#1a5a3f]">Simpan</button>
            </div>
        </form>

        {{-- Edit Form --}}
        <template x-if="editing">
            <form method="POST" :action="`/admin/invoice-product/${editing.id}`" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Produk <span class="text-red-500">*</span></label>
                    <input type="text" name="name" x-model="form.name" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#2d6a4f]/30 focus:outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                    <input type="text" name="category" x-model="form.category"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#2d6a4f]/30 focus:outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                    <textarea name="description" x-model="form.description" rows="2"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#2d6a4f]/30 focus:outline-none resize-none"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Harga (Rp) <span class="text-red-500">*</span></label>
                    <input type="number" name="price" x-model="form.price" required min="0" step="1000"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#2d6a4f]/30 focus:outline-none">
                </div>
                <label class="flex items-center gap-2 text-sm">
                    <input type="checkbox" name="is_active" value="1" x-model="form.is_active" class="rounded border-gray-300 text-[#2d6a4f]">
                    Produk aktif
                </label>
                <div class="flex gap-3 pt-2">
                    <button type="button" @click="open = false" class="flex-1 px-4 py-2 border border-gray-200 text-gray-600 text-sm rounded-lg hover:bg-gray-50">Batal</button>
                    <button type="submit" class="flex-1 px-4 py-2 bg-[#2d6a4f] text-white text-sm rounded-lg hover:bg-[#1a5a3f]">Simpan</button>
                </div>
            </form>
        </template>
    </div>
</div>

</div>
@endsection
