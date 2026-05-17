@extends('layouts.admin')
@section('title', 'Produk Invoice')
@section('page-title', 'Produk Invoice')

@section('content')
<div x-data="{
    open: false,
    editing: null,
    showInactive: false,
    form: { name: '', category: '', description: '', price: '', is_active: true },
    openEdit(p) {
        this.editing = p;
        this.form = { name: p.name, category: p.category ?? '', description: p.description ?? '', price: p.price, is_active: p.is_active };
        this.open = true;
    }
}">

{{-- Header --}}
<div class="flex items-center justify-between mb-5">
    <div>
        <h1 class="text-xl font-bold text-gray-900">Produk / Layanan</h1>
        <p class="text-xs text-gray-400 mt-0.5">Daftar layanan yang bisa dipilih saat membuat invoice</p>
    </div>
    <div class="flex items-center gap-3">
        <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer select-none">
            <input type="checkbox" x-model="showInactive" class="rounded border-gray-300 text-[#2d6a4f]">
            Tampilkan nonaktif
        </label>
        <button @click="open = true; editing = null; form = { name: '', category: '', description: '', price: '', is_active: true }"
                class="flex items-center gap-1.5 px-4 py-2 bg-[#2d6a4f] hover:bg-[#1a5a3f] text-white text-sm font-semibold rounded-lg transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Produk
        </button>
    </div>
</div>

@if(session('success'))
<div class="mb-4 px-4 py-3 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm">{{ session('success') }}</div>
@endif

@if(session('error'))
<div class="mb-4 px-4 py-3 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm">{{ session('error') }}</div>
@endif

@forelse($products->groupBy('category') as $category => $items)
<div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mb-4"
     x-show="showInactive || {{ $items->where('is_active', true)->count() > 0 ? 'true' : 'false' }}">
    <div class="px-5 py-3 bg-gray-50 border-b border-gray-200">
        <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider">{{ $category ?: 'Umum' }}</h3>
    </div>
    <div class="divide-y divide-gray-100">
        @foreach($items as $product)
        <div class="px-5 py-4 flex items-center justify-between hover:bg-gray-50 {{ $product->is_active ? '' : 'opacity-50' }}"
             x-show="showInactive || {{ $product->is_active ? 'true' : 'false' }}">
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2">
                    <span class="text-sm font-medium text-gray-800">{{ $product->name }}</span>
                    @if(!$product->is_active)
                    <span class="text-xs px-2 py-0.5 rounded-full font-medium bg-gray-100 text-gray-500">Nonaktif</span>
                    @endif
                </div>
                @if($product->description)
                <p class="text-xs text-gray-400 mt-0.5 truncate max-w-xs">{{ $product->description }}</p>
                @endif
            </div>
            <div class="flex items-center gap-4 ml-4">
                <span class="text-sm font-semibold text-gray-700">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                <div class="flex items-center gap-1">
                    {{-- Toggle active/inactive --}}
                    <form method="POST" action="{{ route('admin.invoice-product.toggle', $product) }}">
                        @csrf @method('PATCH')
                        <button type="submit" title="{{ $product->is_active ? 'Nonaktifkan' : 'Aktifkan' }}"
                                class="p-1.5 rounded-lg transition-colors {{ $product->is_active ? 'text-amber-500 hover:bg-amber-50' : 'text-green-600 hover:bg-green-50' }}">
                            @if($product->is_active)
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                            </svg>
                            @else
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            @endif
                        </button>
                    </form>
                    {{-- Edit --}}
                    <button @click="openEdit({{ $product->toJson() }})" title="Edit"
                            class="p-1.5 rounded-lg text-gray-400 hover:text-[#2d6a4f] hover:bg-[#2d6a4f]/10 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </button>
                    {{-- Delete --}}
                    <form method="POST" action="{{ route('admin.invoice-product.destroy', $product) }}" onsubmit="return confirm('Hapus produk ini?')">
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
<div x-show="open" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
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
