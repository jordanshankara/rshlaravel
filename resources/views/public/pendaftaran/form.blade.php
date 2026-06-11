@extends('layouts.public')
@section('title', 'Daftar Program Pemulihan Sehat Raga & Jiwa — RSH Satu Bumi')

@push('head')
@if(!empty($settings['turnstile_site_key']))
<script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
@endif
@endpush

@section('content')

{{-- Hero --}}
<section class="relative overflow-hidden py-28 px-4 text-center">
    <img src="{{ asset('assets/latihan/yoga.jpg') }}" alt="" class="absolute inset-0 w-full h-full object-cover object-center">
    <div class="absolute inset-0" style="background:linear-gradient(to bottom,rgba(5,30,15,0.90),rgba(10,50,25,0.84))"></div>
    <div class="relative z-10">
        <span class="inline-block border border-[#f97316]/60 text-[#f97316] text-xs font-bold px-4 py-1.5 rounded-full uppercase tracking-widest mb-5">Program Eksklusif</span>
        <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-white mb-4">Program Pemulihan Sehat Raga &amp; Jiwa</h1>
        <p class="text-green-200 max-w-lg mx-auto text-sm leading-relaxed">
            Mohon luangkan 3 menit untuk melengkapi data di bawah ini. Informasi Anda bersifat rahasia<br class="hidden sm:block">
            dan akan membantu tim ahli kami merancang pendekatan yang paling tepat untuk Anda.
        </p>
    </div>
</section>

{{-- Body --}}
<section class="py-10 px-4 bg-gray-50 min-h-screen">
<div class="max-w-2xl mx-auto" x-data="registrationForm()">

