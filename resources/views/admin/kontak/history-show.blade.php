@extends('layouts.admin')
@section('title', 'Preview Pemulihan')
@section('page-title', 'Preview Pemulihan')
@section('header-actions')
<a href="{{ route('admin.kontak.history') }}" class="text-sm text-gray-500 hover:text-gray-700">← Kembali ke History</a>
@endsection
@section('content')

<div class="max-w-2xl space-y-5">

    {{-- Info card --}}
    <div class="bg-white rounded-xl border shadow-sm p-5">
        <p class="text-xs text-gray-400 mb-1">Memulihkan ke kondisi sebelum:</p>
        <p class="text-base font-semibold text-gray-900">{{ $history->actionLabel() }} — {{ $history->description }}</p>
        <p class="text-xs text-gray-400 mt-1">{{ $history->createdAtWib() }}</p>
    </div>

    {{-- What will happen --}}
    <div class="bg-amber-50 border border-amber-200 rounded-xl p-5">
        <p class="text-sm font-semibold text-amber-800 mb-3">⚠️ Yang akan terjadi setelah dipulihkan:</p>
        <div class="space-y-2 text-sm text-amber-700">
            @if ($preview['toDelete'] > 0)
            <div class="flex items-center gap-2">
                <span class="w-6 h-6 rounded-full bg-red-100 text-red-600 text-xs font-bold flex items-center justify-center flex-shrink-0">{{ $preview['toDelete'] }}</span>
                <span>kontak yang ditambahkan akan <strong>dihapus</strong></span>
            </div>
            @endif
            @if ($preview['toRestoreOld'] > 0)
            <div class="flex items-center gap-2">
                <span class="w-6 h-6 rounded-full bg-yellow-100 text-yellow-700 text-xs font-bold flex items-center justify-center flex-shrink-0">{{ $preview['toRestoreOld'] }}</span>
                <span>kontak akan dikembalikan ke <strong>nilai lama</strong></span>
            </div>
            @endif
            @if ($preview['toReinsert'] > 0)
            <div class="flex items-center gap-2">
                <span class="w-6 h-6 rounded-full bg-green-100 text-green-700 text-xs font-bold flex items-center justify-center flex-shrink-0">{{ $preview['toReinsert'] }}</span>
                <span>kontak yang dihapus akan <strong>dimunculkan kembali</strong></span>
            </div>
            @endif
        </div>
    </div>

    {{-- Sample preview table --}}
    @if (!empty($preview['samples']))
    <div class="bg-white rounded-xl border shadow-sm overflow-hidden">
        <div class="px-5 py-3 border-b bg-gray-50">
            <p class="text-sm font-semibold text-gray-700">Contoh perubahan (5 pertama)</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-xs">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="text-left px-4 py-2.5 font-medium text-gray-500">Tipe</th>
                        <th class="text-left px-4 py-2.5 font-medium text-gray-500">Nama</th>
                        <th class="text-left px-4 py-2.5 font-medium text-gray-500">Telepon</th>
                        <th class="text-left px-4 py-2.5 font-medium text-gray-500">Kondisi sebelum → sesudah restore</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach ($preview['samples'] as $s)
                    @php
                        $typeBadge = match($s['type']) {
                            'added'    => ['Dihapus', 'bg-red-100 text-red-700'],
                            'modified' => ['Dipulihkan', 'bg-yellow-100 text-yellow-700'],
                            'deleted'  => ['Dikembalikan', 'bg-green-100 text-green-700'],
                            default    => [$s['type'], 'bg-gray-100 text-gray-600'],
                        };
                        $nameDisplay = $s['old']['name'] ?? $s['new']['name'] ?? '—';
                        $phoneDisplay = $s['old']['phone'] ?? $s['new']['phone'] ?? '—';
                    @endphp
                    <tr>
                        <td class="px-4 py-2.5">
                            <span class="inline-flex px-1.5 py-0.5 rounded text-xs font-medium {{ $typeBadge[1] }}">
                                {{ $typeBadge[0] }}
                            </span>
                        </td>
                        <td class="px-4 py-2.5 text-gray-700">{{ $nameDisplay }}</td>
                        <td class="px-4 py-2.5 font-mono text-gray-600">{{ $phoneDisplay }}</td>
                        <td class="px-4 py-2.5 text-gray-500">
                            @if ($s['type'] === 'modified' && $s['old'] && $s['new'])
                                @php
                                    $diffs = [];
                                    foreach ($s['new'] as $k => $v) {
                                        $oldVal = $s['old'][$k] ?? null;
                                        if ($oldVal !== $v) $diffs[] = "{$k}: «{$oldVal}» → «{$v}»";
                                    }
                                @endphp
                                {{ implode(', ', array_slice($diffs, 0, 2)) ?: 'tidak ada perubahan' }}
                            @elseif ($s['type'] === 'added')
                                Kontak ini akan dihapus
                            @else
                                Kontak ini akan dikembalikan
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    {{-- Action buttons --}}
    <div class="flex gap-3">
        <form method="POST" action="{{ route('admin.kontak.history.restore', $history) }}"
              onsubmit="return confirm('Yakin ingin memulihkan? Data kontak akan berubah sesuai kondisi ini.')">
            @csrf
            <button type="submit"
                    class="px-6 py-3 bg-[#2d6a4f] text-white font-semibold rounded-xl text-sm hover:bg-[#1a5a3f] transition-colors">
                ✓ Pulihkan Sekarang
            </button>
        </form>
        <a href="{{ route('admin.kontak.history') }}"
           class="px-6 py-3 border border-gray-300 text-gray-600 rounded-xl text-sm hover:bg-gray-50 transition-colors font-medium">
            Batal
        </a>
    </div>

</div>
@endsection
