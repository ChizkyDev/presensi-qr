<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserManagementController extends Controller
{
    /**
     * Menampilkan daftar guru/user
     */
   public function index()
    {
    $teachers = User::where('role', 'guru')->with('guru.sesiPresensi.kelas')->latest()->get();

    return view('Dashboard.admin.user-management', compact('teachers'));
    }

    /**
     * Menyimpan data guru/user baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'nip'      => 'nullable|string',
            'subject'  => 'nullable|string',
            'classes'  => 'nullable|string',
            'status'   => 'required|string',
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => bcrypt('password123'), // Password default
            'nip'      => $request->nip,
            'subject'  => $request->subject,
            'classes'  => $request->classes,
            'status'   => $request->status,
            'role'     => 'teacher',
        ]);

        return redirect()->back()->with('success', 'User berhasil ditambahkan!');
    }

    /**
     * Mengupdate data guru/user
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name'   => 'required|string|max:255',
            'email'  => 'required|email|unique:users,email,'.$id,
            'status' => 'required|string',
        ]);

        $user->update($request->all());

        return redirect()->back()->with('success', 'User berhasil diperbarui!');
    }

    /**
     * Menghapus data guru/user
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->back()->with('success', 'User berhasil dihapus!');
    }
}