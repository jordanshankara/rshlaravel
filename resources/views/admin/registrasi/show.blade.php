@extends('layouts.admin')
@section('title', 'Detail Registrasi')
@section('page-title', 'Detail Registrasi')

@section('header-actions')
<a href="{{ route('admin.registrasi.index') }}" class="text-sm text-gray-500 hover:text-gray-700">← Kembali</a>
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6" x-data="reschedulePanel()">

{{-- Left: Personal & Health data --}}
<div class="lg:col-span-2 space-y-6">
    <div class="bg-white rounded-xl border shadow-sm p-6">
        <div class="flex items-start justify-between mb-4">
            <div>
                <h2 class="text-lg font-semibold">{{ $registration->full_name }}</h2>
                <div class="text-sm text-gray-500 font-mono">{{ $registration->registration_code }}</div>
            </div>
            <span class="text-xs px-2.5 py-1 rounded-full font-semibold
                {{ $registration->status === 'FULLY_PAID' ? 'bg-green-100 text-green-700' :
                   ($registration->status === 'CONFIRMED' ? 'bg-emerald-100 text-emerald-700' :
                   ($registration->status === 'CANCELLED' ? 'bg-gray-100 text-gray-500' :
                   'bg-amber-100 text-amber-700')) }}">
                {{ str_replace('_', ' ', $registration->status) }}
            </span>
        </div>
        <div class="grid grid-cols-2 gap-4 text-sm">
            <div><span class="text-gray-500">Tanggal Lahir:</span><br>{{ $registration->birth_date->format('d M Y') }}</div>
            <div><span class="text-gray-500">Pekerjaan:</span><br>{{ $registration->occupation }}</div>
            <div><span class="text-gray-500">WhatsApp:</span><br>{{ $registration->whatsapp }}</div>
            <div><span class="text-gray-500">TB/BB:</span><br>{{ $registration->height_weight }}</div>
            <div class="col-span-2"><span class="text-gray-500">Alamat:</span><br>{{ $registration->address }}</div>
        </div>
    </div>

    <div class="bg-white rounded-xl border shadow-sm p-6">
        <h3 class="font-semibold mb-4">Data Kesehatan</h3>
        <div class="space-y-3 text-sm">
            <div><span class="text-gray-500 font-medium">Keluhan Kesehatan:</span><br>{{ $registration->health_complaints }}</div>
            <div><span class="text-gray-500 font-medium">Kondisi Klinis:</span><br>{{ $registration->clinical_details }}</div>
            <div><span class="text-gray-500 font-medium">BMI:</span> {{ $registration->bmi ?? '-' }}</div>
            <div><span class="text-gray-500 font-medium">Kondisi Emosi:</span><br>{{ $registration->emotion_state }}</div>
            <div><span class="text-gray-500 font-medium">Alergi Makanan:</span><br>{{ $registration->food_allergies }}</div>
            <div><span class="text-gray-500 font-medium">Riwayat Pengobatan:</span><br>{{ $registration->treatment_history }}</div>
            <div><span class="text-gray-500 font-medium">Obat Saat Ini:</span><br>{{ $registration->current_meds }}</div>
            <div><span class="text-gray-500 font-medium">Tingkat Keyakinan:</span> {{ $registration->confidence_level }}/10</div>
        </div>
    </div>
</div>

