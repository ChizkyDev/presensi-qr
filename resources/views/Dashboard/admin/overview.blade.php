@extends('layouts.admin')

@section('title', 'Overview - Attendly Admin')

@section('content')
<!-- Container Overview -->
<div class="overview-container p-0">

    <!-- ── SECTION TITLE & DATE ── -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold text-dark mb-0">System Overview</h4>
            <small class="text-muted">Manage all things here</small>
        </div>
        <div>
            <button class="btn btn-white bg-white shadow-sm rounded-pill px-3 py-2 border-0 fw-semibold text-primary" style="font-size: 0.82rem;">
                <i class="bi bi-calendar3 me-2"></i> {{ \Carbon\Carbon::now()->isoFormat('D MMMM Y') }} <i class="bi bi-chevron-down ms-1"></i>
            </button>
        </div>
    </div>

    <!-- ── 4 STAT CARDS ── -->
    <div class="row g-3 mb-4">
        <!-- Total Students -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 rounded-4 p-3 shadow-sm bg-white position-relative overflow-hidden" style="padding-bottom: 4rem !important;">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="icon-box rounded-3 p-2 text-primary" style="background: #eff6ff;">
                        <i class="bi bi-mortarboard fs-5"></i>
                    </div>
                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill fw-medium" style="font-size: 0.7rem;">+2.4%</span>
                </div>
                <div class="mt-3">
                    <h3 class="fw-extrabold mb-0 text-dark">{{ $totalStudents ?? 0 }}</h3>
                    <small class="text-muted" style="font-size: 0.78rem;">Total Students</small>
                </div>
                <!-- Grafik Mini SVG (Warna samar memanjang penuh ke bawah card) -->
                <div class="position-absolute bottom-0 start-0 w-100" style="height: 55px; pointer-events: none;">
                    <svg viewBox="0 0 300 100" class="w-100 h-100" preserveAspectRatio="none">
                        <path d="M0,60 Q90,80 180,45 T285,25 L285,100 L0,100 Z" fill="rgba(37, 99, 235, 0.05)"/>
                        <path d="M0,60 Q90,80 180,45 T285,25" fill="none" stroke="#2563eb" stroke-width="3.5" stroke-linecap="round"/>
                        <circle cx="285" cy="25" r="5" fill="#2563eb" stroke="#ffffff" stroke-width="2.5"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Teachers -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 rounded-4 p-3 shadow-sm bg-white position-relative overflow-hidden" style="padding-bottom: 4rem !important;">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="icon-box rounded-3 p-2 text-success" style="background: #f0fdf4;">
                        <i class="bi bi-person-workspace fs-5"></i>
                    </div>
                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill fw-medium" style="font-size: 0.7rem;">stable</span>
                </div>
                <div class="mt-3">
                    <h3 class="fw-extrabold mb-0 text-dark">{{ $totalTeachers ?? 0 }}</h3>
                    <small class="text-muted" style="font-size: 0.78rem;">Total Teachers</small>
                </div>
                <div class="position-absolute bottom-0 start-0 w-100" style="height: 55px; pointer-events: none;">
                    <svg viewBox="0 0 300 100" class="w-100 h-100" preserveAspectRatio="none">
                        <path d="M0,55 Q90,75 180,50 T285,30 L285,100 L0,100 Z" fill="rgba(22, 163, 74, 0.05)"/>
                        <path d="M0,55 Q90,75 180,50 T285,30" fill="none" stroke="#16a34a" stroke-width="3.5" stroke-linecap="round"/>
                        <circle cx="285" cy="30" r="5" fill="#16a34a" stroke="#ffffff" stroke-width="2.5"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Active Classes -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 rounded-4 p-3 shadow-sm bg-white position-relative overflow-hidden" style="padding-bottom: 4rem !important;">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="icon-box rounded-3 p-2" style="background: #f3e8ff; color: #9333ea;">
                        <i class="bi bi-book fs-5"></i>
                    </div>
                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill fw-medium" style="font-size: 0.7rem;">+3</span>
                </div>
                <div class="mt-3">
                    <h3 class="fw-extrabold mb-0 text-dark">{{ $activeClasses ?? 0 }}</h3>
                    <small class="text-muted" style="font-size: 0.78rem;">Active Classes</small>
                </div>
                <div class="position-absolute bottom-0 start-0 w-100" style="height: 55px; pointer-events: none;">
                    <svg viewBox="0 0 300 100" class="w-100 h-100" preserveAspectRatio="none">
                        <path d="M0,55 Q90,40 180,55 T285,22 L285,100 L0,100 Z" fill="rgba(147, 51, 234, 0.05)"/>
                        <path d="M0,55 Q90,40 180,55 T285,22" fill="none" stroke="#9333ea" stroke-width="3.5" stroke-linecap="round"/>
                        <circle cx="285" cy="22" r="5" fill="#9333ea" stroke="#ffffff" stroke-width="2.5"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Today's Attendance -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 rounded-4 p-3 shadow-sm bg-white position-relative overflow-hidden" style="padding-bottom: 4rem !important;">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="icon-box rounded-3 p-2 text-warning" style="background: #fffbeb;">
                        <i class="bi bi-activity fs-5"></i>
                    </div>
                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill fw-medium" style="font-size: 0.7rem;">+2.4%</span>
                </div>
                <div class="mt-3">
                    <h3 class="fw-extrabold mb-0 text-dark">{{ $attendancePercentage ?? 0 }}%</h3>
                    <small class="text-muted" style="font-size: 0.78rem;">Today's Attendance</small>
                </div>
                <div class="position-absolute bottom-0 start-0 w-100" style="height: 55px; pointer-events: none;">
                    <svg viewBox="0 0 300 100" class="w-100 h-100" preserveAspectRatio="none">
                        <path d="M0,60 Q90,40 180,50 T285,20 L285,100 L0,100 Z" fill="rgba(217, 119, 6, 0.05)"/>
                        <path d="M0,60 Q90,40 180,50 T285,20" fill="none" stroke="#d97706" stroke-width="3.5" stroke-linecap="round"/>
                        <circle cx="285" cy="20" r="5" fill="#d97706" stroke="#ffffff" stroke-width="2.5"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- ── BAR CHART + RECENT ACTIVITY ── -->
    <div class="row g-3 mb-4">
        <!-- Recently Attendance Bar Chart -->
        <div class="col-12 col-lg-8">
            <div class="card border-0 rounded-4 p-4 shadow-sm bg-white h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="d-flex align-items-center gap-2">
                        <div class="bg-primary-subtle p-2 rounded-3 text-primary">
                            <i class="bi bi-calendar-event"></i>
                        </div>
                        <h6 class="fw-bold mb-0 text-dark">Recently Attendance</h6>
                    </div>
                    <button class="btn btn-light bg-light btn-sm rounded-pill text-primary fw-medium" style="font-size: 0.75rem;">
                        {{ \Carbon\Carbon::now()->translatedFormat('M Y') }} <i class="bi bi-chevron-down ms-1"></i>
                    </button>
                </div>

                <div style="min-height: 220px;">
                    <canvas id="recentlyAttendanceChart"></canvas>
                </div>

                <div class="d-flex justify-content-center gap-4 mt-3" style="font-size: 0.75rem;">
                    <span class="d-flex align-items-center gap-1"><span class="badge rounded-circle p-1" style="background: #e2e8f0;"></span> Absent</span>
                    <span class="d-flex align-items-center gap-1"><span class="badge rounded-circle p-1" style="background: #93c5fd;"></span> Late</span>
                    <span class="d-flex align-items-center gap-1"><span class="badge rounded-circle p-1" style="background: #2563eb;"></span> Present</span>
                </div>
            </div>
        </div>

        <!-- Recent Activity Feed -->
        <div class="col-12 col-lg-4">
            <div class="card border-0 rounded-4 p-4 shadow-sm bg-white h-100 d-flex flex-column justify-content-between">
                <div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="d-flex align-items-center gap-2">
                            <div class="bg-primary-subtle p-2 rounded-3 text-primary">
                                <i class="bi bi-clock-history"></i>
                            </div>
                            <h6 class="fw-bold mb-0 text-dark">Recent Activity</h6>
                        </div>
                        <span class="text-danger fw-bold" style="font-size: 0.75rem;">&bull; LIVE</span>
                    </div>

                    <div class="d-flex flex-column gap-3">
                        @forelse($recentActivities ?? [] as $activity)
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="p-2 rounded-circle {{ $activity['badge_class'] ?? 'bg-primary-subtle text-primary' }}">
                                        <i class="bi {{ $activity['icon'] ?? 'bi-info-circle' }}"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark lh-1" style="font-size: 0.82rem;">{{ $activity['title'] }}</div>
                                        <small class="text-muted" style="font-size: 0.72rem;">{{ $activity['description'] }}</small>
                                    </div>
                                </div>
                                <small class="text-muted" style="font-size: 0.72rem;">{{ $activity['time'] }}</small>
                            </div>
                        @empty
                            <div class="text-muted text-center py-3" style="font-size: 0.78rem;">No recent activity</div>
                        @endforelse
                    </div>
                </div>

                <div class="mt-3 pt-2">
                    <div class="p-2 rounded-3 bg-primary-subtle d-flex align-items-center justify-content-between" style="font-size: 0.78rem;">
                        <span class="text-primary fw-semibold"><i class="bi bi-activity me-1"></i> Active Session</span>
                        <span class="badge bg-primary text-white">{{ $activeSessionsCount ?? 0 }} running</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ── HEATMAP + SIDE WIDGETS ── -->
    <div class="row g-3 mb-4">
        <!-- Heatmap -->
        <div class="col-12 col-lg-8">
            <div class="card border-0 rounded-4 p-4 shadow-sm bg-white h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="d-flex align-items-center gap-2">
                        <div class="bg-primary-subtle p-2 rounded-3 text-primary">
                            <i class="bi bi-grid-3x3-gap"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-0 text-dark">QR Scan Activity Heatmap</h6>
                            <small class="text-muted" style="font-size: 0.72rem;">Scans per hour - this week</small>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-1" style="font-size: 0.7rem;">
                        <span class="text-muted me-1">Low</span>
                        <span class="d-inline-block rounded-1" style="width: 12px; height: 12px; background: #eff6ff;"></span>
                        <span class="d-inline-block rounded-1" style="width: 12px; height: 12px; background: #bfdbfe;"></span>
                        <span class="d-inline-block rounded-1" style="width: 12px; height: 12px; background: #60a5fa;"></span>
                        <span class="d-inline-block rounded-1" style="width: 12px; height: 12px; background: #2563eb;"></span>
                        <span class="text-muted ms-1">High</span>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-borderless text-center align-middle mb-0" style="font-size: 0.75rem;">
                        <thead>
                            <tr class="text-muted">
                                <th></th>
                                <th>07:00</th>
                                <th>08:00</th>
                                <th>09:00</th>
                                <th>10:00</th>
                                <th>11:00</th>
                                <th>12:00</th>
                                <th>13:00</th>
                                <th>14:00</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $fallbackHeatmapRows = collect(['Sen', 'Sel', 'Rab', 'Kam', 'Jum'])->map(function ($day) {
                                    return [
                                        'day' => $day,
                                        'cells' => array_fill(0, 8, ['count' => 0, 'color' => '#eff6ff']),
                                    ];
                                })->all();
                            @endphp

                            @foreach(($heatmapRows ?? $fallbackHeatmapRows) as $row)
                            <tr>
                                <td class="fw-semibold text-muted text-start py-2" style="width: 45px;" translate="no">{{ $row['day'] }}</td>
                                @foreach($row['cells'] as $cell)
                                <td>
                                    <div
                                        class="rounded-2"
                                        title="{{ $row['day'] }} — {{ $cell['count'] }} scan"
                                        style="height: 22px; background: {{ $cell['color'] }};"
                                    ></div>
                                </td>
                                @endforeach
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Right Side Widgets -->
        <div class="col-12 col-lg-4">
            <div class="d-flex flex-column gap-3 h-100">
                <div class="card border-0 rounded-4 p-3 shadow-sm bg-white flex-fill">
                    <div class="d-flex align-items-center gap-3">
                        <div class="p-3 rounded-4 bg-primary-subtle text-primary">
                            <i class="bi bi-qr-code-scan fs-4"></i>
                        </div>
                        <div>
                            <h4 class="fw-extrabold mb-0 text-dark">{{ $qrSessionsTodayCount ?? 0 }}</h4>
                            <div class="fw-bold text-dark" style="font-size: 0.82rem;">QR Session Today</div>
                            <small class="text-muted" style="font-size: 0.72rem;">{{ $activeSessionsCount ?? 0 }} currently active</small>
                        </div>
                    </div>
                </div>

                <div class="card border-0 rounded-4 p-3 shadow-sm bg-white flex-fill">
                    <div class="d-flex align-items-center gap-3">
                        <div class="p-3 rounded-4 bg-warning-subtle text-warning">
                            <i class="bi bi-file-earmark-text fs-4"></i>
                        </div>
                        <div>
                            <h4 class="fw-extrabold mb-0 text-dark">{{ $pendingLeavesCount ?? 0 }}</h4>
                            <div class="fw-bold text-dark" style="font-size: 0.82rem;">Pending Leaves</div>
                            <small class="text-muted" style="font-size: 0.72rem;">Awaiting review</small>
                        </div>
                    </div>
                </div>

                <div class="card border-0 rounded-4 p-3 shadow-sm bg-white flex-fill">
                    <div class="d-flex align-items-center gap-3">
                        <div class="p-3 rounded-4 bg-danger-subtle text-danger">
                            <i class="bi bi-exclamation-triangle fs-4"></i>
                        </div>
                        <div>
                            <h4 class="fw-extrabold mb-0 text-dark">{{ $criticalStudentsCount ?? 0 }}</h4>
                            <div class="fw-bold text-dark" style="font-size: 0.82rem;">Critical Students</div>
                            <small class="text-muted" style="font-size: 0.72rem;">Below 75% threshold</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ── BOTTOM QUICK ACTIONS ── -->
    <div class="row g-3">
        <div class="col-12 col-md-4">
            <a href="#" class="text-decoration-none">
                <div class="card border-0 rounded-4 p-3 shadow-sm bg-white action-card" style="cursor: pointer;">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-3">
                            <div class="p-2 rounded-3 bg-primary-subtle text-primary">
                                <i class="bi bi-person-plus fs-5"></i>
                            </div>
                            <div>
                                <div class="fw-bold text-dark" style="font-size: 0.85rem;">Add Student</div>
                                <small class="text-muted" style="font-size: 0.72rem;">Register new student</small>
                            </div>
                        </div>
                        <i class="bi bi-chevron-right text-muted"></i>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-12 col-md-4">
            <a href="#" class="text-decoration-none">
                <div class="card border-0 rounded-4 p-3 shadow-sm bg-white action-card" style="cursor: pointer;">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-3">
                            <div class="p-2 rounded-3" style="background: #f3e8ff; color: #9333ea;">
                                <i class="bi bi-qr-code fs-5"></i>
                            </div>
                            <div>
                                <div class="fw-bold text-dark" style="font-size: 0.85rem;">Generate QR</div>
                                <small class="text-muted" style="font-size: 0.72rem;">Create new session code</small>
                            </div>
                        </div>
                        <i class="bi bi-chevron-right text-muted"></i>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-12 col-md-4">
            <a href="#" class="text-decoration-none">
                <div class="card border-0 rounded-4 p-3 shadow-sm bg-white action-card" style="cursor: pointer;">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-3">
                            <div class="p-2 rounded-3 bg-success-subtle text-success">
                                <i class="bi bi-download fs-5"></i>
                            </div>
                            <div>
                                <div class="fw-bold text-dark" style="font-size: 0.85rem;">Export Report</div>
                                <small class="text-muted" style="font-size: 0.72rem;">Download Excel/PDF</small>
                            </div>
                        </div>
                        <i class="bi bi-chevron-right text-muted"></i>
                    </div>
                </div>
            </a>
        </div>
    </div>

