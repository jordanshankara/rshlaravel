@extends('layouts.admin')
@section('title', 'Edit Artikel')
@section('page-title', 'Edit Artikel')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet">
<style>
    .ql-container { font-size: 14px; font-family: inherit; }
    .ql-editor { min-height: 380px; line-height: 1.75; }
    .ql-toolbar { border-top-left-radius: 0.5rem; border-top-right-radius: 0.5rem; border-color: #e5e7eb !important; }
    .ql-container { border-bottom-left-radius: 0.5rem; border-bottom-right-radius: 0.5rem; border-color: #e5e7eb !important; }
    .ql-editor.ql-blank::before { color: #9ca3af; font-style: normal; }
</style>
@endpush

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.artikel.index') }}" class="text-sm text-gray-500 hover:text-gray-700 inline-flex items-center gap-1">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Kembali ke Daftar Artikel
    </a>
</div>

@if($errors->any())
<div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg text-sm text-red-700">
    <ul class="list-disc list-inside space-y-1">
        @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div x-data="articleForm()" class="flex flex-col lg:flex-row gap-6 items-start">

    {{-- ── LEFT COLUMN ── --}}
    <form id="artikel-form" method="POST" action="{{ route('admin.artikel.update', $artikel) }}"
          class="flex-1 min-w-0 space-y-4" @submit.prevent="submitForm">
        @csrf
        @method('PUT')
        <input type="hidden" name="status" x-model="status">
        <input type="hidden" name="cover_image" x-model="coverPath">
        <input type="hidden" name="content" id="content-input">

        {{-- Title --}}
        <div class="bg-white rounded-xl border shadow-sm p-5">
            <input type="text" name="title" x-model="title" @input="onTitleInput"
                   placeholder="Judul artikel..."
                   class="w-full text-2xl font-bold text-gray-900 placeholder-gray-300 border-none outline-none border-b-2 border-transparent focus:border-[#2d6a4f] transition-colors bg-transparent pb-2"
                   required>

            {{-- Slug --}}
            <div class="flex items-center gap-2 mt-3">
                <span class="text-xs text-gray-400 font-mono flex-shrink-0">slug:</span>
                <input type="text" name="slug" x-model="slug" @input="slugEdited = true"
                       class="flex-1 text-xs font-mono text-gray-500 bg-gray-50 border border-gray-200 rounded px-2 py-1 focus:outline-none focus:ring-1 focus:ring-[#2d6a4f]/40">
            </div>
        </div>

        {{-- Editor --}}
        <div class="bg-white rounded-xl border shadow-sm overflow-hidden">
            <div id="quill-editor"></div>
        </div>
    </form>

    {{-- ── RIGHT SIDEBAR ── --}}
    <div class="w-full lg:w-64 lg:flex-shrink-0 space-y-3">

        {{-- Card 1: Cover Image --}}
        <div class="bg-white rounded-xl border shadow-sm p-4">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Gambar Sampul</p>

            <div x-show="!coverUrl" class="relative">
                <label
                    class="flex flex-col items-center justify-center aspect-video border-2 border-dashed border-gray-200 rounded-lg cursor-pointer hover:border-[#2d6a4f] hover:bg-green-50/30 transition-colors"
                    @dragover.prevent="dragging = true"
                    @dragleave="dragging = false"
                    @drop.prevent="handleDrop($event)"
                    :class="dragging ? 'border-[#2d6a4f] bg-green-50/30' : ''">
                    <div x-show="!uploading" class="text-center p-3">
                        <svg class="w-7 h-7 text-gray-300 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span class="text-xs text-gray-400">Klik atau seret gambar</span>
                    </div>
                    <div x-show="uploading" class="flex items-center gap-2 p-4">
                        <svg class="w-4 h-4 text-[#2d6a4f] animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
                        </svg>
                        <span class="text-xs text-gray-500">Mengunggah...</span>
                    </div>
                    <input type="file" accept="image/*" class="sr-only" @change="handleFileInput($event)">
                </label>
            </div>

            <div x-show="coverUrl" class="space-y-2">
                <div class="relative aspect-video rounded-lg overflow-hidden border border-gray-200">
                    <img :src="coverUrl" class="w-full h-full object-cover">
                </div>
                <div x-show="coverInfo" class="text-xs text-gray-400" x-text="coverInfo"></div>
                <div class="flex gap-2">
                    <label class="flex-1 text-center text-xs text-[#2d6a4f] font-medium py-1.5 border border-[#2d6a4f]/30 rounded-lg cursor-pointer hover:bg-green-50 transition-colors">
                        Ganti
                        <input type="file" accept="image/*" class="sr-only" @change="handleFileInput($event)">
                    </label>
                    <button type="button" @click="removeCover()"
                            class="px-3 py-1.5 text-xs text-red-500 border border-red-200 rounded-lg hover:bg-red-50 transition-colors">×</button>
                </div>
            </div>
        </div>

        {{-- Card 2: Status + Completeness --}}
        <div class="bg-white rounded-xl border shadow-sm p-4">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Status</p>
            <div class="flex items-center gap-2 mb-3">
                <span class="w-2 h-2 rounded-full flex-shrink-0"
                      :class="status === 'PUBLISHED' ? 'bg-green-500' : 'bg-yellow-400'"></span>
                <span class="text-sm font-medium text-gray-700"
                      x-text="status === 'PUBLISHED' ? 'Dipublikasikan' : 'Draft'"></span>
            </div>
            <hr class="border-gray-100 mb-3">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs text-gray-500">Kelengkapan:</span>
                <span class="text-xs font-semibold" :class="completeness === 5 ? 'text-green-600' : 'text-gray-500'"
                      x-text="completeness + '/5'"></span>
            </div>
            <div class="space-y-1.5">
                <template x-for="item in checks" :key="item.label">
                    <div class="flex items-center gap-2">
                        <span class="w-4 h-4 rounded-full flex-shrink-0 flex items-center justify-center text-white"
                              :class="item.ok ? 'bg-green-500' : 'bg-gray-200'">
                            <svg x-show="item.ok" class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                            </svg>
                        </span>
                        <span class="text-xs" :class="item.ok ? 'text-gray-700' : 'text-gray-400'" x-text="item.label"></span>
                    </div>
                </template>
            </div>
        </div>

        {{-- Card 3: Category --}}
        <div class="bg-white rounded-xl border shadow-sm p-4"
             x-data="categoryPicker({{ json_encode($artikel->categories->map(fn($c) => ['id' => $c->id, 'name' => $c->name])->values()) }})"
             x-init="fetchAll()">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Kategori</p>

            <div class="flex flex-wrap gap-1.5 mb-2" x-show="selected.length > 0">
                <template x-for="cat in selected" :key="cat.id">
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-[#2d6a4f]/10 text-[#2d6a4f]">
                        <span x-text="cat.name"></span>
                        <button type="button" @click="remove(cat.id)" class="hover:text-[#1a5a3f] leading-none">×</button>
                        <input type="hidden" name="categories[]" :value="cat.id">
                    </span>
                </template>
            </div>

            <div class="relative">
                <button type="button" @click="open = !open"
                        class="w-full flex items-center justify-between px-3 py-2 border border-gray-200 rounded-lg text-xs text-gray-500 hover:border-gray-300 transition-colors">
                    <span x-text="selected.length ? selected.length + ' dipilih' : 'Pilih kategori...'"></span>
                    <svg class="w-3.5 h-3.5 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>

                <div x-show="open" @click.outside="open = false" x-cloak
                     class="absolute top-full mt-1 left-0 right-0 bg-white border border-gray-200 rounded-lg shadow-lg z-20 max-h-48 overflow-y-auto">
                    <div class="p-1">
                        <template x-for="cat in allCategories" :key="cat.id">
                            <button type="button" @click="toggle(cat)"
                                    class="w-full flex items-center gap-2 px-3 py-2 rounded-md text-xs text-left hover:bg-gray-50 transition-colors">
                                <span class="w-4 h-4 rounded border flex-shrink-0 flex items-center justify-center"
                                      :class="isSelected(cat.id) ? 'bg-[#2d6a4f] border-[#2d6a4f]' : 'border-gray-300'">
                                    <svg x-show="isSelected(cat.id)" class="w-2.5 h-2.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </span>
                                <span x-text="cat.name" class="text-gray-700"></span>
                            </button>
                        </template>
                    </div>
                    <hr class="border-gray-100">
                    <div class="p-2">
                        <div x-show="!addingNew">
                            <button type="button" @click="addingNew = true"
                                    class="w-full text-left text-xs text-[#2d6a4f] font-medium px-2 py-1.5 hover:bg-green-50 rounded-md transition-colors">
                                + Tambah kategori baru
                            </button>
                        </div>
                        <div x-show="addingNew" class="flex gap-1.5">
                            <input x-ref="newCatInput" type="text" x-model="newCatName"
                                   placeholder="Nama kategori..."
                                   class="flex-1 text-xs px-2 py-1.5 border border-gray-200 rounded-md focus:outline-none focus:ring-1 focus:ring-[#2d6a4f]/40"
                                   @keydown.enter.prevent="createCategory()"
                                   @keydown.escape="addingNew = false; newCatName = ''">
                            <button type="button" @click="createCategory()"
                                    class="px-2.5 py-1.5 bg-[#2d6a4f] text-white text-xs rounded-md hover:bg-[#1a5a3f] transition-colors">
                                Tambah
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card 4: Excerpt --}}
        <div class="bg-white rounded-xl border shadow-sm p-4">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Ringkasan</p>
            <textarea name="excerpt" x-model="excerpt" rows="4" placeholder="Tulis ringkasan artikel..."
                      class="w-full text-sm text-gray-700 bg-transparent border-none outline-none resize-none placeholder-gray-300"></textarea>
        </div>

        {{-- Card 5: Actions --}}
        <div class="bg-white rounded-xl border shadow-sm p-4 space-y-2">
            <button type="button" @click="submitAs('PUBLISHED')"
                    class="w-full py-2.5 bg-[#2d6a4f] hover:bg-[#1a5a3f] text-white text-sm font-semibold rounded-lg transition-colors">
                {{ $artikel->status === 'PUBLISHED' ? 'Perbarui' : 'Publikasikan' }}
            </button>
            <button type="button" @click="submitAs('DRAFT')"
                    class="w-full py-2.5 border border-gray-200 text-gray-600 hover:bg-gray-50 text-sm font-medium rounded-lg transition-colors">
                Simpan Draft
            </button>
        </div>

    </div>{{-- end sidebar --}}

    {{-- Modal: Warning incomplete --}}
    <div x-show="showModal" x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50">
        <div @click.outside="showModal = false"
             class="bg-white rounded-xl shadow-xl p-6 w-full max-w-sm">
            <h3 class="font-semibold text-gray-900 mb-2">Artikel belum lengkap</h3>
            <p class="text-sm text-gray-500 mb-3">Field berikut belum diisi:</p>
            <ul class="list-disc list-inside text-sm text-gray-700 space-y-1 mb-5">
                <template x-for="f in missingFields" :key="f">
                    <li x-text="f"></li>
                </template>
            </ul>
            <div class="flex gap-2">
                <button type="button" @click="showModal = false"
                        class="flex-1 py-2 border border-gray-200 text-sm text-gray-600 font-medium rounded-lg hover:bg-gray-50 transition-colors">
                    Kembali & Lengkapi
                </button>
                <button type="button" @click="doSubmit('DRAFT')"
                        class="flex-1 py-2 bg-[#2d6a4f] text-white text-sm font-semibold rounded-lg hover:bg-[#1a5a3f] transition-colors">
                    Simpan Draft
                </button>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
<script>
let quill;

function slugify(text) {
    return text.toLowerCase()
        .normalize('NFD').replace(/[̀-ͯ]/g, '')
        .replace(/[^a-z0-9\s-]/g, '')
        .trim().replace(/\s+/g, '-').replace(/-+/g, '-').slice(0, 200);
}

function articleForm() {
    return {
        title: {!! json_encode(old('title', $artikel->title)) !!},
        slug: {!! json_encode(old('slug', $artikel->slug)) !!},
        slugEdited: true,
        excerpt: {!! json_encode(old('excerpt', $artikel->excerpt ?? '')) !!},
        status: {!! json_encode(old('status', $artikel->status)) !!},
        coverPath: {!! json_encode(old('cover_image', $artikel->cover_image ?? '')) !!},
        coverUrl: {!! $artikel->cover_image ? json_encode(asset('storage/'.$artikel->cover_image)) : "''" !!},
        coverInfo: '',
        uploading: false,
        dragging: false,
        showModal: false,
        missingFields: [],

        get completeness() {
            return this.checks.filter(c => c.ok).length;
        },
        get checks() {
            return [
                { label: 'Judul', ok: this.title.trim().length > 0 },
                { label: 'Gambar Sampul', ok: this.coverPath.length > 0 },
                { label: 'Kategori', ok: document.querySelectorAll('input[name="categories[]"]').length > 0 },
                { label: 'Ringkasan', ok: this.excerpt.trim().length > 0 },
                { label: 'Konten', ok: quill && quill.getText().trim().length > 0 },
            ];
        },

        init() {
            quill = new Quill('#quill-editor', {
                theme: 'snow',
                placeholder: 'Tulis konten artikel...',
                modules: {
                    toolbar: [
                        [{ header: [2, 3, false] }],
                        ['bold', 'italic', 'underline'],
                        [{ list: 'ordered' }, { list: 'bullet' }],
                        ['blockquote', 'link'],
                        ['clean'],
                    ]
                }
            });

            const existingContent = {!! json_encode(old('content', $artikel->content ?? ''), JSON_HEX_TAG) !!};
            if (existingContent) {
                const delta = quill.clipboard.convert({ html: existingContent });
                quill.setContents(delta, 'silent');
            }

            quill.on('text-change', () => { this.title = this.title; });
        },

        onTitleInput() {
            if (!this.slugEdited) {
                this.slug = slugify(this.title);
            }
        },

        submitAs(newStatus) {
            if (newStatus === 'PUBLISHED') {
                const missing = [];
                if (!this.title.trim()) missing.push('Judul');
                if (!this.coverPath) missing.push('Gambar Sampul');
                if (!document.querySelectorAll('input[name="categories[]"]').length) missing.push('Kategori');
                if (!this.excerpt.trim()) missing.push('Ringkasan');
                if (!quill || !quill.getText().trim()) missing.push('Konten');

                if (missing.length) {
                    this.missingFields = missing;
                    this.showModal = true;
                    return;
                }
            }
            this.doSubmit(newStatus);
        },

        doSubmit(newStatus) {
            this.showModal = false;
            this.status = newStatus;
            document.getElementById('content-input').value = quill ? quill.root.innerHTML : '';
            this.$nextTick(() => document.getElementById('artikel-form').submit());
        },

        submitForm() {},

        async handleFileInput(e) {
            const file = e.target.files[0];
            if (file) await this.uploadFile(file);
            e.target.value = '';
        },

        handleDrop(e) {
            this.dragging = false;
            const file = e.dataTransfer.files[0];
            if (file && file.type.startsWith('image/')) this.uploadFile(file);
        },

        async uploadFile(file) {
            this.uploading = true;
            const formData = new FormData();
            formData.append('file', file);
            formData.append('_token', '{{ csrf_token() }}');

            try {
                const resp = await fetch('{{ route("admin.artikel.upload-cover") }}', {
                    method: 'POST',
                    body: formData,
                });
                const data = await resp.json();
                if (data.url) {
                    this.coverPath = data.url;
                    this.coverUrl = '/storage/' + data.url;
                    this.coverInfo = (file.size / 1024).toFixed(0) + ' KB';
                }
            } catch (err) {
                alert('Gagal mengunggah gambar.');
            } finally {
                this.uploading = false;
            }
        },

        removeCover() {
            this.coverPath = '';
            this.coverUrl = '';
            this.coverInfo = '';
        },
    };
}

function categoryPicker(initialSelected) {
    return {
        allCategories: [],
        selected: initialSelected || [],
        open: false,
        addingNew: false,
        newCatName: '',

        async fetchAll() {
            const resp = await fetch('{{ route("admin.artikel.kategoris.index") }}');
            this.allCategories = await resp.json();
        },

        isSelected(id) {
            return this.selected.some(c => c.id === id);
        },

        toggle(cat) {
            if (this.isSelected(cat.id)) {
                this.remove(cat.id);
            } else {
                this.selected.push(cat);
            }
        },

        remove(id) {
            this.selected = this.selected.filter(c => c.id !== id);
        },

        async createCategory() {
            const name = this.newCatName.trim();
            if (!name) return;
            const resp = await fetch('{{ route("admin.artikel.kategoris.store") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: JSON.stringify({ name }),
            });
            if (resp.ok) {
                const cat = await resp.json();
                this.allCategories.push(cat);
                this.selected.push(cat);
                this.newCatName = '';
                this.addingNew = false;
            }
        },
    };
}
</script>
@endpush
