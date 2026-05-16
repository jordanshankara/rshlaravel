@extends('layouts.public')
@section('title', ($settings['site_name'] ?? 'RSH Satu Bumi').' — '.($settings['site_tagline'] ?? 'Program Kesehatan Holistik'))

@section('content')

{{-- HERO SECTION --}}
<section class="relative overflow-hidden flex items-center py-20 px-4" style="min-height: 88vh">
    <img src="{{ asset('assets/env/env-1.jpeg') }}" alt=""
         class="absolute inset-0 w-full h-full object-cover object-center">
    <div class="absolute inset-0" style="background: linear-gradient(to right, rgba(5,46,22,0.9), rgba(20,83,45,0.75), rgba(21,128,61,0.4))"></div>
    <div class="absolute inset-0" style="background: linear-gradient(to top, rgba(5,46,22,0.5), transparent, transparent)"></div>

    <div class="max-w-7xl mx-auto w-full relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-12 items-center">
            {{-- Left: Text --}}
            <div class="lg:col-span-3">
                <div class="inline-flex items-center gap-2 border border-white/25 px-4 py-2 rounded-full mb-6 text-sm font-semibold text-green-100"
                     style="background: rgba(255,255,255,0.15); backdrop-filter: blur(4px)">
                    <span>🌿</span>
                    <span>Holistik &amp; Alami — Tubuh, Pikiran &amp; Jiwa</span>
                </div>

                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-white mb-5" style="line-height: 1.1">
                    Rumah Sehat<br>
                    <span class="text-green-300">Holistik</span><br>
                    Satu Bumi
                </h1>

                <p class="text-green-200/80 text-xs sm:text-sm mb-2 font-medium">
                    Dibawah Naungan Yayasan Manusia Sehat Anand Krishna
                </p>
                <p class="text-green-100 text-lg sm:text-xl mb-8 leading-relaxed max-w-lg">
                    Perjalanan menuju kesehatan sejati yang menyentuh tubuh, pikiran, dan jiwa — dengan pendekatan holistik alami.
                </p>

                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="/tentang-kami"
                       class="px-8 py-3.5 bg-white text-[#1a6b2f] font-semibold rounded-xl hover:bg-green-50 transition-all shadow-lg hover:-translate-y-0.5 text-center">
                        Pelajari Selengkapnya
                    </a>
                    <a href="http://api.whatsapp.com/send?phone=62816677225" target="_blank" rel="noopener noreferrer"
                       class="px-8 py-3.5 bg-[#f97316] text-white font-semibold rounded-xl hover:bg-[#ea6d0a] transition-all hover:-translate-y-0.5 text-center"
                       style="box-shadow: 0 4px 16px rgba(249,115,22,0.45)">
                        Konsultasi Offline Gratis!
                    </a>
                </div>
            </div>

            {{-- Right: Glass card --}}
            <div class="lg:col-span-2 relative">
                <div class="rounded-3xl p-5 shadow-2xl border border-white/30"
                     style="background: rgba(255,255,255,0.15); backdrop-filter: blur(20px)">
                    <div class="relative w-full rounded-2xl overflow-hidden mb-4" style="aspect-ratio: 4/5">
                        <img src="{{ asset('assets/env/building-2.jpeg') }}" alt="Terapis RSH Satu Bumi"
                             class="w-full h-full object-cover object-top">
                        <div class="absolute inset-0" style="background: linear-gradient(to top, rgba(5,46,22,0.6), transparent, transparent)"></div>
                        <div class="absolute bottom-3 left-3 right-3">
                            <p class="text-white font-bold text-sm">RSH Satu Bumi</p>
                            <p class="text-green-200 text-xs">Kesehatan Holistik Alami</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-3 gap-2">
                        @foreach([['val'=>'18+','label'=>'Tahun'],['val'=>'1000+','label'=>'Pasien'],['val'=>'95%','label'=>'Puas']] as $s)
                        <div class="rounded-xl p-2.5 text-center border border-white/20"
                             style="background: rgba(255,255,255,0.2); backdrop-filter: blur(4px)">
                            <p class="text-white font-bold text-base leading-none">{{ $s['val'] }}</p>
                            <p class="text-green-200 mt-0.5" style="font-size: 10px">{{ $s['label'] }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- INFO BAR --}}
