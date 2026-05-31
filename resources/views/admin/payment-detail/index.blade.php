@extends('layouts.admin')
@section('title', 'Rekening Pembayaran')
@section('page-title', 'Rekening Pembayaran')

@section('content')
<div x-data="{
    open: false,
    editing: null,
    form: { bank_name: '', account_number: '', account_name: '', is_default: false },
    openEdit(pd) {
        this.editing = pd;
        this.form = { bank_name: pd.bank_name, account_number: pd.account_number, account_name: pd.account_name, is_default: pd.is_default };
        this.open = true;
    }
}">

@if(session('success'))
<div class="mb-4 px-4 py-3 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm">{{ session('success') }}</div>
@endif

<div class="flex justify-end mb-4">
    <button @click="open = true; editing = null; form = { bank_name: '', account_number: '', account_name: '', is_default: false }"
            class="px-4 py-2 bg-[#2d6a4f] text-white text-sm font-semibold rounded-lg hover:bg-[#1a5a3f] transition-colors">
        + Rekening Baru
    </button>
</div>

<div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
    @forelse($paymentDetails as $pd)
    <div class="px-5 py-4 flex items-center justify-between border-b border-gray-100 last:border-0 hover:bg-gray-50">
        <div>
            <div class="flex items-center gap-2">
                <span class="text-sm font-semibold text-gray-800">{{ $pd->bank_name }}</span>
                @if($pd->is_default)
                <span class="text-xs px-2 py-0.5 bg-green-100 text-green-700 rounded-full font-medium">Default</span>
                @endif
            </div>
            <div class="text-xs text-gray-500 mt-0.5">{{ $pd->account_number }} · {{ $pd->account_name }}</div>
        </div>
        <div class="flex items-center gap-2">
            @if(!$pd->is_default)
            <form method="POST" action="{{ route('admin.payment-detail.set-default', $pd) }}">
                @csrf @method('PATCH')
                <button type="submit" class="text-xs text-gray-500 hover:text-[#2d6a4f] hover:underline transition-colors">Jadikan Default</button>
            </form>
            @endif
            <button @click="openEdit({{ $pd->toJson() }})"
                    class="text-xs text-[#2d6a4f] font-medium hover:underline">Edit</button>
            <form method="POST" action="{{ route('admin.payment-detail.destroy', $pd) }}" @submit.prevent="adminConfirm('Hapus Rekening', 'Rekening ini akan dihapus dari daftar metode pembayaran.', {danger:true, okLabel:'Ya, Hapus'}).then(ok => ok && $el.submit())">
                @csrf @method('DELETE')
                <button type="submit" class="text-xs text-red-500 hover:underline">Hapus</button>
            </form>
        </div>
    </div>
    @empty
    <div class="px-5 py-10 text-center text-gray-400 text-sm">Belum ada rekening pembayaran.</div>
    @endforelse
</div>

{{-- Modal --}}
<div x-show="open" class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display:none">
    <div class="absolute inset-0 bg-black/40" @click="open = false"></div>
    <div class="relative bg-white rounded-xl shadow-xl w-full max-w-md p-6">
        <div class="flex items-center justify-between mb-5">
            <h2 class="font-semibold text-gray-800" x-text="editing ? 'Edit Rekening' : 'Tambah Rekening'"></h2>
            <button @click="open = false" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Add Form --}}
        <form x-show="!editing" method="POST" action="{{ route('admin.payment-detail.store') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Bank</label>
                <input type="text" name="bank_name" x-model="form.bank_name" required placeholder="BCA, BRI, Mandiri..."
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#2d6a4f]/30 focus:outline-none">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">No. Rekening</label>
                <input type="text" name="account_number" x-model="form.account_number" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#2d6a4f]/30 focus:outline-none">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Pemilik Rekening</label>
                <input type="text" name="account_name" x-model="form.account_name" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#2d6a4f]/30 focus:outline-none">
            </div>
            <label class="flex items-center gap-2 text-sm">
                <input type="checkbox" name="is_default" value="1" x-model="form.is_default" class="rounded border-gray-300 text-[#2d6a4f]">
                Jadikan rekening default
            </label>
            <div class="flex gap-3 pt-2">
                <button type="button" @click="open = false" class="flex-1 px-4 py-2 border border-gray-200 text-gray-600 text-sm rounded-lg hover:bg-gray-50">Batal</button>
                <button type="submit" class="flex-1 px-4 py-2 bg-[#2d6a4f] text-white text-sm rounded-lg hover:bg-[#1a5a3f]">Simpan</button>
            </div>
        </form>

        {{-- Edit Form --}}
        <template x-if="editing">
            <form method="POST" :action="`/admin/payment-detail/${editing.id}`" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Bank</label>
                    <input type="text" name="bank_name" x-model="form.bank_name" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#2d6a4f]/30 focus:outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">No. Rekening</label>
                    <input type="text" name="account_number" x-model="form.account_number" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#2d6a4f]/30 focus:outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Pemilik Rekening</label>
                    <input type="text" name="account_name" x-model="form.account_name" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#2d6a4f]/30 focus:outline-none">
                </div>
                <label class="flex items-center gap-2 text-sm">
                    <input type="checkbox" name="is_default" value="1" x-model="form.is_default" class="rounded border-gray-300 text-[#2d6a4f]">
                    Jadikan rekening default
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
