@extends('layouts.guru')

@section('title', 'Teacher Profile - Attendly')

@section('content')
@php
    $inisial = strtoupper(substr($guru->nama ?? $user->name, 0, 2));
@endphp

<div class="teacher-overview p-0" style="max-width: 1320px; margin: 0 auto;">

    <!-- ── HEADER TITLE & ACTION BUTTONS ── -->
    <div class="d-flex justify-content-between align-items-center mb-4 px-1">
        <div>
            <h3 class="fw-bold text-dark mb-1" style="font-size: 1.5rem;">My Profile</h3>
            <p class="text-muted mb-0" style="font-size: 0.85rem;">Student Identity & Attendance Journey</p>
        </div>
        <div class="d-flex gap-2">
            <a href="#" class="btn btn-light rounded-pill px-3 py-2 text-primary fw-medium bg-white shadow-sm border" style="font-size: 0.85rem;">
                <i class="bi bi-key me-1"></i> Change password
            </a>
            <a href="#" class="btn btn-primary rounded-pill px-4 py-2 text-white fw-medium shadow-sm border-0" style="background-color: #2563eb; font-size: 0.85rem;">
                <i class="bi bi-pencil-square me-1"></i> Edit profile
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-4 mb-4 shadow-sm" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger rounded-4 mb-4 shadow-sm">{{ session('error') }}</div>
    @endif

    <!-- ── TOP HERO BANNER: TEACHER INFO CARD ── -->
    <div class="card border-0 rounded-4 p-4 shadow-sm bg-white mb-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
            <div class="d-flex align-items-center gap-4">
                <!-- Avatar Box Biru Gradasi -->
                <div class="rounded-4 text-white d-flex align-items-center justify-content-center fw-bold shadow-sm flex-shrink-0" style="width: 75px; height: 75px; font-size: 1.5rem; background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);">
                    {{ $inisial }}
                </div>
                <div>
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <h4 class="fw-bold text-dark mb-0" style="font-size: 1.25rem;">{{ $guru->nama ?? $user->name }}</h4>
                        <i class="bi bi-check-circle-fill text-success" style="font-size: 1rem;" title="Verified"></i>
                    </div>
                    <p class="text-muted mb-1" style="font-size: 0.85rem;">
                        {{ $guru->subject ?: '-' }}{{ $guru->homeroom ? ' • Homeroom ' . $guru->homeroom : '' }}
                    </p>
                    <p class="text-muted mb-0" style="font-size: 0.78rem;">
                        NIP {{ $guru->nip ?: '-' }} • ACTIVE SINCE {{ $guru->created_at?->format('Y') }}
                    </p>
                </div>
            </div>
            <div>
                <span class="badge rounded-pill px-3 py-2 fw-medium" style="background-color: #ecfdf5; color: #10b981; font-size: 0.78rem;">
                    ● Available on School
                </span>
            </div>
        </div>
    </div>

    <!-- ── MAIN CONTENT GRID (2 COLUMNS) ── -->
    <div class="row g-4">

        <!-- LEFT COLUMN: Personal Information & Recent Activity -->
        <div class="col-lg-6 d-flex flex-column gap-4">

            <!-- Personal Information Card -->
            <div class="card border-0 rounded-4 p-4 shadow-sm bg-white">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-3 d-flex align-items-center justify-content-center p-2" style="background-color: #eff6ff; color: #3b82f6; width: 36px; height: 36px;">
                            <i class="bi bi-person fs-5"></i>
                        </div>
                        <h6 class="fw-bold text-dark mb-0" style="font-size: 1rem;">Personal Information</h6>
                    </div>
                    <span class="badge rounded-pill px-3 py-1 fw-medium" style="background-color: #ecfdf5; color: #10b981; font-size: 0.7rem;">
                        <i class="bi bi-check-circle me-1"></i> VERIFIED DIRECTORY DATA
                    </span>
                </div>

                <div class="row g-3" style="font-size: 0.85rem;">
                    <div class="col-6">
                        <span class="text-muted d-block mb-1" style="font-size: 0.75rem;">EMAIL</span>
                        <span class="fw-bold text-dark">{{ $user->email }}</span>
                    </div>
                    <div class="col-6">
                        <span class="text-muted d-block mb-1" style="font-size: 0.75rem;">TEACHER NUMBER</span>
                        <span class="fw-bold text-dark">{{ $guru->nip ?: '-' }}</span>
                    </div>
                    <div class="col-6 pt-2">
                        <span class="text-muted d-block mb-1" style="font-size: 0.75rem;">PRIMARY SUBJECT</span>
                        <span class="fw-bold text-dark">{{ $guru->subject ?: '-' }}</span>
                    </div>
                    <div class="col-6 pt-2">
                        <span class="text-muted d-block mb-1" style="font-size: 0.75rem;">HOMEROOM</span>
                        <span class="fw-bold text-dark">{{ $guru->homeroom ?: '-' }}</span>
                    </div>
                </div>
            </div>

            <!-- Recent Activity Card -->
            <div class="card border-0 rounded-4 p-4 shadow-sm bg-white">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="rounded-3 d-flex align-items-center justify-content-center p-2" style="background-color: #eff6ff; color: #3b82f6; width: 36px; height: 36px;">
                        <i class="bi bi-calendar-event fs-5"></i>
                    </div>
                    <h6 class="fw-bold text-dark mb-0" style="font-size: 1rem;">Recent activity</h6>
                </div>

                <div class="d-flex flex-column gap-3">
                    @forelse($recentSessions as $sesi)
                        @php
                            $selesai = $sesi->status === 'nonaktif' || optional($sesi->expired_at)->isPast();
                            $hadir   = $sesi->hadir_count + $sesi->telat_count;
                        @endphp
                        <div class="d-flex align-items-center justify-content-between {{ !$loop->last ? 'pb-3 border-bottom' : '' }}">
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-3 {{ $selesai ? 'bg-success-subtle text-success' : 'bg-primary-subtle text-primary' }} p-2 d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                                    <i class="bi {{ $selesai ? 'bi-check-circle' : 'bi-qr-code' }} fs-6"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold text-dark mb-0" style="font-size: 0.85rem;">
                                        {{ $selesai ? 'Completed attendance session' : 'Created attendance session' }}
                                    </h6>
                                    <small class="text-muted" style="font-size: 0.75rem;">
                                        {{ $sesi->kelas->nama_lengkap ?? '-' }} • {{ $sesi->mapel }}
                                        @if($selesai)
                                            • {{ $hadir }} of {{ $sesi->kelas->siswa_count ?? 0 }} present
                                        @endif
                                    </small>
                                </div>
                            </div>
                            <span class="text-muted" style="font-size: 0.72rem;">
                                {{ $sesi->created_at->isToday() ? 'Today' : $sesi->created_at->format('d M') }}
                                · {{ $sesi->created_at->format('H:i') }}
                            </span>
                        </div>
                    @empty
                        <p class="text-muted mb-0" style="font-size: 0.85rem;">Belum ada aktivitas.</p>
                    @endforelse
                </div>
            </div>

        </div>

        <!-- RIGHT COLUMN: Teaching Overview & Assigned Classes -->
        <div class="col-lg-6 d-flex flex-column gap-4">

            <!-- Teaching Overview Grid (2x2) -->
            <div class="card border-0 rounded-4 p-4 shadow-sm bg-white">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="rounded-3 d-flex align-items-center justify-content-center p-2" style="background-color: #eff6ff; color: #3b82f6; width: 36px; height: 36px;">
                        <i class="bi bi-mortarboard fs-5"></i>
                    </div>
                    <h6 class="fw-bold text-dark mb-0" style="font-size: 1rem;">Teaching overview</h6>
                </div>

                <div class="row g-3">
                    <div class="col-6">
                        <div class="p-3 rounded-4 bg-light border-0 h-100">
                            <h3 class="fw-bold text-dark mb-1" style="font-size: 1.5rem;">{{ $classAssigned }}</h3>
                            <span class="text-muted" style="font-size: 0.8rem;">Classes</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 rounded-4 bg-light border-0 h-100">
                            <h3 class="fw-bold text-dark mb-1" style="font-size: 1.5rem;">{{ $totalStudents }}</h3>
                            <span class="text-muted" style="font-size: 0.8rem;">Students</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 rounded-4 bg-light border-0 h-100">
                            <h3 class="fw-bold text-dark mb-1" style="font-size: 1.5rem;">{{ $sessionsRun }}</h3>
                            <span class="text-muted" style="font-size: 0.8rem;">Sessions</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 rounded-4 bg-light border-0 h-100">
                            <h3 class="fw-bold text-dark mb-1" style="font-size: 1.5rem;">{{ $avgAttendance }}%</h3>
                            <span class="text-muted" style="font-size: 0.8rem;">Attendance</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Assigned Classes Card -->
            <div class="card border-0 rounded-4 p-4 shadow-sm bg-white">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="rounded-3 d-flex align-items-center justify-content-center p-2" style="background-color: #eff6ff; color: #3b82f6; width: 36px; height: 36px;">
                        <i class="bi bi-journal-check fs-5"></i>
                    </div>
                    <h6 class="fw-bold text-dark mb-0" style="font-size: 1rem;">Assigned Classes</h6>
                </div>

                <div class="d-flex flex-column gap-3" style="font-size: 0.85rem;">
                    @forelse($kelasList as $kelas)
                        @php $isWali = $guru->homeroom && $guru->homeroom === $kelas->nama_lengkap; @endphp
                        <div class="d-flex align-items-center justify-content-between {{ !$loop->last ? 'pb-3 border-bottom' : '' }}">
                            <span class="fw-bold text-dark">
                                {{ $kelas->nama_lengkap }}
                                <span class="text-muted fw-normal" style="font-size: 0.78rem;">· {{ $kelas->siswa_count }} students</span>
                            </span>
                            <span class="badge rounded-pill px-3 py-1 fw-medium"
                                  style="background-color: {{ $isWali ? '#ecfdf5' : '#f1f5f9' }}; color: {{ $isWali ? '#10b981' : '#64748b' }}; font-size: 0.7rem;">
                                {{ $isWali ? 'Homeroom' : 'Active' }}
                            </span>
                        </div>
                    @empty
                        <p class="text-muted mb-0">Belum ada kelas.</p>
                    @endforelse
                </div>
            </div>

        </div>

    </div>
</div>
@endsection