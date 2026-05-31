@extends('layouts.admin')
@section('title', 'History Database Kontak')
@section('page-title', 'History Database Kontak')
@section('header-actions')
<a href="{{ route('admin.kontak.index') }}" class="text-sm text-gray-500 hover:text-gray-700">← Kembali ke Kontak</a>
@endsection
@section('content')

<div class="max-w-2xl">

    <div class="mb-5 p-4 bg-blue-50 border border-blue-200 rounded-xl text-sm text-blue-700">
        <p class="font-semibold mb-1">ℹ️ Cara kerja History</p>
        <p>Setiap kali data kontak berubah (import, edit, hapus), sistem menyimpan snapshot kondisi sebelumnya.
        Maksimal <strong>3 kondisi</strong> tersimpan. Anda bisa melihat apa yang berubah dan memulihkan ke kondisi sebelumnya.</p>
    </div>

    @if ($histories->isEmpty())
    <div class="bg-white rounded-xl border shadow-sm p-10 text-center">
        <p class="text-3xl mb-3">🕓</p>
        <p class="text-gray-500 text-sm">Belum ada history. History akan terbuat saat data kontak pertama kali diubah.</p>
    </div>
    @else
    <div class="space-y-4">
        @foreach ($histories as $i => $h)
        <div class="bg-white rounded-xl border shadow-sm p-5">
            <div class="flex items-start justify-between gap-4">
                <div class="flex-1">
                    <div class="flex items-center gap-2 mb-1">
                        <span class="text-base">{{ $h->actionLabel() }}</span>
                        <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-500">
                            Kondisi {{ $histories->count() - $i }}
                        </span>
                    </div>
                    <p class="text-sm text-gray-700 font-medium">{{ $h->description }}</p>
                    <div class="flex items-center gap-3 mt-2 text-xs text-gray-400">
                        <span>{{ $h->createdAtWib() }}</span>
                        <span>·</span>
                        <span>{{ number_format($h->affected_count) }} kontak terpengaruh</span>
                        @if($h->user)
                        <span>·</span>
                        <span>oleh {{ $h->user->name }}</span>
                        @endif
                    </div>
                </div>
                <a href="{{ route('admin.kontak.history.show', $h) }}"
                   class="flex-shrink-0 inline-flex items-center gap-1.5 px-4 py-2 text-sm bg-[#2d6a4f] text-white rounded-lg hover:bg-[#1a5a3f] transition-colors">
                    Lihat & Pulihkan →
                </a>
            </div>
        </div>
        @endforeach

        <p class="text-xs text-gray-400 text-center pt-2">
            Menampilkan {{ $histories->count() }} dari maksimal 3 history tersimpan.
            History terlama otomatis dihapus saat ada operasi baru.
        </p>
    </div>
    @endif

</div>
@endsection
