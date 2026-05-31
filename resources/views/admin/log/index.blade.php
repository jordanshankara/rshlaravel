@extends('layouts.admin')
@section('title', 'Log Aplikasi')
@section('page-title', 'Log Aplikasi')
@section('content')

@php
$levelConfig = [
    'error'     => ['bg' => 'bg-red-100',    'text' => 'text-red-700',    'border' => 'border-red-200',    'badge' => 'bg-red-100 text-red-700',     'label' => 'ERROR'],
    'warning'   => ['bg' => 'bg-amber-50',   'text' => 'text-amber-700',  'border' => 'border-amber-200',  'badge' => 'bg-amber-100 text-amber-700', 'label' => 'WARNING'],
    'notice'    => ['bg' => 'bg-blue-50',    'text' => 'text-blue-700',   'border' => 'border-blue-200',   'badge' => 'bg-blue-100 text-blue-700',   'label' => 'NOTICE'],
    'info'      => ['bg' => 'bg-sky-50',     'text' => 'text-sky-700',    'border' => 'border-sky-200',    'badge' => 'bg-sky-100 text-sky-700',     'label' => 'INFO'],
    'debug'     => ['bg' => 'bg-gray-50',    'text' => 'text-gray-600',   'border' => 'border-gray-200',   'badge' => 'bg-gray-100 text-gray-600',   'label' => 'DEBUG'],
    'critical'  => ['bg' => 'bg-red-50',     'text' => 'text-red-800',    'border' => 'border-red-300',    'badge' => 'bg-red-200 text-red-800',     'label' => 'CRITICAL'],
    'alert'     => ['bg' => 'bg-orange-50',  'text' => 'text-orange-700', 'border' => 'border-orange-200', 'badge' => 'bg-orange-100 text-orange-700','label' => 'ALERT'],
    'emergency' => ['bg' => 'bg-red-50',     'text' => 'text-red-900',    'border' => 'border-red-400',    'badge' => 'bg-red-300 text-red-900',     'label' => 'EMERGENCY'],
];
@endphp

