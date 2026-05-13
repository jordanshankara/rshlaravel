@extends('layouts.public')
@section('title', 'Cek Status Pendaftaran — RSH Satu Bumi')

@section('content')

{{-- Page Header --}}
<div class="relative h-48 overflow-hidden">
    <img src="{{ asset('assets/green.webp') }}" alt="" class="absolute inset-0 w-full h-full object-cover">
    <div class="absolute inset-0" style="background: linear-gradient(to bottom, rgba(5,46,22,0.75), rgba(13,61,26,0.85))"></div>
    <div class="relative h-full flex flex-col items-center justify-center text-center px-4">
        <span class="text-[#f97316] uppercase tracking-widest text-xs font-bold mb-3">STATUS PENDAFTARAN</span>
        <h1 class="text-3xl font-bold text-white">Cek Pendaftaran</h1>
    </div>
</div>

<div class="max-w-xl mx-auto px-4 py-12" x-data="confirmForm()">
    <p class="text-gray-500 text-center mb-8">Masukkan kode pendaftaran Anda untuk melihat status.</p>

    <div class="glass-card rounded-2xl p-6 mb-6">
        <form @submit.prevent="check()" class="flex gap-3">
            <input type="text" x-model="code" placeholder="Contoh: RSH7A3F2C1"
                   class="input font-mono uppercase flex-1" style="resize:none"
                   :disabled="loading">
            <button type="submit" :disabled="loading || !code"
                    class="px-6 py-2.5 bg-[#1a6b2f] text-white font-semibold rounded-xl hover:bg-[#0d3d1a] disabled:opacity-50 transition flex-shrink-0">
                <span x-show="!loading">Cek</span>
                <span x-show="loading">...</span>
            </button>
        </form>
        <div x-show="error" class="mt-3 text-sm text-red-600" x-text="error"></div>
    </div>

    <div x-show="result" x-cloak class="space-y-4">
        {{-- Registration info --}}
        <div class="glass-card rounded-2xl p-6">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <div class="font-mono text-sm text-gray-500" x-text="result?.registration?.code"></div>
                    <div class="text-xl font-bold text-[#0d3d1a] mt-1" x-text="result?.registration?.full_name"></div>
                </div>
                <span class="text-xs px-2.5 py-1 rounded-full font-semibold"
                      :class="statusClass(result?.registration?.status)"
                      x-text="result?.registration?.status?.replace('_', ' ')"></span>
            </div>
            <div class="text-sm text-gray-600">
                <div class="font-medium text-[#1a6b2f]" x-text="result?.registration?.period"></div>
                <div class="text-gray-400 mt-0.5" x-text="result?.registration?.start_date + ' – ' + result?.registration?.end_date"></div>
            </div>
        </div>

        {{-- Invoice info --}}
        <div x-show="result?.invoice" class="glass-card rounded-2xl p-6">
            <h3 class="font-semibold text-[#0d3d1a] mb-4">Informasi Pembayaran</h3>
            <div class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-500">No. Invoice</span>
                    <span class="font-mono text-gray-700" x-text="result?.invoice?.invoice_number"></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Total</span>
                    <span class="font-bold text-[#0d3d1a]" x-text="'Rp ' + Number(result?.invoice?.total_amount).toLocaleString('id-ID')"></span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-500">Status</span>
                    <span :class="paymentClass(result?.invoice?.payment_status)"
                          class="text-xs px-2.5 py-1 rounded-full font-semibold"
                          x-text="result?.invoice?.payment_status"></span>
                </div>
                <template x-if="result?.invoice?.payment_status === 'BELUM_LUNAS' && result?.invoice?.bank_name">
                    <div class="mt-3 p-4 bg-green-50 border border-green-200 rounded-xl">
                        <div class="text-xs font-semibold text-[#1a6b2f] mb-2">Transfer ke:</div>
                        <div class="font-semibold text-[#0d3d1a]" x-text="result?.invoice?.bank_name"></div>
                        <div class="font-mono text-xl font-bold text-[#1a6b2f]" x-text="result?.invoice?.account_number"></div>
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
