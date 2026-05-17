@extends('layouts.public')
@section('title', $article->title.' — RSH Satu Bumi')

@section('content')

{{-- Page Header --}}
<div class="relative h-64 overflow-hidden">
    <img src="{{ asset('assets/green.webp') }}" alt="" class="absolute inset-0 w-full h-full object-cover">
    <div class="absolute inset-0" style="background: linear-gradient(to bottom, rgba(5,46,22,0.75), rgba(13,61,26,0.85))"></div>
    <div class="relative h-full flex flex-col items-center justify-center text-center px-4">
        <a href="{{ route('artikel.index') }}" class="text-green-200/70 hover:text-[#f97316] text-xs mb-4 transition-colors">← Kembali ke Artikel</a>
        <div class="flex flex-wrap gap-2 justify-center mb-3">
            @foreach($article->categories as $cat)
            <span class="text-xs font-semibold text-white bg-[#1a6b2f]/80 px-3 py-1 rounded-full">{{ $cat->name }}</span>
            @endforeach
        </div>
        <h1 class="text-2xl sm:text-3xl font-bold text-white max-w-2xl leading-snug">{{ $article->title }}</h1>
    </div>
</div>

<div class="max-w-3xl mx-auto px-4 py-12">
    @if($article->cover_image)
    <div class="h-72 md:h-96 rounded-2xl overflow-hidden mb-8 -mt-8 shadow-[0_8px_40px_rgba(13,61,26,0.18)]">
        <img src="{{ asset('storage/'.$article->cover_image) }}" alt="{{ $article->title }}"
             class="w-full h-full object-cover">
    </div>
    @endif

    <div class="glass-card rounded-2xl p-8 mb-8">
        <div class="flex items-center gap-3 text-sm text-gray-500 mb-8 pb-6 border-b border-gray-100">
            @if($article->author)
            <div class="w-8 h-8 rounded-full bg-[#1a6b2f]/10 flex items-center justify-center flex-shrink-0">
                <span class="text-[#1a6b2f] text-xs font-bold uppercase">{{ substr($article->author->name, 0, 1) }}</span>
            </div>
            <span class="font-medium text-gray-700">{{ $article->author->name }}</span>
            <span class="text-gray-300">·</span>
            @endif
            <span>{{ $article->published_at?->format('d M Y') }}</span>
        </div>

        <div class="prose max-w-none text-gray-700 leading-relaxed">
            {!! $article->content !!}
        </div>
    </div>

    @if($related->isNotEmpty())
    <div class="mt-4">
        <h3 class="font-bold text-xl text-[#0d3d1a] mb-6">Artikel Lainnya</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @foreach($related as $r)
            <a href="{{ route('artikel.show', $r->slug) }}"
               class="glass-card rounded-xl p-4 hover:shadow-[0_8px_24px_rgba(13,61,26,0.12)] transition-all duration-300 group">
                <div class="font-semibold text-sm text-[#0d3d1a] group-hover:text-[#1a6b2f] transition-colors line-clamp-2 mb-2">{{ $r->title }}</div>
                <div class="text-xs text-gray-400">{{ $r->published_at?->format('d M Y') }}</div>
            </a>
            @endforeach
        </div>
    </div>
    @endif
</div>

@endsection
