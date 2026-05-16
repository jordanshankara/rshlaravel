@extends('layouts.public')
@section('title', 'Tentang Kami')

@section('content')

{{-- Header --}}
<section class="relative overflow-hidden py-28 px-4 text-center">
    <img src="{{ asset('assets/green.webp') }}" alt="" class="absolute inset-0 w-full h-full object-cover object-center">
    <div class="absolute inset-0 bg-gradient-to-b from-green-950/75 via-green-900/65 to-green-950/80"></div>
    <div class="relative z-10">
        <span class="text-[#f97316] uppercase tracking-widest text-xs font-bold block mb-3">About Us</span>
        <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-white mb-3">Tentang Kami</h1>
        <p class="text-green-200 text-lg">Kami bekerja dengan mitra terbaik</p>
    </div>
</section>

{{-- About Text --}}
<section class="py-20 px-4 bg-white relative overflow-hidden">
    <div class="blob absolute top-0 right-0 w-80 h-80 bg-green-100/50"></div>
    <div class="max-w-4xl mx-auto relative">
        <div class="glass-card rounded-3xl p-8 sm:p-10">
            <h2 class="text-2xl font-bold text-[#0d3d1a] mb-2">Selamat Datang di Rumah Sehat Holistik Satu Bumi</h2>
            <p class="text-[#1a6b2f] text-sm font-medium mb-6">(Dibawah Naungan Yayasan Manusia Sehat Anand Krishna)</p>
            <div class="space-y-4 text-gray-700 leading-relaxed text-sm">
                <p>Rumah Sehat Holistik (RSH) Satu Bumi adalah Pusat Kesehatan berbasis terapi/usada dan pelatihan tradisional yang berlokasi di Jl. Raya Bukit Pelangi Km. 2, Gadog, Ciawi – Bogor, didirikan atas prakarsa Bapak Anand Krishna dan terinspirasi oleh Mā Archanā (Maya Safira Muchtar) yang mendedikasikan seluruh hidupnya untuk menghidupkan kembali ilmu-ilmu Usada Kuno yang masih sangat relevan.</p>
                <p>RSH Satu Bumi percaya bahwa proses penyembuhan bersifat holistik, yaitu menyentuh tubuh, pikiran, rasa dan jiwa. (Sembah Raga, Sembah Cipta, Sembah Rasa &amp; Sembah Jiwa)</p>
                <p>Kami meyakini bahwa terapi/usada dan pelatihan alami serta perubahan gaya hidup yang sehat dapat memberikan manfaat jangka panjang bagi kesehatan secara utuh. Panduan terapi seperti Ananda Divya Ausadh (Neo Zen Reiki), Terapi Herbal, Meditasi dan Pola Makan yang seimbang, akan memperkuat sistem kekebalan tubuh dan mencapai keseimbangan mental dan emosional.</p>
            </div>
        </div>
    </div>
</section>

{{-- Visi & Misi --}}
<section class="py-20 px-4 relative overflow-hidden" style="background: linear-gradient(135deg, #e8f5e9 0%, #f0fff4 100%)">
    <div class="blob absolute top-0 left-0 w-72 h-72 bg-green-200/40"></div>
    <div class="max-w-4xl mx-auto relative">
        <div class="text-center mb-10">
            <span class="text-[#f97316] uppercase tracking-widest text-xs font-bold block mb-3">VISI &amp; MISI</span>
            <h2 class="text-2xl sm:text-3xl font-bold text-[#0d3d1a]">Tujuan &amp; Arah Kami</h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="relative overflow-hidden rounded-2xl p-8" style="background: linear-gradient(135deg, #0d3d1a, #1a6b2f)">
                <div class="blob absolute -top-10 -right-10 w-40 h-40 bg-green-400/20"></div>
                <div class="relative">
                    <h3 class="text-xl font-bold mb-4 text-[#52c273]">Visi</h3>
                    <p class="text-xl italic leading-relaxed text-white">&ldquo;Layani Tuhan dengan Melayani Kemanusiaan dan Masyarakat&rdquo;</p>
                </div>
            </div>
            <div class="glass-card rounded-2xl p-8">
                <h3 class="text-xl font-bold mb-4 text-[#0d3d1a]">Misi</h3>
                <ol class="space-y-4 text-gray-700 text-sm">
                    <li class="flex items-start gap-3">
                        <span class="w-6 h-6 rounded-full bg-[#1a6b2f] text-white text-xs font-bold flex items-center justify-center flex-shrink-0 mt-0.5">1</span>
                        <span class="leading-relaxed">Memberikan pelayanan kesehatan berbasis terapi/usada dan pelatihan tradisional yang aman, efektif dan terpercaya</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="w-6 h-6 rounded-full bg-[#1a6b2f] text-white text-xs font-bold flex items-center justify-center flex-shrink-0 mt-0.5">2</span>
                        <span class="leading-relaxed">Mengedukasi masyarakat tentang manfaat terapi/usada tradisional serta pentingnya perubahan gaya hidup menjadi lebih sehat untuk meraih kesehatan secara holistik.</span>
                    </li>
                </ol>
            </div>
        </div>
    </div>
