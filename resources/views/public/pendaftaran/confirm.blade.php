@extends('layouts.public')
@section('title', 'Cek Status Pendaftaran — RSH Satu Bumi')

@section('content')
<div class="max-w-xl mx-auto px-4 py-12" x-data="confirmForm()">
    <h1 class="text-3xl font-bold mb-2">Cek Pendaftaran</h1>
    <p class="text-gray-500 mb-8">Masukkan kode pendaftaran Anda untuk melihat status.</p>

    <div class="bg-white rounded-2xl border shadow-sm p-6 mb-6">
        <form @submit.prevent="check()" class="flex gap-3">
            <input type="text" x-model="code" placeholder="Contoh: RSH7A3F2C1"
                   class="flex-1 px-4 py-3 border rounded-xl text-sm font-mono focus:ring-2 focus:ring-emerald-500 focus:outline-none uppercase"
                   :disabled="loading">
            <button type="submit" :disabled="loading || !code"
                    class="px-6 py-3 bg-emerald-600 text-white font-semibold rounded-xl hover:bg-emerald-700 disabled:opacity-50 transition">
                <span x-show="!loading">Cek</span>
                <span x-show="loading">...</span>
            </button>
        </form>
        <div x-show="error" class="mt-3 text-sm text-red-600" x-text="error"></div>
    </div>

    <div x-show="result" x-cloak class="space-y-4">
        {{-- Registration info --}}
        <div class="bg-white rounded-2xl border shadow-sm p-6">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <div class="font-mono text-sm text-gray-500" x-text="result?.registration?.code"></div>
                    <div class="text-xl font-bold mt-1" x-text="result?.registration?.full_name"></div>
                </div>
                <span class="text-xs px-2.5 py-1 rounded-full font-semibold"
                      :class="statusClass(result?.registration?.status)"
                      x-text="result?.registration?.status?.replace('_', ' ')"></span>
            </div>
            <div class="text-sm text-gray-600">
                <div class="font-medium" x-text="result?.registration?.period"></div>
                <div class="text-gray-400 mt-0.5" x-text="result?.registration?.start_date + ' – ' + result?.registration?.end_date"></div>
            </div>
        </div>

        {{-- Invoice info --}}
        <div x-show="result?.invoice" class="bg-white rounded-2xl border shadow-sm p-6">
            <h3 class="font-semibold mb-3">Informasi Pembayaran</h3>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-500">No. Invoice</span>
                    <span class="font-mono" x-text="result?.invoice?.invoice_number"></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Total</span>
                    <span class="font-bold" x-text="'Rp ' + Number(result?.invoice?.total_amount).toLocaleString('id-ID')"></span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-500">Status</span>
                    <span :class="paymentClass(result?.invoice?.payment_status)"
                          class="text-xs px-2.5 py-1 rounded-full font-semibold"
                          x-text="result?.invoice?.payment_status"></span>
                </div>
                <template x-if="result?.invoice?.payment_status === 'BELUM_LUNAS' && result?.invoice?.bank_name">
                    <div class="mt-3 p-3 bg-emerald-50 border border-emerald-200 rounded-xl">
                        <div class="text-xs font-semibold text-emerald-700 mb-2">Transfer ke:</div>
                        <div class="font-semibold" x-text="result?.invoice?.bank_name"></div>
                        <div class="font-mono text-lg font-bold" x-text="result?.invoice?.account_number"></div>
                        <div class="text-sm text-gray-600" x-text="result?.invoice?.account_name"></div>
                    </div>
                </template>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function confirmForm() {
    return {
        code: '',
        loading: false,
        error: '',
        result: null,
        async check() {
            this.error = '';
            this.result = null;
            this.loading = true;
            try {
                const res = await fetch('{{ route('daftar.check') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ code: this.code }),
                });
                const data = await res.json();
                if (res.ok) {
                    this.result = data;
                } else {
                    this.error = data.error || 'Kode tidak ditemukan.';
                }
            } catch(e) {
                this.error = 'Terjadi kesalahan. Coba lagi.';
            }
            this.loading = false;
        },
        statusClass(status) {
            const map = { FULLY_PAID:'bg-green-100 text-green-700', CONFIRMED:'bg-emerald-100 text-emerald-700', CANCELLED:'bg-gray-100 text-gray-500' };
            return map[status] || 'bg-amber-100 text-amber-700';
        },
        paymentClass(status) {
            const map = { LUNAS:'bg-green-100 text-green-700', DIBATALKAN:'bg-gray-100 text-gray-500' };
            return map[status] || 'bg-amber-100 text-amber-700';
        }
    }
}
</script>
@endpush
