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
                <td class="px-4 py-3">
                    <div class="flex items-center gap-1">
                        <a href="{{ route('admin.pengguna.edit', $user) }}" title="Edit"
                           class="p-1.5 rounded-lg text-gray-400 hover:text-[#2d6a4f] hover:bg-[#2d6a4f]/10 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </a>
                        @if($user->id !== auth()->id())
                        <form method="POST" action="{{ route('admin.pengguna.destroy', $user) }}" onsubmit="return confirm('Hapus pengguna ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" title="Hapus"
                                    class="p-1.5 rounded-lg text-gray-400 hover:text-red-500 hover:bg-red-50 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
