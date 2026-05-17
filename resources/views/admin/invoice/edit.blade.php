@extends('layouts.admin')
@section('title', 'Edit Invoice')
@section('page-title', 'Edit Invoice')
@section('header-actions')
<a href="{{ route('admin.invoice.show', $invoice) }}" class="text-sm text-gray-500 hover:text-gray-700">← Kembali</a>
@endsection
@section('content')
<div class="max-w-2xl" x-data="invoiceForm(
    {{ $invoice->items->map(fn($i) => ['description'=>$i->description,'quantity'=>$i->quantity,'price'=>(float)$i->price,'discount'=>(float)$i->discount])->values()->toJson() }},
    {{ $products->map(fn($p) => ['id'=>$p->id,'name'=>$p->name,'category'=>$p->category,'price'=>(float)$p->price])->values()->toJson() }}
)">
    <div class="bg-white rounded-xl border shadow-sm p-6">
        <form method="POST" action="{{ route('admin.invoice.update', $invoice) }}" class="space-y-5"
              @submit.prevent="if(cleanBeforeSubmit()) $el.submit()">
            @csrf @method('PUT')
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Klien</label>
                    <input type="text" name="client_name" value="{{ old('client_name', $invoice->client_name) }}" required
                           class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-[#2d6a4f]/30 focus:outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Tanggal</label>
                    <input type="date" name="invoice_date" value="{{ old('invoice_date', $invoice->invoice_date->format('Y-m-d')) }}" required
                           class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-[#2d6a4f]/30 focus:outline-none">
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Status</label>
                    <select name="payment_status" class="w-full px-3 py-2 border rounded-lg text-sm focus:outline-none">
                        @foreach(['BELUM_LUNAS'=>'Belum Lunas','LUNAS'=>'Lunas','DIBATALKAN'=>'Dibatalkan'] as $val => $label)
                        <option value="{{ $val }}" {{ old('payment_status', $invoice->payment_status) === $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Detail Pembayaran</label>
                    <select name="payment_detail_id" class="w-full px-3 py-2 border rounded-lg text-sm focus:outline-none">
                        <option value="">— Tidak Ada —</option>
                        @foreach($paymentDetails as $pd)
                        <option value="{{ $pd->id }}" {{ old('payment_detail_id', $invoice->payment_detail_id) == $pd->id ? 'selected' : '' }}>
                            {{ $pd->bank_name }} - {{ $pd->account_number }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <div class="flex items-center justify-between mb-2">
                    <label class="block text-sm font-medium text-gray-700">Item</label>
                    <button type="button" @click="addItem()" class="text-xs text-[#2d6a4f] hover:underline font-medium">+ Tambah Baris</button>
                </div>

                {{-- Column headers --}}
                <div class="hidden sm:grid grid-cols-[1fr_56px_144px_80px_24px] gap-2 mb-1 px-1">
                    <span class="text-xs text-gray-400">Deskripsi</span>
                    <span class="text-xs text-gray-400 text-center">Qty</span>
                    <span class="text-xs text-gray-400">Harga (Rp)</span>
                    <span class="text-xs text-gray-400">Disc%</span>
                    <span></span>
                </div>

                <div class="space-y-2">
                    <template x-for="(item, i) in items" :key="i">
                        <div class="flex flex-col sm:grid sm:grid-cols-[1fr_56px_144px_80px_24px] gap-2 items-start">
                            {{-- Description with autocomplete --}}
                            <div class="relative">
                                <input type="text"
                                       :name="'items['+i+'][description]'"
                                       x-model="item.description"
                                       @focus="activeRow = i"
                                       @input="activeRow = i; item.priceFromProduct = false"
                                       @blur="setTimeout(() => { if (activeRow === i) activeRow = null }, 180)"
                                       placeholder="Deskripsi produk/layanan"
                                       required
                                       autocomplete="off"
                                       class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-[#2d6a4f]/30 focus:outline-none">
                                <div x-show="activeRow === i && getSuggestions(i).length > 0"
                                     x-cloak
                                     class="absolute top-full left-0 right-0 z-20 mt-1 bg-white border border-gray-200 rounded-lg shadow-lg max-h-52 overflow-y-auto">
                                    <template x-for="product in getSuggestions(i)" :key="product.id">
                                        <button type="button"
                                                @mousedown.prevent="selectProduct(i, product)"
                                                class="w-full text-left px-3 py-2.5 hover:bg-green-50 flex items-center justify-between gap-2 border-b border-gray-50 last:border-0">
                                            <div class="min-w-0">
                                                <div class="text-sm text-gray-800 truncate" x-text="product.name"></div>
                                                <div class="text-xs text-gray-400" x-show="product.category" x-text="product.category"></div>
                                            </div>
                                            <span class="text-xs font-semibold text-[#2d6a4f] flex-shrink-0" x-text="formatPrice(product.price)"></span>
                                        </button>
                                    </template>
                                </div>
                            </div>
                            <div class="grid grid-cols-[56px_1fr_64px_24px] sm:contents gap-2 items-center">
                                <input type="number" :name="'items['+i+'][quantity]'" x-model="item.quantity" min="1" required
                                       class="w-full px-2 py-2 border rounded-lg text-sm focus:outline-none text-center">
                                <input type="number" :name="'items['+i+'][price]'" x-model="item.price" min="0" placeholder="0" required
                                       :readonly="item.priceFromProduct"
                                       :title="item.priceFromProduct ? 'Edit harga dari halaman produk' : ''"
                                       :class="item.priceFromProduct ? 'bg-gray-50 text-gray-500 cursor-not-allowed' : ''"
                                       class="w-full px-3 py-2 border rounded-lg text-sm focus:outline-none">
                                <input type="number" :name="'items['+i+'][discount]'" x-model="item.discount" min="0" max="100" placeholder="0"
                                       class="w-full px-2 py-2 border rounded-lg text-sm focus:outline-none">
                                <button type="button" @click="removeItem(i)" x-show="items.length > 1"
                                        class="text-gray-400 hover:text-red-500 transition-colors justify-self-center">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </template>
                </div>
                <div class="mt-3 text-right font-semibold text-gray-700 text-sm">
                    Total: Rp <span x-text="formatTotal()"></span>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Catatan</label>
                <textarea name="notes" rows="2" class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-[#2d6a4f]/30 focus:outline-none resize-none">{{ old('notes', $invoice->notes) }}</textarea>
            </div>

            <button type="submit" class="w-full py-2.5 bg-[#2d6a4f] text-white font-semibold rounded-lg hover:bg-[#1a5a3f] text-sm">Simpan Perubahan</button>
        </form>
    </div>
</div>
@endsection
@push('scripts')
<script>
function invoiceForm(initialItems, products) {
    return {
        items: (initialItems && initialItems.length)
            ? initialItems.map(i => ({ ...i, priceFromProduct: false }))
            : [{ description: '', quantity: 1, price: 0, discount: 0, priceFromProduct: false }],
        products: products || [],
        activeRow: null,

        addItem() {
            this.items.push({ description: '', quantity: 1, price: 0, discount: 0, priceFromProduct: false });
        },
        removeItem(i) {
            this.items.splice(i, 1);
            if (this.activeRow === i) this.activeRow = null;
        },
        getSuggestions(i) {
            const item = this.items[i];
            if (!item) return [];
            if (item.priceFromProduct) return this.products.slice(0, 8);
            const q = (item.description || '').toLowerCase().trim();
            const list = q
                ? this.products.filter(p =>
                    p.name.toLowerCase().includes(q) ||
                    (p.category && p.category.toLowerCase().includes(q))
                  )
                : this.products;
            return list.slice(0, 8);
        },
        selectProduct(i, product) {
            this.items[i].description = product.name;
            this.items[i].price = product.price;
            this.items[i].priceFromProduct = true;
            this.activeRow = null;
        },
        formatTotal() {
            const t = this.items.reduce((s, item) =>
                s + ((item.price || 0) * (item.quantity || 1) * (1 - ((item.discount || 0) / 100))), 0);
            return new Intl.NumberFormat('id-ID').format(Math.round(t));
        },
        formatPrice(p) {
            return 'Rp ' + new Intl.NumberFormat('id-ID').format(p);
        },
        cleanBeforeSubmit() {
            this.items = this.items.filter(item => (item.description || '').trim() !== '');
            if (this.items.length === 0) {
                this.items = [{ description: '', quantity: 1, price: 0, discount: 0 }];
                return false;
            }
            return true;
        }
    }
}
</script>
@endpush
