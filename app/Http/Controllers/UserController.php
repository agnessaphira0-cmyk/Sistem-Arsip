<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;

class UserController extends Controller
{
    public function index()
    {
        $admins = Admin::latest()->get();
        return view('user.index', compact('admins'));
    }

    public function create()
    {
        if (session('admin_role') !== 'superadmin') {
            return redirect()->route('user.index')->with('error', 'Anda tidak memiliki akses untuk fitur ini!');
        }
        return view('user.create');
    }

    public function store(Request $request)
    {
        if (session('admin_role') !== 'superadmin') {
            return redirect()->route('user.index')->with('error', 'Anda tidak memiliki akses untuk fitur ini!');
        }

        $request->validate([
            'nama'     => 'required',
            'username' => 'required|unique:admins',
            'password' => 'required|min:6|confirmed',
            'role'     => 'required|in:superadmin,admin',
        ]);

        Admin::create([
            'nama'     => $request->nama,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
        ]);

        return redirect()->route('user.index')->with('success', 'Admin berhasil ditambahkan!');
    }

    public function edit(Admin $user)
    {
        if (session('admin_role') !== 'superadmin' && $user->id !== session('admin_id')) {
            return redirect()->route('user.index')->with('error', 'Anda hanya bisa mengedit akun Anda sendiri!');
        }
        return view('user.edit', compact('user'));
    }

    public function update(Request $request, Admin $user)
    {
        if (session('admin_role') !== 'superadmin' && $user->id !== session('admin_id')) {
            return redirect()->route('user.index')->with('error', 'Anda hanya bisa mengedit akun Anda sendiri!');
        }

        $rules = [
            'nama'     => 'required',
            'username' => 'required|unique:admins,username,' . $user->id,
        ];

        // Hanya superadmin yang boleh mengubah role
        if (session('admin_role') === 'superadmin') {
            $rules['role'] = 'required|in:superadmin,admin';
        }

        $request->validate($rules);

        $data = [
            'nama'     => $request->nama,
            'username' => $request->username,
        ];

        if (session('admin_role') === 'superadmin') {
            $data['role'] = $request->role;
        }

        if ($request->filled('password')) {
            $request->validate(['password' => 'min:6|confirmed']);
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        // Sinkronkan session jika user mengedit akunnya sendiri
        if ($user->id === session('admin_id')) {
            session(['admin_nama' => $user->nama]);
        }

        return redirect()->route('user.index')->with('success', 'Admin berhasil diupdate!');
    }

    public function destroy(Admin $user)
    {
        if (session('admin_role') !== 'superadmin') {
            return redirect()->route('user.index')->with('error', 'Anda tidak memiliki akses untuk fitur ini!');
        }

        if ($user->id === session('admin_id')) {
            return back()->with('error', 'Tidak bisa menghapus akun sendiri!');
        }
        $user->delete();
        return redirect()->route('user.index')->with('success', 'Admin berhasil dihapus!');
    }
}