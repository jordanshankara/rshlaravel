@extends('layouts.public')
@section('title', 'Layanan')

@section('content')

{{-- Header --}}
<section class="relative overflow-hidden py-28 px-4 text-center">
    <img src="{{ asset('assets/green.webp') }}" alt="" class="absolute inset-0 w-full h-full object-cover object-center">
    <div class="absolute inset-0 bg-gradient-to-b from-green-950/75 via-green-900/65 to-green-950/80"></div>
    <div class="relative z-10">
        <span class="text-[#f97316] uppercase tracking-widest text-xs font-bold block mb-3">Layanan terapi kami</span>
        <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-white mb-3">Metode Pemulihan Holistik</h1>
        <p class="text-green-200 text-lg max-w-xl mx-auto">Pendekatan holistik yang menyentuh tubuh, pikiran &amp; jiwa</p>
    </div>
</section>

{{-- 3 Kartu Layanan --}}
<section class="py-20 px-4 bg-white relative overflow-hidden">
    <div class="blob absolute top-10 right-10 w-80 h-80 bg-green-100/60"></div>
    <div class="max-w-7xl mx-auto relative">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            {{-- Quantum Scanning --}}
            <div class="glass-card rounded-2xl overflow-hidden hover:-translate-y-2 hover:shadow-[0_16px_48px_rgba(13,61,26,0.18)] transition-all duration-300 group">
                <div class="relative h-48 overflow-hidden">
                    <img src="{{ asset('assets/latihan/quantum.webp') }}" alt="Quantum Scanning" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-green-950/60 to-transparent"></div>
                    <span class="absolute top-3 left-3 bg-[#f97316] text-white text-xs font-bold px-2.5 py-1 rounded-full uppercase tracking-wide">Tes Kesehatan</span>
                </div>
                <div class="p-8">
                    <h2 class="text-xl font-bold text-[#0d3d1a] mb-4">Quantum Scanning</h2>
                    <p class="text-gray-600 leading-relaxed text-sm">Tes Kesehatan Quantum Scanning (Q-Scan) membantu mendeteksi kelemahan atau gangguan tubuh secara cepat, akurat, dan tanpa radiasi. Dengan teknologi Magnetic Resonance Modulation, Q-Scan memberikan analisis lengkap untuk mencegah penyakit sejak dini.</p>
                </div>
            </div>
            {{-- Divya Aushadh --}}
            <div class="glass-card rounded-2xl overflow-hidden hover:-translate-y-2 hover:shadow-[0_16px_48px_rgba(13,61,26,0.18)] transition-all duration-300 group">
                <div class="relative h-48 overflow-hidden">
                    <img src="{{ asset('assets/latihan/reiki.JPG') }}" alt="Divya Aushadh" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-green-950/60 to-transparent"></div>
                    <span class="absolute top-3 left-3 bg-[#1a6b2f] text-white text-xs font-bold px-2.5 py-1 rounded-full uppercase tracking-wide">Terapi Energi Alam</span>
                </div>
                <div class="p-8">
                    <h2 class="text-xl font-bold text-[#0d3d1a] mb-4">Divya Aushadh (Terapi Energi Alam)</h2>
                    <p class="text-gray-600 leading-relaxed text-sm">Metode terapi menggunakan energi alam atau medan energi di sekitar tubuh untuk membantu meningkatkan aliran energi ke seluruh tubuh sehingga membantu proses penyembuhan alami.</p>
                </div>
            </div>
            {{-- Sehat dalam Sekejap --}}
            <div class="glass-card rounded-2xl overflow-hidden hover:-translate-y-2 hover:shadow-[0_16px_48px_rgba(13,61,26,0.18)] transition-all duration-300 group">
                <div class="relative h-48 overflow-hidden">
                    <img src="{{ asset('assets/latihan/meditasi.JPG') }}" alt="Sehat dalam Sekejap" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-green-950/60 to-transparent"></div>
                    <span class="absolute top-3 left-3 bg-[#f97316] text-white text-xs font-bold px-2.5 py-1 rounded-full uppercase tracking-wide">Program Instan</span>
                </div>
                <div class="p-8">
                    <h2 class="text-xl font-bold text-[#0d3d1a] mb-4">Sehat dalam Sekejap</h2>
                    <p class="text-gray-600 leading-relaxed text-sm">Program Khusus yang dirancang untuk membantu Anda memulai langkah awal menuju kesehatan optimal dengan cara praktis dan alami. Teknik detoksifikasi, pola makan sehat, dan keseimbangan tubuh-pikiran.</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Program Eksklusif --}}
