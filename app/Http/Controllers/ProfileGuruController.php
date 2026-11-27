<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SesiPresensi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ProfileGuruController extends Controller
{
    /**
     * Menampilkan halaman profil guru beserta statistik presensi dari database.
     */
    public function index()
    {
        $user = Auth::user();
        $guru = $user->guru; // Relasi dari model User ke Guru

        if (!$guru) {
            return redirect()->back()->with('error', 'Data profil guru tidak ditemukan.');
        }

        // Sesi milik guru ini, lengkap dengan kelas (+ jumlah siswa) dan hitungan presensi
        $sessions = SesiPresensi::where('guru_id', $guru->id)
            ->with(['kelas' => fn ($q) => $q->withCount('siswa')])
            ->withCount([
                'presensis as hadir_count' => fn ($q) => $q->where('status', 'hadir'),
                'presensis as telat_count' => fn ($q) => $q->where('status', 'telat'),
            ])
            ->latest()
            ->get();

        // Statistik
        $sessionsRun   = $sessions->count();
        $totalPresent  = $sessions->sum(fn ($s) => $s->hadir_count + $s->telat_count);
        $totalExpected = $sessions->sum(fn ($s) => $s->kelas->siswa_count ?? 0);

        $avgAttendance = $totalExpected > 0
            ? round($totalPresent / $totalExpected * 100, 1)
            : 0;

        // Kelas yang diampu = kelas unik dari sesi yang pernah dibuat
        $kelasList     = $sessions->pluck('kelas')->filter()->unique('id')->values();
        $classAssigned = $kelasList->count();
        $totalStudents = $kelasList->sum('siswa_count');

        // 5 aktivitas terbaru
        $recentSessions = $sessions->take(5);

        return view('Dashboard.Guru.Profile', compact(
            'guru',
            'user',
            'sessionsRun',
            'totalStudents',
            'classAssigned',
            'avgAttendance',
            'kelasList',
            'recentSessions'
        ));
    }

    /**
     * Memperbarui data profil guru ke database.
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        $guru = $user->guru;

        if (!$guru) {
            return redirect()->back()->with('error', 'Data profil tidak ditemukan.');
        }

        $request->validate([
            'nama'     => 'required|string|max:255',
            'nip'      => ['required', 'string', 'max:50', Rule::unique('gurus', 'nip')->ignore($guru->id)],
            'email'    => 'required|email|max:255|unique:users,email,' . $user->id,
            'subject'  => 'nullable|string|max:100',
            'homeroom' => 'nullable|string|max:50',
        ]);

        $user->update([
            'name'  => $request->nama,
            'email' => $request->email,
        ]);

        $guru->update($request->only('nama', 'nip', 'subject', 'homeroom'));

        return redirect()->route('guru.profile')->with('success', 'Profil berhasil diperbarui!');
    }
}