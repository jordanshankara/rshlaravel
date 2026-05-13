@extends('layouts.admin')
@section('title', 'Edit Pengguna')
@section('page-title', 'Edit Pengguna')
@section('header-actions')
<a href="{{ route('admin.pengguna.index') }}" class="text-sm text-gray-500 hover:text-gray-700">← Kembali</a>
@endsection
@section('content')
<div class="max-w-md">
    <div class="bg-white rounded-xl border shadow-sm p-6">
        <form method="POST" action="{{ route('admin.pengguna.update', $pengguna) }}" class="space-y-4">
            @csrf @method('PUT')
            <div><label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Lengkap</label>
            <input type="text" name="name" value="{{ old('name', $pengguna->name) }}" required class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-[#2d6a4f]/30 focus:outline-none"></div>
            <div><label class="block text-sm font-medium text-gray-700 mb-1.5">Username</label>
            <input type="text" name="username" value="{{ old('username', $pengguna->username) }}" required class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-[#2d6a4f]/30 focus:outline-none"></div>
            <div><label class="block text-sm font-medium text-gray-700 mb-1.5">Password Baru <span class="text-gray-400 font-normal">(kosongkan jika tidak diubah)</span></label>
            <input type="password" name="password" minlength="8" class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-[#2d6a4f]/30 focus:outline-none"></div>
            <div><label class="block text-sm font-medium text-gray-700 mb-1.5">Konfirmasi Password</label>
            <input type="password" name="password_confirmation" class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-[#2d6a4f]/30 focus:outline-none"></div>
            <div><label class="block text-sm font-medium text-gray-700 mb-1.5">Role</label>
            <select name="role" class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-[#2d6a4f]/30 focus:outline-none">
                <option value="EDITOR" {{ old('role', $pengguna->role) === 'EDITOR' ? 'selected' : '' }}>EDITOR</option>
                <option value="ADMIN" {{ old('role', $pengguna->role) === 'ADMIN' ? 'selected' : '' }}>ADMIN</option>
            </select></div>
            <button type="submit" class="w-full py-2.5 bg-[#2d6a4f] text-white font-semibold rounded-lg hover:bg-[#1a5a3f] text-sm">Simpan Perubahan</button>
        </form>
    </div>
</div>
@endsection
