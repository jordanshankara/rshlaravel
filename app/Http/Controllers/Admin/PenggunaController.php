<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PenggunaController extends Controller
{
    public function index()
    {
        $users = User::orderBy('name')->get();
        return view('admin.pengguna.index', compact('users'));
    }

    public function create()
    {
        return view('admin.pengguna.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:100|unique:users,username',
            'name'     => 'required|string|max:255',
            'password' => 'required|string|min:8|confirmed',
            'role'     => 'required|in:ADMIN,EDITOR',
        ]);

        User::create([
            'username' => $request->username,
            'name'     => $request->name,
            'password' => Hash::make($request->password, ['rounds' => 12]),
            'role'     => $request->role,
        ]);

        return redirect()->route('admin.pengguna.index')->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function edit(User $pengguna)
    {
        return view('admin.pengguna.edit', compact('pengguna'));
    }

    public function update(Request $request, User $pengguna)
    {
        $request->validate([
            'username' => 'required|string|max:100|unique:users,username,' . $pengguna->id,
            'name'     => 'required|string|max:255',
            'role'     => 'required|in:ADMIN,EDITOR',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $data = [
            'username' => $request->username,
            'name'     => $request->name,
            'role'     => $request->role,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password, ['rounds' => 12]);
        }

        $pengguna->update($data);
        return redirect()->route('admin.pengguna.index')->with('success', 'Pengguna berhasil diperbarui.');
    }

    public function destroy(User $pengguna)
    {
        if ($pengguna->id === auth()->id()) {
            return back()->withErrors(['error' => 'Anda tidak dapat menghapus akun sendiri.']);
        }
        $pengguna->delete();
        return redirect()->route('admin.pengguna.index')->with('success', 'Pengguna berhasil dihapus.');
    }
}