{{-- ═══════════════════════════════ SUCCESS STATE ═══════════════════════════════ --}}
<div x-show="submitted" x-cloak class="space-y-4">

    {{-- Header card --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 text-center">
        <div class="w-16 h-16 rounded-full bg-green-100 flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-[#1a6b2f]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
            </svg>
        </div>
        <h2 class="text-xl font-bold text-gray-900 mb-1">Data Berhasil Diterima!</h2>
        <p class="text-gray-500 text-sm">Halo, <span class="font-semibold text-gray-800" x-text="fullName"></span>. Lanjutkan ke langkah pembayaran di bawah.</p>
    </div>

    {{-- Langkah Pembayaran --}}
    <div class="rounded-2xl border border-amber-200 p-5" style="background:#fffbeb">
        <div class="flex items-center gap-2 mb-4">
            <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            <span class="font-bold text-amber-800 text-sm">Langkah Pembayaran</span>
        </div>
        <ol class="space-y-3 text-sm text-gray-700 mb-5">
            <li class="flex items-start gap-3">
                <span class="w-6 h-6 rounded-full bg-amber-500 text-white text-xs font-bold flex items-center justify-center flex-shrink-0 mt-0.5">1</span>
                <div>
                    Transfer DP sebesar <strong x-text="invoice ? formatRupiah(invoice.total_amount) : ''"></strong> ke rekening:
                    <div class="mt-1.5 space-y-0.5">
                        <div>Bank: <strong x-text="paymentDetail ? paymentDetail.bank_name : '-'"></strong></div>
                        <div>No. Rekening: <strong x-text="paymentDetail ? paymentDetail.account_number : '-'"></strong></div>
                        <div>Atas Nama: <strong x-text="paymentDetail ? paymentDetail.account_name : '-'"></strong></div>
                    </div>
                </div>
            </li>
            <li class="flex items-start gap-3">
                <span class="w-6 h-6 rounded-full bg-amber-500 text-white text-xs font-bold flex items-center justify-center flex-shrink-0 mt-0.5">2</span>
                <span>Kirimkan bukti transfer ke WhatsApp admin beserta kode pendaftaran Anda.</span>
            </li>
            <li class="flex items-start gap-3">
                <span class="w-6 h-6 rounded-full bg-amber-500 text-white text-xs font-bold flex items-center justify-center flex-shrink-0 mt-0.5">3</span>
                <span>Admin akan mengkonfirmasi dan mengirimkan <strong>kode konfirmasi</strong> kepada Anda.</span>
            </li>
        </ol>
        <a :href="waLink" target="_blank" rel="noopener noreferrer"
           class="flex items-center justify-center gap-2 w-full py-3 bg-[#1a6b2f] text-white font-semibold rounded-xl hover:bg-[#0d3d1a] transition-colors text-sm">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
            </svg>
            Kirim Bukti Pembayaran via WhatsApp
        </a>
    </div>

    {{-- Confirm code --}}
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
        <div x-show="!confirmSuccess">
            <h3 class="font-bold text-gray-900 mb-1">Sudah Dapat Kode dari CS?</h3>
            <p class="text-xs text-gray-500 mb-4">Masukkan kode yang dikirimkan CS setelah pembayaran DP dikonfirmasi.</p>
            <div class="flex gap-2">
                <input type="text" x-model="confirmCode" placeholder="MASUKKAN KODE DARI CS"
                       class="flex-1 px-3 py-2.5 border border-gray-300 rounded-lg text-sm uppercase tracking-widest placeholder-gray-300 focus:ring-2 focus:ring-[#1a6b2f]/30 focus:border-[#1a6b2f] focus:outline-none">
                <button @click="submitConfirm()" :disabled="confirmLoading || !confirmCode.trim()"
                        class="px-4 py-2.5 bg-[#1a6b2f] text-white font-semibold rounded-lg text-sm hover:bg-[#0d3d1a] disabled:opacity-50 transition">
                    <span x-show="!confirmLoading">Daftar</span>
                    <span x-show="confirmLoading">...</span>
                </button>
            </div>
            <p x-show="confirmError" class="mt-2 text-xs text-red-600" x-text="confirmError"></p>
        </div>
        <div x-show="confirmSuccess" class="text-center py-2 space-y-3">
            <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center mx-auto mb-3">
                <svg class="w-5 h-5 text-[#1a6b2f]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <p class="font-bold text-[#0d3d1a] text-sm">Pembayaran Dikonfirmasi!</p>
            <p class="text-xs text-gray-500">Pendaftaran Anda telah dikonfirmasi. Berikut invoice Anda:</p>
            <a :href="'/daftar/invoice/' + (regCode || confirmCode)" target="_blank" rel="noopener noreferrer"
               class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-red-500 text-white text-sm font-semibold rounded-lg hover:bg-red-600 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                Unduh Invoice PDF
            </a>
        </div>
    </div>

    {{-- Bottom links --}}
    <div class="text-center space-y-3 pb-4">
        <div>
            <button @click="showFormData = !showFormData" class="text-sm text-[#1a6b2f] hover:underline">
                <span x-text="showFormData ? 'Sembunyikan data ↑' : 'Lihat data yang saya isi →'"></span>
            </button>
        </div>
        <div x-show="showFormData" class="bg-white rounded-xl border border-gray-100 p-4 text-left text-xs space-y-1.5 text-gray-600">
            <p><span class="font-semibold text-gray-800">Nama:</span> <span x-text="form.full_name"></span></p>
            <p><span class="font-semibold text-gray-800">Tanggal Lahir:</span> <span x-text="form.birth_date"></span></p>
            <p><span class="font-semibold text-gray-800">WhatsApp:</span> <span x-text="form.whatsapp"></span></p>
            <p><span class="font-semibold text-gray-800">Keluhan:</span> <span x-text="form.health_complaints"></span></p>
        </div>
        {{-- Prominent reset button for new registration --}}
        <div class="pt-1">
            <button @click="resetForm()"
                    class="w-full py-3 border-2 border-gray-300 text-gray-600 font-semibold rounded-xl hover:border-[#1a6b2f] hover:text-[#1a6b2f] hover:bg-green-50 transition-all text-sm">
                ↺ Isi Ulang Form (untuk mendaftarkan orang lain)
            </button>
        </div>
    </div>
</div>

{{-- ═══════════════════════════════ FORM ═══════════════════════════════ --}}
<form x-show="!submitted" x-cloak @submit.prevent="submit()" class="space-y-5">

    {{-- ── SECTION 1: Data Pribadi ── --}}
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 space-y-4">
        <h2 class="font-bold text-gray-900 flex items-center gap-2">
            <span class="text-base">🧑</span> Data Pribadi
        </h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                <input type="text" x-model="form.full_name" required placeholder="Nama sesuai KTP"
                       class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#1a6b2f]/30 focus:border-[#1a6b2f] focus:outline-none">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Lahir <span class="text-red-500">*</span></label>
                <input type="date" x-model="form.birth_date" required
                       class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#1a6b2f]/30 focus:border-[#1a6b2f] focus:outline-none">
            </div>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Pekerjaan / Profesi Saat Ini <span class="text-red-500">*</span></label>
                <p class="text-xs text-[#1a6b2f] mb-1.5">Membantu kami memahami ritme aktivitas harian dan tingkat stres Anda.</p>
                <input type="text" x-model="form.occupation" required placeholder="Contoh: Wiraswasta, Ibu Rumah Tangga"
                       class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#1a6b2f]/30 focus:border-[#1a6b2f] focus:outline-none">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nomor WhatsApp Aktif <span class="text-red-500">*</span></label>
                <div class="flex">
                    <select x-model="countryCode" class="px-2 py-2.5 border border-r-0 border-gray-300 rounded-l-lg text-sm bg-gray-50 focus:outline-none text-gray-600">
                        <option value="62">ID +62</option>
                        <option value="60">MY +60</option>
                        <option value="65">SG +65</option>
                        <option value="61">AU +61</option>
                    </select>
                    <input type="tel" x-model="waNumber" required placeholder="81234567890"
                           inputmode="numeric"
                           @keydown="if(!['Backspace','Delete','Tab','ArrowLeft','ArrowRight','Home','End'].includes($event.key) && !/^\d$/.test($event.key) && !$event.ctrlKey && !$event.metaKey) $event.preventDefault()"
                           @input="waNumber = $event.target.value.replace(/\D/g,'')"
                           class="flex-1 px-3 py-2.5 border border-gray-300 rounded-r-lg text-sm focus:ring-2 focus:ring-[#1a6b2f]/30 focus:border-[#1a6b2f] focus:outline-none">
                </div>
                <p class="text-[11px] text-gray-400 mt-1">Hanya angka. Contoh: 81234567890 (tanpa awalan 0 atau 62)</p>
            </div>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tinggi Badan <span class="text-red-500">*</span></label>
                <div class="relative">
                    <input type="number" x-model="heightVal" @input="computeHeightWeight()" min="100" max="250" required
                           class="w-full px-3 py-2.5 pr-10 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#1a6b2f]/30 focus:border-[#1a6b2f] focus:outline-none">
                    <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs">cm</span>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Berat Badan <span class="text-red-500">*</span></label>
                <div class="relative">
                    <input type="number" x-model="weightVal" @input="computeHeightWeight()" min="20" max="300" required
                           class="w-full px-3 py-2.5 pr-10 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#1a6b2f]/30 focus:border-[#1a6b2f] focus:outline-none">
                    <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs">kg</span>
                </div>
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Domisili <span class="text-red-500">*</span></label>
            <textarea x-model="form.address" rows="2" required placeholder="Alamat lengkap tempat tinggal saat ini"
                      class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#1a6b2f]/30 focus:border-[#1a6b2f] focus:outline-none resize-none"></textarea>
        </div>
    </div>

    {{-- ── SECTION 2: Pilih Periode ── --}}
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
        <h2 class="font-bold text-gray-900 flex items-center gap-2 mb-4">
            <span class="text-base">📅</span> Pilih Periode Program
        </h2>
        @forelse($periods as $period)
        @php $available = $period->quota - $period->filled; $full = $available <= 0; @endphp
        <label class="block border rounded-xl p-4 mb-3 last:mb-0 transition-all cursor-pointer {{ $full ? 'cursor-not-allowed opacity-60' : 'hover:border-[#1a6b2f]' }}"
               :class="form.program_period_id == '{{ $period->id }}' ? 'border-[#1a6b2f] bg-green-50/60' : 'border-gray-200 bg-white'">
            <div class="flex items-start gap-3">
                <input type="radio" x-model="form.program_period_id" value="{{ $period->id }}"
                       {{ $full ? 'disabled' : '' }} class="mt-1 text-[#1a6b2f] focus:ring-[#1a6b2f]">
                <div class="flex-1">
                    <div class="font-semibold {{ $full ? 'text-gray-400' : 'text-gray-900' }} text-sm">{{ $period->name }}</div>
                    <div class="text-xs text-gray-500 mt-0.5">{{ $period->start_date->format('d M Y') }} – {{ $period->end_date->format('d M Y') }}</div>
                    <div class="text-sm font-bold text-[#1a6b2f] mt-1">
                        Rp {{ number_format($period->price, 0, ',', '.') }}
                        <span class="text-xs text-gray-500 font-normal">– DP Rp {{ number_format($period->dp_amount, 0, ',', '.') }}</span>
                    </div>
                    <div class="mt-1 text-xs {{ $full ? 'text-[#f97316] font-medium' : 'text-gray-500' }}">
                        @if($full) Kuota penuh
                        @else Sisa {{ $available }} dari {{ $period->quota }} tempat
                        @endif
                    </div>
                </div>
            </div>
        </label>
        @empty
        <p class="text-gray-400 text-sm text-center py-4">Tidak ada periode aktif saat ini. Hubungi kami untuk informasi lebih lanjut.</p>
        @endforelse
    </div>

    {{-- ── SECTION 3: Data Kesehatan ── --}}
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 space-y-5">
        <h2 class="font-bold text-gray-900 flex items-center gap-2">
            <span class="text-base">🏥</span> Data Kesehatan
        </h2>

        {{-- Complaints checkboxes --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-3">Keluhan Kesehatan Utama <span class="text-red-500">*</span></label>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-2 gap-x-4">
                @php
                $clinicalMap = [
                    'Diabetes / Gula Darah Tinggi'      => ['model' => 'bloodSugar',    'label' => 'Gula Darah Puasa',       'placeholder' => 'Contoh: 150',    'unit' => 'mg/dL'],
                    'Hipertensi / Tekanan Darah Tinggi' => ['model' => 'bloodPressure', 'label' => 'Tekanan Darah',          'placeholder' => 'Contoh: 140/90', 'unit' => 'mmHg'],
                    'Kolesterol Tinggi'                 => ['model' => 'cholesterol',   'label' => 'Kadar Kolesterol Total', 'placeholder' => 'Contoh: 220',    'unit' => 'mg/dL'],
                    'Asam Urat'                         => ['model' => 'uricAcid',      'label' => 'Kadar Asam Urat',        'placeholder' => 'Contoh: 8.5',    'unit' => 'mg/dL'],
                ];
                @endphp
                @foreach([
                    'Diabetes / Gula Darah Tinggi',
                    'Hipertensi / Tekanan Darah Tinggi',
                    'Kolesterol Tinggi',
                    'Asam Urat',
                    'Obesitas / Kelebihan Berat Badan',
                    'Gangguan Pencernaan (Konstipasi / BAB tidak lancar)',
                    'Asam Lambung / GERD',
                    'Insomnia / Gangguan Tidur',
                    'Kelelahan Kronis',
                    'Nyeri Sendi / Otot',
                ] as $complaint)
                <div class="flex flex-col">
                    <label class="flex items-start gap-2 cursor-pointer group">
                        <input type="checkbox" value="{{ $complaint }}" x-model="selectedComplaints"
                               @change="onComplaintToggle('{{ $complaint }}', $event.target.checked)"
                               class="mt-0.5 rounded border-gray-300 text-[#1a6b2f] focus:ring-[#1a6b2f] flex-shrink-0">
                        <span class="text-sm text-gray-700 group-hover:text-gray-900 leading-snug">{{ $complaint }}</span>
                    </label>
                    @if(isset($clinicalMap[$complaint]))
                    @php $c = $clinicalMap[$complaint]; @endphp
                    <div x-show="selectedComplaints.includes('{{ $complaint }}')" x-cloak class="ml-6 mt-2 mb-1">
                        <label class="block text-xs text-gray-500 mb-1">{{ $c['label'] }} <span class="text-gray-400">— isi jika tahu</span></label>
                        <div class="flex items-center gap-2">
                            <input type="text" x-model="{{ $c['model'] }}" placeholder="{{ $c['placeholder'] }}"
                                   class="flex-1 px-3 py-1.5 border border-gray-300 rounded-md text-sm focus:ring-2 focus:ring-[#1a6b2f]/30 focus:border-[#1a6b2f] focus:outline-none">
                            <span class="text-xs text-gray-400 whitespace-nowrap">{{ $c['unit'] }}</span>
                        </div>
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
            <div class="mt-3">
                <label class="block text-xs text-gray-500 mb-1">Lainnya:</label>
                <textarea x-model="otherComplaints" rows="2" placeholder="Sebutkan keluhan lain yang tidak ada di atas..."
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#1a6b2f]/30 focus:border-[#1a6b2f] focus:outline-none resize-none"></textarea>
            </div>
        </div>

        {{-- Kondisi Emosi --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Kondisi Emosi yang Dominan Akhir-Akhir Ini <span class="text-red-500">*</span></label>
            <p class="text-xs text-gray-400 mb-1.5">Apakah ada rasa takut berlebih, cemas, stres pekerjaan, atau sulit tidur? Ceritakan dengan nyaman.</p>
            <textarea x-model="form.emotion_state" rows="3" required placeholder="Ceritakan kondisi emosi Anda..."
                      class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#1a6b2f]/30 focus:border-[#1a6b2f] focus:outline-none resize-none"></textarea>
        </div>

        {{-- Alergi --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Alergi atau Pantangan Makanan <span class="text-red-500">*</span></label>
            <p class="text-xs text-gray-400 mb-1.5">Misal: kacang, gluten, susu, dll. Ketik 'tidak ada' jika tidak ada.</p>
            <input type="text" x-model="form.food_allergies" required placeholder="Sebutkan atau ketik 'Tidak ada'"
                   class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#1a6b2f]/30 focus:border-[#1a6b2f] focus:outline-none">
        </div>

        {{-- Riwayat Pengobatan --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Riwayat Pengobatan / Terapi di Tempat Lain <span class="text-red-500">*</span></label>
            <p class="text-xs text-gray-400 mb-1.5">Pernah menjalani pengobatan klinis, terapi, atau diet khusus?</p>
            <textarea x-model="form.treatment_history" rows="2" required placeholder="Sebutkan atau ketik 'Belum pernah'"
                      class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#1a6b2f]/30 focus:border-[#1a6b2f] focus:outline-none resize-none"></textarea>
        </div>

        {{-- Obat / Suplemen --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Konsumsi Obat / Suplemen Saat Ini <span class="text-red-500">*</span></label>
            <p class="text-xs text-gray-400 mb-1.5">Sebutkan obat-obatan medis atau herbal yang rutin dikonsumsi beserta durasinya.</p>
            <textarea x-model="form.current_meds" rows="2" required placeholder="Sebutkan atau ketik 'Tidak ada'"
                      class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#1a6b2f]/30 focus:border-[#1a6b2f] focus:outline-none resize-none"></textarea>
        </div>

        {{-- Confidence level --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-3">Seberapa Yakin Anda Pulih Setelah Mengikuti Program Ini? <span class="text-red-500">*</span></label>
            <div class="flex gap-2">
                @foreach([1,2,3,4,5] as $level)
                <button type="button" @click="form.confidence_level = {{ $level }}"
                        class="w-11 h-11 rounded-full border-2 font-bold text-sm transition-all"
                        :class="form.confidence_level == {{ $level }}
                            ? 'border-[#1a6b2f] bg-[#1a6b2f] text-white'
                            : 'border-gray-300 text-gray-500 hover:border-[#1a6b2f] hover:text-[#1a6b2f]'">
                    {{ $level }}
                </button>
                @endforeach
            </div>
            <p class="text-xs text-gray-400 mt-2">1 = Kurang yakin · 5 = Sangat yakin</p>
        </div>
    </div>

    {{-- Error message --}}
    <div x-show="error" x-cloak class="p-4 bg-red-50 border border-red-200 rounded-xl text-sm text-red-700" x-text="error"></div>

    {{-- Turnstile --}}
    @if(!empty($settings['turnstile_site_key']))
    <div class="cf-turnstile" data-sitekey="{{ $settings['turnstile_site_key'] }}"></div>
    @endif

    {{-- Submit --}}
    <button type="submit" :disabled="loading || !form.program_period_id || !form.confidence_level || selectedComplaints.length === 0"
            class="w-full py-4 bg-[#1a6b2f] text-white font-bold text-base rounded-xl hover:bg-[#0d3d1a] disabled:opacity-50 disabled:cursor-not-allowed transition-all shadow-lg">
        <span x-show="!loading">Daftar Sekarang</span>
        <span x-show="loading" class="flex items-center justify-center gap-2">
            <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
            </svg>
            Mengirim...
        </span>
    </button>

</form>

{{-- ═══════════════════════════════ CONFIRM MODAL ═══════════════════════════════ --}}
<div x-show="showConfirm" x-cloak
     class="fixed inset-0 z-50 flex items-center justify-center p-4"
     style="background:rgba(0,0,0,0.55)">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b border-gray-100">
            <h3 class="font-bold text-gray-900 text-base">Periksa Data Sebelum Daftar</h3>
            <p class="text-xs text-gray-400 mt-1">Setelah dikirim, data tidak dapat diubah. Pastikan sudah benar.</p>
        </div>
        <div class="p-6 space-y-3 text-sm">
            <div class="grid grid-cols-2 gap-x-4 gap-y-2">
                <span class="text-gray-500">Nama</span>
                <span class="font-medium text-gray-900" x-text="form.full_name || '-'"></span>
                <span class="text-gray-500">Tanggal Lahir</span>
                <span class="font-medium text-gray-900" x-text="form.birth_date || '-'"></span>
                <span class="text-gray-500">WhatsApp</span>
                <span class="font-medium text-gray-900" x-text="(countryCode && waNumber) ? '+' + countryCode + waNumber : '-'"></span>
                <span class="text-gray-500">Tinggi / Berat</span>
                <span class="font-medium text-gray-900" x-text="form.height_weight || '-'"></span>
                <span class="text-gray-500">Pekerjaan</span>
                <span class="font-medium text-gray-900" x-text="form.occupation || '-'"></span>
                <span class="text-gray-500">Periode</span>
                <span class="font-medium text-gray-900" x-text="confirmPeriodName"></span>
            </div>
            <div class="pt-2 border-t border-gray-100">
                <span class="text-gray-500">Keluhan</span>
                <p class="font-medium text-gray-900 mt-1 text-xs leading-relaxed" x-text="selectedComplaints.join(', ') + (otherComplaints ? ', ' + otherComplaints : '')"></p>
            </div>
        </div>
        <div class="p-6 border-t border-gray-100 flex gap-3">
            <button type="button" @click="showConfirm = false"
                    class="flex-1 py-2.5 border border-gray-300 text-gray-700 font-semibold rounded-xl text-sm hover:bg-gray-50 transition">
                ← Kembali Edit
            </button>
            <button type="button" @click="doSubmit()" :disabled="loading"
                    class="flex-1 py-2.5 bg-[#1a6b2f] text-white font-bold rounded-xl text-sm hover:bg-[#0d3d1a] disabled:opacity-50 transition">
                <span x-show="!loading">Kirim Pendaftaran</span>
                <span x-show="loading">Mengirim...</span>
            </button>
        </div>
    </div>
</div>

</div>
</section>

@endsection

@push('scripts')
<script>
function registrationForm() {
    return {
        submitted: false,
        loading: false,
        error: '',
        showInvoice: true,
        showFormData: false,
        showConfirm: false,

        regCode: '',
        fullName: '',
        invoice: null,
        paymentDetail: null,
        period: null,

        confirmCode: '',
        confirmLoading: false,
        confirmError: '',
        confirmSuccess: false,

        countryCode: '62',
        waNumber: '',
        heightVal: '',
        weightVal: '',
        selectedComplaints: [],
        otherComplaints: '',
        bloodSugar: '',
        bloodPressure: '',
        cholesterol: '',
        uricAcid: '',

        clinicalTriggers: [
            { complaint: 'Diabetes / Gula Darah Tinggi',      field: 'bloodSugar',    label: 'Gula Darah Puasa',       placeholder: 'Contoh: 150',    unit: 'mg/dL' },
            { complaint: 'Hipertensi / Tekanan Darah Tinggi', field: 'bloodPressure', label: 'Tekanan Darah',          placeholder: 'Contoh: 140/90', unit: 'mmHg' },
            { complaint: 'Kolesterol Tinggi',                 field: 'cholesterol',   label: 'Kadar Kolesterol Total', placeholder: 'Contoh: 220',    unit: 'mg/dL' },
            { complaint: 'Asam Urat',                         field: 'uricAcid',      label: 'Kadar Asam Urat',        placeholder: 'Contoh: 8.5',    unit: 'mg/dL' },
        ],

        onComplaintToggle(complaint, checked) {
            if (!checked) {
                const t = this.clinicalTriggers.find(t => t.complaint === complaint);
                if (t) this[t.field] = '';
            }
        },

        form: {
            full_name: '', birth_date: '', occupation: '', whatsapp: '', address: '',
            height_weight: '', bmi: '', program_period_id: '',
            health_complaints: '', clinical_details: '-',
            emotion_state: '', food_allergies: '', treatment_history: '',
            current_meds: '', confidence_level: '',
        },

        get confirmPeriodName() {
            const id = this.form.program_period_id;
            if (!id) return '-';
            const el = document.querySelector('input[type="radio"][value="' + id + '"]');
            if (!el) return 'Periode #' + id;
            const label = el.closest('label');
            return label ? label.querySelector('.font-semibold')?.textContent?.trim() || '-' : '-';
        },

        get waLink() {
            const phone = '62816677225';
            if (!this.fullName) return 'http://api.whatsapp.com/send?phone=' + phone;
            const period = this.period ? this.period.name : 'Program Pemulihan Sehat Raga & Jiwa';
            const msg = `Halo Admin RSH Satu Bumi,\n\nSaya *${this.fullName}* ingin mengirimkan bukti pembayaran untuk *${period}*.\n\nTerima kasih.`;
            return 'http://api.whatsapp.com/send?phone=' + phone + '&text=' + encodeURIComponent(msg);
        },

        formatRupiah(amount) {
            return 'Rp ' + new Intl.NumberFormat('id-ID').format(amount);
        },

        computeHeightWeight() {
            const h = parseFloat(this.heightVal) || 0;
            const w = parseFloat(this.weightVal) || 0;
            this.form.height_weight = h + ' cm / ' + w + ' kg';
            if (h > 0 && w > 0) {
                this.form.bmi = (w / Math.pow(h / 100, 2)).toFixed(1);
            }
        },

        buildPayload() {
            const complaints = [...this.selectedComplaints];
            if (this.otherComplaints.trim()) {
                complaints.push('Lainnya: ' + this.otherComplaints.trim());
            }
            this.form.health_complaints = JSON.stringify(complaints);

            const parts = [];
            for (const t of this.clinicalTriggers) {
                const val = (this[t.field] || '').trim();
                if (val && this.selectedComplaints.includes(t.complaint)) {
                    parts.push(`${t.label}: ${val} ${t.unit}`);
                }
            }
            this.form.clinical_details = parts.length ? parts.join(', ') : '-';

            let wa = this.waNumber.replace(/\D/g, '');
            if (wa.startsWith('62')) wa = wa.slice(2);
            else if (wa.startsWith('0')) wa = wa.slice(1);
            this.form.whatsapp = this.countryCode + wa;
            this.computeHeightWeight();
        },

        saveDraft() {
            if (this.submitted) return;
            try {
                localStorage.setItem('rsh_draft', JSON.stringify({
                    form: this.form,
                    waNumber: this.waNumber,
                    countryCode: this.countryCode,
                    heightVal: this.heightVal,
                    weightVal: this.weightVal,
                    selectedComplaints: this.selectedComplaints,
                    otherComplaints: this.otherComplaints,
                    bloodSugar: this.bloodSugar,
                    bloodPressure: this.bloodPressure,
                    cholesterol: this.cholesterol,
                    uricAcid: this.uricAcid,
                }));
            } catch(e) {}
        },

        submit() {
            this.buildPayload();
            if (this.selectedComplaints.length === 0) {
                this.error = 'Pilih minimal satu keluhan kesehatan.';
                return;
            }
            this.error = '';
            this.showConfirm = true;
        },

        async doSubmit() {
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
                    this.regCode     = data.code;
                    this.fullName    = data.full_name;
                    this.invoice     = data.invoice;
                    this.paymentDetail = data.payment_detail;
                    this.period      = data.period;
                    this.submitted   = true;
                    this.showConfirm = false;
                    try {
                        localStorage.removeItem('rsh_draft');
                        localStorage.setItem('rsh_submitted', JSON.stringify({
                            regCode: this.regCode, fullName: this.fullName,
                            invoice: this.invoice, paymentDetail: this.paymentDetail,
                            period: this.period, form: this.form,
                        }));
                    } catch(e) {}
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                } else {
                    this.error = data.error || 'Terjadi kesalahan.';
                    this.showConfirm = false;
                }
            } catch(e) {
                this.error = 'Terjadi kesalahan jaringan. Silakan coba lagi.';
                this.showConfirm = false;
            }
            this.loading = false;
        },

        async submitConfirm() {
            if (!this.confirmCode.trim()) return;
            this.confirmError = '';
            this.confirmLoading = true;
            try {
                const res = await fetch('{{ route('daftar.konfirmasi') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ code: this.confirmCode.trim() }),
                });
                const data = await res.json();
                if (data.confirmed) {
                    this.confirmSuccess = true;
                } else {
                    this.confirmError = data.error || 'Kode tidak valid atau pembayaran belum dikonfirmasi oleh admin.';
                }
            } catch(e) {
                this.confirmError = 'Terjadi kesalahan. Silakan coba lagi.';
            }
            this.confirmLoading = false;
        },

        resetForm() {
            if (!confirm('Yakin ingin mengisi ulang dari awal? Semua data akan dihapus.')) return;
            try { localStorage.removeItem('rsh_submitted'); localStorage.removeItem('rsh_draft'); } catch(e) {}
            this.submitted = false;
            this.error = '';
            this.regCode = '';
            this.fullName = '';
            this.invoice = null;
            this.paymentDetail = null;
            this.period = null;
            this.selectedComplaints = [];
            this.otherComplaints = '';
            this.bloodSugar = '';
            this.bloodPressure = '';
            this.cholesterol = '';
            this.uricAcid = '';
            this.waNumber = '';
            this.confirmCode = '';
            this.confirmError = '';
            this.confirmSuccess = false;
            this.form = {
                full_name: '', birth_date: '', occupation: '', whatsapp: '', address: '',
                height_weight: '', bmi: '', program_period_id: '',
                health_complaints: '', clinical_details: '-',
                emotion_state: '', food_allergies: '', treatment_history: '',
                current_meds: '', confidence_level: '',
            };
        },

        init() {
            this.computeHeightWeight();
            try {
                const saved = localStorage.getItem('rsh_submitted');
                if (saved) {
                    const s = JSON.parse(saved);
                    this.regCode       = s.regCode || '';
                    this.fullName      = s.fullName || '';
                    this.invoice       = s.invoice || null;
                    this.paymentDetail = s.paymentDetail || null;
                    this.period        = s.period || null;
                    this.form          = Object.assign(this.form, s.form || {});
                    this.submitted     = true;
                }
            } catch(e) {}

            if (!this.submitted) {
                try {
                    const draft = localStorage.getItem('rsh_draft');
                    if (draft) {
                        const d = JSON.parse(draft);
                        this.form              = Object.assign(this.form, d.form || {});
                        this.waNumber          = d.waNumber || '';
                        this.countryCode       = d.countryCode || '62';
                        this.heightVal         = d.heightVal || '';
                        this.weightVal         = d.weightVal || '';
                        this.selectedComplaints = d.selectedComplaints || [];
                        this.otherComplaints   = d.otherComplaints || '';
                        this.bloodSugar        = d.bloodSugar || '';
                        this.bloodPressure     = d.bloodPressure || '';
                        this.cholesterol       = d.cholesterol || '';
                        this.uricAcid          = d.uricAcid || '';
                        this.computeHeightWeight();
                    }
                } catch(e) {}

                this.$watch('form', () => this.saveDraft(), { deep: true });
                this.$watch('waNumber', () => this.saveDraft());
                this.$watch('countryCode', () => this.saveDraft());
                this.$watch('heightVal', () => this.saveDraft());
                this.$watch('weightVal', () => this.saveDraft());
                this.$watch('selectedComplaints', () => this.saveDraft(), { deep: true });
                this.$watch('otherComplaints', () => this.saveDraft());
                this.$watch('bloodSugar', () => this.saveDraft());
                this.$watch('bloodPressure', () => this.saveDraft());
                this.$watch('cholesterol', () => this.saveDraft());
                this.$watch('uricAcid', () => this.saveDraft());
            }
        }
    }
}
</script>
@endpush
