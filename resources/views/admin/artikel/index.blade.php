@extends('layouts.admin')
@section('title', 'Artikel')
@section('page-title', 'Artikel')
@section('header-actions')
<a href="{{ route('admin.artikel.create') }}" class="px-4 py-2 bg-[#2d6a4f] text-white text-sm font-semibold rounded-lg hover:bg-[#1a5a3f] transition-colors">+ Artikel Baru</a>
@endsection
@section('content')
<div class="bg-white rounded-xl border shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="px-4 py-3 text-left font-semibold text-gray-600">Judul</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-600">Penulis</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-600">Status</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-600">Tanggal</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-600">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($articles as $article)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3">
                        <div class="font-medium line-clamp-1">{{ $article->title }}</div>
                        <div class="text-xs text-gray-400 font-mono">/artikel/{{ $article->slug }}</div>
                    </td>
                    <td class="px-4 py-3 text-xs text-gray-600">{{ $article->author?->name }}</td>
                    <td class="px-4 py-3">
                        <span class="text-xs px-2 py-0.5 rounded-full {{ $article->status === 'PUBLISHED' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                            {{ $article->status }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-xs text-gray-500">{{ $article->created_at->format('d M Y') }}</td>
                    <td class="px-4 py-3 flex items-center gap-3">
                        <a href="{{ route('admin.artikel.edit', $article) }}" class="text-[#2d6a4f] hover:underline text-xs font-medium">Edit</a>
                        <form method="POST" action="{{ route('admin.artikel.destroy', $article) }}" onsubmit="return confirm('Hapus artikel ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-500 hover:underline text-xs">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-4 py-8 text-center text-gray-400">Belum ada artikel.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($articles->hasPages())
    <div class="px-4 py-3 border-t">{{ $articles->links() }}</div>
    @endif
</div>
@endsection
