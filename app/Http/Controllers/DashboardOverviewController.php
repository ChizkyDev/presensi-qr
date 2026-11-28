<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\IzinSakit;
use App\Models\Kelas;
use App\Models\Presensi;
use App\Models\SesiPresensi;
use App\Models\Siswa;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardOverviewController extends Controller
{
    public function index()
    {
        $now = Carbon::now();
        $today = $now->toDateString();

        // 1. Top Cards Data
        $totalStudents = Siswa::count();
        $totalTeachers = Guru::count();
        $activeClasses = Kelas::count();

        $presentOrLateTodayCount = Presensi::whereDate('tanggal', $today)
            ->whereIn('status', ['hadir', 'terlambat'])
            ->distinct('siswa_id')
            ->count('siswa_id');

        $attendancePercentage = $totalStudents > 0
            ? round(($presentOrLateTodayCount / $totalStudents) * 100, 1)
            : 0;

        // 2. Grafik 6 Bulan Terakhir (Dinamis dari tabel presensi)
        $chartLabels = [];
        $chartAbsent = [];
        $chartLate = [];
        $chartPresent = [];

        for ($i = 5; $i >= 0; $i--) {
            $monthDate = $now->copy()->subMonths($i);
            $month = $monthDate->month;
            $year = $monthDate->year;

            $chartLabels[] = $monthDate->translatedFormat('M');

            $monthlyCounts = Presensi::whereMonth('tanggal', $month)
                ->whereYear('tanggal', $year)
                ->select('status', DB::raw('COUNT(*) as total'))
                ->groupBy('status')
                ->pluck('total', 'status');

            $chartPresent[] = (int) ($monthlyCounts['hadir'] ?? 0);
            $chartLate[] = (int) ($monthlyCounts['terlambat'] ?? 0);
            $chartAbsent[] = (int) (
                ($monthlyCounts['alpa'] ?? 0) + ($monthlyCounts['tanpa_keterangan'] ?? 0)
            );
        }

        // 3. Sesi & Widget Counter Samping
        $activeSessionsCount = SesiPresensi::where('status', 'aktif')->count();
        $pendingLeavesCount = IzinSakit::where('status_approve', 'pending')->count();
        $qrSessionsTodayCount = SesiPresensi::whereDate('created_at', $today)->count();

        // Siswa kritis: akumulasi alpa / tanpa_keterangan >= 3 kali
        $criticalStudentsCount = Presensi::whereIn('status', ['alpa', 'tanpa_keterangan'])
            ->select('siswa_id', DB::raw('COUNT(*) as total_alpa'))
            ->groupBy('siswa_id')
            ->having('total_alpa', '>=', 3)
            ->count();

        // 4. Aktivitas Terbaru (Dari presensi & izin_sakit asli di database)
        $recentActivities = collect();

        $latestPresensi = Presensi::with(['siswa', 'sesiPresensi'])
            ->latest('updated_at')
            ->limit(3)
            ->get();

        foreach ($latestPresensi as $item) {
            $namaSiswa = $item->siswa->nama ?? 'Siswa';
            $mapel = $item->sesiPresensi->mapel ?? null;
            $statusLabel = str_replace('_', ' ', ucfirst($item->status ?? 'presensi'));
            $timeSource = $item->jam_scan
                ? Carbon::parse(($item->tanggal ?? $today) . ' ' . $item->jam_scan)
                : ($item->updated_at ?? $item->created_at ?? $now);

            $recentActivities->push([
                'title' => "Presensi {$statusLabel} - {$namaSiswa}",
                'description' => $mapel
                    ? "Mapel {$mapel} (" . Carbon::parse($item->tanggal ?? $timeSource)->format('d M Y') . ')'
                    : 'Tanggal ' . Carbon::parse($item->tanggal ?? $timeSource)->format('d M Y'),
                'time' => Carbon::parse($timeSource)->format('H:i'),
                'sort_at' => Carbon::parse($timeSource),
                'icon' => 'bi-check-circle',
                'badge_class' => 'bg-success-subtle text-success',
            ]);
        }

        $latestLeaves = IzinSakit::with('siswa')
            ->latest('created_at')
            ->limit(3)
            ->get();

        foreach ($latestLeaves as $leave) {
            $namaSiswa = $leave->siswa->nama ?? 'Siswa';
            $jenis = ucfirst($leave->jenis ?? 'izin/sakit');
            $statusApprove = ucfirst($leave->status_approve ?? 'pending');
            $timeSource = $leave->created_at ?? $now;

            $recentActivities->push([
                'title' => "Pengajuan {$jenis} ({$statusApprove})",
                'description' => "Diajukan oleh {$namaSiswa}",
                'time' => Carbon::parse($timeSource)->format('H:i'),
                'sort_at' => Carbon::parse($timeSource),
                'icon' => 'bi-file-earmark-text',
                'badge_class' => 'bg-primary-subtle text-primary',
            ]);
        }

        $recentActivities = $recentActivities
            ->sortByDesc('sort_at')
            ->take(5)
            ->map(function ($row) {
                unset($row['sort_at']);
                return $row;
            })
            ->values()
            ->all();

        // 5. Heatmap Aktivitas Scan QR Minggu Ini (Senin - Jumat, Jam 07:00 - 14:00)
        $startOfWeek = $now->copy()->startOfWeek(Carbon::MONDAY)->toDateString();
        $endOfWeek = $now->copy()->startOfWeek(Carbon::MONDAY)->addDays(4)->toDateString();
        $hours = ['07', '08', '09', '10', '11', '12', '13', '14'];

        $matrixCounts = array_fill(0, 5, array_fill(0, count($hours), 0));

        $weeklyScans = Presensi::whereBetween('tanggal', [$startOfWeek, $endOfWeek])
            ->whereNotNull('jam_scan')
            ->get(['tanggal', 'jam_scan']);

        $maxScan = 0;

        foreach ($weeklyScans as $scan) {
            $tgl = Carbon::parse($scan->tanggal);
            $dayIdx = $tgl->dayOfWeekIso - 1; // Senin = 0, ..., Jumat = 4

            $jamHour = substr((string) $scan->jam_scan, 0, 2);
            $hourIdx = array_search($jamHour, $hours, true);

            if ($dayIdx >= 0 && $dayIdx <= 4 && $hourIdx !== false) {
                $matrixCounts[$dayIdx][$hourIdx]++;
                if ($matrixCounts[$dayIdx][$hourIdx] > $maxScan) {
                    $maxScan = $matrixCounts[$dayIdx][$hourIdx];
                }
            }
        }

        $heatmapRows = [];
        $dayNames = ['Sen', 'Sel', 'Rab', 'Kam', 'Jum'];

        foreach ($dayNames as $dIdx => $dayLabel) {
            $cells = [];
            foreach ($hours as $hIdx => $hourLabel) {
                $count = $matrixCounts[$dIdx][$hIdx];

                if ($count === 0 || $maxScan === 0) {
                    $color = '#eff6ff';
                } else {
                    $ratio = $count / $maxScan;
                    if ($ratio <= 0.33) {
                        $color = '#bfdbfe';
                    } elseif ($ratio <= 0.66) {
                        $color = '#60a5fa';
                    } else {
                        $color = '#2563eb';
                    }
                }

                $cells[] = [
                    'count' => $count,
                    'color' => $color,
                ];
            }

            $heatmapRows[] = [
                'day' => $dayLabel,
                'cells' => $cells,
            ];
        }

        return view('Dashboard.admin.overview', compact(
            'totalStudents',
            'totalTeachers',
            'activeClasses',
            'attendancePercentage',
            'chartLabels',
            'chartAbsent',
            'chartLate',
            'chartPresent',
            'activeSessionsCount',
            'pendingLeavesCount',
            'qrSessionsTodayCount',
            'criticalStudentsCount',
            'recentActivities',
            'heatmapRows'
        ));
    }

    public function guruOverview()
    {
        $today = Carbon::today()->toDateString();
        $userId = Auth::id();

        $guru = Guru::where('user_id', $userId)->first();

        $todaySessionsQuery = SesiPresensi::with(['kelas'])
            ->whereDate('created_at', $today);

        if ($guru) {
            $todaySessionsQuery->where('guru_id', $guru->id);
        }

        $todaySessions = $todaySessionsQuery->latest('created_at')->get();
        $sessionIds = $todaySessions->pluck('id');

        $todaysClassesCount = $todaySessions->count();

        if ($guru && $sessionIds->isNotEmpty()) {
            $targetClassIds = $todaySessions->pluck('kelas_id')->filter()->unique();
            $totalStudents = $targetClassIds->isNotEmpty()
                ? Siswa::whereIn('kelas_id', $targetClassIds)->count()
                : Siswa::count();

            $presentStudentsCount = Presensi::whereIn('sesi_presensi_id', $sessionIds)
                ->where('status', 'hadir')
                ->distinct('siswa_id')
                ->count('siswa_id');

            $absentStudentsCount = Presensi::whereIn('sesi_presensi_id', $sessionIds)
                ->whereIn('status', ['alpa', 'tanpa_keterangan'])
                ->distinct('siswa_id')
                ->count('siswa_id');
        } else {
            $totalStudents = Siswa::count();

            $presentStudentsCount = Presensi::whereDate('tanggal', $today)
                ->where('status', 'hadir')
                ->distinct('siswa_id')
                ->count('siswa_id');

            $absentStudentsCount = Presensi::whereDate('tanggal', $today)
                ->whereIn('status', ['alpa', 'tanpa_keterangan'])
                ->distinct('siswa_id')
                ->count('siswa_id');
        }

        $avgAttendance = $totalStudents > 0
            ? round(($presentStudentsCount / $totalStudents) * 100, 1)
            : 0;

        $approvedLeaveStudentIdsToday = IzinSakit::where('status_approve', 'approved')
            ->whereDate('tanggal', $today)
            ->pluck('siswa_id');

        $unexcusedAbsentsCount = Presensi::whereDate('tanggal', $today)
            ->whereIn('status', ['alpa', 'tanpa_keterangan'])
            ->when($guru && $sessionIds->isNotEmpty(), function ($q) use ($sessionIds) {
                return $q->whereIn('sesi_presensi_id', $sessionIds);
            })
            ->whereNotIn('siswa_id', $approvedLeaveStudentIdsToday)
            ->distinct('siswa_id')
            ->count('siswa_id');

        return view('Dashboard.guru.overview', compact(
            'guru',
            'todaysClassesCount',
            'totalStudents',
            'avgAttendance',
            'todaySessions',
            'presentStudentsCount',
            'absentStudentsCount',
            'unexcusedAbsentsCount'
        ));
    }

    public function siswaOverview()
    {
        $now = Carbon::now();
        $today = $now->toDateString();
        $user = Auth::user();

        $siswa = Siswa::with('kelas')->where('user_id', $user->id ?? null)->first();
        $siswaId = $siswa?->id;

        $currentMonth = $now->month;
        $currentYear = $now->year;

        if ($siswaId) {
            $presentCount = Presensi::where('siswa_id', $siswaId)
                ->whereMonth('tanggal', $currentMonth)
                ->whereYear('tanggal', $currentYear)
                ->where('status', 'hadir')
                ->count();

            $lateCount = Presensi::where('siswa_id', $siswaId)
                ->whereMonth('tanggal', $currentMonth)
                ->whereYear('tanggal', $currentYear)
                ->where('status', 'terlambat')
                ->count();

            $permittedCount = IzinSakit::where('siswa_id', $siswaId)
                ->whereIn('status_approve', ['diterima', 'approved'])
                ->whereMonth('tanggal', $currentMonth)
                ->whereYear('tanggal', $currentYear)
                ->count();

            $absentCount = Presensi::where('siswa_id', $siswaId)
                ->whereMonth('tanggal', $currentMonth)
                ->whereYear('tanggal', $currentYear)
                ->whereIn('status', ['alpa', 'tanpa_keterangan'])
                ->count();
        } else {
            $presentCount = 0;
            $lateCount = 0;
            $permittedCount = 0;
            $absentCount = 0;
        }

        $todaySessions = collect();
        if ($siswa && $siswa->kelas_id) {
            $sessions = SesiPresensi::with(['kelas'])
                ->where('kelas_id', $siswa->kelas_id)
                ->whereDate('created_at', $today)
                ->latest('created_at')
                ->get();

            $presensiTodayBySession = $siswaId
                ? Presensi::where('siswa_id', $siswaId)
                    ->whereDate('tanggal', $today)
                    ->whereIn('sesi_presensi_id', $sessions->pluck('id'))
                    ->get()
                    ->keyBy('sesi_presensi_id')
                : collect();

            $todaySessions = $sessions->map(function ($sess) use ($presensiTodayBySession, $today) {
                $p = $presensiTodayBySession->get($sess->id);
                $rawStatus = $p?->status;

                if ($rawStatus === 'hadir') {
                    $displayStatus = 'Present';
                } elseif ($rawStatus === 'terlambat') {
                    $displayStatus = 'Late';
                } elseif (in_array($rawStatus, ['alpa', 'tanpa_keterangan'], true)) {
                    $displayStatus = 'Absent';
                } else {
                    $displayStatus = 'Upcoming';
                }

                $jamScan = $p?->jam_scan;
                $tglPresensi = $p?->tanggal ?? $today;

                $timeSource = !empty($jamScan)
                    ? Carbon::parse($tglPresensi . ' ' . $jamScan)
                    : Carbon::parse($sess->created_at ?? now());

                return (object) [
                    'subject_name' => $sess->mapel ?? 'Mata Pelajaran',
                    'time'         => $timeSource,
                    'status'       => $displayStatus,
                ];
            });
        }

        $totalRecord = $presentCount + $lateCount + $permittedCount + $absentCount;
        $attendancePercentage = $totalRecord > 0
            ? round((($presentCount + $lateCount) / $totalRecord) * 100, 1)
            : 0;

        $dayMap = [
            1 => 'Mon',
            2 => 'Tue',
            3 => 'Wed',
            4 => 'Thu',
            5 => 'Fri',
        ];
        $weeks = ['W1', 'W2', 'W3', 'W4'];
        $calendarGrid = [];
        foreach ($weeks as $w) {
            $calendarGrid[$w] = [
                'Mon' => '-',
                'Tue' => '-',
                'Wed' => '-',
                'Thu' => '-',
                'Fri' => '-',
            ];
        }

        $monthlyChartData = [0, 0, 0, 0];
        $streakDays = 0;

        if ($siswaId) {
            $monthPresensi = Presensi::where('siswa_id', $siswaId)
                ->whereMonth('tanggal', $currentMonth)
                ->whereYear('tanggal', $currentYear)
                ->orderBy('tanggal')
                ->get();

            $groupedByWeek = [];

            foreach ($monthPresensi as $row) {
                $tgl = Carbon::parse($row->tanggal);
                $dayName = $dayMap[$tgl->dayOfWeekIso] ?? null;
                $weekNum = min(4, max(1, (int) ceil($tgl->day / 7)));
                $weekKey = 'W' . $weekNum;

                if ($row->status === 'hadir') {
                    $code = 'P';
                } elseif ($row->status === 'terlambat') {
                    $code = 'L';
                } elseif (in_array($row->status, ['alpa', 'tanpa_keterangan'], true)) {
                    $code = 'A';
                } else {
                    $code = '-';
                }

                if ($dayName) {
                    $calendarGrid[$weekKey][$dayName] = $code;
                }

                $groupedByWeek[$weekNum][] = $code;
            }

            foreach (range(1, 4) as $wNum) {
                $codes = $groupedByWeek[$wNum] ?? [];
                $countAll = count($codes);
                if ($countAll > 0) {
                    $okCount = count(array_filter($codes, fn ($c) => in_array($c, ['P', 'L'], true)));
                    $monthlyChartData[$wNum - 1] = round(($okCount / $countAll) * 100, 1);
                }
            }

            $datesDesc = Presensi::where('siswa_id', $siswaId)
                ->orderByDesc('tanggal')
                ->get()
                ->groupBy('tanggal');

            foreach ($datesDesc as $itemsOnDate) {
                $hasOk = $itemsOnDate->contains(fn ($r) => in_array($r->status, ['hadir', 'terlambat'], true));
                $hasBad = $itemsOnDate->contains(fn ($r) => in_array($r->status, ['alpa', 'tanpa_keterangan'], true));

                if ($hasOk && !$hasBad) {
                    $streakDays++;
                } else {
                    break;
                }
            }
        }

        return view('Dashboard.siswa.overview', compact(
            'user',
            'siswa',
            'presentCount',
            'lateCount',
            'permittedCount',
            'absentCount',
            'todaySessions',
            'attendancePercentage',
            'calendarGrid',
            'monthlyChartData',
            'streakDays'
        ));
    }
}