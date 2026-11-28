@extends('layouts.siswa')

@section('title', 'Overview - Student Dashboard')

@section('content')
@php
    $activeSiswa = $siswa ?? Auth::user()->siswa ?? null;
    $activeUser = $user ?? Auth::user();
    $siswaKelas = $activeSiswa?->kelas ?? ($kelas ?? ($activeUser->kelas ?? null));
@endphp

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold text-dark mb-0">Overview</h3>
        <small class="text-muted">
            {{ $activeSiswa->nama ?? $activeUser->name ?? 'Siswa' }} ·
            @if(is_object($siswaKelas))
                {{ trim(($siswaKelas->kelas ?? $siswaKelas->nama_kelas ?? $siswaKelas->nama ?? '') . ' ' . ($siswaKelas->jurusan ?? '') . ' ' . ($siswaKelas->nomor_kelas ?? '')) ?: '-' }}
            @else
                {{ $siswaKelas ?? '-' }}
            @endif
            · NIS {{ $activeSiswa->nis ?? $activeUser->nis ?? '-' }}
        </small>
    </div>
    <div class="bg-white px-3 py-2 rounded-pill shadow-sm border text-primary fw-semibold fs-7">
        <i class="bi bi-calendar-event me-1"></i> {{ now()->translatedFormat('d F Y') }}
    </div>
</div>

<!-- 4 KARTU STATISTIK -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-circle bg-success bg-opacity-10 text-success p-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                    <i class="bi bi-check-circle-fill fs-4"></i>
                </div>
                <div>
                    <h3 class="fw-bold mb-0">{{ $presentCount ?? 0 }}</h3>
                    <small class="text-muted">Present this month</small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-circle bg-warning bg-opacity-10 text-warning p-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                    <i class="bi bi-clock-fill fs-4"></i>
                </div>
                <div>
                    <h3 class="fw-bold mb-0">{{ $lateCount ?? 0 }}</h3>
                    <small class="text-muted">Late this month</small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background-color: #f3e8ff;">
                    <i class="bi bi-file-earmark-text-fill fs-4" style="color: #9333ea;"></i>
                </div>
                <div>
                    <h3 class="fw-bold mb-0">{{ $permittedCount ?? 0 }}</h3>
                    <small class="text-muted">Permitted this month</small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-circle bg-danger bg-opacity-10 text-danger p-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                    <i class="bi bi-slash-circle-fill fs-4"></i>
                </div>
                <div>
                    <h3 class="fw-bold mb-0">{{ $absentCount ?? 0 }}</h3>
                    <small class="text-muted">Absent this month</small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- BARIS TENGAH: GRAFIK & TODAY'S SESSION -->
<div class="row g-3 mb-4">
    <!-- Monthly Attendance Chart -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="d-flex align-items-center gap-2">
                    <div class="bg-primary bg-opacity-10 text-primary p-2 rounded-3">
                        <i class="bi bi-calendar-week"></i>
                    </div>
                    <h6 class="fw-bold mb-0">Monthly Attendance</h6>
                </div>
                <select class="form-select form-select-sm w-auto border-0 bg-light rounded-pill">
                    <option>{{ now()->translatedFormat('M Y') }}</option>
                </select>
            </div>
            <div style="height: 220px;">
                <canvas id="attendanceChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Today's Session -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="d-flex align-items-center gap-2">
                    <div class="bg-primary bg-opacity-10 text-primary p-2 rounded-3">
                        <i class="bi bi-clock"></i>
                    </div>
                    <h6 class="fw-bold mb-0">Today's Session</h6>
                </div>
                <a href="#" class="text-decoration-none fs-7 fw-semibold">View all</a>
            </div>

            <div class="d-flex flex-column gap-2">
                @forelse($todaySessions ?? [] as $session)
                    <div class="d-flex align-items-center justify-content-between p-2 rounded-3 hover-bg">
                        <div>
                            <div class="fw-semibold text-dark fs-7">{{ $session->subject_name ?? ($session->mapel ?? 'Mata Pelajaran') }}</div>
                            <small class="text-muted">{{ \Carbon\Carbon::parse($session->time ?? ($session->created_at ?? now()))->format('H:i') }} WIB</small>
                        </div>
                        @if(($session->status ?? '') === 'Present')
                            <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-1">Present</span>
                        @elseif(($session->status ?? '') === 'Late')
                            <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-3 py-1">Late</span>
                        @elseif(($session->status ?? '') === 'Absent')
                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-3 py-1">Absent</span>
                        @else
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-1">Upcoming</span>
                        @endif
                    </div>
                @empty
                    <div class="text-center text-muted py-4 fs-7">
                        Tidak ada sesi/jadwal untuk hari ini.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- BARIS BAWAH: 4-WEEK CALENDAR & MONTHLY STREAK -->