<div class="space-y-4">

    {{-- Toolbar --}}
    <div class="bg-white rounded-xl border shadow-sm p-4">
        <div class="flex flex-col sm:flex-row sm:items-center gap-3">

            {{-- Search --}}
            <form method="GET" action="{{ route('admin.log.index') }}" class="flex-1 flex gap-2">
                <input type="hidden" name="level" value="{{ $level }}">
                <input type="text" name="q" value="{{ $search }}"
                       placeholder="Cari pesan error..."
                       class="flex-1 px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-[#2d6a4f]/30 focus:outline-none">
                <button type="submit"
                        class="px-4 py-2 bg-[#2d6a4f] text-white text-sm font-medium rounded-lg hover:bg-[#1a5a3f] transition-colors">
                    Cari
                </button>
                @if($search)
                <a href="{{ route('admin.log.index', ['level' => $level]) }}"
                   class="px-3 py-2 border rounded-lg text-sm text-gray-500 hover:bg-gray-50 transition-colors">
                    Reset
                </a>
                @endif
            </form>

            {{-- Clear --}}
            <form method="POST" action="{{ route('admin.log.clear') }}"
                  @submit.prevent="adminConfirm('Hapus Semua Log', 'Semua log error akan dihapus permanen. Tindakan ini tidak dapat dibatalkan.', {danger:true, okLabel:'Ya, Hapus Semua'}).then(ok => ok && $el.submit())">
                @csrf
                <button type="submit"
                        class="px-4 py-2 border border-red-200 text-red-600 text-sm font-medium rounded-lg hover:bg-red-50 transition-colors whitespace-nowrap">
                    Hapus Log
                </button>
            </form>
        </div>

        {{-- Level filter tabs --}}
        <div class="flex flex-wrap gap-1.5 mt-3">
            <a href="{{ route('admin.log.index', array_filter(['q' => $search])) }}"
               class="px-3 py-1 rounded-full text-xs font-medium transition-colors
                      {{ $level === 'all' ? 'bg-[#2d6a4f] text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                Semua
                @if(!empty($counts))
                <span class="ml-1 opacity-70">{{ array_sum($counts) }}</span>
                @endif
            </a>
            @foreach(['error','critical','emergency','warning','alert','notice','info','debug'] as $lvl)
            @if(isset($counts[$lvl]) || $level === $lvl)
            <a href="{{ route('admin.log.index', array_filter(['level' => $lvl, 'q' => $search])) }}"
               class="px-3 py-1 rounded-full text-xs font-medium transition-colors
                      {{ $level === $lvl
                           ? ($levelConfig[$lvl]['badge'] ?? 'bg-gray-200 text-gray-700') . ' ring-2 ring-offset-1 ring-current'
                           : ($levelConfig[$lvl]['badge'] ?? 'bg-gray-100 text-gray-600') . ' opacity-80 hover:opacity-100' }}">
                {{ strtoupper($lvl) }}
                @if(isset($counts[$lvl]))
                <span class="ml-1 opacity-70">{{ $counts[$lvl] }}</span>
                @endif
            </a>
            @endif
            @endforeach
        </div>
    </div>

    {{-- Success flash --}}
    @if(session('success'))
    <div class="bg-green-50 border border-green-200 rounded-xl px-4 py-3 text-sm text-green-700">
        {{ session('success') }}
    </div>
    @endif

    {{-- Entries --}}
    @if(empty($entries))
    <div class="bg-white rounded-xl border shadow-sm p-12 text-center">
        <svg class="w-10 h-10 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                  d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
        <p class="text-sm text-gray-400">Tidak ada log{{ $search ? ' yang cocok dengan pencarian' : '' }}.</p>
    </div>
    @else
    <div class="text-xs text-gray-400 px-1">
        Menampilkan {{ count($entries) }} entri terbaru
        @if($search) — hasil pencarian "<strong>{{ $search }}</strong>" @endif
    </div>

    <div class="space-y-1.5" x-data>
        @foreach($entries as $i => $entry)
        @php
            $cfg = $levelConfig[$entry['level']] ?? $levelConfig['debug'];
            $hasTrace = trim($entry['trace']) !== '';
            $shortMsg = mb_strimwidth($entry['message'], 0, 180, '…');
            $isLong   = mb_strlen($entry['message']) > 180;
        @endphp
        <div class="bg-white rounded-lg border {{ $cfg['border'] }} shadow-sm overflow-hidden"
             x-data="{ open: false, msgOpen: false }">
            <div class="flex items-start gap-3 px-4 py-3 cursor-pointer select-none"
                 @click="open = !open">

                {{-- Level badge --}}
                <span class="mt-0.5 px-2 py-0.5 rounded text-[10px] font-bold tracking-wide uppercase flex-shrink-0
                             {{ $cfg['badge'] }}">
                    {{ $cfg['label'] ?? strtoupper($entry['level']) }}
                </span>

                {{-- Message --}}
                <div class="flex-1 min-w-0">
                    <p class="text-sm text-gray-800 break-words leading-snug" x-show="!msgOpen">{{ $shortMsg }}</p>
                    <p class="text-sm text-gray-800 break-words leading-snug" x-show="msgOpen" x-cloak>{{ $entry['message'] }}</p>
                    @if($isLong)
                    <button @click.stop="msgOpen = !msgOpen"
                            class="text-xs text-[#2d6a4f] hover:underline mt-0.5">
                        <span x-show="!msgOpen">Tampilkan selengkapnya</span>
                        <span x-show="msgOpen" x-cloak>Sembunyikan</span>
                    </button>
                    @endif
                </div>

                {{-- Timestamp + expand icon --}}
                <div class="flex items-center gap-2 flex-shrink-0">
                    <span class="text-xs text-gray-400 hidden sm:block">{{ $entry['datetime'] }}</span>
                    @if($hasTrace)
                    <svg class="w-4 h-4 text-gray-400 transition-transform"
                         :class="open ? 'rotate-180' : ''"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                    @endif
                </div>
            </div>

            {{-- Timestamp mobile --}}
            <div class="px-4 pb-2 -mt-1 sm:hidden">
                <span class="text-xs text-gray-400">{{ $entry['datetime'] }}</span>
            </div>

            {{-- Stack trace --}}
            @if($hasTrace)
            <div x-show="open" x-cloak
                 class="border-t {{ $cfg['border'] }} bg-gray-950 px-4 py-3 overflow-x-auto">
                <pre class="text-xs text-gray-300 whitespace-pre-wrap break-words leading-relaxed font-mono">{{ trim($entry['trace']) }}</pre>
            </div>
            @endif
        </div>
        @endforeach
    </div>
    @endif

</div>
@endsection
