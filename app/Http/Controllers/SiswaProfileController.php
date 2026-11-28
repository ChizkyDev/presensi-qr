<?php

namespace App\Http\Controllers;

use App\Models\Presensi;
use App\Models\SesiPresensi;
use App\Models\Siswa;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SiswaProfileController extends Controller
{
    public function index()
    {
        $user  = Auth::user();
        $siswa = $user->siswa()->with('kelas')->first();

        if (!$siswa) {
            return redirect()->route('siswa.dashboard')->with('error', 'Data siswa tidak ditemukan.');
        }

        // ── Semester berjalan: Jul–Des atau Jan–Jun ──
        $now      = Carbon::now();
        $semStart = Carbon::create($now->year, $now->month >= 7 ? 7 : 1, 1)->startOfDay();
        $semEnd   = $semStart->copy()->addMonths(6)->subSecond();

        // Sesi kelas siswa pada semester ini, lengkap dengan status kehadiran siswa ini
        $sessions = SesiPresensi::where('kelas_id', $siswa->kelas_id)
            ->whereBetween('created_at', [$semStart, $semEnd])
            ->withCount([
                'presensis as attended' => fn ($q) => $q->where('siswa_id', $siswa->id)->whereIn('status', ['hadir', 'telat']),
                'presensis as late'     => fn ($q) => $q->where('siswa_id', $siswa->id)->where('status', 'telat'),
            ])
            ->get();

        $totalSessions  = $sessions->count();
        $attendedCount  = $sessions->where('attended', '>', 0)->count();
        $lateCount      = $sessions->where('late', '>', 0)->count();
        $absences       = $totalSessions - $attendedCount;
        $attendanceRate = $totalSessions > 0 ? (int) round($attendedCount / $totalSessions * 100) : 0;

        // Hari sempurna (hadir di semua sesi hari itu) dan streak (hari berturut-turut hadir, dari yang terbaru)
        $byDay = $sessions->groupBy(fn ($s) => $s->created_at->toDateString())->sortKeysDesc();

        $perfectDays = $byDay->filter(fn ($g) => $g->every(fn ($s) => $s->attended > 0))->count();

        $streak = 0;
        foreach ($byDay as $group) {
            if ($group->contains(fn ($s) => $s->attended > 0)) {
                $streak++;
            } else {
                break;
            }
        }

        // Peringkat semester di antara teman sekelas (berdasarkan jumlah sesi yang dihadiri)
        $classmateIds = Siswa::where('kelas_id', $siswa->kelas_id)->pluck('id');
        $counts = Presensi::whereIn('sesi_presensi_id', $sessions->pluck('id'))
            ->whereIn('siswa_id', $classmateIds)
            ->whereIn('status', ['hadir', 'telat'])
            ->select('siswa_id', DB::raw('count(*) as total'))
            ->groupBy('siswa_id')
            ->pluck('total', 'siswa_id');

        $mine = $counts[$siswa->id] ?? 0;
        $rank = $totalSessions > 0
            ? 1 + $classmateIds->filter(fn ($id) => ($counts[$id] ?? 0) > $mine)->count()
            : null;

        // Aktivitas kehadiran terbaru
        $recent = Presensi::where('siswa_id', $siswa->id)
            ->with('sesiPresensi.guru')
            ->orderByDesc('tanggal')
            ->orderByDesc('id')
            ->take(5)
            ->get();

        $recentRange = null;
        if ($recent->isNotEmpty()) {
            $from = Carbon::parse($recent->min('tanggal'));
            $to   = Carbon::parse($recent->max('tanggal'));
            $recentRange = $from->isSameDay($to)
                ? $to->format('M d, Y')
                : $from->format('M d') . ' – ' . $to->format('M d, Y');
        }

        // Pencapaian
        $streakTier = $streak >= 15 ? ['Gold', '#f59e0b']
            : ($streak >= 10 ? ['Silver', '#64748b']
            : ($streak >= 5 ? ['Bronze', '#c2410c'] : null));

        $achievements = [
            [
                'title' => $streak >= 5 ? "{$streak}-Day Streak" : 'Attendance Streak',
                'desc'  => $streak >= 5 ? 'Unlocked this semester' : "{$streak}/5 days to unlock",
                'badge' => $streakTier[0] ?? 'Locked',
                'color' => $streakTier[1] ?? '#94a3b8',
                'icon'  => 'bi-lightning-charge-fill',
            ],
            [
                'title' => 'Top Attendance',
                'desc'  => $attendanceRate >= 90 ? 'Unlocked this semester' : "Reach 90% (now {$attendanceRate}%)",
                'badge' => $attendanceRate >= 90 ? 'Green' : 'Locked',
                'color' => $attendanceRate >= 90 ? '#16a34a' : '#94a3b8',
                'icon'  => 'bi-star-fill',
            ],
            [
                'title' => 'Zero Absences',
                'desc'  => ($totalSessions > 0 && $absences === 0) ? 'Unlocked this semester' : 'Attend every session to unlock',
                'badge' => ($totalSessions > 0 && $absences === 0) ? 'Purple' : 'Locked',
                'color' => ($totalSessions > 0 && $absences === 0) ? '#8b5cf6' : '#94a3b8',
                'icon'  => 'bi-shield-check',
            ],
        ];

        return view('Dashboard.siswa.profile', compact(
            'user', 'siswa',
            'attendanceRate', 'streak', 'rank', 'absences', 'lateCount', 'perfectDays',
            'recent', 'recentRange', 'achievements'
        ));
    }

    public function update(Request $request)
    {
        $user  = Auth::user();
        $siswa = $user->siswa;

        if (!$siswa) {
            return redirect()->route('siswa.dashboard')->with('error', 'Data siswa tidak ditemukan.');
        }

        $data = $request->validate([
            'nama'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ], [
            'password.min'       => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        DB::transaction(function () use ($user, $siswa, $data) {
            $userData = ['name' => $data['nama'], 'email' => $data['email']];
            if (!empty($data['password'])) {
                $userData['password'] = $data['password'];   // di-hash otomatis oleh cast 'hashed' di model User
            }
            $user->update($userData);

            $siswa->update([
                'nama' => $data['nama'],
            ]);
        });

        return redirect()->route('siswa.profile')->with('success', 'Profil berhasil diperbarui!');
    }
}