@extends('layouts.siswa')

@section('title', 'Profile - Student Dashboard')

@section('content')
<div class="container-fluid px-0">
    
    {{-- Header Title --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-0">My Profile</h3>
            <small class="text-muted">Student Identity & Attendance Journey</small>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-primary btn-sm px-3 rounded-pill fw-semibold">
                <i class="bi bi-key me-1"></i> Change password
            </button>
            <button class="btn btn-primary btn-sm px-3 rounded-pill fw-semibold" style="background-color: #2563eb;">
                <i class="bi bi-pencil-square me-1"></i> Edit profile
            </button>
        </div>
    </div>

    {{-- Hero Card Profile Summary --}}
    <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
        <div class="row align-items-center">
            <div class="col-md-8 d-flex align-items-center gap-3">
                <div class="position-relative">
                    <div class="rounded-4 text-white d-flex align-items-center justify-content-center fw-bold fs-3 shadow-sm" 
                         style="width: 75px; height: 75px; background: linear-gradient(135deg, #1e3a8a, #3b82f6);">
                        {{ isset($siswa) ? strtoupper(substr($siswa->nama, 0, 2)) : 'KL' }}
                    </div>
                    <span class="position-absolute bottom-0 end-0 translate-middle badge rounded-pill bg-warning text-dark border border-white fw-bold" style="font-size: 10px;">
                        15
                    </span>
                </div>
                <div>
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <h4 class="fw-bold mb-0 text-dark">{{ $siswa->nama ?? Auth::user()->name }}</h4>
                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 rounded-pill fw-semibold" style="font-size: 11px;">
                            ● Active
                        </span>
                    </div>
                    <p class="text-muted mb-1 small fw-medium">
                        Class {{ optional($siswa->kelas ?? null)->kelas ?? 'XII' }}-{{ optional($siswa->kelas ?? null)->jurusan ?? 'A' }} • {{ optional($siswa->kelas ?? null)->jurusan ?? 'Science Program' }}
                    </p>
                    <p class="text-muted mb-0" style="font-size: 12px;">
                        NIS {{ $siswa->nis ?? '-' }} • ENROLLED 2024
                    </p>
                </div>
            </div>
            <div class="col-md-4 mt-3 mt-md-0 border-start-md ps-md-4">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <span class="text-muted small fw-semibold">Semester attendance</span>
                    <span class="fw-bold text-primary">94%</span>
                </div>
                <div class="progress rounded-pill" style="height: 8px; background-color: #e2e8f0;">
                    <div class="progress-bar rounded-pill bg-primary" role="progressbar" style="width: 94%;" aria-valuenow="94" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Grid Content Layout --}}
    <div class="row g-4">
        
        {{-- Kolom Kiri: Personal Information & Recent Attendance --}}
        <div class="col-lg-7 d-flex flex-column gap-4">
            
            {{-- Personal Information Card --}}
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2">
                        <i class="bi bi-person text-primary"></i> Personal Information
                    </h5>
                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 rounded-pill" style="font-size: 11px;">
                        <i class="bi bi-check-circle-fill me-1"></i> SCHOOL VERIFIED
                    </span>
                </div>

                <div class="row g-3">
                    <div class="col-sm-6">
                        <span class="text-uppercase text-muted d-block fw-bold" style="font-size: 10px; letter-spacing: 0.5px;">Full Name</span>
                        <span class="fw-semibold text-dark">{{ $siswa->nama ?? Auth::user()->name }}</span>
                    </div>
                    <div class="col-sm-6">
                        <span class="text-uppercase text-muted d-block fw-bold" style="font-size: 10px; letter-spacing: 0.5px;">Student Number (NIS)</span>
                        <span class="fw-semibold text-dark">{{ $siswa->nis ?? '-' }}</span>
                    </div>
                    <div class="col-sm-6">
                        <span class="text-uppercase text-muted d-block fw-bold" style="font-size: 10px; letter-spacing: 0.5px;">Class</span>
                        <span class="fw-semibold text-dark">
                            {{ optional($siswa->kelas ?? null)->kelas ?? '' }} {{ optional($siswa->kelas ?? null)->jurusan ?? '' }} {{ optional($siswa->kelas ?? null)->nomor_kelas ?? '' }}
                        </span>
                    </div>
                    <div class="col-sm-6">
                        <span class="text-uppercase text-muted d-block fw-bold" style="font-size: 10px; letter-spacing: 0.5px;">Date of Birth</span>
                        <span class="fw-semibold text-dark">{{ $siswa->tanggal_lahir ?? 'March 15, 2008' }}</span>
                    </div>
                    <div class="col-sm-6">
                        <span class="text-uppercase text-muted d-block fw-bold" style="font-size: 10px; letter-spacing: 0.5px;">Guardian</span>
                        <span class="fw-semibold text-dark">{{ $siswa->wali ?? 'Budi Pratama' }}</span>
                    </div>
                    <div class="col-sm-6">
                        <span class="text-uppercase text-muted d-block fw-bold" style="font-size: 10px; letter-spacing: 0.5px;">Email</span>
                        <span class="fw-semibold text-dark">{{ Auth::user()->email }}</span>
                    </div>
                </div>
            </div>

            {{-- Recent Attendance Activity Card --}}
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2">
                        <i class="bi bi-calendar-check text-primary"></i> Recent attendance activity
                    </h5>
                </div>
                <span class="badge bg-light text-muted border px-2 py-1 rounded-pill mb-3 align-self-start" style="font-size: 11px;">
                    Aug 29 – Sep 4, 2025
                </span>

                <div class="d-flex flex-column gap-3">
                    {{-- Item 1 --}}
                    <div class="d-flex justify-content-between align-items-center p-2 rounded-3 border-bottom pb-3">
                        <div>
                            <h6 class="fw-bold mb-1 text-dark">Mathematics</h6>
                            <p class="text-muted mb-0 small">Dr. Hendra Wijaya</p>
                        </div>
                        <div class="text-end">
                            <span class="d-block text-muted small mb-1">Aug 29 – 09:41</span>
                            <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 rounded-pill" style="font-size: 10px;">Present</span>
                        </div>
                    </div>

                    {{-- Item 2 --}}
                    <div class="d-flex justify-content-between align-items-center p-2 rounded-3 border-bottom pb-3">
                        <div>
                            <h6 class="fw-bold mb-1 text-dark">Physics</h6>
                            <p class="text-muted mb-0 small">Sari Kusuma, S.Pd</p>
                        </div>
                        <div class="text-end">
                            <span class="d-block text-muted small mb-1">Sep 1 – 10:00</span>
                            <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 rounded-pill" style="font-size: 10px;">Present</span>
                        </div>
                    </div>

                    {{-- Item 3 --}}
                    <div class="d-flex justify-content-between align-items-center p-2 rounded-3">
                        <div>
                            <h6 class="fw-bold mb-1 text-dark">Chemistry</h6>
                            <p class="text-muted mb-0 small">Ahmad Fauzi, M.Pd</p>
                        </div>
                        <div class="text-end">
                            <span class="d-block text-muted small mb-1">Aug 30 – 11:15</span>
                            <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-2 py-1 rounded-pill" style="font-size: 10px;">Late</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- Kolom Kanan: Performance Snapshot & Achievements --}}
        <div class="col-lg-5 d-flex flex-column gap-4">
            
            {{-- Performance Snapshot --}}
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                <h5 class="fw-bold mb-3 text-dark d-flex align-items-center gap-2">
                    <i class="bi bi-mortarboard text-primary"></i> Performance Snapshot
                </h5>

                <div class="row g-3">
                    <div class="col-6">
                        <div class="p-3 rounded-4 border bg-light bg-opacity-50">
                            <span class="text-uppercase text-warning fw-bold d-block mb-1" style="font-size: 10px;">
                                <i class="bi bi-trophy"></i> SEMESTER RANK
                            </span>
                            <h3 class="fw-bold mb-0 text-dark">#8</h3>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 rounded-4 border bg-light bg-opacity-50">
                            <span class="text-uppercase text-success fw-bold d-block mb-1" style="font-size: 10px;">
                                <i class="bi bi-graph-up"></i> ABSENCES
                            </span>
                            <h3 class="fw-bold mb-0 text-dark">42</h3>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 rounded-4 border bg-light bg-opacity-50">
                            <span class="text-uppercase text-danger fw-bold d-block mb-1" style="font-size: 10px;">
                                <i class="bi bi-book"></i> LATE
                            </span>
                            <h3 class="fw-bold mb-0 text-dark">3</h3>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 rounded-4 border bg-light bg-opacity-50">
                            <span class="text-uppercase text-primary fw-bold d-block mb-1" style="font-size: 10px;">
                                <i class="bi bi-calendar-event"></i> PERFECT DAYS
                            </span>
                            <h3 class="fw-bold mb-0 text-dark">38</h3>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Achievements Card --}}
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                <h5 class="fw-bold mb-3 text-dark d-flex align-items-center gap-2">
                    <i class="bi bi-award text-warning"></i> Achievements
                </h5>

                <div class="d-flex flex-column gap-3">
                    <div class="d-flex align-items-center justify-content-between p-2 border-bottom pb-3">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-warning bg-opacity-10 text-warning p-2 rounded-3">
                                <i class="bi bi-lightning-charge-fill fs-5"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-0 text-dark" style="font-size: 14px;">15-Day Streak</h6>
                                <small class="text-muted" style="font-size: 11px;">Unlocked this semester</small>
                            </div>
                        </div>
                        <span class="badge bg-warning bg-opacity-15 text-warning fw-semibold px-2 py-1" style="font-size: 10px;">Gold</span>
                    </div>

                    <div class="d-flex align-items-center justify-content-between p-2 border-bottom pb-3">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-success bg-opacity-10 text-success p-2 rounded-3">
                                <i class="bi bi-star-fill fs-5"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-0 text-dark" style="font-size: 14px;">Top Attendance</h6>
                                <small class="text-muted" style="font-size: 11px;">Unlocked this semester</small>
                            </div>
                        </div>
                        <span class="badge bg-success bg-opacity-15 text-success fw-semibold px-2 py-1" style="font-size: 10px;">Green</span>
                    </div>

                    <div class="d-flex align-items-center justify-content-between p-2">
                        <div class="d-flex align-items-center gap-3">
                            <div class="p-2 rounded-3" style="color: #8b5cf6; background-color: rgba(139, 92, 246, 0.1);">
                                <i class="bi bi-shield-check fs-5"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-0 text-dark" style="font-size: 14px;">Zero Absences</h6>
                                <small class="text-muted" style="font-size: 11px;">Unlocked this semester</small>
                            </div>
                        </div>
                        <span class="badge fw-semibold px-2 py-1" style="font-size: 10px; background-color: rgba(139, 92, 246, 0.15); color: #8b5cf6;">Purple</span>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>
@endsection