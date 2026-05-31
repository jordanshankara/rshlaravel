@extends('layouts.admin')
@section('title', 'Import Kontak dari CSV')
@section('page-title', 'Import Kontak')
@section('header-actions')
<a href="{{ route('admin.kontak.index') }}" class="text-sm text-gray-500 hover:text-gray-700">← Kembali</a>
@endsection
@section('content')

<div class="max-w-3xl space-y-5">

    {{-- Step 1: Download sample --}}
    <div class="bg-white rounded-xl border shadow-sm p-5">
        <div class="flex items-start justify-between gap-4 mb-4">
            <div>
                <p class="text-sm font-semibold text-gray-800 flex items-center gap-2">
                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-[#2d6a4f] text-white text-xs font-bold">1</span>
                    Download file contoh, lalu rapikan data Anda
                </p>
                <p class="text-xs text-gray-500 mt-1 ml-8">
                    Pastikan nama kolom <strong>persis sama</strong> seperti di file contoh. Kolom yang tidak dikenali akan dilewati.
                </p>
            </div>
            <a href="{{ route('admin.kontak.import.sample') }}"
               class="flex-shrink-0 inline-flex items-center gap-2 px-4 py-2.5 bg-[#2d6a4f] text-white text-sm font-semibold rounded-lg hover:bg-[#1a5a3f] transition-colors whitespace-nowrap">
                ↓ Download Contoh (Excel)
            </a>
        </div>

        {{-- Sample table preview --}}
        <div class="overflow-x-auto rounded-lg border border-gray-200">
            <table class="text-xs w-full">
                <thead class="bg-[#2d6a4f] text-white">
                    <tr>
                        <th class="px-3 py-2 text-left font-semibold whitespace-nowrap">Nama Lengkap</th>
                        <th class="px-3 py-2 text-left font-semibold whitespace-nowrap">No. Telepon</th>
                        <th class="px-3 py-2 text-left font-semibold whitespace-nowrap">Email</th>
                        <th class="px-3 py-2 text-left font-semibold whitespace-nowrap">Jenis Kelamin</th>
                        <th class="px-3 py-2 text-left font-semibold whitespace-nowrap">Alamat</th>
                        <th class="px-3 py-2 text-left font-semibold whitespace-nowrap">Usia</th>
                        <th class="px-3 py-2 text-left font-semibold whitespace-nowrap">Penyakit/Keluhan</th>
                        <th class="px-3 py-2 text-left font-semibold whitespace-nowrap">Sumber Info</th>
                        <th class="px-3 py-2 text-left font-semibold whitespace-nowrap">Asal File</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    <tr class="hover:bg-gray-50">
                        <td class="px-3 py-2 text-gray-700 whitespace-nowrap">Budi Santoso</td>
                        <td class="px-3 py-2 font-mono text-gray-600 whitespace-nowrap">6281234567890</td>
                        <td class="px-3 py-2 text-gray-600 whitespace-nowrap">budi@email.com</td>
                        <td class="px-3 py-2 text-gray-600">Laki-laki</td>
                        <td class="px-3 py-2 text-gray-500 max-w-[120px] truncate">Jl. Mawar No. 5, Jakarta</td>
                        <td class="px-3 py-2 text-gray-600">65</td>
                        <td class="px-3 py-2 text-gray-600">Diabetes</td>
                        <td class="px-3 py-2 text-gray-600 whitespace-nowrap">Rekomendasi Teman</td>
                        <td class="px-3 py-2 text-gray-600 whitespace-nowrap">Webinar_2024</td>
                    </tr>
                    <tr class="hover:bg-gray-50">
                        <td class="px-3 py-2 text-gray-700 whitespace-nowrap">Siti Rahayu</td>
                        <td class="px-3 py-2 font-mono text-gray-600 whitespace-nowrap">6285678901234</td>
                        <td class="px-3 py-2 text-gray-600 whitespace-nowrap">siti@gmail.com</td>
                        <td class="px-3 py-2 text-gray-600">Perempuan</td>
                        <td class="px-3 py-2 text-gray-500 max-w-[120px] truncate">Jl. Melati No. 12, Bandung</td>
                        <td class="px-3 py-2 text-gray-600">72</td>
                        <td class="px-3 py-2 text-gray-600">Hipertensi</td>
                        <td class="px-3 py-2 text-gray-600">Instagram</td>
                        <td class="px-3 py-2 text-gray-600 whitespace-nowrap">Talkshow_2024</td>
                    </tr>
                    <tr class="hover:bg-gray-50 bg-yellow-50">
                        <td class="px-3 py-2 text-gray-700 whitespace-nowrap">Andi Wijaya</td>
                        <td class="px-3 py-2 font-mono text-gray-600 whitespace-nowrap">6289012345678</td>
                        <td class="px-3 py-2 text-gray-400 italic">kosong</td>
                        <td class="px-3 py-2 text-gray-600">Laki-laki</td>
                        <td class="px-3 py-2 text-gray-400 italic">kosong</td>
                        <td class="px-3 py-2 text-gray-400 italic">kosong</td>
                        <td class="px-3 py-2 text-gray-600 whitespace-nowrap">Obesitas</td>
                        <td class="px-3 py-2 text-gray-400 italic">kosong</td>
                        <td class="px-3 py-2 text-gray-600">Webinar_2024</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <p class="text-xs text-amber-600 mt-2 flex items-center gap-1">
            <span>⚠️</span>
            Baris kuning menunjukkan bahwa kolom boleh dikosongkan — hanya <strong>Nama Lengkap</strong> dan <strong>No. Telepon</strong> yang wajib diisi.
        </p>
    </div>

    {{-- Step 2: Format rules --}}
    <div class="bg-white rounded-xl border shadow-sm p-5">
        <p class="text-sm font-semibold text-gray-800 flex items-center gap-2 mb-3">
            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-[#2d6a4f] text-white text-xs font-bold">2</span>
            Aturan format data
        </p>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs text-gray-600">
            <div class="bg-gray-50 rounded-lg p-3 space-y-1.5">
                <p class="font-semibold text-gray-700">📞 No. Telepon</p>
                <p>Gunakan format internasional tanpa tanda <code>+</code></p>
                <p class="font-mono bg-green-50 text-green-700 px-2 py-1 rounded">✅ 6281234567890</p>
                <p class="font-mono bg-red-50 text-red-600 px-2 py-1 rounded">❌ 081234567890 atau +62...</p>
                <p class="text-gray-400">Pastikan kolom diformat sebagai <strong>Text</strong> di Excel agar angka tidak berubah jadi 6.28E+12</p>
            </div>
            <div class="bg-gray-50 rounded-lg p-3 space-y-1.5">
                <p class="font-semibold text-gray-700">👤 Jenis Kelamin</p>
                <p class="font-mono bg-green-50 text-green-700 px-2 py-1 rounded">✅ Laki-laki / Perempuan</p>
                <p class="font-semibold text-gray-700 mt-2">🎂 Usia</p>
                <p class="font-mono bg-green-50 text-green-700 px-2 py-1 rounded">✅ Angka saja: 65</p>
                <p class="font-mono bg-red-50 text-red-600 px-2 py-1 rounded">❌ "65 tahun"</p>
            </div>
        </div>
        <div class="mt-3 p-3 bg-blue-50 rounded-lg text-xs text-blue-700">
            💡 <strong>Tips:</strong> File contoh yang didownload sudah rapi di Excel. Setelah selesai edit, simpan sebagai
            <strong>File → Save As → CSV (Comma delimited)</strong> sebelum diupload di sini.
        </div>
    </div>

    {{-- Step 3: Upload --}}
    <div class="bg-white rounded-xl border shadow-sm p-5">
        <p class="text-sm font-semibold text-gray-800 flex items-center gap-2 mb-4">
            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-[#2d6a4f] text-white text-xs font-bold">3</span>
            Upload file CSV yang sudah dirapikan
        </p>

        @if ($errors->any())
        <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg text-sm text-red-700">
            @foreach ($errors->all() as $e) <p>• {{ $e }}</p> @endforeach
        </div>
        @endif

        <form method="POST" action="{{ route('admin.kontak.import.store') }}"
              enctype="multipart/form-data" class="space-y-4" id="importForm">
            @csrf

            <div id="dropZone"
                 class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center transition-colors cursor-pointer select-none"
                 onclick="document.getElementById('fileInput').click()">
                <input type="file" id="fileInput" name="csv_file" accept=".csv,.txt" class="hidden">
                <div id="dropPlaceholder">
                    <p class="text-3xl mb-2">📂</p>
                    <p class="text-gray-500 text-sm font-medium">Klik atau drag & drop file CSV di sini</p>
                    <p class="text-gray-300 text-xs mt-1">.csv</p>
                </div>
                <div id="dropSelected" class="hidden">
                    <p class="text-[#2d6a4f] font-semibold text-sm">📄 <span id="selectedName"></span></p>
                    <p class="text-gray-400 text-xs mt-1">Klik untuk ganti file</p>
                </div>
            </div>

            <div class="text-xs text-gray-400 text-center">
                Import aman diulang — kontak dengan nomor yang sama akan <strong>diperbarui</strong>, bukan duplikat.
            </div>

            <button type="submit"
                    class="w-full py-3 bg-[#2d6a4f] text-white font-semibold rounded-xl text-sm hover:bg-[#1a5a3f] transition-colors">
                Import Sekarang →
            </button>
        </form>
    </div>

</div>

<script>
(function () {
    const zone    = document.getElementById('dropZone');
    const input   = document.getElementById('fileInput');
    const ph      = document.getElementById('dropPlaceholder');
    const sel     = document.getElementById('dropSelected');
    const selName = document.getElementById('selectedName');

    function showFile(name) {
        ph.classList.add('hidden');
        sel.classList.remove('hidden');
        selName.textContent = name;
        zone.classList.add('border-[#2d6a4f]', 'bg-green-50');
        zone.classList.remove('border-gray-300');
    }

    input.addEventListener('change', () => { if (input.files[0]) showFile(input.files[0].name); });

    zone.addEventListener('dragover', e => { e.preventDefault(); zone.classList.add('border-[#2d6a4f]', 'bg-green-50'); });
    zone.addEventListener('dragleave', () => { if (!input.files[0]) zone.classList.remove('border-[#2d6a4f]', 'bg-green-50'); });
    zone.addEventListener('drop', e => {
        e.preventDefault();
        const file = e.dataTransfer.files[0];
        if (!file) return;
        const dt = new DataTransfer();
        dt.items.add(file);
        input.files = dt.files;
        showFile(file.name);
    });
})();
</script>

@endsection
