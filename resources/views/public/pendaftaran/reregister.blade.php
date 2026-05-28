<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Ulang — RSH Satu Bumi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-50 min-h-screen py-8 px-4">
<div class="max-w-2xl mx-auto">

    {{-- Welcome banner --}}
    <div class="bg-[#2d6a4f] text-white rounded-2xl p-5 mb-6">
        <p class="text-xs text-green-200 mb-1">Rumah Sehat Holistik Satu Bumi</p>
        <h1 class="text-lg font-bold">Selamat datang kembali, {{ $registration->full_name }}! 🙏</h1>
        <p class="text-sm text-green-200 mt-1">
            Silakan lengkapi data untuk mendaftarkan diri di periode program baru.
            Data pribadi Anda sudah terisi otomatis — hanya data kesehatan yang perlu diisi ulang.
        </p>
    </div>

    @if ($errors->any())
    <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-xl text-sm text-red-700">
        @foreach ($errors->all() as $error)
        <p>• {{ $error }}</p>
        @endforeach
    </div>
    @endif

    <form method="POST" action="{{ route('daftar.reregister.store', $reregToken->token) }}"
          x-data="reregForm()" @submit.prevent="submitForm">
        @csrf

        <div class="space-y-5">

            {{-- Personal data (read-only) --}}
            <div class="bg-white rounded-xl border shadow-sm p-5">
                <h2 class="text-sm font-semibold text-gray-700 mb-4">Data Pribadi <span class="text-xs font-normal text-gray-400">(terisi otomatis)</span></h2>
                <div class="grid grid-cols-2 gap-4 text-sm text-gray-600">
                    <div><p class="text-xs text-gray-400">Nama</p><p class="font-medium text-gray-900">{{ $registration->full_name }}</p></div>
                    <div><p class="text-xs text-gray-400">WhatsApp</p><p>{{ $registration->whatsapp }}</p></div>
                    <div><p class="text-xs text-gray-400">Tgl Lahir</p><p>{{ $registration->birth_date?->format('d M Y') }}</p></div>
                    <div><p class="text-xs text-gray-400">Pekerjaan</p><p>{{ $registration->occupation }}</p></div>
                </div>
            </div>

            {{-- Period selection --}}
            <div class="bg-white rounded-xl border shadow-sm p-5">
                <h2 class="text-sm font-semibold text-gray-700 mb-3">Pilih Periode Program <span class="text-red-500">*</span></h2>
                @if ($periods->isEmpty())
                <p class="text-sm text-red-500">Tidak ada periode aktif yang tersedia saat ini. Hubungi admin.</p>
                @else
                <div class="space-y-2">
                    @foreach ($periods as $period)
                    <label class="flex items-start gap-3 p-3 border rounded-xl cursor-pointer hover:border-[#2d6a4f] transition-colors has-[:checked]:border-[#2d6a4f] has-[:checked]:bg-green-50">
                        <input type="radio" name="program_period_id" value="{{ $period->id }}"
                               {{ old('program_period_id') == $period->id ? 'checked' : '' }}
                               class="mt-0.5" required>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900">{{ $period->name }}</p>
                            <p class="text-xs text-gray-500">
                                {{ $period->start_date->format('d M') }} – {{ $period->end_date->format('d M Y') }}
                                &middot; Rp {{ number_format($period->price, 0, ',', '.') }}
                                &middot; Sisa {{ $period->quota - $period->filled }} tempat
                            </p>
                        </div>
                    </label>
                    @endforeach
                </div>
                @endif
            </div>

            {{-- Height/Weight + BMI --}}
            <div class="bg-white rounded-xl border shadow-sm p-5" x-data="bmiCalc()">
                <h2 class="text-sm font-semibold text-gray-700 mb-4">Tinggi & Berat Badan <span class="text-red-500">*</span></h2>
                <div class="grid grid-cols-3 gap-3">
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Tinggi (cm)</label>
                        <input type="number" x-model="height" min="100" max="250" placeholder="170"
                               class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-[#2d6a4f]/30 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Berat (kg)</label>
                        <input type="number" x-model="weight" min="20" max="300" placeholder="65"
                               class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-[#2d6a4f]/30 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">BMI</label>
                        <div class="px-3 py-2 border border-gray-200 rounded-lg text-sm bg-gray-50"
                             x-text="bmiDisplay"></div>
                    </div>
                </div>
                <input type="hidden" name="height_weight" :value="height && weight ? height + '/' + weight : ''">
                <input type="hidden" name="bmi" :value="bmiValue">
            </div>

            {{-- Health data --}}
            <div class="bg-white rounded-xl border shadow-sm p-5">
                <h2 class="text-sm font-semibold text-gray-700 mb-4">Data Kesehatan Saat Ini</h2>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Keluhan Utama <span class="text-red-500">*</span></label>
                        <textarea name="health_complaints" rows="3" required
                                  placeholder="Keluhan fisik / emosional yang Anda rasakan saat ini…"
                                  class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-[#2d6a4f]/30 focus:outline-none resize-none">{{ old('health_complaints') }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Riwayat Medis / Klinis</label>
                        <textarea name="clinical_details" rows="2"
                                  placeholder="Diagnosa dokter, operasi, atau kondisi medis yang relevan…"
                                  class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-[#2d6a4f]/30 focus:outline-none resize-none">{{ old('clinical_details') }}</textarea>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Alergi Makanan <span class="text-red-500">*</span></label>
                            <input type="text" name="food_allergies" value="{{ old('food_allergies') }}" required
                                   placeholder="Tidak ada / sebutkan…"
                                   class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-[#2d6a4f]/30 focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Obat yang Dikonsumsi <span class="text-red-500">*</span></label>
                            <input type="text" name="current_meds" value="{{ old('current_meds') }}" required
                                   placeholder="Tidak ada / sebutkan…"
                                   class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-[#2d6a4f]/30 focus:outline-none">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Riwayat Perawatan Sebelumnya <span class="text-red-500">*</span></label>
                        <textarea name="treatment_history" rows="2" required
                                  placeholder="Pernah ikut program ini sebelumnya? Terapi lain?"
                                  class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-[#2d6a4f]/30 focus:outline-none resize-none">{{ old('treatment_history') }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Kondisi Emosi Saat Ini <span class="text-red-500">*</span></label>
                        <textarea name="emotion_state" rows="2" required
                                  placeholder="Bagaimana kondisi emosi / pikiran Anda sekarang?"
                                  class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-[#2d6a4f]/30 focus:outline-none resize-none">{{ old('emotion_state') }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            Tingkat Keyakinan Sembuh (1–5) <span class="text-red-500">*</span>
                        </label>
                        <div class="flex gap-2">
                            @for ($i = 1; $i <= 5; $i++)
                            <label class="flex-1">
                                <input type="radio" name="confidence_level" value="{{ $i }}"
                                       {{ old('confidence_level') == $i ? 'checked' : '' }} class="sr-only" required>
                                <div class="text-center py-2 border rounded-xl cursor-pointer text-sm font-medium transition-all
                                            has-[:checked]:bg-[#2d6a4f] has-[:checked]:text-white has-[:checked]:border-[#2d6a4f]
                                            text-gray-600 hover:border-[#2d6a4f]">{{ $i }}</div>
                            </label>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>

            <button type="submit"
                    class="w-full py-3.5 bg-[#2d6a4f] text-white font-semibold rounded-xl hover:bg-[#1a5a3f] transition-colors">
                Daftar Ulang →
            </button>
        </div>
    </form>

</div>

<script>
function bmiCalc() {
    return {
        height: '', weight: '',
        get bmiValue() {
            if (!this.height || !this.weight) return '';
            return (this.weight / ((this.height / 100) ** 2)).toFixed(1);
        },
        get bmiDisplay() {
            const v = parseFloat(this.bmiValue);
            if (!v) return '—';
            const label = v < 18.5 ? 'Underweight' : v < 25 ? 'Normal' : v < 30 ? 'Overweight' : 'Obese';
            return v.toFixed(1) + ' · ' + label;
        },
    };
}
function reregForm() {
    return {
        submitForm() { this.$el.submit(); }
    };
}
</script>
</body>
</html>
