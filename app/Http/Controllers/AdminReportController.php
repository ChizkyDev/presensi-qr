<?php

namespace App\Http\Controllers;

use App\Models\IzinSakit;
use App\Models\Kelas;
use App\Models\Presensi;
use App\Models\SesiPresensi;
use App\Models\Siswa;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminReportController extends Controller
{
    public function index(Request $request)
    {
        $tab = in_array($request->get('tab'), ['overview', 'class', 'subject', 'export'], true)
            ? $request->get('tab')
            : 'overview';

        $now        = Carbon::now();
        $monthStart = $now->copy()->startOfMonth();
        $monthEnd   = $now->copy()->endOfMonth();

        // ── Bulan ini vs bulan lalu ──
        $sessionsMonth = $this->sessionsBetween($monthStart, $monthEnd);
        $thisMonth     = $this->summarize($sessionsMonth);

        $lastStart = $now->copy()->subMonthNoOverflow()->startOfMonth();
        $lastMonth = $this->summarize($this->sessionsBetween($lastStart, $lastStart->copy()->endOfMonth()));

        // ── 4 kartu statistik ──
        $schoolAvgRate = $thisMonth['expected'] > 0 ? $thisMonth['rate'] : null;
        $rateDelta = ($thisMonth['expected'] > 0 && $lastMonth['expected'] > 0)
            ? round($thisMonth['rate'] - $lastMonth['rate'], 1)
            : null;

        $atRiskByKelas  = $this->atRiskByKelas();
        $atRiskStudents = array_sum($atRiskByKelas);

        $totalSessions     = SesiPresensi::count();
        $sessionsThisMonth = SesiPresensi::where('created_at', '>=', $monthStart)->count();

        // Izin yang disetujui (nilai status_approve sama dengan controller lama)
        $approvedStatuses = ['diterima', 'approved'];
        $leaveApproved    = IzinSakit::whereIn('status_approve', $approvedStatuses)->count();
        $leaveThisMonth   = IzinSakit::whereIn('status_approve', $approvedStatuses)
            ->whereMonth('tanggal', $now->month)
            ->whereYear('tanggal', $now->year)
            ->count();

        // ── Laporan per kelas (bulan ini) ──
        $classReports = $sessionsMonth->groupBy('kelas_id')->map(function ($group) use ($atRiskByKelas) {
            $kelas = $group->first()->kelas;
            $sum   = $this->summarize($group);

            return [
                'nama_kelas'  => $kelas->nama_lengkap ?? '-',
                'total'       => $kelas->siswa_count ?? 0,
                'present'     => $sum['present'],
                'late'        => $sum['late'],
                'absent'      => $sum['absent'],
                'present_pct' => $sum['present_pct'],
                'late_pct'    => $sum['late_pct'],
                'absent_pct'  => $sum['absent_pct'],
                'rate'        => $sum['rate'],
                'at_risk'     => $atRiskByKelas[$group->first()->kelas_id] ?? 0,
                'status'      => $sum['rate'] >= 90 ? 'Good' : ($sum['rate'] >= 80 ? 'Fair' : 'Poor'),
            ];
        })->sortByDesc('rate')->values()->all();

        $topClasses = array_slice($classReports, 0, 5);

        // ── Laporan per mata pelajaran (bulan ini) ──
        $subjectReports = $sessionsMonth
            ->groupBy(fn ($s) => $this->normalizeSubject($s->mapel))
            ->map(function ($group, $name) {
                $sum = $this->summarize($group);
                return [
                    'name'     => $name,
                    'sessions' => $sum['sessions'],
                    'rate'     => $sum['rate'],
                    'color'    => $this->rateColor($sum['rate']),
                ];
            })->sortByDesc('rate')->values()->all();

        // ── 6 bulan terakhir: tren + mata pelajaran per bulan ──
        $months   = collect(range(5, 0))->map(fn ($i) => $now->copy()->startOfMonth()->subMonths($i));
        $rangeAll = $this->sessionsBetween($months->first(), $monthEnd);
        $byMonth  = $rangeAll->groupBy(fn ($s) => $s->created_at->format('Y-m'));

        $trendLabels = $trendPresent = $trendLate = $trendAbsent = [];
        foreach ($months as $m) {
            $sum = $this->summarize($byMonth->get($m->format('Y-m'), collect()));
            $trendLabels[]  = $m->format('M');
            $trendPresent[] = $sum['present_pct'];
            $trendLate[]    = $sum['late_pct'];
            $trendAbsent[]  = $sum['absent_pct'];
        }
        $trendRange = $months->first()->format('M') . ' – ' . $months->last()->format('M Y');

        $subjectOf   = fn ($s) => $this->normalizeSubject($s->mapel);
        $topSubjects = $rangeAll->groupBy($subjectOf)->map->count()->sortDesc()->take(5)->keys();
        $palette     = ['#1d4ed8', '#2563eb', '#60a5fa', '#93c5fd', '#bfdbfe'];

        $subjectMonthSeries = $topSubjects->values()->map(function ($name, $i) use ($rangeAll, $months, $subjectOf, $palette) {
            $data = $months->map(function ($m) use ($rangeAll, $name, $subjectOf) {
                $group = $rangeAll->filter(fn ($s) =>
                    $subjectOf($s) === $name && $s->created_at->format('Y-m') === $m->format('Y-m'));
                return $this->summarize($group)['rate'];
            })->all();

            return ['name' => $name, 'color' => $palette[$i] ?? '#94a3b8', 'data' => $data];
        })->all();

        // ── Minggu ini (Senin–Jumat) ──
        $weekStart    = $now->copy()->startOfWeek();
        $weekSessions = $this->sessionsBetween($weekStart, $weekStart->copy()->addDays(4)->endOfDay())
            ->groupBy(fn ($s) => $s->created_at->format('Y-m-d'));

        $weekly = [];
        foreach (['Mon', 'Tue', 'Wed', 'Thu', 'Fri'] as $i => $dayName) {
            $date     = $weekStart->copy()->addDays($i);
            $weekly[] = ['day' => $dayName] + $this->summarize($weekSessions->get($date->format('Y-m-d'), collect()));
        }
        $weekRange = $weekStart->format('M j') . '–' . $weekStart->copy()->addDays(4)->format('j, Y');

        $statusSplit = $thisMonth;
        $kelasList   = Kelas::orderBy('kelas')->orderBy('jurusan')->orderBy('nomor_kelas')->get();

        return view('Dashboard.admin.reports', compact(
            'tab',
            'schoolAvgRate', 'rateDelta', 'atRiskStudents', 'totalSessions', 'sessionsThisMonth',
            'leaveApproved', 'leaveThisMonth',
            'classReports', 'topClasses', 'subjectReports',
            'trendLabels', 'trendPresent', 'trendLate', 'trendAbsent', 'trendRange',
            'subjectMonthSeries', 'months',
            'weekly', 'weekRange', 'statusSplit', 'kelasList'
        ));
    }

    /**
     * Method untuk menangani proses unduh file export laporan.
     */
    public function exportData(Request $request)
    {
        $type = $request->get('type', 'monthly');
        $from = $request->get('from');
        $to = $request->get('to');
        $classId = $request->get('class_id');

        $filename = 'attendly-report-' . $type . '-' . Carbon::now()->format('Y-m-d') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0',
        ];

        $query = Presensi::with(['siswa.kelas', 'sesiPresensi']);

        if ($from && $to) {
            $query->whereBetween('tanggal', [$from, $to]);
        }

        if ($classId) {
            $query->where(function ($q) use ($classId) {
                $q->whereHas('siswa', function ($sq) use ($classId) {
                    $sq->where('kelas_id', $classId);
                })->orWhereHas('sesiPresensi', function ($vq) use ($classId) {
                    $vq->where('kelas_id', $classId);
                });
            });
        }

        $data = $query->latest('tanggal')->latest('id')->get();

        $callback = function () use ($data) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Nama Siswa', 'Kelas', 'Tanggal', 'Jam Scan', 'Status', 'Mata Pelajaran']);

            foreach ($data as $row) {
                $kelasObj = $row->siswa->kelas ?? $row->sesiPresensi->kelas ?? null;
                $namaKelas = $kelasObj
                    ? trim(($kelasObj->kelas ?? '') . ' ' . ($kelasObj->jurusan ?? '') . ' ' . ($kelasObj->nomor_kelas ?? ''))
                    : '-';

                fputcsv($file, [
                    $row->id,
                    $row->siswa->nama ?? 'N/A',
                    $namaKelas !== '' ? $namaKelas : '-',
                    $row->tanggal,
                    $row->jam_scan ?? '-',
                    $row->status,
                    $row->sesiPresensi->mapel ?? '-',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /** Sesi dalam rentang waktu, lengkap dengan jumlah siswa kelas dan hitungan hadir/telat. */
    private function sessionsBetween(Carbon $from, Carbon $to)
    {
        return SesiPresensi::whereBetween('created_at', [$from, $to])
            ->with(['kelas' => fn ($q) => $q->withCount('siswa')])
            ->withCount([
                'presensis as hadir_count' => fn ($q) => $q->where('status', 'hadir'),
                'presensis as telat_count' => fn ($q) => $q->where('status', 'telat'),
            ])
            ->get();
    }

    /** Ringkasan sekumpulan sesi: jumlah dan persentase hadir, telat, absent. */
    private function summarize($sessions): array
    {
        $expected = $sessions->sum(fn ($s) => $s->kelas->siswa_count ?? 0);
        $present  = $sessions->sum('hadir_count');
        $late     = $sessions->sum('telat_count');
        $absent   = max(0, $expected - $present - $late);
        $pct      = fn ($n) => $expected > 0 ? round($n / $expected * 100, 1) : 0;

        return [
            'sessions'    => $sessions->count(),
            'expected'    => $expected,
            'present'     => $present,
            'late'        => $late,
            'absent'      => $absent,
            'present_pct' => $pct($present),
            'late_pct'    => $pct($late),
            'absent_pct'  => $pct($absent),
            'rate'        => $pct($present + $late),
        ];
    }

    /** Jumlah siswa berisiko (kehadiran < 75% dari seluruh sesi kelasnya), per kelas. */
    private function atRiskByKelas(): array
    {
        $sessionsPerKelas = SesiPresensi::select('kelas_id', DB::raw('count(*) as total'))
            ->groupBy('kelas_id')->pluck('total', 'kelas_id');

        $attended = Presensi::whereIn('status', ['hadir', 'telat'])
            ->select('siswa_id', DB::raw('count(*) as total'))
            ->groupBy('siswa_id')->pluck('total', 'siswa_id');

        $result = [];
        foreach (Siswa::get(['id', 'kelas_id']) as $s) {
            $expected = $sessionsPerKelas[$s->kelas_id] ?? 0;
            if ($expected > 0 && (($attended[$s->id] ?? 0) / $expected * 100) < 75) {
                $result[$s->kelas_id] = ($result[$s->kelas_id] ?? 0) + 1;
            }
        }

        return $result;
    }

    private function normalizeSubject($mapel): string
    {
        $name = trim((string) $mapel);
        return $name === '' ? 'Unknown' : mb_convert_case($name, MB_CASE_TITLE, 'UTF-8');
    }

    private function rateColor($rate): string
    {
        return $rate >= 90 ? '#16a34a' : ($rate >= 80 ? '#2563eb' : ($rate >= 70 ? '#f59e0b' : '#ef4444'));
    }
}