@extends('layouts.admin')
@section('title', 'Database Kontak')
@section('page-title', 'Database Kontak')
@section('header-actions')
<div class="flex items-center gap-2">
    <a href="{{ route('admin.kontak.import') }}"
       class="inline-flex items-center gap-1.5 px-3 py-2 text-sm border border-gray-300 rounded-lg text-gray-600 hover:bg-gray-50">
        ↑ Import CSV
    </a>
    <a href="{{ route('admin.kontak.create') }}"
       class="inline-flex items-center gap-1.5 px-3 py-2 text-sm bg-[#2d6a4f] text-white rounded-lg hover:bg-[#1a5a3f]">
        + Tambah Kontak
    </a>
</div>
@endsection
@section('content')

@if (session('success'))
<div class="mb-4 p-3 bg-green-50 border border-green-200 rounded-lg text-sm text-green-700">
    {{ session('success') }}
</div>
@endif

{{-- Stats bar --}}
<div class="grid grid-cols-3 gap-3 mb-5">
    <div class="bg-white rounded-xl border shadow-sm px-4 py-3">
        <p class="text-xs text-gray-400">Total Kontak</p>
        <p class="text-xl font-bold text-gray-900">{{ number_format($totalCount) }}</p>
    </div>
    <div class="bg-white rounded-xl border shadow-sm px-4 py-3">
        <p class="text-xs text-gray-400">Sudah Dihubungi</p>
        <p class="text-xl font-bold text-green-700">{{ number_format($contactedCount) }}</p>
    </div>
    <div class="bg-white rounded-xl border shadow-sm px-4 py-3">
        <p class="text-xs text-gray-400">Belum Dihubungi</p>
        <p class="text-xl font-bold text-orange-600">{{ number_format($totalCount - $contactedCount) }}</p>
    </div>
</div>

