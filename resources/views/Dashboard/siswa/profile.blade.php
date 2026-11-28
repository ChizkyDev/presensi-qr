@extends('layouts.siswa')

@section('title', 'Profile - Student Dashboard')

@section('content')
@php
    $inisial    = strtoupper(mb_substr($siswa->nama ?? $user->name, 0, 2));
    $kelas      = $siswa->kelas;
    $kelasLabel = $kelas->nama_lengkap ?? '-';
    $editing    = $errors->any();   // kalau ada error validasi, langsung buka mode edit
    $statusMap  = [
        'hadir' => ['Present', 'bg-success-subtle text-success border-success-subtle'],
        'telat' => ['Late',    'bg-warning-subtle text-warning border-warning-subtle'],
        'izin'  => ['Excused', 'bg-info-subtle text-info border-info-subtle'],
        'sakit' => ['Sick',    'bg-info-subtle text-info border-info-subtle'],
        'alpha' => ['Absent',  'bg-danger-subtle text-danger border-danger-subtle'],
    ];
@endphp

<div class="container-fluid px-0 {{ $editing ? 'is-editing' : '' }}" id="profileRoot">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-0">My Profile</h3>
            <small class="text-muted">Student Identity & Attendance Journey</small>
        </div>

        {{-- Tombol mode lihat --}}
        <div class="d-flex gap-2 view-only">
            <button type="button" id="btnChangePassword" class="btn btn-outline-primary btn-sm px-3 rounded-pill fw-semibold">
                <i class="bi bi-key me-1"></i> Change password
            </button>
            <button type="button" id="btnEdit" class="btn btn-primary btn-sm px-3 rounded-pill fw-semibold" style="background-color: #2563eb;">
                <i class="bi bi-pencil-square me-1"></i> Edit profile
            </button>
        </div>

        {{-- Tombol mode edit --}}
        <div class="d-flex gap-2 edit-only">
            <button type="button" id="btnCancel" class="btn btn-outline-primary btn-sm px-3 rounded-pill fw-semibold">Cancel</button>
            <button type="submit" form="profileForm" class="btn btn-primary btn-sm px-3 rounded-pill fw-semibold" style="background-color: #2563eb;">
                <i class="bi bi-check2-circle me-1"></i> Save Changes
            </button>
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
    @if($errors->any())
        <div class="alert alert-danger rounded-4 mb-4 shadow-sm">
            <ul class="mb-0 small">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form kosong: semua input di bawah terhubung lewat atribut form="profileForm" --}}
    <form id="profileForm" action="{{ route('siswa.profile.update') }}" method="POST" class="d-none">
        @csrf
        @method('PUT')
    </form>

    {{-- Hero --}}
    <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
        <div class="row align-items-center">
            <div class="col-md-8 d-flex align-items-center gap-3">
                <div class="position-relative">
                    <div class="rounded-4 text-white d-flex align-items-center justify-content-center fw-bold fs-3 shadow-sm"
                         style="width: 75px; height: 75px; background: linear-gradient(135deg, #1e3a8a, #3b82f6);">
                        {{ $inisial }}
                    </div>
                    <span class="position-absolute bottom-0 end-0 translate-middle badge rounded-pill bg-warning text-dark border border-white fw-bold"
                          style="font-size: 10px;" title="Attendance streak (days)">
                        <i class="bi bi-lightning-charge-fill"></i> {{ $streak }}
                    </span>
                </div>
                <div>
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <h4 class="fw-bold mb-0 text-dark">{{ $siswa->nama ?? $user->name }}</h4>
                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 rounded-pill fw-semibold" style="font-size: 11px;">
                            ● Active
                        </span>
                    </div>
                    <p class="text-muted mb-1 small fw-medium">
                        Class {{ $kelasLabel }}{{ $kelas?->jurusan ? ' • ' . $kelas->jurusan . ' Program' : '' }}
                    </p>
                    <p class="text-muted mb-0" style="font-size: 12px;">
                        NIS {{ $siswa->nis ?: '-' }} • ENROLLED {{ $siswa->created_at?->format('Y') }}
                    </p>
                </div>
            </div>
            <div class="col-md-4 mt-3 mt-md-0 ps-md-4">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <span class="text-muted small fw-semibold">Semester attendance</span>
                    <span class="fw-bold text-primary">{{ $attendanceRate }}%</span>
                </div>
                <div class="progress rounded-pill" style="height: 8px; background-color: #e2e8f0;">
                    <div class="progress-bar rounded-pill bg-primary" role="progressbar" style="width: {{ $attendanceRate }}%;"
                         aria-valuenow="{{ $attendanceRate }}" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">

        {{-- Kolom kiri --}}
        <div class="col-lg-7 d-flex flex-column gap-4">

            {{-- Personal Information (mode lihat + mode edit) --}}
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2">
                        <i class="bi bi-person text-primary"></i> Personal Information
                    </h5>
                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 rounded-pill view-only" style="font-size: 11px;">
                        <i class="bi bi-check-circle-fill me-1"></i> SCHOOL VERIFIED
                    </span>
                    <small class="text-muted edit-only" style="font-size: 11px;">Student Number and Class are managed by the school.</small>
                </div>

                {{-- Mode lihat --}}
                <div class="row g-3 view-only">
                    <div class="col-sm-6">
                        <span class="text-uppercase text-muted d-block fw-bold" style="font-size: 10px; letter-spacing: 0.5px;">Full Name</span>
                        <span class="fw-semibold text-dark">{{ $siswa->nama ?? $user->name }}</span>
                    </div>
                    <div class="col-sm-6">
                        <span class="text-uppercase text-muted d-block fw-bold" style="font-size: 10px; letter-spacing: 0.5px;">Student Number</span>
                        <span class="fw-semibold text-dark">{{ $siswa->nis ?: '-' }}</span>
                    </div>
                    <div class="col-sm-6">
                        <span class="text-uppercase text-muted d-block fw-bold" style="font-size: 10px; letter-spacing: 0.5px;">Class</span>
                        <span class="fw-semibold text-dark">{{ $kelasLabel }}</span>
                    </div>
                    <div class="col-sm-6">
                        <span class="text-uppercase text-muted d-block fw-bold" style="font-size: 10px; letter-spacing: 0.5px;">Email</span>
                        <span class="fw-semibold text-dark">{{ $user->email }}</span>
                    </div>
                </div>

                {{-- Mode edit --}}
                <div class="row g-3 edit-only">
                    <div class="col-sm-6">
                        <label class="form-label text-muted mb-1" style="font-size: 0.75rem;">Full Name</label>
                        <input type="text" name="nama" form="profileForm" class="form-control bg-light border-0 py-2 @error('nama') is-invalid @enderror"
                               value="{{ old('nama', $siswa->nama ?? $user->name) }}" required>
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label text-muted mb-1" style="font-size: 0.75rem;">Student Number</label>
                        <input type="text" class="form-control bg-light border-0 py-2 text-muted" value="{{ $siswa->nis ?: '-' }}" disabled>
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label text-muted mb-1" style="font-size: 0.75rem;">Class</label>
                        <input type="text" class="form-control bg-light border-0 py-2 text-muted" value="{{ $kelasLabel }}" disabled>
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label text-muted mb-1" style="font-size: 0.75rem;">Email Address</label>
                        <input type="email" name="email" form="profileForm" class="form-control bg-light border-0 py-2 @error('email') is-invalid @enderror"
                               value="{{ old('email', $user->email) }}" required>
                    </div>
                </div>
            </div>

            {{-- Security & password (hanya tampil di mode edit) --}}
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white edit-only" id="security">
                <h5 class="fw-bold text-dark mb-1" style="font-size: 1.05rem;">Security &amp; password</h5>
                <p class="text-muted small mb-4">Leave password fields blank to keep your current password.</p>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label text-muted mb-1" style="font-size: 0.75rem;">New Password</label>
                        <input type="password" name="password" id="newPassword" form="profileForm"
                               class="form-control bg-light border-0 py-2 @error('password') is-invalid @enderror"
                               placeholder="Enter new password" autocomplete="new-password">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted mb-1" style="font-size: 0.75rem;">Confirm Password</label>
                        <input type="password" name="password_confirmation" form="profileForm"
                               class="form-control bg-light border-0 py-2"
                               placeholder="Repeat new password" autocomplete="new-password">
                    </div>
                </div>
            </div>

            {{-- Recent attendance activity --}}
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2">
                        <i class="bi bi-calendar-check text-primary"></i> Recent attendance activity
                    </h5>
                </div>
                @if($recentRange)
                    <span class="badge bg-light text-muted border px-2 py-1 rounded-pill mb-3 align-self-start" style="font-size: 11px;">
                        {{ $recentRange }}
                    </span>
                @endif

                <div class="d-flex flex-column gap-3">
                    @forelse($recent as $row)
                        @php
                            $sesi    = $row->sesiPresensi;
                            $teacher = $sesi?->guru?->nama ?? $sesi?->guru?->user?->name ?? $sesi?->guru?->name ?? '-';
                            [$label, $badgeClass] = $statusMap[$row->status] ?? [ucfirst($row->status), 'bg-light text-muted border'];
                            $jamTampil = !empty($row->jam_scan)
                                ? substr((string) $row->jam_scan, 0, 5)
                                : ($row->created_at ? \Carbon\Carbon::parse($row->created_at)->format('H:i') : '--:--');
                            $when = \Carbon\Carbon::parse($row->tanggal)->format('M d') . ' – ' . $jamTampil;
                        @endphp
                        <div class="d-flex justify-content-between align-items-center p-2 rounded-3 {{ !$loop->last ? 'border-bottom pb-3' : '' }}">
                            <div>
                                <h6 class="fw-bold mb-1 text-dark">{{ $sesi->mapel ?? '-' }}</h6>
                                <p class="text-muted mb-0 small">{{ $teacher }}</p>
                            </div>
                            <div class="text-end">
                                <span class="d-block text-muted small mb-1">{{ $when }}</span>
                                <span class="badge {{ $badgeClass }} border px-2 py-1 rounded-pill" style="font-size: 10px;">{{ $label }}</span>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted small mb-0">Belum ada riwayat kehadiran.</p>
                    @endforelse
                </div>
            </div>

        </div>

        {{-- Kolom kanan --}}
        <div class="col-lg-5 d-flex flex-column gap-4">

            {{-- Performance Snapshot --}}
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                <h5 class="fw-bold mb-3 text-dark d-flex align-items-center gap-2">
                    <i class="bi bi-mortarboard text-primary"></i> Performance Snapshot
                </h5>

                <div class="row g-3">
                    <div class="col-6">
                        <div class="p-3 rounded-4 h-100" style="background-color: #fff7ed; border: 1px solid #fed7aa;">
                            <span class="text-uppercase fw-bold d-block mb-1" style="font-size: 10px; color: #ea580c;">
                                <i class="bi bi-trophy"></i> Semester Rank
                            </span>
                            <h3 class="fw-bold mb-0 text-dark">{{ $rank ? '#' . $rank : '-' }}</h3>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 rounded-4 h-100" style="background-color: #ecfdf5; border: 1px solid #a7f3d0;">
                            <span class="text-uppercase fw-bold d-block mb-1" style="font-size: 10px; color: #10b981;">
                                <i class="bi bi-graph-up"></i> Absences
                            </span>
                            <h3 class="fw-bold mb-0 text-dark">{{ $absences }}</h3>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 rounded-4 h-100" style="background-color: #fef2f2; border: 1px solid #fecaca;">
                            <span class="text-uppercase fw-bold d-block mb-1" style="font-size: 10px; color: #ef4444;">
                                <i class="bi bi-clock-history"></i> Late
                            </span>
                            <h3 class="fw-bold mb-0 text-dark">{{ $lateCount }}</h3>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 rounded-4 h-100" style="background-color: #eff6ff; border: 1px solid #bfdbfe;">
                            <span class="text-uppercase fw-bold d-block mb-1" style="font-size: 10px; color: #2563eb;">
                                <i class="bi bi-calendar-event"></i> Perfect Days
                            </span>
                            <h3 class="fw-bold mb-0 text-dark">{{ $perfectDays }}</h3>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Achievements --}}
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                <h5 class="fw-bold mb-3 text-dark d-flex align-items-center gap-2">
                    <i class="bi bi-award text-warning"></i> Achievements
                </h5>

                <div class="d-flex flex-column gap-3">
                    @foreach($achievements as $a)
                    <div class="d-flex align-items-center justify-content-between p-2 {{ !$loop->last ? 'border-bottom pb-3' : '' }}">
                        <div class="d-flex align-items-center gap-3">
                            <div class="p-2 rounded-3" style="color: {{ $a['color'] }}; background-color: {{ $a['color'] }}1a;">
                                <i class="bi {{ $a['icon'] }} fs-5"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-0 text-dark" style="font-size: 14px;">{{ $a['title'] }}</h6>
                                <small class="text-muted" style="font-size: 11px;">{{ $a['desc'] }}</small>
                            </div>
                        </div>
                        <span class="badge fw-semibold px-2 py-1" style="font-size: 10px; background-color: {{ $a['color'] }}26; color: {{ $a['color'] }};">{{ $a['badge'] }}</span>
                    </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
</div>

<style>
    /* Tampilkan elemen sesuai mode */
    #profileRoot:not(.is-editing) .edit-only { display: none !important; }
    #profileRoot.is-editing .view-only { display: none !important; }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const root = document.getElementById('profileRoot');
        const profileUrl = @json(route('siswa.profile'));

        document.getElementById('btnEdit').addEventListener('click', function () {
            root.classList.add('is-editing');
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });

        document.getElementById('btnChangePassword').addEventListener('click', function () {
            root.classList.add('is-editing');
            document.getElementById('security').scrollIntoView({ behavior: 'smooth', block: 'center' });
            document.getElementById('newPassword').focus({ preventScroll: true });
        });

        // Cancel: muat ulang halaman supaya semua isian kembali ke data di database
        document.getElementById('btnCancel').addEventListener('click', function () {
            window.location.href = profileUrl;
        });
    });
</script>
@endsection