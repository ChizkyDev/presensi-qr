@extends('layouts.admin')

@section('title', 'Student Management - Attendly Admin')

@section('content')
<div class="student-management-container p-0">

    <!-- ── HEADER TITLE & SUBTITLE ── -->
    <div class="mb-4">
        <h4 class="fw-bold text-dark mb-0">Student Management</h4>
        <small class="text-muted">Manage Students</small>
    </div>

    <!-- ── ALERT FLASH MESSAGE ── -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 border-0 shadow-sm mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- ── MAIN CONTENT CARD ── -->
    <div class="card border-0 rounded-4 p-4 shadow-sm bg-white">
        
        <!-- Table Header & Controls (Search, Filter, Add) -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
            <!-- Left Side: Icon & Info -->
            <div class="d-flex align-items-center gap-3">
                <div class="p-2 rounded-3 d-flex align-items-center justify-content-center" style="width: 42px; height: 42px; background-color: #eff6ff; color: #3b82f6;">
                    <i class="bi bi-person-check-fill fs-5"></i>
                </div>
                <div>
                    <h6 class="fw-bold text-dark mb-0">All Students</h6>
                    <small class="text-muted" style="font-size: 0.78rem;">
                        {{ isset($students) ? count($students) : 0 }} records
                    </small>
                </div>
            </div>

            <!-- Right Side: Controls -->
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <!-- Search Input -->
                <div class="input-group" style="width: 220px;">
                    <span class="input-group-text bg-light border-0 rounded-start-pill ps-3 text-muted" style="font-size: 0.85rem;">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" class="form-control bg-light border-0 rounded-end-pill shadow-none" placeholder="Search anything..." style="font-size: 0.82rem;">
                </div>

                <!-- Filter Button -->
                <button class="btn btn-light bg-light rounded-pill px-3 py-2 border-0 fw-medium text-primary d-flex align-items-center gap-1" style="font-size: 0.82rem;">
                    <i class="bi bi-funnel"></i> Filter
                </button>

                <!-- Add New Button (Membuka Halaman atau Modal Tambah) -->
                <a href="{{ route('admin.students.create') }}" class="btn btn-primary rounded-pill px-3 py-2 fw-semibold d-flex align-items-center gap-1" style="font-size: 0.82rem;">
                    <i class="bi bi-plus-lg"></i> Add New
                </a>
            </div>
        </div>

        <!-- ── STUDENT TABLE ── -->
        <div class="table-responsive">
            <table class="table table-borderless align-middle mb-0" style="font-size: 0.85rem;">
                <thead>
                    <tr class="text-muted border-bottom">
                        <th class="fw-semibold pb-3" style="min-width: 220px;">Student</th>
                        <th class="fw-semibold pb-3">NIS</th>
                        <th class="fw-semibold pb-3">Classes</th>
                        <th class="fw-semibold pb-3" style="min-width: 150px;">Attendance</th>
                        <th class="fw-semibold pb-3">Streak</th>
                        <th class="fw-semibold pb-3">Status</th>
                        <th class="fw-semibold pb-3 text-end" style="width: 100px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $item)
                        <tr>
                            <!-- Student Profile -->
                            <td class="py-3">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="bg-primary-subtle text-primary fw-bold rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 38px; height: 38px; font-size: 0.78rem;">
                                        @php
                                            $namaSiswa = $item->siswa->nama ?? $item->name ?? 'User';
                                            $words = explode(' ', $namaSiswa);
                                            $initials = strtoupper(substr($words[0], 0, 1) . (isset($words[1]) ? substr($words[1], 0, 1) : ''));
                                        @endphp
                                        {{ $initials }}
                                    </div>
                                    <div style="line-height: 1.2;">
                                        <div class="fw-bold text-dark">{{ $namaSiswa }}</div>
                                        <small class="text-muted" style="font-size: 0.72rem;">S-{{ str_pad($item->id, 3, '0', STR_PAD_LEFT) }}</small>
                                    </div>
                                </div>
                            </td>

                            <!-- NIS -->
                            <td class="text-muted py-3">{{ $item->siswa->nis ?? '-' }}</td>

                            <!-- Classes -->
                            <td class="text-dark fw-medium py-3">
                                @php
                                    $kls = $item->siswa?->kelas;
                                    $kelasLabel = $kls
                                        ? trim(($kls->kelas ?? '') . ' ' . ($kls->jurusan ?? '') . ' ' . ($kls->nomor_kelas ?? ''))
                                        : null;
                                @endphp
                                {{ $kelasLabel ?: '-' }}
                            </td>

                            <!-- Attendance Progress Bar -->
                            <td class="py-3">
                                <div class="d-flex align-items-center gap-2">
                                    @php
                                        $att = $item->attendance_percentage ?? 0;
                                        $barColor = '#10b981'; // Green
                                        if($att < 60) { $barColor = '#ef4444'; } // Red
                                        elseif($att < 80) { $barColor = '#f97316'; } // Orange
                                    @endphp
                                    <div class="progress flex-grow-1 rounded-pill" style="height: 6px; background-color: #f1f5f9;">
                                        <div class="progress-bar rounded-pill" role="progressbar" 
                                             style="width: {{ $att }}%; background-color: {{ $barColor }};" 
                                             aria-valuenow="{{ $att }}" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <span class="fw-bold text-dark" style="font-size: 0.8rem; min-width: 35px; text-align: right;">{{ $att }}%</span>
                                </div>
                            </td>

                            <!-- Streak -->
                            <td class="py-3">
                                @if($item->streak_days)
                                    <div class="d-flex align-items-center gap-1" style="color: #f97316;">
                                        <i class="bi bi-lightning-charge-fill"></i>
                                        <span class="fw-bold">{{ $item->streak_days }}d</span>
                                    </div>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>

                            <!-- Status Badge -->
                            <td class="py-3">
                                @php
                                    $status = strtolower($item->status ?? 'active');
                                @endphp

                                @if($status === 'active')
                                    <span class="badge rounded-pill fw-medium px-3 py-1 d-inline-flex align-items-center gap-1" style="font-size: 0.72rem; background-color: #ecfdf5; color: #10b981; border: 1px solid #a7f3d0;">
                                        <span class="rounded-circle" style="width: 5px; height: 5px; background-color: #10b981;"></span> Active
                                    </span>
                                @elseif($status === 'warning')
                                    <span class="badge rounded-pill fw-medium px-3 py-1 d-inline-flex align-items-center gap-1" style="font-size: 0.72rem; background-color: #fffbeb; color: #f97316; border: 1px solid #fed7aa;">
                                        <span class="rounded-circle" style="width: 5px; height: 5px; background-color: #f97316;"></span> Warning
                                    </span>
                                @elseif($status === 'critical')
                                    <span class="badge rounded-pill fw-medium px-3 py-1 d-inline-flex align-items-center gap-1" style="font-size: 0.72rem; background-color: #fef2f2; color: #ef4444; border: 1px solid #fecaca;">
                                        <span class="rounded-circle" style="width: 5px; height: 5px; background-color: #ef4444;"></span> Critical
                                    </span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill fw-medium px-3 py-1" style="font-size: 0.72rem;">
                                        &bull; {{ ucfirst($status) }}
                                    </span>
                                @endif
                            </td>

                            <!-- Actions (Edit & Delete Form) -->
                            <td class="text-end py-3">
                                <div class="d-flex align-items-center justify-content-end gap-1">
                                    {{-- Edit Button --}}
                                    <a href="#" class="btn btn-primary bg-primary-subtle text-primary border-0 rounded-3 p-0 d-flex align-items-center justify-content-center" style="width: 28px; height: 28px; font-size: 0.85rem;" title="Edit Student">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>

                                    {{-- Delete Form & Button --}}
                                    <form action="{{ route('admin.students.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data siswa ini?');" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger bg-danger-subtle text-danger border-0 rounded-3 p-0 d-flex align-items-center justify-content-center" style="width: 28px; height: 28px; font-size: 0.85rem;" title="Delete Student">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-5">
                                <i class="bi bi-people fs-1 d-block mb-3"></i>
                                No student records found. Click 'Add New' to add students.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>
@endsection