<div class="row g-3">
    <!-- 4-Week Attendance Calendar -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
            <div class="d-flex align-items-center gap-2 mb-3">
                <div class="bg-primary bg-opacity-10 text-primary p-2 rounded-3">
                    <i class="bi bi-grid-3x3-gap"></i>
                </div>
                <h6 class="fw-bold mb-0">4-Week Attendance Calendar</h6>
            </div>

            <div class="table-responsive">
                <table class="table table-borderless text-center align-middle mb-0">
                    <thead>
                        <tr class="text-muted fs-7">
                            <th></th>
                            <th>Mon</th>
                            <th>Tue</th>
                            <th>Wed</th>
                            <th>Thu</th>
                            <th>Fri</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $days = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri'];
                            $weeks = ['W1', 'W2', 'W3', 'W4'];
                        @endphp

                        @foreach($weeks as $week)
                            <tr>
                                <td class="text-muted fs-7 fw-semibold">{{ $week }}</td>
                                @foreach($days as $day)
                                    @php
                                        $status = $calendarGrid[$week][$day] ?? '-';
                                    @endphp
                                    <td>
                                        @if($status === 'P')
                                            <span class="badge bg-success-subtle text-success rounded-3 w-100 py-2">P</span>
                                        @elseif($status === 'L')
                                            <span class="badge bg-warning-subtle text-warning rounded-3 w-100 py-2">L</span>
                                        @elseif($status === 'A')
                                            <span class="badge bg-danger-subtle text-danger rounded-3 w-100 py-2">A</span>
                                        @else
                                            <span class="badge bg-light text-muted rounded-3 w-100 py-2">-</span>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex gap-3 fs-7 text-muted mt-3">
                <span><span class="badge bg-success-subtle text-success me-1">P</span> Present</span>
                <span><span class="badge bg-warning-subtle text-warning me-1">L</span> Late</span>
                <span><span class="badge bg-danger-subtle text-danger me-1">A</span> Absent</span>
            </div>
        </div>
    </div>

    <!-- Monthly Streak -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white text-center h-100 d-flex flex-column justify-content-between">
            <div class="d-flex align-items-center gap-2 mb-2">
                <div class="bg-primary bg-opacity-10 text-primary p-2 rounded-3">
                    <i class="bi bi-hand-thumbs-up"></i>
                </div>
                <h6 class="fw-bold mb-0">Monthly Streak</h6>
            </div>

            <div class="my-3 position-relative d-inline-block mx-auto" style="width: 130px; height: 130px;">
                <canvas id="streakChart"></canvas>
                <div class="position-absolute top-50 start-50 translate-middle">
                    <h4 class="fw-bold mb-0">{{ $attendancePercentage ?? 0 }}%</h4>
                    <small class="text-muted fs-8">Attendance</small>
                </div>
            </div>

            <div>
                <div class="bg-primary bg-opacity-10 text-primary rounded-pill py-2 px-3 d-inline-flex align-items-center gap-2 mb-2 fs-7 fw-semibold">
                    <i class="bi bi-hand-thumbs-up-fill"></i> {{ $streakDays ?? 0 }}-day streak!
                </div>
                <p class="text-muted fs-7 mb-0">
                    @if(($attendancePercentage ?? 0) >= 85)
                        Consistent attendance record this month
                    @elseif(($attendancePercentage ?? 0) >= 75)
                        Steady progress — keep tracking your attendance
                    @else
                        Check your attendance history and upcoming sessions
                    @endif
                </p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
    const chartData = @js($monthlyChartData ?? [0, 0, 0, 0]);
    const rawPercentage = Number(@js($attendancePercentage ?? 0));
    const percentage = Math.min(Math.max(rawPercentage, 0), 100);

    // 1. Line Chart Monthly Attendance
    const ctx = document.getElementById('attendanceChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['W1', 'W2', 'W3', 'W4'],
            datasets: [{
                label: 'Attendance Rate',
                data: chartData,
                borderColor: '#2563eb',
                backgroundColor: 'rgba(37, 99, 235, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointRadius: 5,
                pointBackgroundColor: '#2563eb'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { min: 0, max: 100, ticks: { stepSize: 20 } },
                x: { grid: { display: false } }
            }
        }
    });

    // 2. Doughnut Chart Monthly Streak
    const streakCtx = document.getElementById('streakChart').getContext('2d');
    new Chart(streakCtx, {
        type: 'doughnut',
        data: {
            datasets: [{
                data: [percentage, 100 - percentage],
                backgroundColor: ['#2563eb', '#e2e8f0'],
                borderWidth: 0
            }]
        },
        options: {
            cutout: '80%',
            responsive: true,
            maintainAspectRatio: false,
            plugins: { tooltip: { enabled: false } }
        }
    });
</script>
@endpush