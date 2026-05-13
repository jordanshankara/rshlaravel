<!DOCTYPE html>
<html lang="id" class="h-full bg-gray-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') — RSH Satu Bumi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="h-full" x-data="{ sidebarOpen: false }">
<div class="flex h-full">
    {{-- Sidebar --}}
    <aside class="fixed inset-y-0 left-0 z-40 w-64 bg-emerald-900 text-white flex flex-col transform transition-transform duration-200 ease-in-out"
           :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'">
        <div class="flex items-center gap-3 px-6 py-5 border-b border-emerald-700">
            <span class="text-xl font-bold">RSH Admin</span>
        </div>
        <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('admin.dashboard') ? 'bg-emerald-700' : 'hover:bg-emerald-800' }}">
                <i class="fa-solid fa-gauge-high w-5 text-center"></i> Dashboard
            </a>
            <a href="{{ route('admin.registrasi.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('admin.registrasi.*') ? 'bg-emerald-700' : 'hover:bg-emerald-800' }}">
                <i class="fa-solid fa-clipboard-list w-5 text-center"></i> Registrasi
            </a>
            <a href="{{ route('admin.invoice.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('admin.invoice.*') ? 'bg-emerald-700' : 'hover:bg-emerald-800' }}">
                <i class="fa-solid fa-file-invoice w-5 text-center"></i> Invoice
            </a>
            <a href="{{ route('admin.artikel.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('admin.artikel.*') ? 'bg-emerald-700' : 'hover:bg-emerald-800' }}">
                <i class="fa-solid fa-newspaper w-5 text-center"></i> Artikel
            </a>
            @if(auth()->user()->role === 'ADMIN')
            <a href="{{ route('admin.program.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('admin.program.*') ? 'bg-emerald-700' : 'hover:bg-emerald-800' }}">
                <i class="fa-solid fa-calendar-days w-5 text-center"></i> Program
            </a>
            <a href="{{ route('admin.pengguna.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('admin.pengguna.*') ? 'bg-emerald-700' : 'hover:bg-emerald-800' }}">
                <i class="fa-solid fa-users w-5 text-center"></i> Pengguna
            </a>
            <a href="{{ route('admin.pengaturan.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('admin.pengaturan.*') ? 'bg-emerald-700' : 'hover:bg-emerald-800' }}">
                <i class="fa-solid fa-gear w-5 text-center"></i> Pengaturan
            </a>
            @endif
        </nav>
        <div class="px-4 py-4 border-t border-emerald-700">
            <div class="text-sm font-medium mb-1">{{ auth()->user()->name }}</div>
            <div class="text-xs text-emerald-300 mb-3">{{ auth()->user()->role }}</div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center gap-2 text-sm text-emerald-300 hover:text-white">
                    <i class="fa-solid fa-right-from-bracket"></i> Keluar
                </button>
            </form>
        </div>
    </aside>

    {{-- Overlay --}}
    <div class="fixed inset-0 z-30 bg-black/50 lg:hidden" x-show="sidebarOpen" @click="sidebarOpen = false"></div>

    {{-- Main --}}
    <div class="flex-1 flex flex-col min-w-0 lg:ml-64">
        <header class="sticky top-0 z-20 bg-white border-b px-4 py-3 flex items-center gap-4">
            <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden p-2 rounded-md hover:bg-gray-100">
                <i class="fa-solid fa-bars"></i>
            </button>
            <h1 class="text-lg font-semibold text-gray-800 flex-1">@yield('page-title', 'Dashboard')</h1>
            @yield('header-actions')
        </header>

        <main class="flex-1 overflow-y-auto p-6">
            @if(session('success'))
                <div class="mb-4 px-4 py-3 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm">
                    {{ session('success') }}
                </div>
            @endif
            @if($errors->any())
                <div class="mb-4 px-4 py-3 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @yield('content')
        </main>
    </div>
</div>
@stack('scripts')
</body>
</html>
