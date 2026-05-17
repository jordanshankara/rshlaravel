<!DOCTYPE html>
<html lang="id" class="h-full bg-gray-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') — RSH Satu Bumi</title>
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
                        'admin': '#2d6a4f',
                    }
                }
            }
        }
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @stack('styles')
</head>
<body class="h-full" x-data="{ sidebarOpen: false }">
<div class="flex h-full min-h-screen">

    {{-- Desktop sidebar --}}
    <aside class="hidden lg:flex flex-col w-60 bg-white border-r border-gray-200 sticky top-0 h-screen overflow-y-auto flex-shrink-0">
        @include('layouts.partials.admin-sidebar')
    </aside>

    {{-- Mobile overlay --}}
    <div class="lg:hidden fixed inset-0 bg-black/40 z-40" x-show="sidebarOpen" x-cloak @click="sidebarOpen = false"></div>

    {{-- Mobile drawer --}}
    <aside class="lg:hidden fixed left-0 top-0 h-full w-60 bg-white z-50 shadow-xl flex flex-col overflow-y-auto"
           x-show="sidebarOpen" x-cloak>
        @include('layouts.partials.admin-sidebar')
    </aside>

    {{-- Main content --}}
    <div class="flex-1 flex flex-col min-w-0">
        <header class="sticky top-0 z-20 bg-white border-b border-gray-200 px-4 py-3 flex items-center gap-3">
            {{-- Burger — inline in header, not floating --}}
            <button class="lg:hidden p-1.5 rounded-lg text-gray-500 hover:bg-gray-100 transition-colors flex-shrink-0"
                    @click="sidebarOpen = !sidebarOpen">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path x-show="!sidebarOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    <path x-show="sidebarOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
            <h1 class="text-base font-semibold text-gray-800 flex-1">@yield('page-title', 'Dashboard')</h1>
            @yield('header-actions')
        </header>

        <main class="flex-1 overflow-y-auto p-6 bg-gray-50">
            @if(session('success'))
                <div class="mb-4 px-4 py-3 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm flex items-center gap-2">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-4 px-4 py-3 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm flex items-center gap-2">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    {{ session('error') }}
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
