<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use Illuminate\Http\Request;

class DashboardClassController extends Controller
{
    /**
     * Menampilkan daftar kelas dari database.
     */
    public function index()
    {
        // Ambil semua data kelas dari database beserta jumlah siswanya
        $classes = Kelas::withCount('siswa')->get();

        // Hitung total ringkasan
        $totalClasses = $classes->count();
        $totalEnrolled = $classes->sum('siswa_count');
        $avgAttendance = '0%'; // Dapat disesuaikan jika nanti ada relasi ke tabel presensi

        return view('Dashboard.admin.classes', compact('classes', 'totalClasses', 'totalEnrolled', 'avgAttendance'));
    }

    /**
     * Menyimpan kelas baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'kelas' => 'required|in:10,11,12',
            'jurusan' => 'required|string|max:50',
            'nomor_kelas' => 'required|integer|min:1',
        ]);

        Kelas::create([
            'kelas' => $request->kelas,
            'jurusan' => strtoupper($request->jurusan),
            'nomor_kelas' => $request->nomor_kelas,
        ]);

        return redirect()->route('admin.classes.index')->with('success', 'Kelas berhasil ditambahkan!');
    }

    /**
     * Menghapus kelas dari database.
     */
    public function destroy($id)
    {
        $kelas = Kelas::findOrFail($id);
        $kelas->delete();

        return redirect()->route('admin.classes.index')->with('success', 'Kelas berhasil dihapus!');
    }
}