<section class="relative px-4 py-5" style="background: rgba(13,61,26,0.95)">
    <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-center gap-8 text-sm">
        <div class="flex items-center gap-3 text-green-100">
            <div class="w-8 h-8 rounded-lg bg-white/10 flex items-center justify-center">
                <svg class="w-4 h-4 text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <span>Jl. Raya Bukit Pelangi Km. 2, Gadog Ciawi – Bogor</span>
        </div>
        <div class="w-px h-5 bg-white/20 hidden sm:block"></div>
        <div class="flex items-center gap-3 text-green-100">
            <div class="w-8 h-8 rounded-lg bg-white/10 flex items-center justify-center">
                <svg class="w-4 h-4 text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                </svg>
            </div>
            <span>+62 816-677-225</span>
        </div>
        <div class="w-px h-5 bg-white/20 hidden sm:block"></div>
        <div class="flex items-center gap-3 text-green-100">
            <div class="w-8 h-8 rounded-lg bg-white/10 flex items-center justify-center">
                <svg class="w-4 h-4 text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <span>Senin – Sabtu, 09.00 – 16.00 WIB</span>
        </div>
    </div>
</section>

{{-- ABOUT SECTION --}}
<section class="relative py-20 px-4 overflow-hidden bg-white">
    <div class="blob absolute top-0 right-0 w-96 h-96 bg-green-100/60 -z-0"></div>
    <div class="blob absolute bottom-0 left-0 w-72 h-72 bg-emerald-100/50 -z-0"></div>

    <div class="max-w-7xl mx-auto relative">
        <div class="text-center mb-3">
            <span class="text-[#f97316] uppercase tracking-widest text-xs font-bold">ABOUT US</span>
        </div>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            {{-- Left: photo + glass overlay --}}
            <div class="relative rounded-3xl overflow-hidden shadow-[0_8px_40px_rgba(13,61,26,0.18)]" style="aspect-ratio: 4/3">
                <img src="{{ asset('assets/env/env-4.jpg') }}" alt="Fasilitas RSH Satu Bumi"
                     class="w-full h-full object-cover">
                <div class="absolute inset-0" style="background: linear-gradient(to top, rgba(5,46,22,0.7), transparent, transparent)"></div>
                <div class="absolute bottom-4 left-4 right-4 grid grid-cols-2 gap-3">
                    @foreach([['num'=>'18+','label'=>'Tahun Pengalaman'],['num'=>'1000+','label'=>'Pasien Terlayani']] as $s)
                    <div class="rounded-xl p-3 border border-white/30"
                         style="background: rgba(255,255,255,0.2); backdrop-filter: blur(12px)">
                        <p class="text-white font-bold text-xl leading-none">{{ $s['num'] }}</p>
                        <p class="text-green-200 text-xs mt-0.5">{{ $s['label'] }}</p>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Right: text --}}
            <div>
                <h2 class="text-2xl sm:text-3xl font-bold text-[#0d3d1a] mb-5 leading-snug">
                    Perawatan Kesehatan Terbaik untuk Keluarga Anda
                </h2>
                <p class="text-gray-600 mb-6 leading-relaxed">
                    Rumah Sehat Holistik Satu Bumi hadir dengan tim terapis berpengalaman yang berdedikasi membantu Anda mencapai kesehatan secara holistik — menyentuh tubuh, pikiran, dan jiwa.
                </p>
                <ul class="space-y-3 mb-8">
                    @foreach(['Terapis Berpengalaman (>18 tahun)','Pelayanan Nyaman & Berkualitas','Pendekatan Holistik Alami','Staf yang Ramah & Profesional'] as $item)
                    <li class="flex items-center gap-3 text-gray-700">
                        <div class="w-5 h-5 rounded-full bg-[#2d9348] flex items-center justify-center flex-shrink-0">
                            <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <span class="text-sm">{{ $item }}</span>
                    </li>
                    @endforeach
                </ul>
                <div class="flex flex-col sm:flex-row gap-3">
                    <a href="/tentang-kami"
                       class="px-6 py-2.5 border-2 border-[#1a6b2f] text-[#1a6b2f] font-semibold rounded-xl hover:bg-[#1a6b2f] hover:text-white transition-all text-sm text-center">
                        Tentang Kami →
                    </a>
                    <a href="http://api.whatsapp.com/send?phone=62816677225" target="_blank" rel="noopener noreferrer"
                       class="px-6 py-2.5 bg-[#f97316] text-white font-semibold rounded-xl hover:bg-[#ea6d0a] transition-all text-sm text-center"
                       style="box-shadow: 0 4px 12px rgba(249,115,22,0.3)">
                        Konsultasi Gratis!
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- SERVICES SECTION --}}
<section class="relative py-20 px-4 overflow-hidden" style="background: linear-gradient(135deg, #e8f5e9 0%, #f0fff4 50%, #f1f8e9 100%)">
    <div class="blob absolute top-10 right-10 w-80 h-80 bg-green-200/40"></div>
    <div class="blob absolute bottom-10 left-10 w-64 h-64 bg-emerald-200/30"></div>

    <div class="max-w-7xl mx-auto relative">
        <div class="text-center mb-12">
            <span class="text-[#f97316] uppercase tracking-widest text-xs font-bold">Layanan terapi kami</span>
            <h2 class="text-2xl sm:text-3xl font-bold text-[#0d3d1a] mt-2 mb-3">Metode Pemulihan Holistik</h2>
            <p class="text-gray-600 max-w-xl mx-auto text-sm">
                Kami menghadirkan berbagai metode penyembuhan holistik yang telah terbukti memberikan manfaat nyata
            </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach([
                ['badge'=>'Tes Kesehatan','title'=>'Quantum Scanning','desc'=>'Deteksi kelemahan tubuh secara cepat, akurat, dan tanpa radiasi menggunakan teknologi Magnetic Resonance Modulation.','img'=>'latihan/quantum.webp','icon'=>'M9 3H5a2 2 0 00-2 2v4m6-6h10a2 2 0 012 2v4M9 3v18m0 0h10a2 2 0 002-2V9M9 21H5a2 2 0 01-2-2V9m0 0h18'],
                ['badge'=>'Terapi Energi Alam','title'=>'Divya Aushadh','desc'=>'Pengobatan menggunakan energi alam untuk meningkatkan aliran energi ke seluruh tubuh dan membantu proses penyembuhan alami.','img'=>'latihan/reiki.JPG','icon'=>'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z'],
                ['badge'=>'Program Instan','title'=>'Sehat dalam Sekejap','desc'=>'Teknik detoksifikasi, pola makan sehat, dan keseimbangan tubuh-pikiran yang mudah diterapkan dalam waktu singkat.','img'=>'latihan/meditasi.JPG','icon'=>'M13 10V3L4 14h7v7l9-11h-7z'],
                ['badge'=>'Program Eksklusif','title'=>'7 Hari Menuju Sehat','desc'=>'Detoks alami, pola makan bergizi, meditasi, dan aktivitas fisik untuk penyembuhan tubuh dan pikiran secara holistik.','img'=>'latihan/yoga.jpg','icon'=>'M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z'],
            ] as $service)
            <div class="glass-card rounded-2xl overflow-hidden hover:-translate-y-2 transition-all duration-300 group"
                 style="--hover-shadow: 0 16px 48px rgba(13,61,26,0.18)">
                <div class="relative h-40 overflow-hidden">
                    <img src="{{ asset('assets/'.$service['img']) }}" alt="{{ $service['title'] }}"
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute inset-0" style="background: linear-gradient(to top, rgba(5,46,22,0.6), transparent)"></div>
                    <div class="absolute top-3 left-3">
                        <span class="text-xs font-bold text-white bg-[#f97316] px-2.5 py-1 rounded-full uppercase tracking-wide">
                            {{ $service['badge'] }}
                        </span>
                    </div>
                    <div class="absolute bottom-3 right-3 w-9 h-9 rounded-xl bg-white/20 backdrop-blur-sm flex items-center justify-center text-white border border-white/30">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $service['icon'] }}"/>
                        </svg>
                    </div>
                </div>
                <div class="p-5">
                    <h3 class="font-bold text-[#0d3d1a] mb-2 text-base">{{ $service['title'] }}</h3>
                    <p class="text-gray-600 text-sm leading-relaxed mb-4">{{ $service['desc'] }}</p>
                    <a href="/layanan" class="text-[#1a6b2f] text-sm font-semibold flex items-center gap-1 hover:gap-2 transition-all group-hover:text-[#f97316]">
                        Selengkapnya <span>→</span>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- STATS BAR --}}
