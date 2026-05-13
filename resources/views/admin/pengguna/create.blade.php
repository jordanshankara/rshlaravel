@extends('layouts.admin')
@section('title', 'Tambah Pengguna')
@section('page-title', 'Tambah Pengguna')
@section('header-actions')
<a href="{{ route('admin.pengguna.index') }}" class="text-sm text-gray-500 hover:text-gray-700">← Kembali</a>
@endsection
@section('content')
<div class="max-w-md">
    <div class="bg-white rounded-xl border shadow-sm p-6">
        <form method="POST" action="{{ route('admin.pengguna.store') }}" class="space-y-4">
            @csrf
            <div><label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Lengkap</label>
            <input type="text" name="name" value="{{ old('name') }}" required class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none"></div>
            <div><label class="block text-sm font-medium text-gray-700 mb-1.5">Username</label>
            <input type="text" name="username" value="{{ old('username') }}" required class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none"></div>
            <div><label class="block text-sm font-medium text-gray-700 mb-1.5">Password</label>
            <input type="password" name="password" required minlength="8" class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none"></div>
            <div><label class="block text-sm font-medium text-gray-700 mb-1.5">Konfirmasi Password</label>
            <input type="password" name="password_confirmation" required class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none"></div>
            <div><label class="block text-sm font-medium text-gray-700 mb-1.5">Role</label>
            <select name="role" class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                <option value="EDITOR" {{ old('role') === 'EDITOR' ? 'selected' : '' }}>EDITOR</option>
                <option value="ADMIN" {{ old('role') === 'ADMIN' ? 'selected' : '' }}>ADMIN</option>
            </select></div>
            <button type="submit" class="w-full py-2.5 bg-emerald-600 text-white font-semibold rounded-lg hover:bg-emerald-700 text-sm">Tambah Pengguna</button>
        </form>
    </div>
</div>
@endsection
