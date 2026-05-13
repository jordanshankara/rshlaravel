<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'RSH Satu Bumi')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
    @stack('head')
</head>
<body class="bg-white text-gray-800" x-data="{ mobileMenu: false }">

<nav class="sticky top-0 z-50 bg-white/90 backdrop-blur border-b border-gray-100 shadow-sm">
    <div class="max-w-6xl mx-auto px-4 flex items-center justify-between h-16">
        <a href="{{ route('home') }}" class="text-xl font-bold text-emerald-700">RSH Satu Bumi</a>
        <div class="hidden md:flex items-center gap-8">
            <a href="{{ route('home') }}" class="text-sm font-medium text-gray-600 hover:text-emerald-700">Beranda</a>
            <a href="{{ route('artikel.index') }}" class="text-sm font-medium text-gray-600 hover:text-emerald-700">Artikel</a>
            <a href="{{ route('daftar.index') }}" class="text-sm font-medium text-gray-600 hover:text-emerald-700">Program</a>
            <a href="{{ route('daftar.confirm') }}" class="text-sm font-medium text-gray-600 hover:text-emerald-700">Cek Pendaftaran</a>
            <a href="{{ route('daftar.index') }}" class="px-4 py-2 bg-emerald-600 text-white text-sm font-semibold rounded-lg hover:bg-emerald-700 transition">Daftar Sekarang</a>
        </div>
        <button @click="mobileMenu = !mobileMenu" class="md:hidden p-2 rounded-md hover:bg-gray-100">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path x-show="!mobileMenu" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                <path x-show="mobileMenu" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>
    <div x-show="mobileMenu" class="md:hidden bg-white border-t px-4 py-3 space-y-2">
        <a href="{{ route('home') }}" class="block py-2 text-sm font-medium text-gray-700">Beranda</a>
        <a href="{{ route('artikel.index') }}" class="block py-2 text-sm font-medium text-gray-700">Artikel</a>
        <a href="{{ route('daftar.index') }}" class="block py-2 text-sm font-medium text-gray-700">Program</a>
        <a href="{{ route('daftar.confirm') }}" class="block py-2 text-sm font-medium text-gray-700">Cek Pendaftaran</a>
        <a href="{{ route('daftar.index') }}" class="block mt-2 px-4 py-2 bg-emerald-600 text-white text-sm font-semibold rounded-lg text-center">Daftar Sekarang</a>
    </div>
</nav>

<main>
    @yield('content')
</main>

<footer class="bg-gray-900 text-gray-300 mt-20">
    <div class="max-w-6xl mx-auto px-4 py-12 grid grid-cols-1 md:grid-cols-3 gap-8">
        <div>
            <div class="text-xl font-bold text-white mb-2">RSH Satu Bumi</div>
            <p class="text-sm leading-relaxed">Program kesehatan holistik untuk keseimbangan raga dan jiwa.</p>
        </div>
        <div>
            <div class="font-semibold text-white mb-3">Navigasi</div>
            <ul class="space-y-2 text-sm">
                <li><a href="{{ route('home') }}" class="hover:text-white">Beranda</a></li>
                <li><a href="{{ route('artikel.index') }}" class="hover:text-white">Artikel</a></li>
                <li><a href="{{ route('daftar.index') }}" class="hover:text-white">Daftar Program</a></li>
                <li><a href="{{ route('daftar.confirm') }}" class="hover:text-white">Cek Pendaftaran</a></li>
            </ul>
        </div>
        <div>
            <div class="font-semibold text-white mb-3">Kontak</div>
            <ul class="space-y-2 text-sm">
                <li>WhatsApp: <a href="#" class="hover:text-white">Hubungi Kami</a></li>
            </ul>
        </div>
    </div>
    <div class="border-t border-gray-800 py-4 text-center text-xs text-gray-500">
        &copy; {{ date('Y') }} RSH Satu Bumi. Hak Cipta Dilindungi.
    </div>
</footer>

@stack('scripts')
</body>
</html>
