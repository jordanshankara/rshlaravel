<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'RSH Satu Bumi')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#1a6b2f',
                        'primary-dark': '#0d3d1a',
                        'primary-mid': '#2d9348',
                        'primary-light': '#52c273',
                        accent: '#f97316',
                    }
                }
            }
        }
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @stack('head')
</head>
<body class="bg-[#f8fffe] text-[#0d2b1a]">

<nav class="sticky top-0 z-50 bg-white/75 backdrop-blur-md border-b border-white/50 shadow-[0_2px_16px_rgba(13,61,26,0.08)]"
     x-data="{ mobileOpen: false, mobileDropdown: null }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-20">
            {{-- Logo --}}
            <a href="{{ route('home') }}" class="flex items-center flex-shrink-0">
                <img src="{{ asset('assets/logo/logo-rec-colored.png') }}" alt="Rumah Sehat Holistik Satu Bumi"
                     class="h-12 w-auto object-contain">
            </a>

            {{-- Desktop Nav --}}
            <div class="hidden lg:flex items-center gap-5">
                <a href="{{ route('home') }}"
                   class="text-gray-700 hover:text-[#1a6b2f] font-medium transition-colors text-sm relative group">
                    Beranda
                    <span class="absolute -bottom-0.5 left-0 w-0 h-0.5 bg-[#f97316] rounded-full transition-all duration-300 group-hover:w-full"></span>
                </a>

                {{-- Anand Krishna dropdown --}}
                <div class="dropdown relative">
                    <button class="flex items-center gap-1 text-gray-700 hover:text-[#1a6b2f] font-medium transition-colors text-sm py-1">
                        Anand Krishna
                        <svg class="w-3.5 h-3.5 opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div class="dropdown-menu hidden absolute top-full left-0 pt-2 z-50">
                        <div class="glass-card rounded-xl py-2 min-w-[200px] shadow-[0_8px_24px_rgba(13,61,26,0.15)]">
                            <a href="/anand-krishna" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-[#e8f5e9] hover:text-[#1a6b2f] transition-colors rounded-lg mx-1">Anand Krishna</a>
                            <a href="/sembuh-dari-leukimia" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-[#e8f5e9] hover:text-[#1a6b2f] transition-colors rounded-lg mx-1">Sembuh dari Leukimia</a>
                        </div>
                    </div>
                </div>

                {{-- Tentang Kami dropdown --}}
                <div class="dropdown relative">
                    <button class="flex items-center gap-1 text-gray-700 hover:text-[#1a6b2f] font-medium transition-colors text-sm py-1">
                        Tentang Kami
                        <svg class="w-3.5 h-3.5 opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div class="dropdown-menu hidden absolute top-full left-0 pt-2 z-50">
                        <div class="glass-card rounded-xl py-2 min-w-[200px] shadow-[0_8px_24px_rgba(13,61,26,0.15)]">
                            <a href="/tentang-kami" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-[#e8f5e9] hover:text-[#1a6b2f] transition-colors rounded-lg mx-1">Tentang Kami</a>
                            <a href="/inspirator-kami" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-[#e8f5e9] hover:text-[#1a6b2f] transition-colors rounded-lg mx-1">Inspirator Kami</a>
                            <a href="/yayasan-anand-ashram" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-[#e8f5e9] hover:text-[#1a6b2f] transition-colors rounded-lg mx-1">Yayasan Anand Ashram</a>
                        </div>
                    </div>
                </div>

                <a href="/layanan"
                   class="text-gray-700 hover:text-[#1a6b2f] font-medium transition-colors text-sm relative group">
                    Layanan
                    <span class="absolute -bottom-0.5 left-0 w-0 h-0.5 bg-[#f97316] rounded-full transition-all duration-300 group-hover:w-full"></span>
                </a>
                <a href="{{ route('artikel.index') }}"
                   class="text-gray-700 hover:text-[#1a6b2f] font-medium transition-colors text-sm relative group">
                    Artikel
                    <span class="absolute -bottom-0.5 left-0 w-0 h-0.5 bg-[#f97316] rounded-full transition-all duration-300 group-hover:w-full"></span>
                </a>
                <a href="/kontak"
                   class="text-gray-700 hover:text-[#1a6b2f] font-medium transition-colors text-sm relative group">
                    Kontak
                    <span class="absolute -bottom-0.5 left-0 w-0 h-0.5 bg-[#f97316] rounded-full transition-all duration-300 group-hover:w-full"></span>
                </a>

                <a href="http://api.whatsapp.com/send?phone=62816677225" target="_blank" rel="noopener noreferrer"
                   class="ml-2 px-5 py-2.5 bg-[#f97316] text-white text-sm font-semibold rounded-xl hover:bg-[#ea6d0a] transition-all hover:scale-105 shadow-[0_4px_12px_rgba(249,115,22,0.35)]">
                    Hubungi Kami
                </a>
            </div>

            {{-- Hamburger --}}
            <button @click="mobileOpen = !mobileOpen"
                    class="lg:hidden p-2 text-gray-700 hover:text-[#1a6b2f] rounded-lg hover:bg-[#e8f5e9] transition-colors"
                    aria-label="Toggle menu">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path x-show="!mobileOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    <path x-show="mobileOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </div>

    {{-- Mobile Menu --}}
    <div x-show="mobileOpen" x-cloak
         class="lg:hidden glass-card border-t border-white/30 px-4 py-4 space-y-1">
        <a href="{{ route('home') }}"
           class="block text-gray-700 hover:text-[#1a6b2f] font-medium py-2.5 px-3 rounded-xl hover:bg-[#e8f5e9] text-sm transition-colors">
            Beranda
        </a>
        <div>
            <button @click="mobileDropdown = mobileDropdown === 'anand' ? null : 'anand'"
                    class="w-full flex items-center justify-between text-gray-700 hover:text-[#1a6b2f] font-medium py-2.5 px-3 rounded-xl hover:bg-[#e8f5e9] text-sm transition-colors">
                Anand Krishna
                <svg class="w-4 h-4 opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
            <div x-show="mobileDropdown === 'anand'" class="pl-4 space-y-1 mt-1 border-l-2 border-[#52c273] ml-3">
                <a href="/anand-krishna" class="block py-2 px-3 text-sm text-gray-600 hover:text-[#1a6b2f] rounded-lg hover:bg-[#e8f5e9] transition-colors">Anand Krishna</a>
                <a href="/sembuh-dari-leukimia" class="block py-2 px-3 text-sm text-gray-600 hover:text-[#1a6b2f] rounded-lg hover:bg-[#e8f5e9] transition-colors">Sembuh dari Leukimia</a>
            </div>
        </div>
        <div>
            <button @click="mobileDropdown = mobileDropdown === 'tentang' ? null : 'tentang'"
                    class="w-full flex items-center justify-between text-gray-700 hover:text-[#1a6b2f] font-medium py-2.5 px-3 rounded-xl hover:bg-[#e8f5e9] text-sm transition-colors">
                Tentang Kami
                <svg class="w-4 h-4 opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
            <div x-show="mobileDropdown === 'tentang'" class="pl-4 space-y-1 mt-1 border-l-2 border-[#52c273] ml-3">
                <a href="/tentang-kami" class="block py-2 px-3 text-sm text-gray-600 hover:text-[#1a6b2f] rounded-lg hover:bg-[#e8f5e9] transition-colors">Tentang Kami</a>
                <a href="/inspirator-kami" class="block py-2 px-3 text-sm text-gray-600 hover:text-[#1a6b2f] rounded-lg hover:bg-[#e8f5e9] transition-colors">Inspirator Kami</a>
                <a href="/yayasan-anand-ashram" class="block py-2 px-3 text-sm text-gray-600 hover:text-[#1a6b2f] rounded-lg hover:bg-[#e8f5e9] transition-colors">Yayasan Anand Ashram</a>
            </div>
        </div>
        <a href="/layanan" class="block text-gray-700 hover:text-[#1a6b2f] font-medium py-2.5 px-3 rounded-xl hover:bg-[#e8f5e9] text-sm transition-colors">Layanan</a>
        <a href="{{ route('artikel.index') }}" class="block text-gray-700 hover:text-[#1a6b2f] font-medium py-2.5 px-3 rounded-xl hover:bg-[#e8f5e9] text-sm transition-colors">Artikel</a>
        <a href="/kontak" class="block text-gray-700 hover:text-[#1a6b2f] font-medium py-2.5 px-3 rounded-xl hover:bg-[#e8f5e9] text-sm transition-colors">Kontak</a>
        <a href="http://api.whatsapp.com/send?phone=62816677225" target="_blank" rel="noopener noreferrer"
           class="block mt-3 px-4 py-3 bg-[#f97316] text-white text-sm font-semibold rounded-xl text-center hover:bg-[#ea6d0a] transition-colors shadow-[0_4px_12px_rgba(249,115,22,0.3)]">
            Hubungi Kami
        </a>
    </div>
</nav>

<main>
    @yield('content')
</main>

<footer style="background: linear-gradient(135deg, #0a2e15 0%, #0d3d1a 50%, #122b1a 100%)" class="text-white relative overflow-hidden">
    {{-- Decorative blobs --}}
    <div class="absolute top-0 right-0 w-80 h-80 bg-green-700/20 blob" style="pointer-events:none"></div>
    <div class="absolute bottom-0 left-0 w-64 h-64 bg-green-600/15 blob" style="pointer-events:none"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
            {{-- Brand --}}
            <div>
                <div class="mb-4">
                    <img src="{{ asset('assets/logo/logo-rec-white.png') }}" alt="Rumah Sehat Holistik Satu Bumi"
                         class="h-12 w-auto object-contain">
                </div>
                <p class="text-green-300/80 text-sm mb-5 leading-relaxed">
                    Perjalanan Menuju Kesehatan Holistik Secara Alami — Tubuh, Pikiran &amp; Jiwa
                </p>
                <div class="flex gap-3">
                    <a href="https://www.instagram.com/rshsatubumi/" target="_blank" rel="noopener noreferrer"
                       class="w-8 h-8 rounded-full glass-dark flex items-center justify-center text-green-300 hover:text-white transition-colors">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                        </svg>
                    </a>
                    <a href="https://www.tiktok.com/@rshsatubumi" target="_blank" rel="noopener noreferrer"
                       class="w-8 h-8 rounded-full glass-dark flex items-center justify-center text-green-300 hover:text-white transition-colors">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M19.59 6.69a4.83 4.83 0 01-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 01-2.88 2.5 2.89 2.89 0 01-2.89-2.89 2.89 2.89 0 012.89-2.89c.28 0 .54.04.79.1V9.01a6.33 6.33 0 00-.79-.05 6.34 6.34 0 00-6.34 6.34 6.34 6.34 0 006.34 6.34 6.34 6.34 0 006.33-6.34V8.69a8.18 8.18 0 004.78 1.52V6.75a4.85 4.85 0 01-1.01-.06z"/>
                        </svg>
                    </a>
                </div>
            </div>

            {{-- Contact --}}
            <div>
                <h4 class="text-base font-semibold mb-5 text-[#52c273]">Kontak Kami</h4>
                <ul class="space-y-3 text-sm">
                    <li class="flex items-start gap-3">
                        <div class="w-7 h-7 rounded-lg glass-dark flex items-center justify-center flex-shrink-0 mt-0.5">
                            <svg class="w-3.5 h-3.5 text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <span class="text-green-100/80">info@rshsatubumi.id</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <div class="w-7 h-7 rounded-lg glass-dark flex items-center justify-center flex-shrink-0 mt-0.5">
                            <svg class="w-3.5 h-3.5 text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                        </div>
                        <span class="text-green-100/80">+62 816-677-225</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <div class="w-7 h-7 rounded-lg glass-dark flex items-center justify-center flex-shrink-0 mt-0.5">
                            <svg class="w-3.5 h-3.5 text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <span class="text-green-100/80">Senin – Sabtu : 09.00 – 16.00</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <div class="w-7 h-7 rounded-lg glass-dark flex items-center justify-center flex-shrink-0 mt-0.5">
                            <svg class="w-3.5 h-3.5 text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <span class="text-green-100/80">Jl. Bukit Pelangi KM 2,<br>Gn. Geulis, Sukaraja, Bogor</span>
                    </li>
                </ul>
            </div>

            {{-- Links --}}
            <div>
                <h4 class="text-base font-semibold mb-5 text-[#52c273]">Tautan</h4>
                <ul class="space-y-2">
                    @foreach([
                        ['label' => 'Anand Krishna', 'href' => '/anand-krishna', 'external' => false],
                        ['label' => 'Yayasan Anand Ashram', 'href' => '/yayasan-anand-ashram', 'external' => false],
                        ['label' => 'Anand Ashram Jakarta', 'href' => 'https://anandashram.or.id', 'external' => true],
                        ['label' => 'Anand Ashram Ubud', 'href' => 'https://anandashramubud.com', 'external' => true],
                        ['label' => 'Anand Krishna Centre Kuta', 'href' => 'https://akcbali.org/', 'external' => true],
                        ['label' => 'Anand Krishna Centre Singaraja', 'href' => '#', 'external' => false],
                        ['label' => 'Anand Krishna Centre Joglosemar', 'href' => 'https://yogameditasi.com', 'external' => true],
                        ['label' => 'One Earth Yoga & Meditation Retreat Centre Bogor', 'href' => 'https://oneearthretreat.com', 'external' => true],
                    ] as $link)
                    <li>
                        <a href="{{ $link['href'] }}" {{ $link['external'] ? 'target="_blank" rel="noopener noreferrer"' : '' }}
                           class="text-sm text-green-100/70 hover:text-[#f97316] transition-colors flex items-center gap-1.5">
                            <span class="w-1 h-1 rounded-full bg-green-500 flex-shrink-0"></span>
                            {{ $link['label'] }}
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <div class="mt-10 pt-6 border-t border-white/10 flex flex-col sm:flex-row items-center justify-between gap-3">
            <p class="text-sm text-green-100/50">&copy; {{ date('Y') }} Rumah Sehat Holistik Satu Bumi. All rights reserved.</p>
            <p class="text-xs text-green-100/30">Dibawah Naungan Yayasan Manusia Sehat Anand Krishna</p>
        </div>
    </div>
</footer>

@stack('scripts')
</body>
</html>
