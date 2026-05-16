@extends('layouts.public')
@section('title', 'Kontak')

@section('content')

{{-- Header --}}
<section class="relative overflow-hidden py-28 px-4 text-center">
    <img src="{{ asset('assets/green.webp') }}" alt="" class="absolute inset-0 w-full h-full object-cover object-center">
    <div class="absolute inset-0 bg-gradient-to-b from-green-950/75 via-green-900/65 to-green-950/80"></div>
    <div class="relative z-10">
        <span class="text-[#f97316] uppercase tracking-widest text-xs font-bold block mb-3">Contact</span>
        <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-white mb-3">Hubungi Kami</h1>
        <p class="text-green-200 text-lg max-w-md mx-auto">Kami siap membantu perjalanan kesehatan holistik Anda</p>
    </div>
</section>

{{-- Contact Info --}}
<section class="py-20 px-4 bg-white relative overflow-hidden">
    <div class="blob absolute top-10 right-10 w-80 h-80 bg-green-100/60"></div>
    <div class="blob absolute bottom-10 left-10 w-72 h-72 bg-emerald-100/50"></div>
    <div class="max-w-6xl mx-auto relative">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            {{-- Left: Info --}}
            <div>
                <h2 class="text-2xl font-bold text-[#0d3d1a] mb-8">Informasi Kontak</h2>
                <div class="space-y-5">
                    {{-- Address --}}
                    <div class="glass-card rounded-2xl p-5 flex items-start gap-4 hover:-translate-y-0.5 transition-transform">
                        <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-[#1a6b2f] to-[#52c273] flex items-center justify-center flex-shrink-0 shadow-[0_4px_12px_rgba(26,107,47,0.3)]">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-[#0d3d1a] mb-1">Alamat</h3>
                            <p class="text-gray-600 text-sm">Jl. Bukit Pelangi KM 2,</p>
                            <p class="text-gray-600 text-sm">Gn. Geulis, Sukaraja, Bogor</p>
                        </div>
                    </div>

                    {{-- Email --}}
                    <div class="glass-card rounded-2xl p-5 flex items-start gap-4 hover:-translate-y-0.5 transition-transform">
                        <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-[#f97316] to-[#ea6d0a] flex items-center justify-center flex-shrink-0 shadow-[0_4px_12px_rgba(249,115,22,0.3)]">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-[#0d3d1a] mb-1">Email</h3>
                            <a href="mailto:rshsatubumi@gmail.com" class="text-[#1a6b2f] hover:text-[#f97316] transition-colors text-sm font-medium">rshsatubumi@gmail.com</a>
                        </div>
                    </div>

                    {{-- Phone --}}
                    <div class="glass-card rounded-2xl p-5 flex items-start gap-4 hover:-translate-y-0.5 transition-transform">
                        <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-[#2d9348] to-[#52c273] flex items-center justify-center flex-shrink-0 shadow-[0_4px_12px_rgba(45,147,72,0.3)]">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-[#0d3d1a] mb-1">Phone / WhatsApp</h3>
                            <a href="http://api.whatsapp.com/send?phone=62816677225" target="_blank" rel="noopener noreferrer" class="text-[#1a6b2f] hover:text-[#f97316] transition-colors text-sm font-medium">+62 816-677-225</a>
                        </div>
                    </div>

                    {{-- Jam Operasional --}}
                    <div class="glass-card rounded-2xl p-5 flex items-start gap-4 hover:-translate-y-0.5 transition-transform">
                        <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-[#0d3d1a] to-[#1a6b2f] flex items-center justify-center flex-shrink-0 shadow-[0_4px_12px_rgba(13,61,26,0.3)]">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-[#0d3d1a] mb-1">Jam Operasional</h3>
                            <p class="text-gray-600 text-sm">Senin – Sabtu</p>
                            <p class="text-gray-600 text-sm">09.00 – 16.00 WIB</p>
                        </div>
                    </div>

                    {{-- Social Media --}}
                    <div class="glass-card rounded-2xl p-5">
                        <h3 class="font-bold text-[#0d3d1a] mb-3">Media Sosial</h3>
                        <div class="flex flex-wrap gap-3">
                            <a href="https://www.instagram.com/rshsatubumi/" target="_blank" rel="noopener noreferrer"
                               class="flex items-center gap-2 px-4 py-2 rounded-full text-sm font-semibold text-[#1a6b2f] border border-[#1a6b2f]/30 bg-green-50 hover:bg-[#1a6b2f] hover:text-white transition-colors">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                                </svg>
                                Instagram
                            </a>
                            <a href="https://www.tiktok.com/@rshsatubumi" target="_blank" rel="noopener noreferrer"
                               class="flex items-center gap-2 px-4 py-2 rounded-full text-sm font-semibold text-[#1a6b2f] border border-[#1a6b2f]/30 bg-green-50 hover:bg-[#1a6b2f] hover:text-white transition-colors">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M19.59 6.69a4.83 4.83 0 01-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 01-2.88 2.5 2.89 2.89 0 01-2.89-2.89 2.89 2.89 0 012.89-2.89c.28 0 .54.04.79.1V9.01a6.33 6.33 0 00-.79-.05 6.34 6.34 0 00-6.34 6.34 6.34 6.34 0 006.34 6.34 6.34 6.34 0 006.33-6.34V8.69a8.18 8.18 0 004.79 1.53V6.77a4.85 4.85 0 01-1.02-.08z"/>
                                </svg>
                                TikTok
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right: Map + CTA --}}
            <div class="space-y-6">
                <div class="rounded-2xl overflow-hidden shadow-[0_4px_24px_rgba(13,61,26,0.12)]">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m28!1m12!1m3!1d15000!2d106.8571!3d-6.6284!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!4m13!3e0!4m5!1s0x2e69f353533c629f%3A0x5d02642a169bcff4!2sSummarecon+Bogor!3m2!1d-6.623145!2d106.8363691!4m5!1s0x2e69c9f6e29813dd%3A0x65263debe78cfe04!2sRumah+Sehat+Holistik+Satu+Bumi!3m2!1d-6.6335785!2d106.8778293!5e0!3m2!1sid!2sid!4v1"
                        width="100%" height="320" loading="lazy" class="border-0 block"
                        title="Rute ke RSH Satu Bumi dari Exit Tol Summarecon Bogor" allowfullscreen></iframe>
                </div>
                <a href="https://www.google.com/maps/dir/Summarecon+Bogor,+Jl.+Summarecon+Bogor+Boulevard+Utama,+Cibanon,+Kec.+Sukaraja,+Kabupaten+Bogor,+Jawa+Barat+13210/Rumah+Sehat+Holistik+Satu+Bumi,+Kp.+Bojong+Honje,+Gunung+Geulis,+Sukaraja,+Bogor+Regency"
                   target="_blank" rel="noopener noreferrer"
                   class="inline-flex items-center gap-1.5 text-sm font-semibold text-[#1a6b2f] hover:text-[#f97316] transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                    Lihat Rute dari Exit Tol Summarecon Bogor
                </a>

                <div class="relative overflow-hidden rounded-2xl p-8 text-white text-center" style="background: linear-gradient(135deg, #0d3d1a, #1a6b2f)">
                    <div class="blob absolute -top-10 -right-10 w-40 h-40 bg-green-400/20"></div>
                    <div class="relative">
                        <div class="w-14 h-14 rounded-2xl bg-white/15 flex items-center justify-center mx-auto mb-4">
                            <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.890-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold mb-2">Konsultasi via WhatsApp</h3>
                        <p class="text-green-200 mb-6 text-sm">Tim kami siap menjawab pertanyaan Anda dan membantu menjadwalkan sesi konsultasi.</p>
                        <a href="http://api.whatsapp.com/send?phone=62816677225" target="_blank" rel="noopener noreferrer"
                           class="inline-flex items-center gap-2 px-8 py-3 bg-[#f97316] text-white font-bold rounded-xl hover:bg-[#ea6d0a] transition-all shadow-[0_4px_12px_rgba(249,115,22,0.4)]">
                            Chat WhatsApp
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
