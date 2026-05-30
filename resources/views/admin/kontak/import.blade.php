@extends('layouts.admin')
@section('title', 'Import Kontak dari CSV')
@section('page-title', 'Import Kontak')
@section('header-actions')
<a href="{{ route('admin.kontak.index') }}" class="text-sm text-gray-500 hover:text-gray-700">← Kembali</a>
@endsection
@section('content')

<div class="max-w-xl space-y-5">

    {{-- Instructions --}}
    <div class="bg-blue-50 border border-blue-200 rounded-xl p-5 text-sm text-blue-800">
        <p class="font-semibold mb-2">📋 Cara Import dari Excel:</p>
        <ol class="list-decimal list-inside space-y-1 text-blue-700">
            <li>Buka file Excel (<code>.xlsx</code>) di Microsoft Excel atau Google Sheets</li>
            <li>Pilih <strong>File → Save As → CSV (Comma delimited)</strong></li>
            <li>Upload file <code>.csv</code> tersebut di form di bawah</li>
        </ol>
        <p class="mt-3 font-semibold">Kolom yang dikenali (nama harus persis):</p>
        <div class="grid grid-cols-2 gap-x-4 mt-1 text-xs font-mono">
            <span>Nama Lengkap</span><span>No. Telepon</span>
            <span>Email</span><span>Jenis Kelamin</span>
            <span>Alamat</span><span>Usia</span>
            <span>Penyakit/Keluhan</span><span>Sumber Info</span>
            <span>Asal File</span>
        </div>
        <p class="mt-3 text-xs text-blue-600">
            ℹ️ Import aman diulang — kontak dengan nomor yang sama akan <strong>diperbarui</strong>, bukan duplikat.
        </p>
    </div>

    {{-- Upload form --}}
    <div class="bg-white rounded-xl border shadow-sm p-6">

        @if ($errors->any())
        <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg text-sm text-red-700">
            @foreach ($errors->all() as $e) <p>• {{ $e }}</p> @endforeach
        </div>
        @endif

        <form method="POST" action="{{ route('admin.kontak.import.store') }}"
              enctype="multipart/form-data" class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Upload File CSV <span class="text-red-500">*</span>
                </label>
                <div x-data="{ filename: '' }"
                     class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:border-[#2d6a4f] transition-colors cursor-pointer"
                     onclick="document.getElementById('csvInput').click()">
                    <input type="file" id="csvInput" name="csv_file" accept=".csv,.txt" class="hidden"
                           x-on:change="filename = $event.target.files[0]?.name ?? ''">
                    <div x-show="!filename">
                        <p class="text-gray-400 text-sm">Klik untuk pilih file CSV</p>
                        <p class="text-gray-300 text-xs mt-1">atau drag & drop</p>
                    </div>
                    <div x-show="filename" x-cloak>
                        <p class="text-[#2d6a4f] font-medium text-sm">📄 <span x-text="filename"></span></p>
                        <p class="text-gray-400 text-xs mt-1">Klik untuk ganti file</p>
                    </div>
                </div>
            </div>

            <button type="submit"
                    class="w-full py-3 bg-[#2d6a4f] text-white font-semibold rounded-xl text-sm hover:bg-[#1a5a3f] transition-colors">
                Import Sekarang →
            </button>
        </form>
    </div>

</div>

@endsection
