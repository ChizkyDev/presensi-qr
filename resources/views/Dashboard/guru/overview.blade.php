@extends('layouts.guru')

@section('title', 'Teacher Dashboard - Attendly')

@section('content')
<div class="teacher-overview p-0">

    <!-- ── WELCOME HEADER & DATE FILTER ── -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1">Good Morning, {{ $guru->nama ?? Auth::user()->name ?? 'Teacher' }} ! </h3>
            <p class="text-muted mb-0" style="font-size: 0.88rem;">Start your daily lessons here</p>
        </div>
        <div>
            <button class="btn btn-white bg-white rounded-pill px-3 py-2 shadow-sm border-0 text-primary fw-medium d-flex align-items-center gap-2" style="font-size: 0.85rem;">
                <i class="bi bi-calendar-event text-primary"></i> {{ \Carbon\Carbon::now()->isoFormat('D MMMM Y') }}
                <i class="bi bi-chevron-down text-muted ms-1" style="font-size: 0.75rem;"></i>
            </button>
        </div>
    </div>

    <!-- ── TOP STAT CARDS (Lengkap dengan Grafik Kurva, Garis Tebal & Titik Bulat) ── -->
    <div class="row g-3 mb-4">
        <!-- Today's Classes -->
        <div class="col-md-4">
            <div class="card border-0 rounded-4 p-3 shadow-sm bg-white position-relative overflow-hidden h-100" style="padding-bottom: 3.5rem !important;">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div class="rounded-3 d-flex align-items-center justify-content-center p-2" style="background-color: #f3e8ff; color: #a855f7; width: 44px; height: 44px;">
                        <i class="bi bi-book fs-5"></i>
                    </div>
                    <span class="badge rounded-pill fw-medium px-2 py-1" style="background-color: #ecfdf5; color: #10b981; font-size: 0.72rem;">1 upcoming</span>
                </div>
                <div>
                    <h2 class="fw-bold text-dark mb-0">{{ $todaysClassesCount ?? 0 }}</h2>
                    <span class="text-muted fw-medium" style="font-size: 0.82rem;">Today's Classes</span>
                </div>
                <!-- Grafik SVG Ungu -->
                <div class="position-absolute bottom-0 start-0 w-100" style="height: 45px; pointer-events: none;">
                    <svg viewBox="0 0 300 100" class="w-100 h-100" preserveAspectRatio="none">
                        <path d="M0,55 Q90,40 180,55 T285,22 L285,100 L0,100 Z" fill="rgba(168, 85, 247, 0.05)"/>
                        <path d="M0,55 Q90,40 180,55 T285,22" fill="none" stroke="#a855f7" stroke-width="3.5" stroke-linecap="round"/>
                        <circle cx="285" cy="22" r="5" fill="#a855f7" stroke="#ffffff" stroke-width="2.5"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Students Today -->
        <div class="col-md-4">
            <div class="card border-0 rounded-4 p-3 shadow-sm bg-white position-relative overflow-hidden h-100" style="padding-bottom: 3.5rem !important;">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div class="rounded-3 d-flex align-items-center justify-content-center p-2" style="background-color: #eff6ff; color: #3b82f6; width: 44px; height: 44px;">
                        <i class="bi bi-mortarboard fs-5"></i>
                    </div>
                    <span class="badge rounded-pill fw-medium px-2 py-1 text-muted" style="background-color: #f1f5f9; font-size: 0.72rem;">across all</span>
                </div>
                <div>
                    <h2 class="fw-bold text-dark mb-0">{{ $totalStudents ?? 0 }}</h2>
                    <span class="text-muted fw-medium" style="font-size: 0.82rem;">Students Today</span>
                </div>
                <!-- Grafik SVG Biru -->
                <div class="position-absolute bottom-0 start-0 w-100" style="height: 45px; pointer-events: none;">
                    <svg viewBox="0 0 300 100" class="w-100 h-100" preserveAspectRatio="none">
                        <path d="M0,60 Q90,80 180,45 T285,25 L285,100 L0,100 Z" fill="rgba(59, 130, 246, 0.05)"/>
                        <path d="M0,60 Q90,80 180,45 T285,25" fill="none" stroke="#3b82f6" stroke-width="3.5" stroke-linecap="round"/>
                        <circle cx="285" cy="25" r="5" fill="#3b82f6" stroke="#ffffff" stroke-width="2.5"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Avg Attendance -->
        <div class="col-md-4">
            <div class="card border-0 rounded-4 p-3 shadow-sm bg-white position-relative overflow-hidden h-100" style="padding-bottom: 3.5rem !important;">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div class="rounded-3 d-flex align-items-center justify-content-center p-2" style="background-color: #fff7ed; color: #f97316; width: 44px; height: 44px;">
                        <i class="bi bi-activity fs-5"></i>
                    </div>
                    <span class="badge rounded-pill fw-medium px-2 py-1" style="background-color: #ecfdf5; color: #10b981; font-size: 0.72rem;">+1.2%</span>
                </div>
                <div>
                    <h2 class="fw-bold text-dark mb-0">{{ $avgAttendance ?? 0 }}%</h2>
                    <span class="text-muted fw-medium" style="font-size: 0.82rem;">Avg Attendance</span>
                </div>
                <!-- Grafik SVG Oranye -->
                <div class="position-absolute bottom-0 start-0 w-100" style="height: 45px; pointer-events: none;">
                    <svg viewBox="0 0 300 100" class="w-100 h-100" preserveAspectRatio="none">
                        <path d="M0,60 Q90,40 180,50 T285,20 L285,100 L0,100 Z" fill="rgba(249, 115, 22, 0.05)"/>
                        <path d="M0,60 Q90,40 180,50 T285,20" fill="none" stroke="#f97316" stroke-width="3.5" stroke-linecap="round"/>
                        <circle cx="285" cy="20" r="5" fill="#f97316" stroke="#ffffff" stroke-width="2.5"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- ── MAIN SCHEDULE & ATTENDANCE SECTION ── -->
    <div class="row g-4">
        
        <!-- LEFT COLUMN: Today's Schedule -->
        <div class="col-lg-8">
            <div class="card border-0 rounded-4 p-4 shadow-sm bg-white h-100">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="d-flex align-items-center gap-2">
                        <div class="p-2 rounded-3 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; background-color: #eff6ff; color: #3b82f6;">
                            <i class="bi bi-calendar3 fs-6"></i>
                        </div>
                        <h6 class="fw-bold text-dark mb-0">Today's Schedule</h6>
                    </div>
                    <a href="{{ route('guru.sessions.create') ?? '#' }}" class="btn btn-primary rounded-pill px-3 py-1 text-white fw-medium d-flex align-items-center gap-1 text-decoration-none" style="font-size: 0.82rem;">
                        <i class="bi bi-plus-lg"></i> New Session
                    </a>
                </div>

                <!-- Schedule List Items -->
                <div class="d-flex flex-column gap-3">
                    @forelse($todaySessions ?? [] as $session)
                        <div class="d-flex align-items-center justify-content-between p-3 rounded-4 bg-light border-0">
                            <div class="d-flex align-items-center gap-3">
                                <div class="text-center" style="line-height: 1.1; min-width: 50px;">
                                    <div class="fw-bold text-dark" style="font-size: 0.9rem;">
                                        {{ isset($session->jam_mulai) ? \Carbon\Carbon::parse($session->jam_mulai)->format('H:i') : \Carbon\Carbon::parse($session->created_at)->format('H:i') }}
                                    </div>
                                    <small class="text-muted" style="font-size: 0.72rem;">
                                        {{ isset($session->jam_selesai) ? \Carbon\Carbon::parse($session->jam_selesai)->format('H:i') : \Carbon\Carbon::parse($session->created_at)->addMinutes(90)->format('H:i') }}
                                    </small>
                                </div>
                                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 28px; height: 28px; background-color: #eff6ff; color: #2563eb;">
                                    <i class="bi bi-play-fill"></i>
                                </div>
                                <div>
                                    <div class="fw-bold text-dark mb-0" style="font-size: 0.9rem;">{{ $session->mapel ?? $session->mata_pelajaran ?? 'Mata Pelajaran' }}</div>
                                    <div class="text-muted d-flex align-items-center gap-2" style="font-size: 0.78rem;">
                                        <span><i class="bi bi-book me-1"></i>{{ $session->kelas->kelas ?? '' }} {{ $session->kelas->jurusan ?? '' }}</span>
                                        <span>•</span>
                                        <span><i class="bi bi-geo-alt me-1"></i>{{ $session->ruangan ?? 'Lab Komputer' }}</span>
                                    </div>
                                </div>
                            </div>
                            @if(($session->status ?? 'aktif') == 'aktif')
                                <a href="#" class="btn btn-outline-primary rounded-pill px-3 py-1 btn-sm fw-medium d-flex align-items-center gap-1 text-decoration-none" style="font-size: 0.78rem;">
                                    <i class="bi bi-qr-code"></i> Start QR
                                </a>
                            @else
                                <span class="badge rounded-pill fw-medium px-3 py-1" style="background-color: #ecfdf5; color: #10b981; font-size: 0.78rem;">Completed</span>
                            @endif
                        </div>
                    @empty
                        <div class="text-center text-muted py-5 my-2">
                            <i class="bi bi-journal-x fs-2 d-block mb-2 text-secondary"></i>
                            <p class="mb-0 fw-medium" style="font-size: 0.88rem;">Belum ada jadwal atau sesi mengajar untuk hari ini.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- RIGHT COLUMN: Today's Attendance & Alerts -->
        <div class="col-lg-4 d-flex flex-column gap-3">
            
            <!-- Donut Chart Attendance Card -->
            <div class="card border-0 rounded-4 p-4 shadow-sm bg-white">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <div class="p-2 rounded-3 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; background-color: #eff6ff; color: #3b82f6;">
                        <i class="bi bi-clock-history fs-6"></i>
                    </div>
                    <h6 class="fw-bold text-dark mb-0">Today's Attendance</h6>
                </div>

                <!-- Donut Progress Ring -->
                <div class="d-flex justify-content-center my-3">
                    <div class="position-relative d-flex align-items-center justify-content-center" style="width: 170px; height: 170px;">
                        <svg width="170" height="170" viewBox="0 0 36 36" class="d-block mx-auto my-2 w-100" style="max-height: 250px;">
                            <path stroke="#f1f5f9" stroke-width="3.8" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                            <path stroke="#3b82f6" stroke-width="3.8" stroke-linecap="round" fill="none" stroke-dasharray="{{ $avgAttendance ?? 0 }}, 100" style="transition: stroke-dasharray 1s ease-out;" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                        </svg>
                        <div class="text-center position-absolute">
                            <h3 class="fw-bold text-dark mb-0" style="font-size: 1.6rem;">{{ $avgAttendance ?? 0 }}%</h3>
                            <small class="text-muted fw-medium" style="font-size: 0.75rem;">Present</small>
                        </div>
                    </div>
                </div>

                <!-- Legend Breakdown -->
                <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top">
                    <div class="d-flex align-items-center gap-2">
                        <span class="rounded-circle" style="width: 8px; height: 8px; background-color: #3b82f6;"></span>
                        <span class="text-muted fw-medium" style="font-size: 0.82rem;">Present</span>
                    </div>
                    <span class="fw-bold text-dark" style="font-size: 0.85rem;">{{ $presentStudentsCount ?? 0 }}</span>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-2">
                    <div class="d-flex align-items-center gap-2">
                        <span class="rounded-circle" style="width: 8px; height: 8px; background-color: #f97316;"></span>
                        <span class="text-muted fw-medium" style="font-size: 0.82rem;">Absent</span>
                    </div>
                    <span class="fw-bold text-dark" style="font-size: 0.85rem;">{{ $absentStudentsCount ?? 0 }}</span>
                </div>
            </div>

            <!-- Absent Alert Box -->
            <div class="card border-0 rounded-4 p-3 shadow-sm bg-white">
                <a href="#" class="text-decoration-none d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center p-2 flex-shrink-0" style="background-color: #fef2f2; color: #ef4444; width: 40px; height: 40px;">
                            <i class="bi bi-exclamation-triangle fs-5"></i>
                        </div>
                        <div>
                            <div class="fw-bold text-dark" style="font-size: 0.85rem;">{{ $unexcusedAbsentsCount ?? 0 }} students absent</div>
                            <small class="text-muted" style="font-size: 0.75rem;">No leave request on file</small>
                        </div>
                    </div>
                    <i class="bi bi-chevron-right text-muted fs-6"></i>
                </a>
            </div>

        </div>

    </div>

</div>
@endsection