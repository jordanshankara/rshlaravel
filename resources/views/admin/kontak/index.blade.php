@extends('layouts.admin')
@section('title', 'Database Kontak')
@section('page-title', 'Database Kontak')
@section('header-actions')
<div class="flex flex-wrap items-center gap-2">
    <a href="{{ route('admin.kontak.history') }}"
       class="inline-flex items-center gap-1.5 px-3 py-2 text-sm border border-gray-300 rounded-lg text-gray-600 hover:bg-gray-50">
        🕓 <span class="hidden sm:inline">History</span>
    </a>
    <a href="{{ route('admin.kontak.import') }}"
       class="inline-flex items-center gap-1.5 px-3 py-2 text-sm border border-gray-300 rounded-lg text-gray-600 hover:bg-gray-50">
        ↑ <span class="hidden sm:inline">Import CSV</span><span class="sm:hidden">Import</span>
    </a>
    <a href="{{ route('admin.kontak.create') }}"
       class="inline-flex items-center gap-1.5 px-3 py-2 text-sm bg-[#2d6a4f] text-white rounded-lg hover:bg-[#1a5a3f]">
        + <span class="hidden sm:inline">Tambah</span>
    </a>
</div>
@endsection
@section('content')

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
        <div class="flex-1 min-w-[10rem]">
            <label class="block text-xs text-gray-500 mb-1">Cari</label>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Nama / telepon / email…"
                   class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-[#2d6a4f]/30 focus:outline-none">
        </div>
        <div class="flex-1 min-w-[8rem]">
            <label class="block text-xs text-gray-500 mb-1">Keluhan</label>
            <select name="complaint" class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-[#2d6a4f]/30 focus:outline-none">
                <option value="">Semua Keluhan</option>
                @foreach ($complaints as $c)
                <option value="{{ $c }}" {{ request('complaint') === $c ? 'selected' : '' }}>{{ $c }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex-1 min-w-[8rem]">
            <label class="block text-xs text-gray-500 mb-1">Sumber</label>
            <select name="source" class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-[#2d6a4f]/30 focus:outline-none">
                <option value="">Semua Sumber</option>
                @foreach ($sources as $s)
                <option value="{{ $s }}" {{ request('source') === $s ? 'selected' : '' }}>{{ $s }}</option>
                @endforeach
            </select>
        </div>
        <label class="flex items-center gap-2 cursor-pointer text-sm text-gray-600 pb-2">
            <input type="checkbox" name="not_contacted" value="1" {{ request('not_contacted') ? 'checked' : '' }}
                   class="rounded border-gray-300 text-[#2d6a4f]">
            Belum dihubungi
        </label>
        <button type="submit" class="px-4 py-2 bg-[#2d6a4f] text-white rounded-lg text-sm hover:bg-[#1a5a3f]">Filter</button>
        <a href="{{ route('admin.kontak.index') }}" class="px-4 py-2 border rounded-lg text-sm text-gray-500 hover:bg-gray-50">Reset</a>
    </form>
</div>

{{-- Table with bulk select --}}
<div x-data="bulkSelect()" class="relative">

    {{-- Floating bulk action bar --}}
    <div x-show="selected.length > 0" x-cloak
         class="fixed bottom-6 left-1/2 -translate-x-1/2 z-50 bg-gray-900 text-white rounded-2xl shadow-2xl px-5 py-3 flex items-center gap-4 text-sm"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0">
        <span class="font-medium" x-text="selected.length + ' kontak terpilih'"></span>
        <div class="h-4 w-px bg-gray-600"></div>
        <button @click="openBulkEdit()"
                class="flex items-center gap-1.5 px-3 py-1.5 bg-blue-500 rounded-lg hover:bg-blue-600 transition-colors">
            ✏️ Edit Kolom…
        </button>
        <button @click="confirmBulkDelete()"
                class="flex items-center gap-1.5 px-3 py-1.5 bg-red-500 rounded-lg hover:bg-red-600 transition-colors">
            🗑 Hapus (<span x-text="selected.length"></span>)
        </button>
        <button @click="clearSelection()" class="text-gray-400 hover:text-white transition-colors">✕</button>
    </div>

    {{-- Bulk delete form (hidden, submitted via JS) --}}
    <form id="bulkDeleteForm" method="POST" action="{{ route('admin.kontak.bulk-destroy') }}" class="hidden">
        @csrf
        <div id="bulkDeleteIds"></div>
    </form>

    {{-- Bulk edit form (hidden, submitted via JS) --}}
    <form id="bulkEditForm" method="POST" action="{{ route('admin.kontak.bulk-update') }}" class="hidden">
        @csrf
        <div id="bulkEditIds"></div>
        <input type="hidden" name="field" id="bulkEditField">
        <input type="hidden" name="value" id="bulkEditValue">
    </form>

    {{-- Bulk edit modal --}}
    <div x-show="showBulkEditModal" x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/40"
         @click.self="showBulkEditModal = false">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-sm p-6 mx-4">
            <h3 class="text-base font-semibold text-gray-900 mb-1">Edit Kolom untuk <span x-text="selected.length"></span> Kontak</h3>
            <p class="text-xs text-gray-400 mb-4">Nilai yang sama akan diterapkan ke semua kontak terpilih.</p>
            <div class="space-y-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Kolom yang diubah</label>
                    <select x-model="bulkField"
                            class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-[#2d6a4f]/30 focus:outline-none">
                        <option value="">— Pilih kolom —</option>
                        <option value="health_complaint">Penyakit / Keluhan</option>
                        <option value="source_file">Asal File</option>
                        <option value="info_source">Sumber Info</option>
                        <option value="gender">Jenis Kelamin</option>
                        <option value="notes">Catatan Internal</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Nilai baru</label>
                    <input type="text" x-model="bulkValue" placeholder="Isi nilai baru…"
                           class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-[#2d6a4f]/30 focus:outline-none">
                </div>
            </div>
            <div class="flex gap-2 mt-5">
                <button @click="submitBulkEdit()"
                        :disabled="!bulkField"
                        class="flex-1 py-2.5 bg-[#2d6a4f] text-white font-semibold rounded-lg text-sm hover:bg-[#1a5a3f] disabled:opacity-40 disabled:cursor-not-allowed">
                    Terapkan ke <span x-text="selected.length"></span> Kontak
                </button>
                <button @click="showBulkEditModal = false"
                        class="px-4 py-2.5 border border-gray-300 text-gray-600 rounded-lg text-sm hover:bg-gray-50">
                    Batal
                </button>
            </div>
        </div>
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
                        <th class="px-4 py-3 w-10">
                            <input type="checkbox" @change="toggleAll($event)"
                                   :checked="allSelected"
                                   class="rounded border-gray-300 text-[#2d6a4f]">
                        </th>
                        <th class="text-left px-4 py-3 font-medium text-gray-500">Nama</th>
                        <th class="text-left px-4 py-3 font-medium text-gray-500">Telepon</th>
                        <th class="text-left px-4 py-3 font-medium text-gray-500">Email</th>
                        <th class="text-left px-4 py-3 font-medium text-gray-500">Keluhan</th>
                        <th class="text-left px-4 py-3 font-medium text-gray-500 min-w-[8rem]">Terakhir Dihubungi</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach ($contacts as $contact)
                    <tr class="hover:bg-gray-50 transition-colors" id="row-{{ $contact->id }}">
                        <td class="px-4 py-3 w-10">
                            <input type="checkbox" :value="{{ $contact->id }}" x-model="selected"
                                   class="rounded border-gray-300 text-[#2d6a4f]">
                        </td>
                        <td class="px-4 py-3">
                            <p class="font-medium text-gray-900">{{ $contact->name }}</p>
                            <span class="inline-flex px-1.5 py-0.5 rounded text-xs font-medium {{ $contact->sourceBadgeClass() }}">
                                {{ $contact->sourceBadgeLabel() }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <span class="text-gray-700 font-mono text-xs">{{ $contact->phone }}</span>
                                <button onclick="contactLog({{ $contact->id }}, 'wa', 'https://wa.me/{{ $contact->waPhone() }}')"
                                        class="inline-flex items-center gap-1 px-2 py-1 bg-green-500 text-white rounded-lg text-xs font-medium hover:bg-green-600 transition-colors">
                                    💬 WA
                                </button>
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            @if ($contact->email)
                            <div class="flex items-center gap-2">
                                <span class="text-gray-600 text-xs truncate max-w-[8rem] sm:max-w-xs" title="{{ $contact->email }}">{{ $contact->email }}</span>
                                <button onclick="contactLog({{ $contact->id }}, 'email', 'mailto:{{ $contact->email }}')"
                                        class="inline-flex items-center gap-1 px-2 py-1 bg-blue-500 text-white rounded-lg text-xs font-medium hover:bg-blue-600 transition-colors flex-shrink-0">
                                    ✉️
                                </button>
                            </div>
                            @else
                            <span class="text-gray-300 text-xs">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            @if ($contact->health_complaint)
                            <span class="text-xs text-gray-600" title="{{ $contact->health_complaint }}">
                                {{ Str::limit($contact->health_complaint, 35) }}
                            </span>
                            @else
                            <span class="text-gray-300 text-xs">—</span>
                            @endif
                        </td>
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
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-1.5">
                                <a href="{{ route('admin.kontak.edit', $contact) }}"
                                   class="p-1.5 text-gray-400 hover:text-[#2d6a4f] rounded transition-colors" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                <form method="POST" action="{{ route('admin.kontak.destroy', $contact) }}"
                                      @submit.prevent="adminConfirm('Hapus Kontak', 'Hapus {{ addslashes($contact->name) }} dari database?', {danger:true, okLabel:'Ya, Hapus'}).then(ok => ok && $el.submit())">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-1.5 text-gray-300 hover:text-red-500 rounded transition-colors">
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
</div>

<script>
// ── Bulk select (Alpine) ──────────────────────────────────────────────────
function bulkSelect() {
    return {
        selected: [],
        showBulkEditModal: false,
        bulkField: '',
        bulkValue: '',

        get allSelected() {
            const boxes = document.querySelectorAll('tbody input[type=checkbox]');
            return boxes.length > 0 && this.selected.length === boxes.length;
        },

        toggleAll(event) {
            if (event.target.checked) {
                this.selected = Array.from(
                    document.querySelectorAll('tbody input[type=checkbox]')
                ).map(cb => parseInt(cb.value));
            } else {
                this.selected = [];
            }
        },

        clearSelection() { this.selected = []; },

        openBulkEdit() {
            this.bulkField = '';
            this.bulkValue = '';
            this.showBulkEditModal = true;
        },

        submitBulkEdit() {
            if (!this.bulkField) return;
            const form = document.getElementById('bulkEditForm');
            const idsDiv = document.getElementById('bulkEditIds');
            idsDiv.innerHTML = this.selected.map(id =>
                `<input type="hidden" name="ids[]" value="${id}">`
            ).join('');
            document.getElementById('bulkEditField').value = this.bulkField;
            document.getElementById('bulkEditValue').value = this.bulkValue;
            this.showBulkEditModal = false;
            form.submit();
        },

        async confirmBulkDelete() {
            const ok = await adminConfirm(
                `Hapus ${this.selected.length} Kontak`,
                `${this.selected.length} kontak yang dipilih akan dihapus. Gunakan History untuk membatalkan jika diperlukan.`,
                { danger: true, okLabel: 'Ya, Hapus' }
            );
            if (!ok) return;
            const form = document.getElementById('bulkDeleteForm');
            const idsDiv = document.getElementById('bulkDeleteIds');
            idsDiv.innerHTML = this.selected.map(id =>
                `<input type="hidden" name="ids[]" value="${id}">`
            ).join('');
            form.submit();
        },
    };
}

// ── WA / Email contact log ────────────────────────────────────────────────
const csrfToken = document.querySelector('meta[name=csrf-token]').content;

function contactLog(id, type, link) {
    window.open(link, '_blank');
    fetch(`/admin/kontak/${id}/log`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': csrfToken, 'Content-Type': 'application/json' },
        body: JSON.stringify({ type }),
    })
    .then(r => r.json())
    .then(data => {
        if (!data.success) return;
        const cell = document.getElementById('last-contact-' + id);
        if (!cell) return;
        const badgeClass = type === 'wa' ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700';
        cell.innerHTML = `<span class="inline-flex px-1.5 py-0.5 rounded text-xs font-medium ${badgeClass} mr-1">${type.toUpperCase()}</span><span class="text-xs text-gray-500">${data.contacted_at}</span>`;
    })
    .catch(() => {});
}
</script>
@endsection
