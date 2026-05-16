@extends('layouts.public')
@section('title', 'Yayasan Anand Ashram')

@section('content')

{{-- Header --}}
<section class="relative overflow-hidden py-28 px-4 text-center">
    <img src="{{ asset('assets/anand-ashram.webp') }}" alt="Yayasan Anand Ashram" class="absolute inset-0 w-full h-full object-cover object-center">
    <div class="absolute inset-0 bg-gradient-to-b from-green-950/80 via-green-900/70 to-green-950/85"></div>
    <div class="relative z-10">
        <span class="text-[#f97316] uppercase tracking-widest text-xs font-bold block mb-3">Yayasan</span>
        <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-white">Tentang Yayasan Anand Ashram</h1>
    </div>
</section>

{{-- Content --}}
<section class="py-20 px-4 bg-white relative overflow-hidden">
    <div class="blob absolute top-10 right-10 w-80 h-80 bg-green-100/50"></div>
    <div class="blob absolute bottom-10 left-10 w-64 h-64 bg-emerald-100/40"></div>
    <div class="max-w-4xl mx-auto relative space-y-8">

        {{-- Intro --}}
        <div class="glass-card rounded-3xl p-8 sm:p-10">
            <p class="text-gray-700 leading-relaxed mb-4 text-sm">Yayasan Anand Ashram adalah sebuah lembaga nirlaba yang didirikan oleh Anand Krishna dengan misi mulia: membantu setiap manusia untuk tumbuh dan berkembang secara utuh — jasmani, rohani, dan spiritual.</p>
            <p class="text-gray-700 leading-relaxed text-sm">Berdiri sejak tahun 1990-an, Yayasan Anand Ashram telah berkembang menjadi salah satu lembaga pengembangan diri dan spiritualitas yang paling berpengaruh di Indonesia. Dengan puluhan ribu anggota aktif dan jutaan pembaca buku karya Anand Krishna, pengaruhnya telah menyentuh kehidupan banyak orang di seluruh penjuru Nusantara.</p>
        </div>

        {{-- Visi & Misi --}}
        <div class="glass-card rounded-2xl p-8">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-[#1a6b2f] to-[#52c273] flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                </div>
                <h2 class="text-xl font-bold text-[#0d3d1a]">Visi &amp; Misi</h2>
            </div>
            <p class="text-gray-700 text-sm leading-relaxed">Yayasan Anand Ashram berkomitmen untuk mewujudkan manusia Indonesia yang sehat, cerdas, dan berkarakter — manusia yang mampu memberikan kontribusi nyata bagi bangsa dan kemanusiaan.</p>
        </div>

        {{-- Program --}}
        <div class="glass-card rounded-2xl p-8">
            <div class="flex items-center gap-3 mb-5">
                <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-[#f97316] to-[#ea6d0a] flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <h2 class="text-xl font-bold text-[#0d3d1a]">Program dan Kegiatan</h2>
            </div>
            <p class="text-gray-700 text-sm mb-4">Yayasan Anand Ashram menyelenggarakan berbagai program dan kegiatan yang dirancang untuk mendukung pertumbuhan holistik setiap individu, antara lain:</p>
            <ul class="space-y-2">
                @foreach([
                    'Program meditasi reguler di berbagai kota',
                    'Kelas yoga dan pranayama',
                    'Seminar dan workshop pengembangan diri',
                    'Retreat spiritual di berbagai lokasi',
                    'Program kesehatan holistik melalui Rumah Sehat Holistik Satu Bumi',
                    'Penerbitan dan distribusi buku-buku karya Anand Krishna',
                    'Program sosial dan kemanusiaan',
                ] as $item)
                <li class="flex items-start gap-3 text-sm text-gray-700">
                    <div class="w-5 h-5 rounded-full bg-[#2d9348] flex items-center justify-center flex-shrink-0 mt-0.5">
                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    {{ $item }}
                </li>
                @endforeach
            </ul>
        </div>

        {{-- Jaringan --}}
        <div class="glass-card rounded-2xl p-8">
            <div class="flex items-center gap-3 mb-5">
                <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-[#2d9348] to-[#52c273] flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <h2 class="text-xl font-bold text-[#0d3d1a]">Jaringan Ashram dan Centre</h2>
            </div>
            <ul class="space-y-2">
                @foreach([
                    'Anand Ashram Jakarta — pusat kegiatan utama di ibukota',
                    'Anand Ashram Ubud, Bali — retreat dan meditasi di jantung budaya Bali',
                    'Anand Krishna Centre Kuta, Bali',
                    'Anand Krishna Centre Singaraja, Bali',
                    'One Earth Retreat Centre Bogor — untuk program retreat dan kesehatan holistik',
                    'Dan berbagai centre lainnya di kota-kota besar Indonesia',
                ] as $item)
                <li class="flex items-start gap-3 text-sm text-gray-700">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#f97316] flex-shrink-0 mt-1.5"></span>
                    {{ $item }}
                </li>
                @endforeach
            </ul>
        </div>

        {{-- Pendekatan --}}
        <div class="glass-card rounded-2xl p-8">
            <h2 class="text-xl font-bold text-[#0d3d1a] mb-4">Pendekatan Holistik</h2>
            <p class="text-gray-700 text-sm leading-relaxed mb-4">Yang membedakan Yayasan Anand Ashram adalah pendekatannya yang benar-benar holistik. Tidak ada sekat antara kesehatan fisik, kesehatan mental, dan pertumbuhan spiritual — semuanya dipandang sebagai satu kesatuan yang tidak terpisahkan.</p>
            <p class="text-gray-700 text-sm leading-relaxed">Ajaran-ajaran yang dikembangkan di Anand Ashram tidak berafiliasi dengan satu agama tertentu, melainkan mengambil inti kebijaksanaan dari berbagai tradisi spiritual dunia dan menyajikannya dalam format yang dapat diterima oleh siapa saja.</p>
        </div>

        <div class="glass-card rounded-2xl p-8">
            <h2 class="text-xl font-bold text-[#0d3d1a] mb-4">Hubungan dengan Rumah Sehat Holistik Satu Bumi</h2>
            <p class="text-gray-700 text-sm leading-relaxed mb-4">Rumah Sehat Holistik Satu Bumi adalah salah satu wujud nyata dari misi Yayasan Anand Ashram dalam bidang kesehatan. Didirikan di bawah Yayasan Manusia Sehat Anand Krishna, RSH Satu Bumi mengimplementasikan prinsip-prinsip kesehatan holistik yang telah dikembangkan selama bertahun-tahun dalam ekosistem Anand Ashram.</p>
            <p class="text-gray-700 text-sm leading-relaxed">Di sinilah para terapis yang telah terlatih dalam tradisi dan pendekatan Anand Ashram memberikan pelayanan kesehatan yang mencakup tubuh, pikiran, dan jiwa.</p>
        </div>

        {{-- CTA --}}
        <div class="text-center pt-2">
            <a href="https://anandashram.or.id" target="_blank" rel="noopener noreferrer"
               class="inline-block px-8 py-3.5 bg-[#1a6b2f] text-white font-semibold rounded-xl hover:bg-[#0d3d1a] transition-all shadow-[0_4px_16px_rgba(26,107,47,0.3)] hover:-translate-y-0.5">
                Info Selengkapnya →
            </a>
        </div>
    </div>
</section>

@endsection
