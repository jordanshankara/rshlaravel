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
<body class="h-full" x-data="adminApp()">
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
{{-- ── Global Confirm Modal ──────────────────────────────────────────────── --}}
<div x-show="confirm.show" x-cloak
     class="fixed inset-0 z-[60] flex items-center justify-center p-4"
     x-transition:enter="transition ease-out duration-200"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100">
    {{-- Backdrop --}}
    <div class="absolute inset-0 bg-black/40" @click="confirm.cancel()"></div>
    {{-- Card --}}
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-sm p-6 z-10"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         @keydown.escape.window="confirm.cancel()">
        {{-- Icon --}}
        <div class="flex items-center gap-3 mb-3">
            <div class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0"
                 :class="confirm.danger ? 'bg-red-100' : 'bg-amber-100'">
                <svg class="w-5 h-5" :class="confirm.danger ? 'text-red-600' : 'text-amber-600'"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                </svg>
            </div>
            <h3 class="text-base font-semibold text-gray-900" x-text="confirm.title"></h3>
        </div>
        <p class="text-sm text-gray-500 mb-6 leading-relaxed" x-text="confirm.message"></p>
        <div class="flex gap-3 justify-end">
            <button @click="confirm.cancel()"
                    class="px-4 py-2 text-sm font-medium text-gray-600 border border-gray-300 rounded-xl hover:bg-gray-50 transition-colors">
                Batal
            </button>
            <button @click="confirm.ok()"
                    class="px-4 py-2 text-sm font-semibold text-white rounded-xl transition-colors"
                    :class="confirm.danger ? 'bg-red-500 hover:bg-red-600' : 'bg-[#2d6a4f] hover:bg-[#1a5a3f]'"
                    x-text="confirm.okLabel">
            </button>
        </div>
    </div>
</div>

<script>
function adminApp() {
    return {
        sidebarOpen: false,
        confirm: {
            show: false,
            title: '',
            message: '',
            okLabel: 'Ya, Lanjutkan',
            danger: false,
            _resolve: null,
            ok()    { this.show = false; if (this._resolve) this._resolve(true);  this._resolve = null; },
            cancel(){ this.show = false; if (this._resolve) this._resolve(false); this._resolve = null; },
        },
    };
}

/**
 * Replace browser confirm() with the themed modal.
 * Usage: await adminConfirm('Judul', 'Pesan', { danger: true, okLabel: 'Hapus' })
 * Returns a Promise<boolean>.
 */
window.adminConfirm = function(title, message, opts = {}) {
    return new Promise(resolve => {
        const app = document.querySelector('[x-data]').__x?.$data ?? Alpine.$data(document.body);
        app.confirm.title   = title;
        app.confirm.message = message;
        app.confirm.okLabel = opts.okLabel  ?? 'Ya, Lanjutkan';
        app.confirm.danger  = opts.danger   ?? false;
        app.confirm._resolve = resolve;
        app.confirm.show    = true;
    });
};
</script>

@stack('scripts')
</body>
</html>