</section>

{{-- Tim Terapis --}}
<section class="py-20 px-4 bg-white relative overflow-hidden">
    <div class="blob absolute top-10 right-10 w-80 h-80 bg-green-100/60"></div>
    <div class="max-w-6xl mx-auto relative">
        <div class="text-center mb-12">
            <span class="text-[#f97316] uppercase tracking-widest text-xs font-bold block mb-3">TIM TERAPIS</span>
            <h2 class="text-2xl sm:text-3xl font-bold text-[#0d3d1a] mb-3">Terapis Handal &amp; Terpercaya dengan Pengalaman Belasan Tahun</h2>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach([
                ['name'=>'Mikael Budi Setiawan, MD','role'=>'Dokter Naturopati, Fasilitator Meditasi','exp'=>'Pengalaman lebih dari 18 tahun','img'=>'terapis/terapis-michael.webp'],
                ['name'=>'Tanti Meilyawati, CYA','role'=>'Certified Yoga Acharya','exp'=>'Pengalaman lebih dari 18 tahun','img'=>'terapis/terapis-tanti.jpg'],
                ['name'=>'Liny Tjeris','role'=>'Master Divya Ausadh (Neo Zen Reiki)','exp'=>'Pengalaman lebih dari 20 tahun','img'=>'terapis/terapis-liny.jpg'],
                ['name'=>'Dra. Dewi Juniarti, Psi','role'=>'Psikolog','exp'=>'Pengalaman lebih dari 20 tahun','img'=>'terapis/terapis-dewi.jpg'],
                ['name'=>'Ismoyo Palgunadi','role'=>'Pakar Ayurveda','exp'=>'Pengalaman lebih dari 18 tahun','img'=>'terapis/terapis-ismoyo.jpg'],
                ['name'=>'Wito','role'=>'Certified Acupressure Therapist','exp'=>'','img'=>'terapis/terapis-wito.jpg'],
                ['name'=>'Muslihah','role'=>'Certified Acupressure Therapist','exp'=>'','img'=>'terapis/terapis-muslihah.jpg'],
            ] as $t)
            <div class="glass-card rounded-2xl p-6 hover:-translate-y-1 transition-transform flex gap-4 items-start">
                <div class="relative w-14 h-14 rounded-full overflow-hidden flex-shrink-0 ring-2 ring-[#52c273]/40">
                    <img src="{{ asset('assets/'.$t['img']) }}" alt="{{ $t['name'] }}" class="w-full h-full object-cover object-top">
                </div>
                <div>
                    <h4 class="font-bold text-[#0d3d1a] mb-0.5 text-sm">{{ $t['name'] }}</h4>
                    <p class="text-[#1a6b2f] text-xs font-semibold mb-1">{{ $t['role'] }}</p>
                    @if($t['exp'])
                    <p class="text-gray-500 text-xs flex items-center gap-1">
                        <svg class="w-3 h-3 text-[#f97316] flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                        </svg>
                        {{ $t['exp'] }}
                    </p>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Dewan Pengawas --}}