<section class="relative py-24 px-4 overflow-hidden">
    <img src="{{ asset('assets/latihan/yoga.jpg') }}" alt="Program 7 Hari" class="absolute inset-0 w-full h-full object-cover object-center">
    <div class="absolute inset-0" style="background: linear-gradient(to right, rgba(5,30,15,0.95), rgba(10,50,25,0.90), rgba(15,70,35,0.82))"></div>
    <div class="max-w-4xl mx-auto text-center relative z-10">
        <span class="inline-block bg-[#f97316] text-white text-sm font-bold px-5 py-2 rounded-full mb-6 uppercase tracking-wide shadow-[0_4px_12px_rgba(249,115,22,0.4)]">PROGRAM PEMULIHAN EKSKLUSIF</span>
        <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-white mb-5 leading-tight">7 Hari Menuju Sehat<br>Raga &amp; Jiwa</h2>
        <p class="text-green-200 text-lg mb-4 leading-relaxed max-w-2xl mx-auto">Program yang dirancang untuk membantu Anda mengubah diri menjadi sehat dan seimbang. Selama 7 hari, Anda akan menjalani detoks alami, pola makan bergizi, Terapi Self Healing, dan aktivitas fisik yang mendukung penyembuhan tubuh dan pikiran secara holistik.</p>
        <div class="bg-white/10 backdrop-blur-md inline-flex px-6 py-3 rounded-2xl mt-2 border border-white/20">
            <p class="text-[#52c273] font-semibold text-lg italic">Your Luxury Healing Program</p>
        </div>
    </div>
</section>

