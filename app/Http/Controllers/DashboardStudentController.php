<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class DashboardStudentController extends Controller
{
    /**
     * Tampilkan daftar data siswa.
     */
    public function index()
    {
        $students = User::where('role', 'siswa')->has('siswa')->with('siswa.kelas')->latest()->get();

        // Diarahkan ke resources/views/Dashboard/admin/index.blade.php
        return view('Dashboard.admin.students', compact('students'));
    }

    /**
     * Redirect kembali ke index karena form tambah menggunakan Modal di halaman index.
     */
    public function create()
    {
        return redirect()->route('admin.students.index');
    }

    /**
     * Simpan data siswa baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|unique:users,email',
            'nis'     => 'nullable|string|max:20',
            'classes' => 'nullable|string|max:50',
            'status'  => 'required|in:active,warning,critical',
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => bcrypt('password123'),
            'nis'      => $request->nis,
            'classes'  => $request->classes,
            'status'   => $request->status,
            'role'     => 'student',
        ]);

        return redirect()->route('admin.students.index')->with('success', 'Data siswa berhasil ditambahkan!');
    }

    /**
     * Tampilkan detail siswa jika diperlukan.
     */
    public function show($id)
    {
        $student = User::findOrFail($id);
        return view('Dashboard.admin.show', compact('student'));
    }

    /**
     * Form edit.
     */
    public function edit($id)
    {
        return redirect()->route('admin.students.index');
    }

    /**
     * Update data siswa di database.
     */
    public function update(Request $request, $id)
    {
        $student = User::findOrFail($id);

        $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|unique:users,email,' . $id,
            'nis'     => 'nullable|string|max:20',
            'classes' => 'nullable|string|max:50',
            'status'  => 'required|in:active,warning,critical',
        ]);

        $student->update([
            'name'    => $request->name,
            'email'   => $request->email,
            'nis'     => $request->nis,
            'classes' => $request->classes,
            'status'  => $request->status,
        ]);

        return redirect()->route('Dashboard.admin.students')->with('success', 'Data siswa berhasil diperbarui!');
    }

    /**
     * Hapus data siswa dari database.
     */
    public function destroy($id)
    {
        $student = User::findOrFail($id);
        $student->delete();

        return redirect()->route('admin.students.index')->with('success', 'Data siswa berhasil dihapus!');
    }
}