<section class="relative py-12 px-4 overflow-hidden" style="background: linear-gradient(135deg, #0d3d1a 0%, #1a6b2f 100%)">
    <div class="blob absolute -top-20 right-20 w-72 h-72 bg-green-400/15"></div>
    <div class="max-w-7xl mx-auto relative">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach([['value'=>'GRATIS','label'=>'Konsultasi Perdana'],['value'=>'18+','label'=>'Tahun Pengalaman'],['value'=>'1000+','label'=>'Pasien Terlayani'],['value'=>'95%','label'=>'Tingkat Kepuasan']] as $s)
            <div class="text-center glass-dark rounded-2xl py-6 px-4">
                <p class="text-3xl sm:text-4xl font-bold text-white mb-1">{{ $s['value'] }}</p>
                <p class="text-green-300 text-sm">{{ $s['label'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- CTA SECTION --}}
<section class="relative py-20 px-4 overflow-hidden bg-white">
    <div class="blob absolute top-0 left-1/2 -translate-x-1/2 w-[600px] h-72 bg-green-100/60"></div>
    <div class="max-w-7xl mx-auto relative">
        <div class="glass-card rounded-3xl overflow-hidden shadow-[0_8px_40px_rgba(13,61,26,0.12)]">
            <div class="grid grid-cols-1 lg:grid-cols-2">
                {{-- Left: photo + dark overlay --}}
                <div class="relative overflow-hidden" style="min-height: 360px">
                    <img src="{{ asset('assets/env/tim.JPG') }}" alt="Tim Terapis RSH Satu Bumi"
                         class="absolute inset-0 w-full h-full object-cover">
                    <div class="absolute inset-0" style="background: linear-gradient(to right, rgba(5,46,22,0.85), rgba(20,83,45,0.7), rgba(21,128,61,0.4))"></div>
                    <div class="relative z-10 p-10 h-full flex flex-col justify-center">
                        <span class="inline-block text-xs font-bold text-[#f97316] px-3 py-1 rounded-full mb-4 uppercase tracking-wide w-fit"
                              style="background: rgba(249,115,22,0.2)">
                            Tim Profesional
                        </span>
                        <h2 class="text-2xl sm:text-3xl font-bold text-white mb-4 leading-snug">
                            Terapis Berpengalaman &amp; Bersertifikasi
                        </h2>
                        <p class="text-green-200 mb-6 leading-relaxed text-sm">
                            Tim terapis kami memiliki pengalaman lebih dari 18 tahun dengan sertifikasi resmi. Berkomitmen penuh untuk kesehatan holistik Anda.
                        </p>
                        <a href="/tentang-kami"
                           class="inline-block px-6 py-3 bg-white text-[#1a6b2f] font-semibold rounded-xl hover:bg-[#e8f5e9] transition-colors text-sm w-fit">
                            Kenali Tim Kami →
                        </a>
                    </div>
                </div>
                {{-- Right: light glass --}}
                <div class="p-10 flex flex-col justify-center">
                    <span class="inline-block text-xs font-bold text-[#1a6b2f] bg-green-100 px-3 py-1 rounded-full mb-4 uppercase tracking-wide w-fit">
                        Mulai Perjalanan Anda
                    </span>
                    <h3 class="text-xl font-bold text-[#0d3d1a] mb-3">
                        Ingin Memulai Perjalanan Menuju Kesehatan Holistik?
                    </h3>
                    <p class="text-gray-600 text-sm mb-6 leading-relaxed">
                        Konsultasikan kondisi kesehatan Anda dengan tim kami. Konsultasi pertama gratis, baik secara offline langsung maupun via WhatsApp.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-3">
                        <a href="{{ route('daftar.index') }}"
                           class="px-6 py-3 bg-[#f97316] text-white font-semibold rounded-xl hover:bg-[#ea6d0a] transition-all text-sm text-center"
                           style="box-shadow: 0 4px 12px rgba(249,115,22,0.35)">
                            Daftar Sekarang!
                        </a>
                        <a href="/layanan"
                           class="px-6 py-3 border-2 border-[#1a6b2f] text-[#1a6b2f] font-semibold rounded-xl hover:bg-[#1a6b2f] hover:text-white transition-all text-sm text-center">
                            Lihat Layanan
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- BOOKS SECTION --}}
<section class="relative py-16 px-4 overflow-hidden" style="background: linear-gradient(135deg, #e8f5e9 0%, #d4edda 100%)">
    <div class="blob absolute top-0 right-0 w-64 h-64 bg-green-300/30"></div>
    <div class="max-w-5xl mx-auto text-center relative">
        <span class="text-[#f97316] uppercase tracking-widest text-xs font-bold block mb-3">INSPIRASI</span>
        <h2 class="text-2xl sm:text-3xl font-bold text-[#0d3d1a] mb-4">
            Buku Kesehatan Holistik — Karya Bapak Anand Krishna
        </h2>
        <p class="text-gray-600 mb-8 max-w-xl mx-auto text-sm">
            Lebih dari 170 judul buku tentang meditasi, yoga, dan pengembangan diri yang telah dibaca jutaan orang.
        </p>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-8">
            @foreach([
                ['img'=>'assets/buku/neo-self.jpg','title'=>'Neo Self Empowerment'],
                ['img'=>'assets/buku/kundalini-yoga.jpg','title'=>'Kundalini Yoga'],
                ['img'=>'assets/buku/yoga-sutra.jpg','title'=>'Yoga Sutra Patanjali'],
                ['img'=>'assets/buku/science-fear.jpg','title'=>'The Science of Fear Management'],
            ] as $buku)
            <div class="relative rounded-xl overflow-hidden shadow-md hover:shadow-xl transition-all duration-300 group" style="aspect-ratio: 3/4">
                <img src="{{ asset($buku['img']) }}" alt="{{ $buku['title'] }}"
                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
            </div>
            @endforeach
        </div>
        <a href="/anand-krishna"
           class="inline-block px-8 py-3.5 bg-[#1a6b2f] text-white font-semibold rounded-xl hover:bg-[#0d3d1a] transition-all hover:-translate-y-0.5"
           style="box-shadow: 0 4px 16px rgba(26,107,47,0.3)">
            Selengkapnya →
        </a>
    </div>
</section>

@endsection