{{-- Gejala --}}
<section class="py-20 px-4 relative overflow-hidden" style="background: linear-gradient(135deg, #e8f5e9 0%, #f0fff4 100%)">
    <div class="blob absolute top-0 right-0 w-64 h-64 bg-green-200/40"></div>
    <div class="max-w-4xl mx-auto relative">
        <div class="text-center mb-10">
            <h2 class="text-2xl font-bold text-[#0d3d1a] mb-2">Apakah anda pernah atau sering merasakan…</h2>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-8">
            @foreach(['Pusing dan sakit kepala','Mudah lelah atau pegal','Sering buang air atau malah sembelit','Sering kram atau nyeri leher','Sesak di dada'] as $gejala)
            <div class="glass-card rounded-xl px-4 py-3.5 flex items-center gap-3">
                <div class="w-6 h-6 rounded-full bg-gradient-to-br from-[#f97316] to-[#ea6d0a] flex items-center justify-center flex-shrink-0">
                    <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01M12 3C6.477 3 2 7.477 2 12s4.477 9 10 9 10-4.477 10-9S17.523 3 12 3z"/>
                    </svg>
                </div>
                <span class="text-gray-700 text-sm">{{ $gejala }}</span>
            </div>
            @endforeach
        </div>
        <div class="glass-dark rounded-2xl p-6 mb-8 text-center">
            <p class="text-white font-semibold leading-relaxed">Waspadalah — ini adalah pertanda awal <span class="text-yellow-300">PENYAKIT AKIBAT GAYA HIDUP</span> seperti diabetes, hipertensi, obesitas, kolesterol tinggi dan insomnia!</p>
        </div>
        <div class="glass-card rounded-2xl p-8">
            <h3 class="text-xl font-bold text-[#0d3d1a] mb-5 text-center">Apakah anda sudah mencoba mengubah pola hidup? Namun gagal, karena:</h3>
            <div class="space-y-3">
                @foreach(['Lingkungan dan situasi tidak mendukung','Kurang pengetahuan tentang gaya hidup sehat','Terjebak di trauma/emosi yang lama/sama'] as $item)
                <div class="flex items-start gap-3 py-3 border-b border-gray-100 last:border-0">
                    <div class="w-6 h-6 rounded-full bg-red-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                        <svg class="w-3.5 h-3.5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </div>
                    <span class="text-gray-700 text-sm">{{ $item }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

{{-- Program Detail --}}
<section class="py-20 px-4 bg-white relative overflow-hidden">
    <div class="blob absolute -top-10 left-1/2 -translate-x-1/2 w-[500px] h-64 bg-green-100/60"></div>
    <div class="max-w-4xl mx-auto relative">
        <div class="text-center mb-10">
            <span class="text-[#f97316] uppercase tracking-widest text-xs font-bold block mb-3">PROGRAM DETAIL</span>
            <h2 class="text-2xl sm:text-3xl font-bold text-[#0d3d1a] mb-3">Ikuti Program 7 Hari Menuju Sehat Raga &amp; Jiwa</h2>
            <p class="text-gray-600 text-sm"><span class="font-semibold">Lokasi:</span> Rumah Sehat Holistik Satu Bumi, Jl. Raya Bukit Pelangi Km. 2 Gadog Ciawi – Bogor</p>
        </div>

        <div class="glass-card rounded-2xl p-8 mb-8">
            <h3 class="text-lg font-bold text-[#1a6b2f] mb-6">Alami &amp; Rasakan Langsung:</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                @foreach([
                    ['title'=>'Holistic Detox','desc'=>'buang racun dari tubuh dan pikiran Anda'],
                    ['title'=>'Life Style Transformation','desc'=>'transformasi nyata dari gaya hidup Anda'],
                    ['title'=>'Self Energy Healing Therapy','desc'=>'Terapi Pemulihan Energi, Pikiran & Fisik'],
                    ['title'=>'Specific Yoga Therapy','desc'=>'Terapi Yoga yang sudah berusia ribuan tahun'],
                    ['title'=>'Plant Based Healthy Diet','desc'=>'makanan sehat yang rendah inflamasi'],
                    ['title'=>'Sound & Music Therapy','desc'=>'terapi suara dan musik untuk sehat holistik'],
                ] as $item)
                <div class="flex items-start gap-3 p-4 rounded-xl" style="background: linear-gradient(135deg, #e8f5e9, #f0fff4)">
                    <div class="w-6 h-6 rounded-full bg-[#2d9348] flex items-center justify-center flex-shrink-0 mt-0.5">
                        <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <div>
                        <p class="font-semibold text-[#0d3d1a] text-sm">{{ $item['title'] }}</p>
                        <p class="text-xs text-gray-500 mt-0.5">({{ $item['desc'] }})</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <div class="rounded-2xl p-8 mb-8" style="background: linear-gradient(135deg, #0d9488 0%, #0f766e 100%)">
            <div class="text-center mb-6">
                <h3 class="text-xl font-bold text-white mb-2">Hasil Berdasarkan Penelitian</h3>
                <p class="text-teal-100 text-sm">Berdasarkan penelitian dan umpan balik dari lebih dari 1.000 peserta aktif.</p>
            </div>
            <div class="grid grid-cols-2 gap-4">
                @foreach([
                    ['icon'=>'⚡','pct'=>'94%','label'=>'Merasakan perbaikan kondisi dalam 14 hari'],
                    ['icon'=>'🌙','pct'=>'89%','label'=>'Kualitas tidur meningkat signifikan'],
                    ['icon'=>'📈','pct'=>'91%','label'=>'Energi dan vitalitas tubuh meningkat'],
                    ['icon'=>'❤️','pct'=>'96%','label'=>'Merekomendasikan program kepada keluarga'],
                ] as $s)
                <div class="bg-white/15 backdrop-blur-sm rounded-2xl p-5 text-center border border-white/20">
                    <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center mx-auto mb-3 text-xl">{{ $s['icon'] }}</div>
                    <p class="text-4xl font-bold text-white mb-2">{{ $s['pct'] }}</p>
                    <p class="text-teal-100 text-xs leading-snug">{{ $s['label'] }}</p>
                </div>
                @endforeach
            </div>
        </div>

        <div class="text-center">
            <p class="text-2xl font-bold text-[#1a6b2f] mb-6">Yuk! Sehat dari sekarang!</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center flex-wrap">
                <a href="http://api.whatsapp.com/send?phone=62816677225" target="_blank" rel="noopener noreferrer"
                   class="px-8 py-3.5 bg-[#f97316] text-white font-bold rounded-xl hover:bg-[#ea6d0a] transition-all shadow-[0_4px_16px_rgba(249,115,22,0.35)] hover:-translate-y-0.5">
                    Daftar Sekarang!
                </a>
                <span class="text-red-500 font-semibold text-sm">⚡ Tempat Terbatas!</span>
                <a href="{{ route('kontak') }}" class="px-8 py-3.5 border-2 border-[#1a6b2f] text-[#1a6b2f] font-semibold rounded-xl hover:bg-[#1a6b2f] hover:text-white transition-all">
                    Hubungi Kami
                </a>
            </div>
        </div>
    </div>
</section>

@endsection
