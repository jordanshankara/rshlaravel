@extends('layouts.public')
@section('title', $article->title.' — RSH Satu Bumi')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-12">
    <a href="{{ route('artikel.index') }}" class="text-sm text-gray-500 hover:text-emerald-700 mb-6 inline-block">← Kembali ke Artikel</a>

    @if($article->cover_image)
    <div class="h-64 md:h-80 rounded-2xl overflow-hidden mb-8">
        <img src="{{ asset('storage/'.$article->cover_image) }}" alt="{{ $article->title }}" class="w-full h-full object-cover">
    </div>
    @endif

    <div class="flex flex-wrap gap-2 mb-4">
        @foreach($article->categories as $cat)
        <span class="text-xs font-semibold text-emerald-700 bg-emerald-50 px-3 py-1 rounded-full">{{ $cat->name }}</span>
        @endforeach
    </div>

    <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $article->title }}</h1>

    <div class="flex items-center gap-3 text-sm text-gray-500 mb-8 pb-8 border-b">
        <span>{{ $article->author?->name }}</span>
        <span>·</span>
        <span>{{ $article->published_at?->format('d M Y') }}</span>
    </div>

    <div class="prose prose-emerald max-w-none text-gray-700 leading-relaxed">
        {!! nl2br(e($article->content)) !!}
    </div>

    @if($related->isNotEmpty())
    <div class="mt-16 pt-8 border-t">
        <h3 class="font-bold text-xl mb-6">Artikel Lainnya</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @foreach($related as $r)
            <a href="{{ route('artikel.show', $r->slug) }}" class="group">
                <div class="font-semibold text-sm group-hover:text-emerald-700 transition line-clamp-2">{{ $r->title }}</div>
                <div class="text-xs text-gray-400 mt-1">{{ $r->published_at?->format('d M Y') }}</div>
            </a>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection
