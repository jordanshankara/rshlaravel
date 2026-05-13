@extends('layouts.public')
@section('title', 'Artikel — RSH Satu Bumi')

@section('content')

{{-- Page Header --}}
<div class="relative h-64 overflow-hidden">
    <img src="{{ asset('assets/green.webp') }}" alt="" class="absolute inset-0 w-full h-full object-cover">
    <div class="absolute inset-0" style="background: linear-gradient(to bottom, rgba(5,46,22,0.75), rgba(13,61,26,0.85))"></div>
    <div class="relative h-full flex flex-col items-center justify-center text-center px-4">
        <span class="text-[#f97316] uppercase tracking-widest text-xs font-bold mb-3">PENGETAHUAN &amp; INSIGHT</span>
        <h1 class="text-3xl sm:text-4xl font-bold text-white mb-3">Artikel Kesehatan</h1>
        <p class="text-green-200/80 text-sm max-w-md">Pengetahuan dan insight untuk perjalanan kesehatan holistik Anda</p>
    </div>
</div>

<div class="max-w-6xl mx-auto px-4 py-12">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @forelse($articles as $article)
        <a href="{{ route('artikel.show', $article->slug) }}"
           class="glass-card rounded-2xl overflow-hidden hover:-translate-y-1 hover:shadow-[0_16px_48px_rgba(13,61,26,0.15)] transition-all duration-300 group">
            @if($article->cover_image)
            <div class="h-48 overflow-hidden">
                <img src="{{ asset('storage/'.$article->cover_image) }}" alt="{{ $article->title }}"
                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
            </div>
            @else
            <div class="h-48 flex items-center justify-center" style="background: linear-gradient(135deg, #e8f5e9, #d4edda)">
                <span class="text-5xl">🌿</span>
            </div>
            @endif
            <div class="p-5">
                <div class="flex flex-wrap gap-1.5 mb-3">
                    @foreach($article->categories as $cat)
                    <span class="text-xs font-semibold text-[#1a6b2f] bg-green-50 px-2.5 py-0.5 rounded-full">{{ $cat->name }}</span>
                    @endforeach
                </div>
                <h2 class="font-bold text-[#0d3d1a] mb-2 line-clamp-2 group-hover:text-[#1a6b2f] transition-colors">{{ $article->title }}</h2>
                <p class="text-sm text-gray-500 line-clamp-2 mb-3">{{ $article->excerpt }}</p>
                <div class="flex items-center gap-2">
                    <span class="text-xs text-gray-400">{{ $article->published_at?->format('d M Y') }}</span>
                    @if($article->author)
                    <span class="text-gray-300">·</span>
                    <span class="text-xs text-gray-400">{{ $article->author->name }}</span>
                    @endif
                </div>
            </div>
        </a>
        @empty
        <div class="col-span-3 text-center py-20">
            <div class="text-5xl mb-4">🌿</div>
            <p class="text-gray-400">Belum ada artikel tersedia.</p>
        </div>
        @endforelse
    </div>

    @if($articles->hasPages())
    <div class="mt-10">{{ $articles->links() }}</div>
    @endif
</div>

@endsection
