@extends('layouts.admin')

@section('title', 'Reports & Analytics - Attendly Admin')

@section('content')
<div class="reports-container p-0" style="max-width: 1400px; margin: 0 auto;">

    <!-- ── HEADER TITLE & DATE ── -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-4 px-1">
        <div>
            <h4 class="fw-bold text-dark mb-1" style="font-size: 1.5rem;">Reports & Analytics</h4>
            <p class="text-muted mb-0" style="font-size: 0.85rem;">Analytics & Export</p>
        </div>
        <div>
            <button class="btn btn-white bg-white rounded-pill px-3 py-2 shadow-sm border-0 text-primary fw-medium d-flex align-items-center gap-2" style="font-size: 0.85rem;">
                <i class="bi bi-calendar3"></i> {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}
            </button>
        </div>
    </div>

    <!-- ── 4 STATS CARDS ── -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 rounded-4 p-4 shadow-sm bg-white h-100">
                <span class="text-muted fw-medium" style="font-size: 0.82rem;">School Avg Rate</span>
                <h2 class="fw-bold text-dark my-2" style="font-size: 2rem;">{{ $schoolAvgRate !== null ? $schoolAvgRate . '%' : '-' }}</h2>
                @if($rateDelta !== null)
                    <div class="d-flex align-items-center gap-1 {{ $rateDelta >= 0 ? 'text-success' : 'text-danger' }}" style="font-size: 0.76rem;">
                        <i class="bi {{ $rateDelta >= 0 ? 'bi-arrow-up-right' : 'bi-arrow-down-right' }}"></i>
                        <span class="fw-semibold">{{ $rateDelta >= 0 ? '+' : '' }}{{ $rateDelta }}%</span> vs last month
                    </div>
                @else
                    <div class="text-muted" style="font-size: 0.76rem;">No comparison yet</div>
                @endif
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 rounded-4 p-4 shadow-sm bg-white h-100">
                <span class="text-muted fw-medium" style="font-size: 0.82rem;">At-Risk Students</span>
                <h2 class="fw-bold text-dark my-2" style="font-size: 2rem;">{{ $atRiskStudents }}</h2>
                <div class="d-flex align-items-center gap-1 text-danger" style="font-size: 0.76rem;">
                    <i class="bi bi-exclamation-circle"></i> below 75% attendance
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 rounded-4 p-4 shadow-sm bg-white h-100">
                <span class="text-muted fw-medium" style="font-size: 0.82rem;">Total Sessions</span>
                <h2 class="fw-bold text-dark my-2" style="font-size: 2rem;">{{ number_format($totalSessions) }}</h2>
                <div class="d-flex align-items-center gap-1 text-success" style="font-size: 0.76rem;">
                    <i class="bi bi-arrow-up-right"></i> <span class="fw-semibold">+{{ $sessionsThisMonth }}</span> this month
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 rounded-4 p-4 shadow-sm bg-white h-100">
                <span class="text-muted fw-medium" style="font-size: 0.82rem;">Leave Approved</span>
                <h2 class="fw-bold text-dark my-2" style="font-size: 2rem;">{{ $leaveApproved }}</h2>
                <div class="d-flex align-items-center gap-1 text-success" style="font-size: 0.76rem;">
                    <i class="bi bi-arrow-up-right"></i> <span class="fw-semibold">+{{ $leaveThisMonth }}</span> this month
                </div>
            </div>
        </div>
    </div>

    <!-- ── NAVIGATION TABS ── -->
    @php $currentTab = $tab ?? 'overview'; @endphp
    <div class="d-flex align-items-center gap-3 mb-4 px-1">
        <a href="{{ route('admin.reports.index', ['tab' => 'overview']) }}" 
           class="btn rounded-pill px-4 py-2.5 text-decoration-none shadow-sm {{ $currentTab == 'overview' ? 'fw-bold shadow' : 'fw-semibold' }}" 
           style="font-size: 0.9rem; background-color: {{ $currentTab == 'overview' ? '#dbeafe' : '#EBF3FE' }}; color: #1e293b; border: 1px solid #bfdbfe; {{ $currentTab == 'overview' ? 'border-bottom: 3px solid #2563eb;' : '' }}">
            Overview
        </a>
        <a href="{{ route('admin.reports.index', ['tab' => 'class']) }}" 
           class="btn rounded-pill px-4 py-2.5 text-decoration-none shadow-sm {{ $currentTab == 'class' ? 'fw-bold shadow' : 'fw-semibold' }}" 
           style="font-size: 0.9rem; background-color: {{ $currentTab == 'class' ? '#dbeafe' : '#EBF3FE' }}; color: #1e293b; border: 1px solid #bfdbfe; {{ $currentTab == 'class' ? 'border-bottom: 3px solid #2563eb;' : '' }}">
            By Class
        </a>
        <a href="{{ route('admin.reports.index', ['tab' => 'subject']) }}" 
           class="btn rounded-pill px-4 py-2.5 text-decoration-none shadow-sm {{ $currentTab == 'subject' ? 'fw-bold shadow' : 'fw-semibold' }}" 
           style="font-size: 0.9rem; background-color: {{ $currentTab == 'subject' ? '#dbeafe' : '#EBF3FE' }}; color: #1e293b; border: 1px solid #bfdbfe; {{ $currentTab == 'subject' ? 'border-bottom: 3px solid #2563eb;' : '' }}">
            By Subject
        </a>
        <a href="{{ route('admin.reports.index', ['tab' => 'export']) }}" 
           class="btn rounded-pill px-4 py-2.5 text-decoration-none shadow-sm {{ $currentTab == 'export' ? 'fw-bold shadow' : 'fw-semibold' }}" 
           style="font-size: 0.9rem; background-color: {{ $currentTab == 'export' ? '#dbeafe' : '#EBF3FE' }}; color: #1e293b; border: 1px solid #bfdbfe; {{ $currentTab == 'export' ? 'border-bottom: 3px solid #2563eb;' : '' }}">
            Export
        </a>
    </div>

    <!-- ── KONDISI KONTEN BERDASARKAN TAB ── -->
    @if($currentTab == 'class')

        <!-- ── TAB: BY CLASS ── -->
        <div class="card border-0 rounded-4 p-4 shadow-sm bg-white mb-4" style="padding: 2rem !important;">
            <div class="d-flex align-items-center gap-2 mb-4">
                <div class="bg-primary-subtle text-primary p-2 rounded-3 d-flex align-items-center justify-content-center" style="width: 35px; height: 35px; background-color: #eff6ff;">
                    <i class="bi bi-journal-bookmark fs-6"></i>
                </div>
                <h5 class="fw-bold text-dark mb-0" style="font-size: 1.1rem;">Attendance Rate per Class</h5>
            </div>

            <div class="d-flex flex-column gap-3">
                @forelse($classReports as $cls)
                <div class="d-flex align-items-center justify-content-between gap-3" style="font-size: 0.85rem;">
                    <div class="fw-bold text-dark" style="width: 90px;">{{ $cls['nama_kelas'] }}</div>
                    <div class="flex-grow-1 bg-light rounded-pill overflow-hidden d-flex" style="height: 10px;">
                        <div class="bg-primary h-100 rounded-start-pill" style="width: {{ $cls['present_pct'] }}%;"></div>
                        <div class="h-100" style="width: {{ $cls['late_pct'] }}%; background-color: #fb923c;"></div>
                        <div class="h-100 rounded-end-pill" style="width: {{ $cls['absent_pct'] }}%; background-color: #f87171;"></div>
                    </div>
                    <div class="fw-bold text-success" style="width: 45px; text-align: right; color: #16a34a !important;">{{ $cls['rate'] }}%</div>
                    <div class="badge bg-light text-danger border rounded-pill px-2 py-1 d-flex align-items-center gap-1" style="width: 50px; justify-content: center; font-size: 0.75rem;">
                        <i class="bi bi-exclamation-circle"></i> {{ $cls['at_risk'] }}
                    </div>
                </div>
                @empty
                <p class="text-muted mb-0" style="font-size: 0.85rem;">Belum ada sesi presensi bulan ini.</p>
                @endforelse
            </div>
        </div>

        <div class="card border-0 rounded-4 p-4 shadow-sm bg-white mb-5" style="padding: 2rem !important;">
            <div class="d-flex align-items-center gap-2 mb-4">
                <div class="p-2 rounded-3 d-flex align-items-center justify-content-center" style="width: 35px; height: 35px; background-color: #eff6ff;">
                    <i class="bi bi-list-columns-reverse fs-6 text-primary"></i>
                </div>
                <h5 class="fw-bold text-dark mb-0" style="font-size: 1.1rem;">Detailed Class Metrics</h5>
            </div>

            <div class="table-responsive">
                <table class="table align-middle mb-0" style="font-size: 0.85rem;">
                    <thead>
                        <tr class="text-muted border-bottom" style="font-size: 0.78rem;">
                            <th class="pb-3 fw-semibold">Class</th>
                            <th class="pb-3 fw-semibold text-center">Total</th>
                            <th class="pb-3 fw-semibold text-center">Present</th>
                            <th class="pb-3 fw-semibold text-center">Late</th>
                            <th class="pb-3 fw-semibold text-center">Absent</th>
                            <th class="pb-3 fw-semibold">Rate</th>
                            <th class="pb-3 fw-semibold">At Risk</th>
                            <th class="pb-3 fw-semibold text-end">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($classReports as $cls)
                        <tr>
                            <td class="fw-bold text-dark py-3">{{ $cls['nama_kelas'] }}</td>
                            <td class="text-center"><span class="badge bg-primary-subtle text-primary px-2 py-1 rounded-2 fw-bold" style="background-color: #eff6ff !important;">{{ $cls['total'] }}</span></td>
                            <td class="text-center"><span class="badge px-2 py-1 rounded-2 fw-bold" style="background-color: #dcfce7 !important; color: #16a34a !important;">{{ $cls['present'] }}</span></td>
                            <td class="text-center"><span class="badge px-2 py-1 rounded-2 fw-bold" style="background-color: #ffedd5 !important; color: #c2410c !important;">{{ $cls['late'] }}</span></td>
                            <td class="text-center"><span class="badge px-2 py-1 rounded-2 fw-bold" style="background-color: #fee2e2 !important; color: #dc2626 !important;">{{ $cls['absent'] }}</span></td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="flex-grow-1 bg-light rounded-pill overflow-hidden" style="height: 6px; width: 60px;">
                                        <div class="h-100 rounded-pill {{ $cls['rate'] >= 90 ? 'bg-success' : ($cls['rate'] >= 80 ? 'bg-warning' : 'bg-danger') }}" style="width: {{ min(100, $cls['rate']) }}%;"></div>
                                    </div>
                                    <span class="fw-bold text-success" style="font-size: 0.78rem; color: #16a34a !important;">{{ $cls['rate'] }}%</span>
                                </div>
                            </td>
                            <td class="text-muted" style="font-size: 0.8rem;">{{ $cls['at_risk'] }} students</td>
                            <td class="text-end">
                                <span class="badge rounded-pill px-3 py-1.5 fw-medium" style="font-size: 0.75rem; background-color: {{ $cls['status'] == 'Good' ? '#dcfce7' : ($cls['status'] == 'Fair' ? '#fef3c7' : '#fee2e2') }}; color: {{ $cls['status'] == 'Good' ? '#16a34a' : ($cls['status'] == 'Fair' ? '#d97706' : '#dc2626') }};">
                                    • {{ $cls['status'] }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">Belum ada sesi presensi bulan ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    @elseif($currentTab == 'subject')

        <!-- ── TAB: BY SUBJECT ── -->
        <div class="card border-0 rounded-4 p-4 shadow-sm bg-white mb-4" style="padding: 2.2rem !important;">
            <div class="d-flex align-items-center gap-2 mb-4">
                <div class="p-2 rounded-3 d-flex align-items-center justify-content-center text-primary" style="width: 35px; height: 35px; background-color: #eff6ff;">
                    <i class="bi bi-journal-bookmark fs-6"></i>
                </div>
                <h5 class="fw-bold text-dark mb-0" style="font-size: 1.1rem;">Attendance Rate by Subject</h5>
            </div>

            <div class="d-flex flex-column gap-4">
                @forelse($subjectReports as $subj)
                <div>
                    <div class="d-flex justify-content-between align-items-center mb-1" style="font-size: 0.85rem;">
                        <span class="fw-bold text-dark">{{ $subj['name'] }}</span>
                        <span class="text-muted" style="font-size: 0.78rem;">{{ $subj['sessions'] }} sessions <strong style="color: {{ $subj['color'] }} !important;">{{ $subj['rate'] }}%</strong></span>
                    </div>
                    <div class="bg-light rounded-pill overflow-hidden" style="height: 8px;">
                        <div class="h-100 rounded-pill" style="width: {{ min(100, $subj['rate']) }}%; background-color: {{ $subj['color'] }} !important;"></div>
                    </div>
                </div>
                @empty
                <p class="text-muted mb-0" style="font-size: 0.85rem;">Belum ada sesi presensi bulan ini.</p>
                @endforelse
            </div>
        </div>

        <div class="card border-0 rounded-4 p-4 shadow-sm bg-white mb-5" style="padding: 2.2rem !important;">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div class="d-flex align-items-center gap-2">
                    <div class="p-2 rounded-3 d-flex align-items-center justify-content-center text-primary" style="width: 35px; height: 35px; background-color: #eff6ff;">
                        <i class="bi bi-calendar3 fs-6"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-0" style="font-size: 1.1rem;">Subjects vs Month</h5>
                </div>
                <span class="text-muted" style="font-size: 0.78rem;">5 mata pelajaran dengan sesi terbanyak, 6 bulan terakhir</span>
            </div>

            <div style="height: 280px;">
                <canvas id="subjectMonthChart"></canvas>
            </div>
        </div>

        @push('js')
        @php
            $subjectMonthLabels = $months->map(fn ($m) => $m->format('M'))->all();
            $subjectDatasets = collect($subjectMonthSeries)->map(fn ($s) => [
                'label' => $s['name'],
                'data' => $s['data'],
                'backgroundColor' => $s['color'],
                'borderRadius' => 6,
                'barThickness' => 10,
            ])->all();
        @endphp
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            new Chart(document.getElementById('subjectMonthChart'), {
                type: 'bar',
                data: {
                    labels: @json($subjectMonthLabels),
                    datasets: @json($subjectDatasets)
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { position: 'bottom', labels: { usePointStyle: true, boxWidth: 8 } } },
                    scales: {
                        y: { beginAtZero: true, max: 100, ticks: { callback: v => v + '%' }, grid: { color: 'rgba(0,0,0,0.04)' } },
                        x: { grid: { display: false } }
                    }
                }
            });
        </script>
        @endpush

    @elseif($currentTab == 'export')

        <!-- ── TAB: EXPORT ── -->
        <div class="row g-4 mb-4">
            
            <div class="col-xl-6">
                <div class="card border-0 rounded-4 p-4 shadow-sm bg-white h-100 d-flex flex-row align-items-center justify-content-between" style="padding: 1.8rem !important;">
                    <div class="d-flex align-items-start gap-3">
                        <div class="p-3 rounded-4 text-primary d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; background-color: #eff6ff;">
                            <i class="bi bi-file-earmark-text fs-5"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold text-dark mb-1" style="font-size: 1rem;">Monthly Attendance Summary</h6>
                            <p class="text-muted mb-2" style="font-size: 0.8rem;">Full breakdown per student and class</p>
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-light text-muted border px-2 py-1 rounded-2" style="font-size: 0.7rem;">PDF</span>
                                <span class="badge bg-light text-muted border px-2 py-1 rounded-2" style="font-size: 0.7rem;">XLSX</span>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('admin.reports.export.download', ['type' => 'monthly']) }}" class="btn btn-primary rounded-pill px-4 py-2 text-white fw-medium shadow-sm d-flex align-items-center gap-2" style="background-color: #2563eb; font-size: 0.82rem;">
                        <i class="bi bi-download"></i> Export
                    </a>
                </div>
            </div>

            <div class="col-xl-6">
                <div class="card border-0 rounded-4 p-4 shadow-sm bg-white h-100 d-flex flex-row align-items-center justify-content-between" style="padding: 1.8rem !important;">
                    <div class="d-flex align-items-start gap-3">
                        <div class="p-3 rounded-4 text-primary d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; background-color: #eff6ff;">
                            <i class="bi bi-graph-up-arrow fs-5"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold text-dark mb-1" style="font-size: 1rem;">Per-Class Breakdown Report</h6>
                            <p class="text-muted mb-2" style="font-size: 0.8rem;">Aggregated metrics per class section</p>
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-light text-muted border px-2 py-1 rounded-2" style="font-size: 0.7rem;">XLSX</span>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('admin.reports.export.download', ['type' => 'class']) }}" class="btn btn-primary rounded-pill px-4 py-2 text-white fw-medium shadow-sm d-flex align-items-center gap-2" style="background-color: #2563eb; font-size: 0.82rem;">
                        <i class="bi bi-download"></i> Export
                    </a>
                </div>
            </div>

            <div class="col-xl-6">
                <div class="card border-0 rounded-4 p-4 shadow-sm bg-white h-100 d-flex flex-row align-items-center justify-content-between" style="padding: 1.8rem !important;">
                    <div class="d-flex align-items-start gap-3">
                        <div class="p-3 rounded-4 text-primary d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; background-color: #eff6ff;">
                            <i class="bi bi-exclamation-octagon fs-5"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold text-dark mb-1" style="font-size: 1rem;">Student Risk Report</h6>
                            <p class="text-muted mb-2" style="font-size: 0.8rem;">Students below attendance threshold</p>
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-light text-muted border px-2 py-1 rounded-2" style="font-size: 0.7rem;">PDF</span>
                                <span class="badge bg-light text-muted border px-2 py-1 rounded-2" style="font-size: 0.7rem;">XLSX</span>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('admin.reports.export.download', ['type' => 'risk']) }}" class="btn btn-primary rounded-pill px-4 py-2 text-white fw-medium shadow-sm d-flex align-items-center gap-2" style="background-color: #2563eb; font-size: 0.82rem;">
                        <i class="bi bi-download"></i> Export
                    </a>
                </div>
            </div>

            <div class="col-xl-6">
                <div class="card border-0 rounded-4 p-4 shadow-sm bg-white h-100 d-flex flex-row align-items-center justify-content-between" style="padding: 1.8rem !important;">
                    <div class="d-flex align-items-start gap-3">
                        <div class="p-3 rounded-4 text-primary d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; background-color: #eff6ff;">
                            <i class="bi bi-journal-check fs-5"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold text-dark mb-1" style="font-size: 1rem;">Leave Request Log</h6>
                            <p class="text-muted mb-2" style="font-size: 0.8rem;">All leave requests with approval status</p>
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-light text-muted border px-2 py-1 rounded-2" style="font-size: 0.7rem;">XLSX</span>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('admin.reports.export.download', ['type' => 'leave']) }}" class="btn btn-primary rounded-pill px-4 py-2 text-white fw-medium shadow-sm d-flex align-items-center gap-2" style="background-color: #2563eb; font-size: 0.82rem;">
                        <i class="bi bi-download"></i> Export
                    </a>
                </div>
            </div>

            <div class="col-xl-6">
                <div class="card border-0 rounded-4 p-4 shadow-sm bg-white h-100 d-flex flex-row align-items-center justify-content-between" style="padding: 1.8rem !important;">
                    <div class="d-flex align-items-start gap-3">
                        <div class="p-3 rounded-4 text-primary d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; background-color: #eff6ff;">
                            <i class="bi bi-book fs-5"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold text-dark mb-1" style="font-size: 1rem;">Subject Attendance Report</h6>
                            <p class="text-muted mb-2" style="font-size: 0.8rem;">Attendance by subject per teacher</p>
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-light text-muted border px-2 py-1 rounded-2" style="font-size: 0.7rem;">PDF</span>
                                <span class="badge bg-light text-muted border px-2 py-1 rounded-2" style="font-size: 0.7rem;">XLSX</span>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('admin.reports.export.download', ['type' => 'subject']) }}" class="btn btn-primary rounded-pill px-4 py-2 text-white fw-medium shadow-sm d-flex align-items-center gap-2" style="background-color: #2563eb; font-size: 0.82rem;">
                        <i class="bi bi-download"></i> Export
                    </a>
                </div>
            </div>

            <div class="col-xl-6">
                <div class="card border-0 rounded-4 p-4 shadow-sm bg-white h-100 d-flex flex-row align-items-center justify-content-between" style="padding: 1.8rem !important;">
                    <div class="d-flex align-items-start gap-3">
                        <div class="p-3 rounded-4 text-primary d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; background-color: #eff6ff;">
                            <i class="bi bi-download fs-5"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold text-dark mb-1" style="font-size: 1rem;">Monthly Attendance Summary</h6>
                            <p class="text-muted mb-2" style="font-size: 0.8rem;">Full breakdown per student and class</p>
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-light text-muted border px-2 py-1 rounded-2" style="font-size: 0.7rem;">XLSX</span>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('admin.reports.export.download', ['type' => 'monthly-summary']) }}" class="btn btn-primary rounded-pill px-4 py-2 text-white fw-medium shadow-sm d-flex align-items-center gap-2" style="background-color: #2563eb; font-size: 0.82rem;">
                        <i class="bi bi-download"></i> Export
                    </a>
                </div>
            </div>

        </div>

        <div class="card border-0 rounded-4 p-4 shadow-sm bg-white mb-5" style="padding: 2.2rem !important;">
            <div class="d-flex align-items-center gap-2 mb-4">
                <div class="p-2 rounded-3 d-flex align-items-center justify-content-center text-primary" style="width: 35px; height: 35px; background-color: #eff6ff;">
                    <i class="bi bi-graph-up-arrow fs-6"></i>
                </div>
                <h5 class="fw-bold text-dark mb-0" style="font-size: 1.1rem;">Custom Date Range Export</h5>
            </div>

            <form action="{{ route('admin.reports.export.download') }}" method="GET" class="row g-3 align-items-end">
                <input type="hidden" name="type" value="custom">
                
                <div class="col-md-3">
                    <label class="form-label text-muted fw-medium" style="font-size: 0.80rem;">From</label>
                    <input type="date" name="from" class="form-control rounded-pill px-3 py-2 border-light bg-light text-dark" value="{{ now()->startOfMonth()->toDateString() }}" style="font-size: 0.85rem;">
                </div>

                <div class="col-md-3">
                    <label class="form-label text-muted fw-medium" style="font-size: 0.80rem;">To</label>
                    <input type="date" name="to" class="form-control rounded-pill px-3 py-2 border-light bg-light text-dark" value="{{ now()->toDateString() }}" style="font-size: 0.85rem;">
                </div>

                <div class="col-md-3">
                    <label class="form-label text-muted fw-medium" style="font-size: 0.80rem;">Classes</label>
                    <select name="class_id" class="form-select rounded-pill px-3 py-2 border-light bg-light text-dark" style="font-size: 0.85rem;">
                        <option value="">All Classes</option>
                        @foreach($kelasList as $cls)
                            <option value="{{ $cls->id }}">{{ $cls->nama_lengkap }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary rounded-pill px-4 py-2 text-white fw-medium shadow-sm w-100 d-flex align-items-center justify-content-center gap-2" style="background-color: #2563eb; font-size: 0.85rem; height: 38px;">
                        <i class="bi bi-download"></i> Generate Export
                    </button>
                </div>
            </form>
        </div>

    @else

        <!-- ── TAB: OVERVIEW ── -->
        @php
            $sp = $statusSplit['present_pct'];
            $sl = $statusSplit['late_pct'];
            $sa = $statusSplit['absent_pct'];
            $hasSplit = $statusSplit['expected'] > 0;
            $gradient = $hasSplit
                ? "conic-gradient(#2563eb 0% {$sp}%, #fb923c {$sp}% " . ($sp + $sl) . "%, #ef4444 " . ($sp + $sl) . "% 100%)"
                : 'conic-gradient(#e2e8f0 0% 100%)';
        @endphp

        <div class="row g-4 mb-4">
            
            <!-- Kolom Kiri Atas: 6–Month Attendance Trend -->
            <div class="col-xl-8">
                <div class="card border-0 rounded-4 p-4 shadow-sm bg-white h-100" style="padding: 2.2rem !important;">
                    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2 mb-4">
                        <div class="d-flex align-items-center gap-2.5">
                            <div class="p-2 rounded-3 d-flex align-items-center justify-content-center text-primary" style="width: 38px; height: 38px; background-color: #eff6ff;">
                                <i class="bi bi-graph-up-arrow fs-6"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold text-dark mb-0" style="font-size: 1.1rem; letter-spacing: -0.3px;">6–Month Attendance Trend</h5>
                                <span class="text-muted" style="font-size: 0.78rem;">{{ $trendRange }}</span>
                            </div>
                        </div>
                        
                        <div class="d-flex align-items-center gap-3 text-muted ms-auto ms-sm-0" style="font-size: 0.82rem;">
                            <div class="d-flex align-items-center gap-1.5"><span class="rounded-circle d-inline-block" style="width: 9px; height: 9px; background-color: #2563eb;"></span> Present</div>
                            <div class="d-flex align-items-center gap-1.5"><span class="rounded-circle d-inline-block" style="width: 9px; height: 9px; background-color: #f59e0b;"></span> Late</div>
                            <div class="d-flex align-items-center gap-1.5"><span class="rounded-circle d-inline-block" style="width: 9px; height: 9px; background-color: #ef4444;"></span> Absent</div>
                        </div>
                    </div>

                    <div style="height: 270px;">
                        <canvas id="trendChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Kolom Kanan Atas: Status Split -->
            <div class="col-xl-4">
                <div class="card border-0 rounded-4 p-4 shadow-sm bg-white h-100" style="padding: 2rem !important;">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <div class="d-flex align-items-center gap-2">
                            <div class="p-2 rounded-3 d-flex align-items-center justify-content-center text-primary" style="width: 35px; height: 35px; background-color: #eff6ff;">
                                <i class="bi bi-pie-chart fs-6"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold text-dark mb-0" style="font-size: 1.05rem;">Status Split</h5>
                                <span class="text-muted" style="font-size: 0.72rem;">{{ now()->format('M Y') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="text-center position-relative py-3 d-flex justify-content-center align-items-center" style="height: 180px;">
                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle position-relative shadow-sm" style="width: 140px; height: 140px; background: {{ $gradient }};">
                            <div class="bg-white rounded-circle d-flex flex-column align-items-center justify-content-center" style="width: 104px; height: 104px;">
                                <h3 class="fw-bold text-dark mb-0" style="font-size: 1.5rem;">{{ $hasSplit ? $sp . '%' : '-' }}</h3>
                                <span class="text-muted" style="font-size: 0.7rem;">present</span>
                            </div>

                            @if($hasSplit)
                            <div class="position-absolute bg-white rounded-3 shadow-sm border px-2 py-1 d-flex align-items-center gap-1" style="top: 15px; left: -18px; font-size: 0.72rem; z-index: 10; border-color: #fed7aa !important;">
                                <span style="color: #d97706 !important; font-weight: 700;">▲ {{ $sl }}%</span>
                            </div>
                            <div class="position-absolute bg-white rounded-3 shadow-sm border px-2 py-1 d-flex align-items-center gap-1" style="top: -10px; right: 45px; font-size: 0.72rem; z-index: 10; border-color: #fca5a5 !important;">
                                <span style="color: #ef4444 !important; font-weight: 700;">{{ $sa }}% ▼</span>
                            </div>
                            <div class="position-absolute bg-white rounded-3 shadow-sm border px-2 py-1 d-flex align-items-center gap-1" style="bottom: 12px; right: -22px; font-size: 0.72rem; z-index: 10; border-color: #bfdbfe !important;">
                                <span style="color: #2563eb !important; font-weight: 700;">{{ $sp }}% ▼</span>
                            </div>
                            @endif
                        </div>
                    </div>

                    <div class="d-flex flex-column gap-2 mt-2" style="font-size: 0.8rem;">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="d-flex align-items-center gap-2 text-dark fw-medium"><span class="rounded-circle d-inline-block" style="width: 8px; height: 8px; background-color: #2563eb;"></span> Present</span>
                            <span class="fw-bold" style="color: #2563eb !important;">{{ $sp }}%</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="d-flex align-items-center gap-2 text-dark fw-medium"><span class="rounded-circle d-inline-block" style="width: 8px; height: 8px; background-color: #fb923c;"></span> Late</span>
                            <span class="fw-bold" style="color: #d97706 !important;">{{ $sl }}%</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="d-flex align-items-center gap-2 text-dark fw-medium"><span class="rounded-circle d-inline-block" style="width: 8px; height: 8px; background-color: #ef4444;"></span> Absent</span>
                            <span class="fw-bold" style="color: #ef4444 !important;">{{ $sa }}%</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kolom Kiri Bawah: This Week — Daily Breakdown -->
            <div class="col-xl-8 col-lg-7">
                <div class="card border-0 rounded-4 p-4 shadow-sm bg-white" style="padding: 2.2rem !important; height: 420px;">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="d-flex align-items-center gap-2.5">
                            <div class="p-2 rounded-3 d-flex align-items-center justify-content-center text-primary" style="width: 38px; height: 38px; background-color: #eff6ff;">
                                <i class="bi bi-bar-chart-fill fs-6"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold text-dark mb-0" style="font-size: 1.1rem; letter-spacing: -0.3px;">This Week — Daily Breakdown</h5>
                                <span class="text-muted" style="font-size: 0.78rem;">{{ $weekRange }} · <span class="text-success fw-medium">stacked</span></span>
                            </div>
                        </div>
                    </div>

                    <div class="position-relative w-100 mt-2" style="height: 290px;">
                        <div class="position-absolute text-muted" style="left: 0; top: 0px; font-size: 0.72rem;">100%</div>
                        <div class="position-absolute w-100 border-bottom border-light" style="top: 8px; left: 35px; right: 0; border-style: dashed !important;"></div>

                        <div class="position-absolute text-muted" style="left: 0; top: 55px; font-size: 0.72rem;">75%</div>
                        <div class="position-absolute w-100 border-bottom border-light" style="top: 63px; left: 35px; right: 0; border-style: dashed !important;"></div>

                        <div class="position-absolute text-muted" style="left: 0; top: 110px; font-size: 0.72rem;">50%</div>
                        <div class="position-absolute w-100 border-bottom border-light" style="top: 118px; left: 35px; right: 0; border-style: dashed !important;"></div>

                        <div class="position-absolute text-muted" style="left: 0; top: 165px; font-size: 0.72rem;">25%</div>
                        <div class="position-absolute w-100 border-bottom border-light" style="top: 173px; left: 35px; right: 0; border-style: dashed !important;"></div>

                        <div class="position-absolute text-muted" style="left: 0; top: 220px; font-size: 0.72rem;">0%</div>
                        <div class="position-absolute w-100 border-bottom border-light" style="top: 228px; left: 35px; right: 0;"></div>

                        <div class="d-flex align-items-end justify-content-around h-100 position-relative ps-4" style="margin-left: 25px; height: 215px; padding-bottom: 25px;">
                            @foreach($weekly as $d)
                            <div class="d-flex flex-column align-items-center h-100 justify-content-end" style="width: 15%;">
                                @if($d['expected'] > 0)
                                    <div class="w-100 rounded-top-3 d-flex flex-column overflow-hidden shadow-sm"
                                         style="height: 195px; max-width: 24px;"
                                         title="Present {{ $d['present_pct'] }}% · Late {{ $d['late_pct'] }}% · Absent {{ $d['absent_pct'] }}%">
                                        <div style="height: {{ $d['absent_pct'] }}%; background-color: #ef4444;"></div>
                                        <div class="flex-grow-1" style="background-color: #2563eb;"></div>
                                        <div style="height: {{ $d['late_pct'] }}%; background-color: #f59e0b;"></div>
                                    </div>
                                @else
                                    <div class="w-100 rounded-top-3" style="height: 6px; max-width: 24px; background-color: #e2e8f0;" title="Belum ada sesi"></div>
                                @endif
                                <span class="text-muted fw-semibold mt-1" style="font-size: 0.82rem;">{{ $d['day'] }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kolom Kanan Bawah: Rate by Class -->
            <div class="col-xl-4 col-lg-5">
                <div class="card border-0 rounded-4 p-4 shadow-sm bg-white d-flex flex-column" style="padding: 2.2rem !important; height: 420px;">
                    <div class="d-flex align-items-center justify-content-between mb-2 flex-shrink-0">
                        <div class="d-flex align-items-center gap-2.5">
                            <div class="p-2 rounded-3 d-flex align-items-center justify-content-center text-primary" style="width: 38px; height: 38px; background-color: #eff6ff;">
                                <i class="bi bi-pie-chart-fill fs-6"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold text-dark mb-0" style="font-size: 1.1rem; letter-spacing: -0.3px;">Rate by Class</h5>
                                <span class="text-muted" style="font-size: 0.78rem;">{{ now()->format('M Y') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="text-center py-1 position-relative flex-shrink-0 d-flex justify-content-center align-items-center" style="height: 130px;">
                        @php
                            $ringRadii  = [62, 52, 42, 32, 22];
                            $ringWidth  = [7, 6, 5, 4.5, 4];
                            $ringColors = ['#2563eb', '#3b82f6', '#60a5fa', '#93c5fd', '#bfdbfe'];
                        @endphp
                        <svg width="125" height="125" viewBox="0 0 140 140" style="overflow: visible;">
                            @foreach($ringRadii as $i => $r)
                                @php
                                    $circ   = 2 * pi() * $r;
                                    $rate   = isset($topClasses[$i]) ? min(100, $topClasses[$i]['rate']) : 0;
                                    $offset = $circ * (1 - $rate / 100);
                                @endphp
                                <circle cx="70" cy="70" r="{{ $r }}" fill="none" stroke="#e0f2fe" stroke-width="{{ $ringWidth[$i] }}" />
                                @if($rate > 0)
                                <circle cx="70" cy="70" r="{{ $r }}" fill="none" stroke="{{ $ringColors[$i] }}" stroke-width="{{ $ringWidth[$i] }}"
                                        stroke-dasharray="{{ round($circ, 1) }}" stroke-dashoffset="{{ round($offset, 1) }}"
                                        stroke-linecap="round" transform="rotate(-90 70 70)" />
                                @endif
                            @endforeach
                        </svg>
                    </div>

                    <div class="row g-2 pt-2 pe-1 overflow-y-auto custom-scroll flex-grow-1" style="font-size: 0.8rem; max-height: 160px;">
                        @forelse($classReports as $cls)
                        <div class="col-6 d-flex justify-content-between align-items-center pe-2 mb-1">
                            <span class="text-muted d-flex align-items-center gap-1.5 text-truncate" style="max-width: 95px;">
                                <span class="rounded-circle d-inline-block bg-primary flex-shrink-0" style="width: 6px; height: 6px;"></span>
                                {{ $cls['nama_kelas'] }}
                            </span>
                            <strong class="text-dark fw-bold">{{ $cls['rate'] }}%</strong>
                        </div>
                        @empty
                        <div class="col-12 text-muted">Belum ada sesi presensi bulan ini.</div>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>

        @push('js')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            new Chart(document.getElementById('trendChart'), {
                type: 'line',
                data: {
                    labels: @json($trendLabels),
                    datasets: [
                        { label: 'Present', data: @json($trendPresent), borderColor: '#2563eb', backgroundColor: 'rgba(37,99,235,0.08)', fill: true, tension: 0.35, pointBackgroundColor: '#fff', pointBorderWidth: 2, borderWidth: 2.5 },
                        { label: 'Late',    data: @json($trendLate),    borderColor: '#f59e0b', tension: 0.35, pointBackgroundColor: '#fff', pointBorderWidth: 2, borderWidth: 2 },
                        { label: 'Absent',  data: @json($trendAbsent),  borderColor: '#ef4444', tension: 0.35, pointBackgroundColor: '#fff', pointBorderWidth: 2, borderWidth: 2 }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: { mode: 'index', intersect: false },
                    plugins: { legend: { display: false }, tooltip: { callbacks: { label: c => c.dataset.label + ': ' + c.parsed.y + '%' } } },
                    scales: {
                        y: { beginAtZero: true, max: 100, ticks: { stepSize: 25, callback: v => v + '%' }, grid: { color: 'rgba(0,0,0,0.04)' } },
                        x: { grid: { display: false } }
                    }
                }
            });
        </script>
        @endpush

        <style>
            .custom-scroll::-webkit-scrollbar {
                width: 4px;
            }
            .custom-scroll::-webkit-scrollbar-track {
                background: #f1f5f9;
                border-radius: 10px;
            }
            .custom-scroll::-webkit-scrollbar-thumb {
                background: #cbd5e1;
                border-radius: 10px;
            }
            .custom-scroll::-webkit-scrollbar-thumb:hover {
                background: #94a3b8;
            }
        </style>

    @endif

</div>
@endsection