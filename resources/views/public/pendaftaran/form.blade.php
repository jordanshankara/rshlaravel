@extends('layouts.public')
@section('title', 'Daftar Program — RSH Satu Bumi')

@push('head')
@if(!empty($settings['turnstile_site_key']))
<script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
@endif
@endpush

@section('content')
<div class="max-w-2xl mx-auto px-4 py-12" x-data="registrationForm()">
    <h1 class="text-3xl font-bold mb-2">Daftar Program</h1>
    <p class="text-gray-500 mb-8">Isi formulir di bawah untuk mendaftarkan diri ke Program 7 Hari Menuju Sehat Raga & Jiwa.</p>

    {{-- Success state --}}
    <div x-show="submitted" x-cloak class="bg-emerald-50 border border-emerald-200 rounded-2xl p-8 text-center">
        <div class="text-5xl mb-4">✅</div>
        <h2 class="text-2xl font-bold text-emerald-800 mb-2">Pendaftaran Berhasil!</h2>
        <p class="text-gray-600 mb-4">Kode pendaftaran Anda:</p>
        <div class="text-3xl font-mono font-bold text-emerald-700 bg-white border-2 border-emerald-300 rounded-xl px-8 py-4 inline-block mb-6" x-text="regCode"></div>
        <p class="text-sm text-gray-500 mb-6">Simpan kode ini untuk melacak status pendaftaran Anda.</p>
        <div class="flex gap-3 justify-center">
            <a href="{{ route('daftar.confirm') }}" class="px-6 py-2.5 border border-emerald-600 text-emerald-600 font-semibold rounded-xl hover:bg-emerald-50">Cek Status</a>
            <a href="{{ route('home') }}" class="px-6 py-2.5 bg-emerald-600 text-white font-semibold rounded-xl hover:bg-emerald-700">Kembali ke Beranda</a>
        </div>
    </div>

    {{-- Form --}}
    <form x-show="!submitted" @submit.prevent="submit()" class="space-y-6">
        <div class="bg-white rounded-2xl border shadow-sm p-6 space-y-4">
            <h2 class="font-bold text-lg text-gray-800">Data Pribadi</h2>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label>
                <input type="text" x-model="form.full_name" required class="w-full px-3 py-2.5 border rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Tanggal Lahir <span class="text-red-500">*</span></label>
                    <input type="date" x-model="form.birth_date" required class="w-full px-3 py-2.5 border rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Pekerjaan <span class="text-red-500">*</span></label>
                    <input type="text" x-model="form.occupation" required class="w-full px-3 py-2.5 border rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">WhatsApp <span class="text-red-500">*</span></label>
                    <input type="text" x-model="form.whatsapp" required class="w-full px-3 py-2.5 border rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none" placeholder="628xxxx">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">TB/BB <span class="text-red-500">*</span></label>
                    <input type="text" x-model="form.height_weight" required class="w-full px-3 py-2.5 border rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none" placeholder="170cm / 65kg">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Alamat <span class="text-red-500">*</span></label>
                <textarea x-model="form.address" rows="2" required class="w-full px-3 py-2.5 border rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none resize-none"></textarea>
            </div>
        </div>

        <div class="bg-white rounded-2xl border shadow-sm p-6">
            <h2 class="font-bold text-lg text-gray-800 mb-4">Pilih Periode Program</h2>
            @forelse($periods as $period)
            @php $available = $period->quota - $period->filled; @endphp
            <label class="flex items-start gap-3 p-4 border rounded-xl cursor-pointer hover:border-emerald-400 transition mb-3 last:mb-0"
                   :class="form.program_period_id == {{ $period->id }} ? 'border-emerald-500 bg-emerald-50' : ''">
                <input type="radio" x-model="form.program_period_id" value="{{ $period->id }}"
                       {{ $available <= 0 ? 'disabled' : '' }} class="mt-1 text-emerald-600">
                <div class="flex-1">
                    <div class="font-semibold {{ $available <= 0 ? 'text-gray-400' : '' }}">{{ $period->name }}</div>
                    <div class="text-sm text-gray-500">{{ $period->start_date->format('d M Y') }} – {{ $period->end_date->format('d M Y') }}</div>
                    <div class="text-sm font-bold text-emerald-700 mt-1">Rp {{ number_format($period->price, 0, ',', '.') }}
                        <span class="text-xs text-gray-500 font-normal">· DP Rp {{ number_format($period->dp_amount, 0, ',', '.') }}</span>
                    </div>
                </div>
                <div class="text-right">
                    @if($available > 0)
                    <span class="text-xs text-emerald-600 font-medium">{{ $available }} kursi</span>
                    @else
                    <span class="text-xs text-red-500 font-medium">Penuh</span>
                    @endif
                </div>
            </label>
            @empty
            <p class="text-gray-400 text-sm">Tidak ada periode aktif saat ini.</p>
            @endforelse
        </div>

        <div class="bg-white rounded-2xl border shadow-sm p-6 space-y-4">
            <h2 class="font-bold text-lg text-gray-800">Data Kesehatan</h2>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Keluhan Kesehatan <span class="text-red-500">*</span></label>
                <textarea x-model="form.health_complaints" rows="3" required class="w-full px-3 py-2.5 border rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none resize-none" placeholder="Jelaskan keluhan atau kondisi kesehatan Anda saat ini..."></textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Kondisi Klinis / Diagnosis</label>
                <textarea x-model="form.clinical_details" rows="2" class="w-full px-3 py-2.5 border rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none resize-none"></textarea>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">BMI (opsional)</label>
                    <input type="number" step="0.1" x-model="form.bmi" class="w-full px-3 py-2.5 border rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Tingkat Keyakinan (1-10) <span class="text-red-500">*</span></label>
                    <input type="number" x-model="form.confidence_level" min="1" max="10" required class="w-full px-3 py-2.5 border rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Kondisi Emosi <span class="text-red-500">*</span></label>
                <textarea x-model="form.emotion_state" rows="2" required class="w-full px-3 py-2.5 border rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none resize-none"></textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Alergi Makanan <span class="text-red-500">*</span></label>
                <input type="text" x-model="form.food_allergies" required class="w-full px-3 py-2.5 border rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none" placeholder="Tidak ada / sebutkan">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Riwayat Pengobatan <span class="text-red-500">*</span></label>
                <textarea x-model="form.treatment_history" rows="2" required class="w-full px-3 py-2.5 border rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none resize-none"></textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Obat yang Sedang Dikonsumsi <span class="text-red-500">*</span></label>
                <input type="text" x-model="form.current_meds" required class="w-full px-3 py-2.5 border rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none" placeholder="Tidak ada / sebutkan">
            </div>
        </div>

        {{-- Error --}}
        <div x-show="error" class="p-4 bg-red-50 border border-red-200 rounded-xl text-sm text-red-700" x-text="error"></div>

        {{-- Turnstile --}}
        @if(!empty($settings['turnstile_site_key']))
        <div class="cf-turnstile" data-sitekey="{{ $settings['turnstile_site_key'] }}"></div>
        @endif

        <button type="submit" :disabled="loading"
                class="w-full py-3.5 bg-emerald-600 text-white font-bold text-lg rounded-xl hover:bg-emerald-700 disabled:opacity-50 disabled:cursor-not-allowed transition">
            <span x-show="!loading">Kirim Pendaftaran</span>
            <span x-show="loading">Mengirim...</span>
        </button>
    </form>
</div>
@endsection

@push('scripts')
<script>
function registrationForm() {
    return {
        submitted: false,
        loading: false,
        error: '',
        regCode: '',
        form: {
            full_name: '', birth_date: '', occupation: '', whatsapp: '', address: '',
            height_weight: '', program_period_id: '',
            health_complaints: '', clinical_details: '', bmi: '',
            emotion_state: '', food_allergies: '', treatment_history: '',
            current_meds: '', confidence_level: '',
        },
        async submit() {
            this.error = '';
            this.loading = true;
            try {
                const payload = { ...this.form };
                const turnstile = document.querySelector('[name="cf-turnstile-response"]');
                if (turnstile) payload['cf-turnstile-response'] = turnstile.value;

                const res = await fetch('{{ route('daftar.store') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify(payload),
                });
                const data = await res.json();
                if (data.success) {
                    this.regCode = data.code;
                    this.submitted = true;
                } else {
                    this.error = data.error || 'Terjadi kesalahan.';
                }
            } catch(e) {
                this.error = 'Terjadi kesalahan. Silakan coba lagi.';
            }
            this.loading = false;
        }
    }
}
</script>
@endpush