{{-- Filters --}}
<div class="bg-white rounded-xl border shadow-sm p-4 mb-4">
    <form method="GET" class="flex flex-wrap gap-3 items-end">
        <div class="flex-1 min-w-36">
            <label class="block text-xs text-gray-500 mb-1">Cari</label>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Nama / telepon / email…"
                   class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-[#2d6a4f]/30 focus:outline-none">
        </div>
        <div class="w-44">
            <label class="block text-xs text-gray-500 mb-1">Keluhan</label>
            <select name="complaint" class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-[#2d6a4f]/30 focus:outline-none">
                <option value="">Semua Keluhan</option>
                @foreach ($complaints as $c)
                <option value="{{ $c }}" {{ request('complaint') === $c ? 'selected' : '' }}>{{ $c }}</option>
                @endforeach
            </select>
        </div>
        <div class="w-40">
            <label class="block text-xs text-gray-500 mb-1">Sumber</label>
            <select name="source" class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-[#2d6a4f]/30 focus:outline-none">
                <option value="">Semua Sumber</option>
                @foreach ($sources as $s)
                <option value="{{ $s }}" {{ request('source') === $s ? 'selected' : '' }}>{{ $s }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex items-end gap-1.5">
            <label class="flex items-center gap-2 cursor-pointer text-sm text-gray-600 pb-2">
                <input type="checkbox" name="not_contacted" value="1" {{ request('not_contacted') ? 'checked' : '' }}
                       class="rounded border-gray-300 text-[#2d6a4f]">
                Belum dihubungi
            </label>
        </div>
        <button type="submit" class="px-4 py-2 bg-[#2d6a4f] text-white rounded-lg text-sm hover:bg-[#1a5a3f]">Filter</button>
        <a href="{{ route('admin.kontak.index') }}" class="px-4 py-2 border rounded-lg text-sm text-gray-500 hover:bg-gray-50">Reset</a>
    </form>
</div>

{{-- Table --}}
<div class="bg-white rounded-xl border shadow-sm overflow-hidden">
    @if ($contacts->isEmpty())
    <div class="p-10 text-center text-gray-400 text-sm">
        Tidak ada kontak ditemukan.
        @if (!request()->hasAny(['search','complaint','source','not_contacted']))
        <a href="{{ route('admin.kontak.import') }}" class="text-[#2d6a4f] underline ml-1">Import dari CSV</a>
        @endif
    </div>
    @else
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b text-xs">
                <tr>
                    <th class="text-left px-4 py-3 font-medium text-gray-500">Nama</th>
                    <th class="text-left px-4 py-3 font-medium text-gray-500">Telepon</th>
                    <th class="text-left px-4 py-3 font-medium text-gray-500">Email</th>
                    <th class="text-left px-4 py-3 font-medium text-gray-500">Keluhan</th>
                    <th class="text-left px-4 py-3 font-medium text-gray-500 min-w-36">Terakhir Dihubungi</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach ($contacts as $contact)
                <tr class="hover:bg-gray-50 transition-colors" id="row-{{ $contact->id }}">
                    {{-- Nama --}}
                    <td class="px-4 py-3">
                        <p class="font-medium text-gray-900">{{ $contact->name }}</p>
                        <span class="inline-flex px-1.5 py-0.5 rounded text-xs font-medium {{ $contact->sourceBadgeClass() }}">
                            {{ $contact->sourceBadgeLabel() }}
                        </span>
                    </td>

                    {{-- Telepon + WA button --}}
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-2">
                            <span class="text-gray-700 font-mono text-xs">{{ $contact->phone }}</span>
                            <button onclick="contactLog({{ $contact->id }}, 'wa', 'https://wa.me/{{ $contact->waPhone() }}')"
                                    class="inline-flex items-center gap-1 px-2 py-1 bg-green-500 text-white rounded-lg text-xs font-medium hover:bg-green-600 transition-colors">
                                💬 WA
                            </button>
                        </div>
                    </td>

                    {{-- Email + Email button --}}
                    <td class="px-4 py-3">
                        @if ($contact->email)
                        <div class="flex items-center gap-2">
                            <span class="text-gray-600 text-xs truncate max-w-[140px]" title="{{ $contact->email }}">
                                {{ $contact->email }}
                            </span>
                            <button onclick="contactLog({{ $contact->id }}, 'email', 'mailto:{{ $contact->email }}')"
                                    class="inline-flex items-center gap-1 px-2 py-1 bg-blue-500 text-white rounded-lg text-xs font-medium hover:bg-blue-600 transition-colors flex-shrink-0">
                                ✉️
                            </button>
                        </div>
                        @else
                        <span class="text-gray-300 text-xs">—</span>
                        @endif
                    </td>

                    {{-- Keluhan --}}
                    <td class="px-4 py-3">
                        @if ($contact->health_complaint)
                        <span class="text-xs text-gray-600" title="{{ $contact->health_complaint }}">
                            {{ Str::limit($contact->health_complaint, 35) }}
                        </span>
                        @else
                        <span class="text-gray-300 text-xs">—</span>
                        @endif
                    </td>

                    {{-- Terakhir Dihubungi --}}
                    <td class="px-4 py-3" id="last-contact-{{ $contact->id }}">
                        @if ($contact->last_contacted_at)
                        @php $typeClass = $contact->last_contacted_type === 'wa' ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700'; @endphp
                        <span class="inline-flex px-1.5 py-0.5 rounded text-xs font-medium {{ $typeClass }} mr-1">
                            {{ strtoupper($contact->last_contacted_type) }}
                        </span>
                        <span class="text-xs text-gray-500">
                            {{ $contact->last_contacted_at->setTimezone('Asia/Jakarta')->format('d M Y, H:i') }}
                        </span>
                        @else
                        <span class="text-xs text-gray-300">Belum</span>
                        @endif
                    </td>

                    {{-- Aksi --}}
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-1.5">
                            <a href="{{ route('admin.kontak.edit', $contact) }}"
                               class="p-1.5 text-gray-400 hover:text-[#2d6a4f] rounded transition-colors" title="Edit">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </a>
                            <form method="POST" action="{{ route('admin.kontak.destroy', $contact) }}"
                                  onsubmit="return confirm('Hapus kontak {{ addslashes($contact->name) }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-1.5 text-gray-300 hover:text-red-500 rounded transition-colors" title="Hapus">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="px-4 py-3 border-t flex items-center justify-between">
        <p class="text-xs text-gray-400">
            Menampilkan {{ $contacts->firstItem() }}–{{ $contacts->lastItem() }} dari {{ $contacts->total() }} kontak
        </p>
        {{ $contacts->links() }}
    </div>
    @endif
</div>

<script>
const csrfToken = document.querySelector('meta[name=csrf-token]').content;

function contactLog(id, type, link) {
    // Open the link
    window.open(link, '_blank');

    // Log it to backend
    fetch(`/admin/kontak/${id}/log`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ type }),
    })
    .then(r => r.json())
    .then(data => {
        if (!data.success) return;
        const cell = document.getElementById('last-contact-' + id);
        if (!cell) return;
        const badgeClass = type === 'wa'
            ? 'bg-green-100 text-green-700'
            : 'bg-blue-100 text-blue-700';
        cell.innerHTML = `
            <span class="inline-flex px-1.5 py-0.5 rounded text-xs font-medium ${badgeClass} mr-1">
                ${type.toUpperCase()}
            </span>
            <span class="text-xs text-gray-500">${data.contacted_at}</span>
        `;
    })
    .catch(() => {}); // Silent fail — link already opened
}
</script>
@endsection
