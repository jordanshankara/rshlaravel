@extends('layouts.public')
@section('title', ($settings['site_name'] ?? 'RSH Satu Bumi').' — '.($settings['site_tagline'] ?? 'Program Kesehatan Holistik'))

@section('content')
{{-- Hero --}}
<section class="relative bg-gradient-to-br from-emerald-700 to-teal-800 text-white py-24 md:py-32">
    <div class="max-w-4xl mx-auto px-4 text-center">
        <h1 class="text-4xl md:text-5xl font-bold leading-tight mb-6">
            {{ $settings['site_tagline'] ?? 'Program 7 Hari Menuju Sehat Raga & Jiwa' }}
        </h1>
        <p class="text-lg text-emerald-100 max-w-2xl mx-auto mb-8">
            {{ $settings['program_description'] ?? 'Sebuah perjalanan transformasi kesehatan yang holistik, menyelaraskan tubuh, pikiran, dan jiwa.' }}
        </p>
        <a href="{{ route('daftar.index') }}" class="inline-block px-8 py-3.5 bg-white text-emerald-700 font-bold rounded-xl text-lg hover:shadow-lg transition">
            Daftar Program Sekarang
        </a>
    </div>
</section>

{{-- Active Periods --}}
@if($activePeriods->isNotEmpty())
<section class="py-16">
    <div class="max-w-6xl mx-auto px-4">
        <h2 class="text-2xl font-bold text-center mb-2">Jadwal Program Tersedia</h2>
        <p class="text-center text-gray-500 mb-10">Pilih jadwal yang sesuai dengan Anda</p>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($activePeriods as $period)
            <div class="bg-white border rounded-2xl p-6 shadow-sm hover:shadow-md transition">
                <h3 class="font-bold text-lg mb-1">{{ $period->name }}</h3>
                <p class="text-sm text-gray-500 mb-4">
                    {{ $period->start_date->format('d M Y') }} – {{ $period->end_date->format('d M Y') }}
                </p>
                <div class="mb-4">
                    <div class="flex justify-between text-xs mb-1 text-gray-500">
                        <span>Kuota tersisa</span>
                        <span>{{ $period->quota - $period->filled }} / {{ $period->quota }}</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-1.5">
                        <div class="bg-emerald-500 h-1.5 rounded-full" style="width: {{ $period->quota > 0 ? min(100, round($period->filled / $period->quota * 100)) : 0 }}%"></div>
                    </div>
                </div>
                <div class="text-2xl font-bold text-emerald-700 mb-1">Rp {{ number_format($period->price, 0, ',', '.') }}</div>
                <div class="text-xs text-gray-500 mb-4">DP: Rp {{ number_format($period->dp_amount, 0, ',', '.') }}</div>
                @if($period->filled < $period->quota)
                <a href="{{ route('daftar.index') }}" class="block text-center py-2.5 bg-emerald-600 text-white font-semibold rounded-xl hover:bg-emerald-700 transition">Daftar Sekarang</a>
                @else
                <div class="text-center py-2.5 bg-gray-100 text-gray-400 font-semibold rounded-xl cursor-not-allowed">Kuota Penuh</div>
                @endif
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- Latest Articles --}}
@if($latestArticles->isNotEmpty())
<section class="py-16 bg-gray-50">
    <div class="max-w-6xl mx-auto px-4">
        <h2 class="text-2xl font-bold text-center mb-2">Artikel Terbaru</h2>
        <p class="text-center text-gray-500 mb-10">Pengetahuan untuk perjalanan kesehatan Anda</p>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($latestArticles as $article)
            <a href="{{ route('artikel.show', $article->slug) }}" class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition group">
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
                    <h3 class="font-bold text-gray-800 mb-2 line-clamp-2 group-hover:text-emerald-700 transition">{{ $article->title }}</h3>
                    <p class="text-sm text-gray-500 line-clamp-2">{{ $article->excerpt }}</p>
                    <div class="text-xs text-gray-400 mt-3">{{ $article->published_at?->format('d M Y') }}</div>
                </div>
            </a>
            @endforeach
        </div>
        <div class="text-center mt-8">
            <a href="{{ route('artikel.index') }}" class="inline-block px-6 py-2.5 border border-emerald-600 text-emerald-600 font-semibold rounded-xl hover:bg-emerald-50 transition">Lihat Semua Artikel</a>
        </div>
    </div>
</section>
@endif
@endsection