<section class="py-20 px-4 relative overflow-hidden" style="background: linear-gradient(135deg, #e8f5e9 0%, #f0fff4 100%)">
    <div class="blob absolute bottom-0 right-0 w-72 h-72 bg-green-200/40"></div>
    <div class="max-w-6xl mx-auto relative">
        <div class="text-center mb-12">
            <span class="text-[#f97316] uppercase tracking-widest text-xs font-bold block mb-3">TIM AHLI</span>
            <h2 class="text-2xl sm:text-3xl font-bold text-[#0d3d1a] mb-3">Dewan Pengawas</h2>
            <p class="text-gray-600 max-w-2xl mx-auto text-sm">Mewujudkan kesejahteraan dan kebahagiaan sejati dengan dukungan para Pakar Kesehatan melalui pendekatan holistik bagi Body, Mind, dan Soul</p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach([
                ['name'=>'dr. Danny Wiradharma, S.H., M.S.','role'=>'Ketua Dewan Pengawas','specialty'=>'Ahli Imunologi','img'=>'doctor/dokter-danny.jpg'],
                ['name'=>'dr. Made Arya Wardhana, MARS','role'=>'Anggota Dewan Pengawas','specialty'=>'Dokter Umum','img'=>'doctor/dokter-made.jpg'],
                ['name'=>'Dr. dr. Lili Indrawati, M. Kes.','role'=>'Anggota Dewan Pengawas','specialty'=>'Doktor Ilmu Gizi FKUI','img'=>'doctor/dokter-lili.jpg'],
                ['name'=>'dr. Jeumpa Fitri','role'=>'Anggota Dewan Pengawas','specialty'=>'Dokter Umum','img'=>'doctor/dokter-jeumpa.jpg'],
            ] as $d)
            <div class="glass-card rounded-2xl p-6 text-center hover:-translate-y-1 transition-transform">
                <div class="relative w-20 h-20 rounded-full overflow-hidden mx-auto mb-4 ring-2 ring-[#52c273]/40">
                    <img src="{{ asset('assets/'.$d['img']) }}" alt="{{ $d['name'] }}" class="w-full h-full object-cover object-top">
                </div>
                <h4 class="font-bold text-[#0d3d1a] text-sm mb-1 leading-tight">{{ $d['name'] }}</h4>
                <p class="text-[#1a6b2f] text-xs font-semibold mb-1">{{ $d['role'] }}</p>
                <p class="text-gray-500 text-xs">{{ $d['specialty'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Testimoni --}}
<section class="py-20 px-4 relative overflow-hidden" style="background: linear-gradient(135deg, #0d3d1a 0%, #1a6b2f 100%)">
    <div class="blob absolute -top-20 right-20 w-96 h-96 bg-green-400/15"></div>
    <div class="max-w-6xl mx-auto relative">
        <div class="text-center mb-12">
            <span class="text-[#f97316] uppercase tracking-widest text-xs font-bold block mb-3">TESTIMONI</span>
            <h2 class="text-2xl sm:text-3xl font-bold text-white mb-2">Mereka yang merasakan manfaat program ini</h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach([
                ['text'=>'Saya memiliki masalah dengan berat badan yang menyebabkan lutut saya sakit dan menghambat aktivitas saya. Namun, dari hari ke hari, lutut saya terasa jauh lebih baik. Ternyata, dalam 7 hari ini, berat badan saya turun 2 kg. Program yang luar biasa','name'=>'Oka','age'=>50,'title'=>'Dosen, Denpasar'],
                ['text'=>'Ketika didiagnosis dengan gangguan kesehatan kronis, saya merasa sangat takut dan menderita. Setelah mengikuti program, terjadi perubahan baik secara fisik maupun mental. Bengkak di pipi agak mengecil dan mental saya lebih tenang.','name'=>'Dachlia','age'=>30,'title'=>'Ibu Rumah Tangga, Tangerang'],
                ['text'=>'Saya mengalami masalah asam urat tinggi yang menyebabkan nyeri pada tangan dan kaki saya. Namun, setelah mengikuti program selama 7 hari, kondisi tangan dan kaki saya telah membaik. Rasa kaku dan pegal-pegal saya hilang.','name'=>'Djumari','age'=>69,'title'=>'Wiraswasta, Magelang'],
            ] as $t)
            <div class="glass-dark rounded-2xl p-6 hover:-translate-y-1 transition-transform">
                <svg class="w-8 h-8 text-[#52c273] mb-4 opacity-80" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/>
                </svg>
                <p class="text-green-100/90 text-sm leading-relaxed mb-5">{{ $t['text'] }}</p>
                <div class="border-t border-white/10 pt-4 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center flex-shrink-0">
                        <span class="text-white font-bold text-sm">{{ substr($t['name'],0,1) }}</span>
                    </div>
                    <div>
                        <p class="font-bold text-white text-sm">{{ $t['name'] }} ({{ $t['age'] }})</p>
                        <p class="text-[#52c273] text-xs mt-0.5">{{ $t['title'] }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="text-center mt-12">
            <a href="{{ route('kontak') }}" class="inline-block px-8 py-3.5 bg-[#f97316] text-white font-bold rounded-xl hover:bg-[#ea6d0a] transition-all shadow-[0_4px_16px_rgba(249,115,22,0.4)] hover:-translate-y-0.5">
                Dapatkan Konsultasi Gratis
            </a>
        </div>
    </div>
</section>

@endsection
