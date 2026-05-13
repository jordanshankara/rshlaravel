@extends('layouts.public')
@section('title', 'Daftar Program — RSH Satu Bumi')

@push('head')
@if(!empty($settings['turnstile_site_key']))
<script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
@endif
@endpush

@section('content')

{{-- Page Header --}}
<div class="relative h-48 overflow-hidden">
    <img src="{{ asset('assets/green.webp') }}" alt="" class="absolute inset-0 w-full h-full object-cover">
    <div class="absolute inset-0" style="background: linear-gradient(to bottom, rgba(5,46,22,0.75), rgba(13,61,26,0.85))"></div>
    <div class="relative h-full flex flex-col items-center justify-center text-center px-4">
        <span class="text-[#f97316] uppercase tracking-widest text-xs font-bold mb-3">PROGRAM 7 HARI</span>
        <h1 class="text-3xl font-bold text-white">Daftar Program</h1>
    </div>
</div>

<div class="max-w-2xl mx-auto px-4 py-12" x-data="registrationForm()">
    <p class="text-gray-500 text-center mb-8">Isi formulir di bawah untuk mendaftarkan diri ke Program 7 Hari Menuju Sehat Raga &amp; Jiwa.</p>

    {{-- Success state --}}
    <div x-show="submitted" x-cloak class="glass-card rounded-2xl p-8 text-center border-green-200/50">
        <div class="text-5xl mb-4">✅</div>
        <h2 class="text-2xl font-bold text-[#0d3d1a] mb-2">Pendaftaran Berhasil!</h2>
        <p class="text-gray-600 mb-4">Kode pendaftaran Anda:</p>
        <div class="text-3xl font-mono font-bold text-[#1a6b2f] bg-white border-2 border-[#1a6b2f]/30 rounded-xl px-8 py-4 inline-block mb-6" x-text="regCode"></div>
        <p class="text-sm text-gray-500 mb-6">Simpan kode ini untuk melacak status pendaftaran Anda.</p>
        <div class="flex gap-3 justify-center">
            <a href="{{ route('daftar.confirm') }}" class="px-6 py-2.5 border border-[#1a6b2f] text-[#1a6b2f] font-semibold rounded-xl hover:bg-green-50 transition">Cek Status</a>
            <a href="{{ route('home') }}" class="px-6 py-2.5 bg-[#1a6b2f] text-white font-semibold rounded-xl hover:bg-[#0d3d1a] transition">Kembali ke Beranda</a>
        </div>
    </div>

    {{-- Form --}}
    <form x-show="!submitted" @submit.prevent="submit()" class="space-y-6">
        {{-- Data Pribadi --}}
        <div class="glass-card rounded-2xl p-6 space-y-4">
            <h2 class="font-bold text-lg text-[#0d3d1a]">Data Pribadi</h2>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label>
                <input type="text" x-model="form.full_name" required class="input">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Tanggal Lahir <span class="text-red-500">*</span></label>
                    <input type="date" x-model="form.birth_date" required class="input">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Pekerjaan <span class="text-red-500">*</span></label>
                    <input type="text" x-model="form.occupation" required class="input">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">WhatsApp <span class="text-red-500">*</span></label>
                    <input type="text" x-model="form.whatsapp" required class="input" placeholder="628xxxx">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">TB/BB <span class="text-red-500">*</span></label>
                    <input type="text" x-model="form.height_weight" required class="input" placeholder="170cm / 65kg">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Alamat <span class="text-red-500">*</span></label>
                <textarea x-model="form.address" rows="2" required class="input" style="resize:none"></textarea>
            </div>
        </div>

        {{-- Pilih Periode --}}
        <div class="glass-card rounded-2xl p-6">
            <h2 class="font-bold text-lg text-[#0d3d1a] mb-4">Pilih Periode Program</h2>
            @forelse($periods as $period)
            @php $available = $period->quota - $period->filled; @endphp
            <label class="flex items-start gap-3 p-4 border rounded-xl cursor-pointer hover:border-[#1a6b2f] transition-colors mb-3 last:mb-0 bg-white/60"
                   :class="form.program_period_id == {{ $period->id }} ? 'border-[#1a6b2f] bg-green-50/80' : 'border-gray-200'">
                <input type="radio" x-model="form.program_period_id" value="{{ $period->id }}"
                       {{ $available <= 0 ? 'disabled' : '' }}
                       class="mt-1 text-[#1a6b2f] focus:ring-[#1a6b2f]">
                <div class="flex-1">
                    <div class="font-semibold {{ $available <= 0 ? 'text-gray-400' : 'text-[#0d3d1a]' }}">{{ $period->name }}</div>
                    <div class="text-sm text-gray-500">{{ $period->start_date->format('d M Y') }} – {{ $period->end_date->format('d M Y') }}</div>
                    <div class="text-sm font-bold text-[#1a6b2f] mt-1">Rp {{ number_format($period->price, 0, ',', '.') }}
                        <span class="text-xs text-gray-500 font-normal">· DP Rp {{ number_format($period->dp_amount, 0, ',', '.') }}</span>
                    </div>
                </div>
                <div class="text-right">
                    @if($available > 0)
                    <span class="text-xs text-[#1a6b2f] font-medium">{{ $available }} kursi</span>
                    @else
                    <span class="text-xs text-red-500 font-medium">Penuh</span>
                    @endif
                </div>
            </label>
            @empty
            <p class="text-gray-400 text-sm">Tidak ada periode aktif saat ini.</p>
            @endforelse
        </div>

        {{-- Data Kesehatan --}}
        <div class="glass-card rounded-2xl p-6 space-y-4">
            <h2 class="font-bold text-lg text-[#0d3d1a]">Data Kesehatan</h2>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Keluhan Kesehatan <span class="text-red-500">*</span></label>
                <textarea x-model="form.health_complaints" rows="3" required class="input" style="resize:none"
                          placeholder="Jelaskan keluhan atau kondisi kesehatan Anda saat ini..."></textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Kondisi Klinis / Diagnosis</label>
                <textarea x-model="form.clinical_details" rows="2" class="input" style="resize:none"></textarea>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">BMI (opsional)</label>
                    <input type="number" step="0.1" x-model="form.bmi" class="input">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Tingkat Keyakinan (1-10) <span class="text-red-500">*</span></label>
                    <input type="number" x-model="form.confidence_level" min="1" max="10" required class="input">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Kondisi Emosi <span class="text-red-500">*</span></label>
                <textarea x-model="form.emotion_state" rows="2" required class="input" style="resize:none"></textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Alergi Makanan <span class="text-red-500">*</span></label>
                <input type="text" x-model="form.food_allergies" required class="input" placeholder="Tidak ada / sebutkan">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Riwayat Pengobatan <span class="text-red-500">*</span></label>
                <textarea x-model="form.treatment_history" rows="2" required class="input" style="resize:none"></textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Obat yang Sedang Dikonsumsi <span class="text-red-500">*</span></label>
                <input type="text" x-model="form.current_meds" required class="input" placeholder="Tidak ada / sebutkan">
            </div>
        </div>

        {{-- Error --}}
        <div x-show="error" class="p-4 bg-red-50 border border-red-200 rounded-xl text-sm text-red-700" x-text="error"></div>

        {{-- Turnstile --}}
        @if(!empty($settings['turnstile_site_key']))
        <div class="cf-turnstile" data-sitekey="{{ $settings['turnstile_site_key'] }}"></div>
        @endif

        <button type="submit" :disabled="loading"
                class="w-full py-3.5 bg-[#1a6b2f] text-white font-bold text-lg rounded-xl hover:bg-[#0d3d1a] disabled:opacity-50 disabled:cursor-not-allowed transition">
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
