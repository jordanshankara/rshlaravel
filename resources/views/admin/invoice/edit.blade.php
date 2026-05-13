@extends('layouts.admin')
@section('title', 'Edit Invoice')
@section('page-title', 'Edit Invoice')
@section('header-actions')
<a href="{{ route('admin.invoice.show', $invoice) }}" class="text-sm text-gray-500 hover:text-gray-700">← Kembali</a>
@endsection
@section('content')
<div class="max-w-2xl" x-data="invoiceForm({{ json_encode($invoice->items->map(fn($i) => ['description'=>$i->description,'quantity'=>$i->quantity,'price'=>(float)$i->price,'discount'=>(float)$i->discount])) }})">
    <div class="bg-white rounded-xl border shadow-sm p-6">
        <form method="POST" action="{{ route('admin.invoice.update', $invoice) }}" class="space-y-5">
            @csrf @method('PUT')
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Klien</label>
                    <input type="text" name="client_name" value="{{ old('client_name', $invoice->client_name) }}" required class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-[#2d6a4f]/30 focus:outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Tanggal</label>
                    <input type="date" name="invoice_date" value="{{ old('invoice_date', $invoice->invoice_date->format('Y-m-d')) }}" required class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-[#2d6a4f]/30 focus:outline-none">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
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
                        <option value="{{ $pd->id }}" {{ old('payment_detail_id', $invoice->payment_detail_id) == $pd->id ? 'selected' : '' }}>{{ $pd->bank_name }} - {{ $pd->account_number }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <div class="flex items-center justify-between mb-2">
                    <label class="block text-sm font-medium text-gray-700">Item</label>
                    <button type="button" @click="addItem()" class="text-xs text-[#2d6a4f] hover:underline font-medium">+ Tambah Baris</button>
                </div>
                <div class="space-y-2">
                    <template x-for="(item, i) in items" :key="i">
                        <div class="flex gap-2 items-start">
                            <input type="text" :name="'items['+i+'][description]'" x-model="item.description" placeholder="Deskripsi" required class="flex-1 px-3 py-2 border rounded-lg text-sm focus:outline-none">
                            <input type="number" :name="'items['+i+'][quantity]'" x-model="item.quantity" min="1" required class="w-16 px-3 py-2 border rounded-lg text-sm focus:outline-none text-center">
                            <input type="number" :name="'items['+i+'][price]'" x-model="item.price" min="0" placeholder="Harga" required class="w-36 px-3 py-2 border rounded-lg text-sm focus:outline-none">
                            <input type="number" :name="'items['+i+'][discount]'" x-model="item.discount" min="0" max="100" placeholder="Disc%" class="w-20 px-3 py-2 border rounded-lg text-sm focus:outline-none">
                            <button type="button" @click="removeItem(i)" x-show="items.length > 1" class="text-red-400 hover:text-red-600 mt-2">✕</button>
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
function invoiceForm(initialItems) {
    return {
        items: initialItems && initialItems.length ? initialItems : [{ description: '', quantity: 1, price: 0, discount: 0 }],
        addItem() { this.items.push({ description: '', quantity: 1, price: 0, discount: 0 }); },
        removeItem(i) { this.items.splice(i, 1); },
        formatTotal() {
            const t = this.items.reduce((s, i) => s + (i.price * i.quantity * (1 - (i.discount || 0) / 100)), 0);
            return new Intl.NumberFormat('id-ID').format(Math.round(t));
        }
    }
}
</script>
@endpush