{{-- Right: Status management, Invoice, Reschedule --}}
<div class="space-y-5">

    {{-- Program info --}}
    <div class="bg-white rounded-xl border shadow-sm p-5">
        <h3 class="font-semibold mb-3 text-sm">Program</h3>
        <div class="text-sm font-medium">{{ $registration->programPeriod?->name }}</div>
        <div class="text-xs text-gray-500 mt-1">
            {{ $registration->programPeriod?->start_date?->format('d M Y') }}
            – {{ $registration->programPeriod?->end_date?->format('d M Y') }}
        </div>
        <div class="text-xs text-gray-500 mt-1">Didaftarkan: {{ $registration->submitted_at->format('d M Y H:i') }}</div>
    </div>

    {{-- Update Status --}}
    @php
        $validNext = [
            'PENDING_PAYMENT' => ['CONFIRMED', 'CANCELLED'],
            'CONFIRMED'       => ['FULLY_PAID', 'CANCELLED'],
            'FULLY_PAID'      => [],
            'CANCELLED'       => [],
        ][$registration->status] ?? [];
    @endphp
    @if(count($validNext) > 0)
    <div class="bg-white rounded-xl border shadow-sm p-5">
        <h3 class="font-semibold mb-3 text-sm">Update Status</h3>
        <form method="POST" action="{{ route('admin.registrasi.update-status', $registration) }}">
            @csrf @method('PATCH')
            <select name="status" class="w-full px-3 py-2 border rounded-lg text-sm mb-3 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                @foreach($validNext as $s)
                <option value="{{ $s }}">{{ str_replace('_', ' ', $s) }}</option>
                @endforeach
            </select>
            <textarea name="payment_note" rows="2" placeholder="Catatan pembayaran (opsional)"
                      class="w-full px-3 py-2 border rounded-lg text-sm mb-3 focus:outline-none focus:ring-2 focus:ring-emerald-500 resize-none"></textarea>
            <button type="submit" class="w-full py-2 bg-emerald-600 text-white text-sm font-semibold rounded-lg hover:bg-emerald-700">
                Perbarui Status
            </button>
        </form>
    </div>
    @endif

    {{-- Invoice --}}
    @if($registration->invoice)
    <div class="bg-white rounded-xl border shadow-sm p-5">
        <div class="flex items-center justify-between mb-3">
            <h3 class="font-semibold text-sm">Invoice</h3>
            <a href="{{ route('admin.invoice.pdf', $registration->invoice) }}" class="text-xs text-emerald-600 hover:underline">Unduh PDF</a>
        </div>
        <div class="text-xs space-y-1.5">
            <div class="flex justify-between">
                <span class="text-gray-500">Nomor</span>
                <span class="font-mono">{{ $registration->invoice->invoice_number }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Total</span>
                <span class="font-semibold">Rp {{ number_format($registration->invoice->total_amount, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-gray-500">Status</span>
                <span class="px-2 py-0.5 rounded-full font-medium text-xs
                    {{ $registration->invoice->payment_status === 'LUNAS' ? 'bg-green-100 text-green-700' :
                       ($registration->invoice->payment_status === 'DIBATALKAN' ? 'bg-gray-100 text-gray-500' :
                       'bg-amber-100 text-amber-700') }}">
                    {{ $registration->invoice->payment_status }}
                </span>
            </div>
        </div>
    </div>
    @endif

    {{-- Reschedule --}}
    @if(!in_array($registration->status, ['CANCELLED']))
    <div class="bg-white rounded-xl border shadow-sm p-5">
        <h3 class="font-semibold mb-3 text-sm">Reschedule</h3>
        <button @click="open()" x-show="!show"
                class="w-full py-2 border border-emerald-600 text-emerald-600 text-sm font-semibold rounded-lg hover:bg-emerald-50">
            Ganti Jadwal
        </button>

        <div x-show="show" x-cloak>
            <div x-show="loading" class="text-sm text-gray-400 py-2">Memuat periode...</div>
            <div x-show="!loading && periods.length === 0" class="text-sm text-gray-500 py-2">
                Tidak ada periode tersedia.
                <a href="{{ route('admin.program.create') }}" class="text-emerald-600 font-medium hover:underline block mt-2">+ Buat Jadwal Baru</a>
            </div>
            <div x-show="!loading && periods.length > 0">
                <form method="POST" action="{{ route('admin.registrasi.reschedule', $registration) }}">
                    @csrf
                    <select name="program_period_id" class="w-full px-3 py-2 border rounded-lg text-sm mb-3 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        <template x-for="p in periods" :key="p.id">
                            <option :value="p.id" x-text="p.name + ' · ' + p.available + ' kursi tersisa'"></option>
                        </template>
                    </select>
                    <div class="flex gap-2">
                        <button type="button" @click="show = false" class="flex-1 py-2 border text-sm rounded-lg text-gray-600">Batal</button>
                        <button type="submit" class="flex-1 py-2 bg-emerald-600 text-white text-sm font-semibold rounded-lg">Konfirmasi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    {{-- Quick Contact --}}
    <div class="bg-white rounded-xl border shadow-sm p-5">
        <h3 class="font-semibold mb-3 text-sm">Kontak Cepat</h3>
        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $registration->whatsapp) }}"
           target="_blank"
           class="flex items-center gap-2 text-sm text-green-600 hover:underline">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.890-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
            WhatsApp {{ $registration->whatsapp }}
        </a>
    </div>
</div>
</div>
@endsection

@push('scripts')
<script>
function reschedulePanel() {
    return {
        show: false,
        loading: false,
        periods: [],
        async open() {
            this.show = true;
            this.loading = true;
            try {
                const res = await fetch('{{ route('admin.registrasi.periods', $registration) }}');
                const data = await res.json();
                this.periods = data.periods || [];
            } catch(e) {
                this.periods = [];
            }
            this.loading = false;
        }
    }
}
</script>
@endpush
