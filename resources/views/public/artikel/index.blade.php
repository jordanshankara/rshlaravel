@extends('layouts.public')
@section('title', 'Artikel — RSH Satu Bumi')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-12">
    <h1 class="text-3xl font-bold mb-2">Artikel</h1>
    <p class="text-gray-500 mb-8">Pengetahuan dan insight untuk perjalanan kesehatan Anda</p>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @forelse($articles as $article)
        <a href="{{ route('artikel.show', $article->slug) }}" class="bg-white border rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition group">
            @if($article->cover_image)
            <div class="h-48 overflow-hidden">
                <img src="{{ asset('storage/'.$article->cover_image) }}" alt="{{ $article->title }}" class="w-full h-full object-cover group-hover:scale-105 transition">
            </div>
            @else
            <div class="h-48 bg-emerald-50 flex items-center justify-center">
                <span class="text-4xl">🌿</span>
            </div>
            @endif
            <div class="p-5">
                @foreach($article->categories as $cat)
                <span class="inline-block text-xs font-semibold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full mb-2">{{ $cat->name }}</span>
                @endforeach
                <h2 class="font-bold text-gray-800 mb-2 line-clamp-2 group-hover:text-emerald-700 transition">{{ $article->title }}</h2>
                <p class="text-sm text-gray-500 line-clamp-2">{{ $article->excerpt }}</p>
                <div class="flex items-center gap-2 mt-3">
                    <div class="text-xs text-gray-400">{{ $article->published_at?->format('d M Y') }}</div>
                    <span class="text-gray-300">·</span>
                    <div class="text-xs text-gray-400">{{ $article->author?->name }}</div>
                </div>
            </div>
        </a>
        @empty
        <div class="col-span-3 text-center py-16 text-gray-400">Belum ada artikel.</div>
        @endforelse
    </div>

    @if($articles->hasPages())
    <div class="mt-8">{{ $articles->links() }}</div>
    @endif
</div>
@endsection
