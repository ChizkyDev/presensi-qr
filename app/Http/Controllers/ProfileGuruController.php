<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\SesiPresensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ProfileGuruController extends Controller
{
    /**
     * Menampilkan halaman profil guru beserta statistik presensi dari database.
     */
    public function index()
    {
        $user = Auth::user();
        $guru = $user->guru;

        if (!$guru) {
            return redirect()->back()->with('error', 'Data profil guru tidak ditemukan.');
        }

        $sessions = SesiPresensi::where('guru_id', $guru->id)
            ->with(['kelas' => fn ($q) => $q->withCount('siswa')])
            ->withCount([
                'presensis as hadir_count' => fn ($q) => $q->where('status', 'hadir'),
                'presensis as telat_count' => fn ($q) => $q->where('status', 'telat'),
            ])
            ->latest()
            ->get();

        $sessionsRun   = $sessions->count();
        $totalPresent  = $sessions->sum(fn ($s) => $s->hadir_count + $s->telat_count);
        $totalExpected = $sessions->sum(fn ($s) => $s->kelas->siswa_count ?? 0);

        $avgAttendance = $totalExpected > 0
            ? round($totalPresent / $totalExpected * 100, 1)
            : 0;

        $kelasList      = $sessions->pluck('kelas')->filter()->unique('id')->values();
        $classAssigned  = $kelasList->count();
        $totalStudents  = $kelasList->sum('siswa_count');
        $recentSessions = $sessions->take(5);

        // Saran isian Homeroom: nama kelas yang ada di database (misal "12 RPL 1")
        $kelasOptions = Kelas::orderBy('kelas')
            ->orderBy('jurusan')
            ->orderBy('nomor_kelas')
            ->get()
            ->pluck('nama_lengkap');

        return view('Dashboard.Guru.Profile', compact(
            'guru',
            'user',
            'sessionsRun',
            'totalStudents',
            'classAssigned',
            'avgAttendance',
            'kelasList',
            'recentSessions',
            'kelasOptions'
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
            return redirect()->route('guru.dashboard')->with('error', 'Data profil tidak ditemukan.');
        }

        $data = $request->validate([
            'nama'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'nip'      => ['required', 'string', 'max:50', Rule::unique('gurus', 'nip')->ignore($guru->id)],
            'subject'  => ['nullable', 'string', 'max:100'],
            'homeroom' => ['nullable', 'string', 'max:50'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ], [
            'nip.unique'         => 'Teacher Number sudah dipakai guru lain.',
            'password.min'       => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        DB::transaction(function () use ($user, $guru, $data) {
            $userData = ['name' => $data['nama'], 'email' => $data['email']];
            if (!empty($data['password'])) {
                $userData['password'] = $data['password'];   // di-hash otomatis oleh cast 'hashed' di model User
            }
            $user->update($userData);

            $guru->update([
                'nama'     => $data['nama'],
                'nip'      => $data['nip'],
                'subject'  => $data['subject'] ?? null,
                'homeroom' => $data['homeroom'] ?? null,
            ]);
        });

        return redirect()->route('guru.profile')->with('success', 'Profil berhasil diperbarui!');
    }
}