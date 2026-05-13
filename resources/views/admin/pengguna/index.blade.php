@extends('layouts.admin')
@section('title', 'Pengguna')
@section('page-title', 'Pengguna')
@section('header-actions')
<a href="{{ route('admin.pengguna.create') }}" class="px-4 py-2 bg-[#2d6a4f] text-white text-sm font-semibold rounded-lg hover:bg-[#1a5a3f] transition-colors">+ Pengguna Baru</a>
@endsection
@section('content')
<div class="bg-white rounded-xl border shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b">
            <tr>
                <th class="px-4 py-3 text-left font-semibold text-gray-600">Nama</th>
                <th class="px-4 py-3 text-left font-semibold text-gray-600">Username</th>
                <th class="px-4 py-3 text-left font-semibold text-gray-600">Role</th>
                <th class="px-4 py-3 text-left font-semibold text-gray-600">Dibuat</th>
                <th class="px-4 py-3 text-left font-semibold text-gray-600">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @foreach($users as $user)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 font-medium">{{ $user->name }}</td>
                <td class="px-4 py-3 font-mono text-xs text-gray-500">{{ $user->username }}</td>
                <td class="px-4 py-3">
                    <span class="text-xs px-2 py-0.5 rounded-full {{ $user->role === 'ADMIN' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700' }}">
                        {{ $user->role }}
                    </span>
                </td>
                <td class="px-4 py-3 text-xs text-gray-500">{{ $user->created_at->format('d M Y') }}</td>
                <td class="px-4 py-3 flex items-center gap-2">
                    <a href="{{ route('admin.pengguna.edit', $user) }}" class="text-[#2d6a4f] hover:underline text-xs font-medium">Edit</a>
                    @if($user->id !== auth()->id())
                    <form method="POST" action="{{ route('admin.pengguna.destroy', $user) }}" onsubmit="return confirm('Hapus pengguna ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-red-500 hover:underline text-xs">Hapus</button>
                    </form>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
