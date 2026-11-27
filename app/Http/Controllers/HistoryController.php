<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SesiPresensi;
use Illuminate\Support\Facades\Auth;

class HistoryController extends Controller
{
    /**
     * Menampilkan halaman riwayat dan rekapitulasi sesi presensi guru.
     */
    public function index()
    {
        // Mengambil data guru yang sedang login berdasarkan relasi user_id ke tabel gurus
        $guru = Auth::user()->guru;

        if (!$guru) {
            return redirect()->back()->with('error', 'Data profil guru tidak ditemukan.');
        }

        // Ambil semua sesi yang dibuat oleh guru ini beserta relasi kelas dan presensi siswa
        $sessions = SesiPresensi::with(['kelas', 'presensis'])
                    ->where('guru_id', $guru->id)
                    ->orderBy('created_at', 'desc')
                    ->get();

        // Hitung Statistik Atas
        $totalSessionsRun = $sessions->count();
        
        $totalPresent = 0;
        $totalLate = 0;
        $totalAbsent = 0;

        foreach ($sessions as $session) {
            $totalPresent += $session->presensis->where('status', 'hadir')->count();
            $totalLate += $session->presensis->where('status', 'telat')->count();
            $totalAbsent += $session->presensis->where('status', 'alpha')->count();
        }

        return view('Dashboard.Guru.History', compact(
            'sessions', 
            'totalSessionsRun', 
            'totalPresent', 
            'totalLate', 
            'totalAbsent'
        ));
    }
}