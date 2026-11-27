<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SesiPresensi;
use App\Models\Kelas;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;

class SessionController extends Controller
{
    public function create()
    {
        $guru = Auth::user()->guru;

        if (!$guru) {
            return redirect()->back()->with('error', 'Data profil guru tidak ditemukan.');
        }

        $listKelas = Kelas::all();

        if ($listKelas->isEmpty()) {
            $listKelas = collect([
                (object)['id' => 1, 'kelas' => 'XII', 'jurusan' => 'RPL', 'nomor_kelas' => '1'],
                (object)['id' => 2, 'kelas' => 'XII', 'jurusan' => 'RPL', 'nomor_kelas' => '2'],
                (object)['id' => 3, 'kelas' => 'XI', 'jurusan' => 'TKJ', 'nomor_kelas' => '1'],
                (object)['id' => 4, 'kelas' => 'X', 'jurusan' => 'DKV', 'nomor_kelas' => '1'],
            ]);
        }

        $requestHistories = SesiPresensi::with('kelas')
            ->where('guru_id', $guru->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('Dashboard.Guru.Create_Session', compact('listKelas', 'requestHistories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kelas_id'    => 'required',
            'mapel'       => 'required|string|max:100',
            'qr_duration' => 'required|integer|min:1',
        ]);

        $guru = Auth::user()->guru;

        if (!$guru) {
            return redirect()->back()->with('error', 'Akses ditolak. Anda bukan guru terdaftar.');
        }

        $durasiMenit = (int) $request->qr_duration;
        $kodeSesi = $request->session_code ?: strtoupper(Str::random(6));
        $expiredAt = Carbon::now()->addMinutes($durasiMenit);

        $sesi = SesiPresensi::create([
            'guru_id'    => $guru->id,
            'kelas_id'   => $request->kelas_id,
            'mapel'      => $request->mapel,
            'qr_token'   => $kodeSesi,
            'expired_at' => $expiredAt,
            'status'     => 'aktif',
        ]);

        return redirect()
            ->route('guru.sessions.active', ['id' => $sesi->id])
            ->with('success', 'Sesi presensi berhasil diaktifkan!');
    }

    public function active(Request $request, $id = null)
    {
        $guru = Auth::user()->guru;

        if (!$guru) {
            return redirect()->back()->with('error', 'Data profil guru tidak ditemukan.');
        }

        $query = SesiPresensi::with('kelas')->where('guru_id', $guru->id);

        if ($id) {
            $session = (clone $query)->where('id', $id)->first();
        } else {
            // clone: supaya filter 'aktif' tidak ikut terbawa ke query cadangan
            $session = (clone $query)->where('status', 'aktif')->latest()->first()
                ?? (clone $query)->latest()->first();
        }

        // Jika belum ada sesi sama sekali, arahkan guru untuk membuat sesi baru
        if (!$session) {
            return redirect()
                ->route('guru.sessions.create')
                ->with('error', 'Belum ada sesi presensi. Silakan buat sesi baru.');
        }

        $kelasLabel = 'XII-A';
        if ($session && $session->kelas) {
            $kelasLabel = trim(
                ($session->kelas->kelas ?? '') . ' ' .
                ($session->kelas->jurusan ?? '') . ' ' .
                ($session->kelas->nomor_kelas ?? '')
            ) ?: 'XII-A';
        } elseif ($session && $session->kelas_id) {
            $kelasLabel = 'Kelas #' . $session->kelas_id;
        }

        $kodeSesi = $session->qr_token ?? 'X7K9QP';
        $mapelLabel = $session->mapel ?? 'Mathematics';
        $ruanganLabel = $session->ruangan ?? 'Lab Komputer 1';

        $qrPayload = json_encode([
            'app'        => 'Attendly',
            'session_id' => $session->id ?? null,
            'code'       => $kodeSesi,
            'class'      => $kelasLabel,
            'subject'    => $mapelLabel,
            'room'       => $ruanganLabel,
            'date'       => optional($session?->created_at)->toDateString() ?? Carbon::today()->toDateString(),
            'expired_at' => optional($session?->expired_at)->toDateTimeString(),
        ]);

        $remainingSeconds = 900;
        if ($session && $session->expired_at) {
            $remainingSeconds = max(0, (int) Carbon::now()->diffInSeconds(Carbon::parse($session->expired_at), false));
        }

        // Statistik nyata dari database (sebelumnya ditulis tetap: 32 siswa, 0 hadir)
        $expectedStudents = $session->kelas ? $session->kelas->siswa()->count() : 0;
        $present = $session->presensis()->where('status', 'hadir')->count();
        $late    = $session->presensis()->where('status', 'telat')->count();

        $stats = [
            'present' => $present,
            'late'    => $late,
            'absent'  => max(0, $expectedStudents - ($present + $late)),
        ];

        // Daftar scan diisi oleh polling (method feed) begitu halaman terbuka
        $attendances = collect();

        return view('Dashboard.guru.active_session', compact(
            'session',
            'kelasLabel',
            'ruanganLabel',
            'qrPayload',
            'remainingSeconds',
            'expectedStudents',
            'stats',
            'attendances'
        ));
    }

    /**
     * Data live untuk halaman Active Session (dipanggil berkala oleh JavaScript).
     */
    public function feed($id)
    {
        $guru = Auth::user()->guru;

        // hanya sesi milik guru yang sedang login
        $session = SesiPresensi::with('kelas')
            ->where('guru_id', $guru?->id)
            ->findOrFail($id);

        $total = $session->kelas ? $session->kelas->siswa()->count() : 0;

        $items = $session->presensis()
            ->with('siswa')
            ->orderByDesc('id')
            ->get()
            ->map(fn ($p) => [
                'name'   => $p->siswa->nama ?? 'Student',
                'code'   => $p->siswa->nis ?? '-',
                'time'   => $p->jam_scan,
                'status' => match ($p->status) {
                    'hadir' => 'present',
                    'telat' => 'late',
                    default => 'absent',   // izin, sakit, alpha
                },
            ]);

        $present = $items->where('status', 'present')->count();
        $late    = $items->where('status', 'late')->count();

        $remaining = $session->expired_at
            ? max(0, $session->expired_at->timestamp - now()->timestamp)
            : 0;

        return response()->json([
            'total'     => $total,
            'present'   => $present,
            'late'      => $late,
            'absent'    => max(0, $total - ($present + $late)),
            'remaining' => $remaining,
            'items'     => $items->values(),
        ]);
    }

    public function end($id)
    {
        $guru = Auth::user()->guru;

        if (!$guru) {
            return redirect()->back()->with('error', 'Akses ditolak.');
        }

        $session = SesiPresensi::where('guru_id', $guru->id)->where('id', $id)->firstOrFail();
        $session->update([
            'status'     => 'nonaktif',
            'expired_at' => Carbon::now(),
        ]);

        return redirect()
            ->route('guru.sessions.create')
            ->with('success', 'Sesi presensi telah diakhiri.');
    }
}