</div>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctxRecently = document.getElementById('recentlyAttendanceChart').getContext('2d');

    @php
        $jsLabels  = $chartLabels ?? [];
        $jsAbsent  = $chartAbsent ?? [];
        $jsLate    = $chartLate ?? [];
        $jsPresent = $chartPresent ?? [];
    @endphp

    const chartLabels = {!! json_encode($jsLabels) !!};
    const chartAbsent = {!! json_encode($jsAbsent) !!};
    const chartLate = {!! json_encode($jsLate) !!};
    const chartPresent = {!! json_encode($jsPresent) !!};

    new Chart(ctxRecently, {
        type: 'bar',
        data: {
            labels: chartLabels,
            datasets: [
                {
                    label: 'Absent',
                    data: chartAbsent,
                    backgroundColor: '#e2e8f0',
                    borderRadius: 6,
                    barThickness: 8,
                },
                {
                    label: 'Late',
                    data: chartLate,
                    backgroundColor: '#93c5fd',
                    borderRadius: 6,
                    barThickness: 8,
                },
                {
                    label: 'Present',
                    data: chartPresent,
                    backgroundColor: '#2563eb',
                    borderRadius: 6,
                    barThickness: 8,
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100,
                    ticks: {
                        stepSize: 25,
                        callback: function(value) { return value + '%'; }
                    },
                    grid: { color: 'rgba(0,0,0,0.03)' }
                },
                x: {
                    grid: { display: false }
                }
            }
        }
    });
</script>
@endpush