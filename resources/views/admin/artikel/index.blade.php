@extends('layouts.admin')
@section('title', 'Artikel')
@section('page-title', 'Artikel')
@section('content')

<div class="space-y-4" x-data="{ aiOpen: false, aiLoading: false }">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <h1 class="text-xl font-bold text-gray-900">Artikel</h1>
        <div class="flex items-center gap-2">
            <button @click="aiOpen = true"
                    class="flex-1 sm:flex-none flex items-center justify-center gap-1.5 px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-semibold rounded-lg transition-colors whitespace-nowrap">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
                Buat dengan AI
            </button>
            <a href="{{ route('admin.artikel.create') }}"
               class="flex-1 sm:flex-none flex items-center justify-center gap-1.5 px-4 py-2 bg-[#2d6a4f] hover:bg-[#1a5a3f] text-white text-sm font-semibold rounded-lg transition-colors whitespace-nowrap">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Tulis Baru
            </a>
        </div>
    </div>

    {{-- Search + Filter --}}
    <div class="flex flex-col sm:flex-row gap-3">
        <form method="GET" action="{{ route('admin.artikel.index') }}" class="flex-1 relative">
            <input type="hidden" name="status" value="{{ request('status') }}">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none"
                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/>
            </svg>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Cari artikel..."
                   class="w-full pl-9 pr-4 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-[#2d6a4f]/30 focus:outline-none bg-white">
        </form>
        <div class="flex gap-2 flex-wrap">
            @foreach(['' => ['label' => 'Semua', 'count' => $counts['all']], 'PUBLISHED' => ['label' => 'Dipublikasikan', 'count' => $counts['PUBLISHED']], 'DRAFT' => ['label' => 'Draft', 'count' => $counts['DRAFT']]] as $val => $cfg)
            <a href="{{ route('admin.artikel.index', array_filter(['status' => $val ?: null, 'search' => request('search') ?: null])) }}"
               class="px-4 py-2 text-sm font-medium rounded-lg transition-colors whitespace-nowrap flex items-center gap-1.5
                      {{ request('status', '') === $val
                         ? 'bg-[#2d6a4f] text-white'
                         : 'border border-gray-200 text-gray-600 hover:bg-gray-50' }}">
                {{ $cfg['label'] }}
                <span class="text-xs px-1.5 py-0.5 rounded-full {{ request('status', '') === $val ? 'bg-white/20 text-white' : 'bg-gray-100 text-gray-500' }}">{{ $cfg['count'] }}</span>
            </a>
            @endforeach
        </div>
    </div>

    @if(session('success'))
    <div class="p-3 bg-green-50 border border-green-200 rounded-lg text-sm text-green-700">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="p-3 bg-red-50 border border-red-200 rounded-lg text-sm text-red-700">
        {{ session('error') }}
    </div>
    @endif

    {{-- Table --}}
    <div class="bg-white rounded-xl border shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b bg-gray-50">
                    <th class="w-12 px-4 py-3 hidden sm:table-cell"></th>
                    <th class="text-left px-4 py-3 font-semibold text-gray-600">Judul</th>
                    <th class="text-left px-4 py-3 font-semibold text-gray-600 hidden sm:table-cell">Penulis</th>
                    <th class="text-left px-4 py-3 font-semibold text-gray-600 hidden sm:table-cell">Diperbarui</th>
                    <th class="text-left px-4 py-3 font-semibold text-gray-600 hidden sm:table-cell">Status</th>
                    <th class="text-right px-4 py-3 font-semibold text-gray-600">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($articles as $article)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-4 py-3 hidden sm:table-cell">
                        @if($article->cover_image)
                        <img src="{{ asset('storage/'.$article->cover_image) }}" alt=""
                             class="w-10 h-10 rounded-lg object-cover bg-gray-100">
                        @else
                        <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                            <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        <div class="font-medium text-gray-800 line-clamp-1">{{ $article->title }}</div>
                        @if($article->categories->isNotEmpty())
                        <div class="flex flex-wrap gap-1 mt-1">
                            @foreach($article->categories as $cat)
                            <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700">{{ $cat->name }}</span>
                            @endforeach
                        </div>
                        @endif
                        <div class="mt-1 sm:hidden">
                            @if($article->status === 'PUBLISHED')
                            <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium bg-green-50 text-green-700">Dipublikasikan</span>
                            @else
                            <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-50 text-yellow-700">Draft</span>
                            @endif
                        </div>
                    </td>
                    <td class="px-4 py-3 text-gray-500 hidden sm:table-cell">
                        {{ $article->author?->name ?? '—' }}
                    </td>
                    <td class="px-4 py-3 text-gray-500 hidden sm:table-cell whitespace-nowrap">
                        {{ $article->updated_at->format('d M Y') }}
                    </td>
                    <td class="px-4 py-3 hidden sm:table-cell">
                        @if($article->status === 'PUBLISHED')
                        <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium bg-green-50 text-green-700">Dipublikasikan</span>
                        @else
                        <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium bg-yellow-50 text-yellow-700">Draft</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center justify-end gap-1">
                            <a href="{{ route('admin.artikel.edit', $article) }}" title="Edit"
                               class="p-1.5 rounded-lg text-gray-400 hover:text-[#2d6a4f] hover:bg-[#2d6a4f]/10 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </a>
                            <form method="POST" action="{{ route('admin.artikel.destroy', $article) }}"
                                  @submit.prevent="adminConfirm('Hapus Artikel', 'Artikel ini akan dihapus permanen.', {danger:true, okLabel:'Ya, Hapus'}).then(ok => ok && $el.submit())">
                                @csrf @method('DELETE')
                                <button type="submit" title="Hapus"
                                        class="p-1.5 rounded-lg text-gray-400 hover:text-red-500 hover:bg-red-50 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-4 py-10 text-center text-gray-400 text-sm">
                        Belum ada artikel.
                        <a href="{{ route('admin.artikel.create') }}" class="text-[#2d6a4f] hover:underline ml-1">Tulis sekarang →</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($articles->hasPages())
    <div class="flex items-center justify-between text-sm text-gray-500">
        <span>{{ $articles->currentPage() }} / {{ $articles->lastPage() }}</span>
        <div class="flex gap-2">
            @if($articles->onFirstPage())
            <span class="px-3 py-1.5 border border-gray-200 rounded-lg text-gray-300 cursor-not-allowed">← Sebelumnya</span>
            @else
            <a href="{{ $articles->previousPageUrl() }}"
               class="px-3 py-1.5 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">← Sebelumnya</a>
            @endif

            @if($articles->hasMorePages())
            <a href="{{ $articles->nextPageUrl() }}"
               class="px-3 py-1.5 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">Berikutnya →</a>
            @else
            <span class="px-3 py-1.5 border border-gray-200 rounded-lg text-gray-300 cursor-not-allowed">Berikutnya →</span>
            @endif
        </div>
    </div>
    @endif

    {{-- AI Modal --}}
    <div x-show="aiOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/50" @click="aiOpen = false"></div>
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="font-bold text-gray-900 text-lg flex items-center gap-2">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                        Buat Artikel dengan AI
                    </h2>
                    <p class="text-xs text-gray-400 mt-0.5">AI akan mengisi judul, ringkasan, dan konten artikel secara otomatis.</p>
                </div>
                <button @click="aiOpen = false" class="text-gray-400 hover:text-gray-600 ml-4 flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form method="POST" action="{{ route('admin.artikel.ai-generate') }}"
                  @submit="aiLoading = true" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Topik / Arah Pembahasan <span class="text-red-500">*</span>
                    </label>
                    <textarea name="topic" rows="3" required
                              placeholder="Contoh: manfaat meditasi harian untuk kesehatan mental dan fisik"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-purple-500/30 focus:border-purple-500 focus:outline-none resize-none"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Bahan Sumber / Referensi
                        <span class="text-xs text-gray-400 font-normal">(opsional)</span>
                    </label>
                    <textarea name="sources" rows="3"
                              placeholder="Tempel link, kutipan, atau poin-poin referensi di sini..."
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-purple-500/30 focus:border-purple-500 focus:outline-none resize-none"></textarea>
                </div>
                <div class="flex gap-3 pt-1">
                    <button type="button" @click="aiOpen = false"
                            class="flex-1 px-4 py-2.5 border border-gray-200 text-gray-600 text-sm rounded-lg hover:bg-gray-50 transition-colors">
                        Batal
                    </button>
                    <button type="submit" :disabled="aiLoading"
                            class="flex-1 px-4 py-2.5 bg-purple-600 text-white text-sm font-semibold rounded-lg hover:bg-purple-700 disabled:opacity-60 transition-colors flex items-center justify-center gap-2">
                        <span x-show="!aiLoading">Generate →</span>
                        <span x-show="aiLoading" class="flex items-center gap-2">
                            <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                            </svg>
                            Membuat artikel...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

@endsection
