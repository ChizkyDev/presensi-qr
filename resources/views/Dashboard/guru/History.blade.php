@extends('layouts.guru')

@section('title', 'Session History - Attendly')

@section('content')
<div class="teacher-overview p-0">

    <!-- ── HEADER TITLE ── -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1">Leave Approvals</h3>
            <p class="text-muted mb-0" style="font-size: 0.88rem;">Review Students Request & Session Records</p>
        </div>
        <div>
            <button class="btn btn-primary rounded-pill px-3 py-2 shadow-sm border-0 text-white fw-medium d-flex align-items-center gap-2" style="font-size: 0.85rem; background-color: #2563eb;">
                <i class="bi bi-calendar-event text-white"></i> 29 Agustus 2026
                <i class="bi bi-chevron-down text-white ms-1" style="font-size: 0.75rem;"></i>
            </button>
        </div>
    </div>

    <!-- ── TOP STAT CARDS (Dengan aksen garis warna di atas) ── -->
    <div class="row g-3 mb-4">
        <!-- Sessions Run -->
        <div class="col-md-3">
            <div class="card border-0 rounded-4 p-3 shadow-sm bg-white h-100 position-relative overflow-hidden">
                <div class="position-absolute top-0 start-0 w-100" style="height: 4px; background-color: #3b82f6;"></div>
                <span class="text-muted fw-medium mb-1 mt-1 d-block" style="font-size: 0.82rem;">Sessions Run</span>
                {{-- UBAH: warna angka lewat style, bukan text-dark + text-primary --}}
                <h2 class="fw-bold mb-0" style="color: #2563eb;">{{ $totalSessionsRun ?? 0 }}</h2>
            </div>
        </div>

        <!-- Total Present -->
        <div class="col-md-3">
            <div class="card border-0 rounded-4 p-3 shadow-sm bg-white h-100 position-relative overflow-hidden">
                <div class="position-absolute top-0 start-0 w-100" style="height: 4px; background-color: #10b981;"></div>
                <span class="text-muted fw-medium mb-1 mt-1 d-block" style="font-size: 0.82rem;">Total Present</span>
                <h2 class="fw-bold mb-0" style="color: #10b981;">{{ number_format($totalPresent ?? 0) }}</h2>
            </div>
        </div>

        <!-- Total Late -->
        <div class="col-md-3">
            <div class="card border-0 rounded-4 p-3 shadow-sm bg-white h-100 position-relative overflow-hidden">
                <div class="position-absolute top-0 start-0 w-100" style="height: 4px; background-color: #f97316;"></div>
                <span class="text-muted fw-medium mb-1 mt-1 d-block" style="font-size: 0.82rem;">Total Late</span>
                <h2 class="fw-bold mb-0" style="color: #f97316;">{{ number_format($totalLate ?? 0) }}</h2>
            </div>
        </div>

        <!-- Total Absent -->
        <div class="col-md-3">
            <div class="card border-0 rounded-4 p-3 shadow-sm bg-white h-100 position-relative overflow-hidden">
                <div class="position-absolute top-0 start-0 w-100" style="height: 4px; background-color: #ef4444;"></div>
                <span class="text-muted fw-medium mb-1 mt-1 d-block" style="font-size: 0.82rem;">Total Absent</span>
                <h2 class="fw-bold mb-0" style="color: #ef4444;">{{ number_format($totalAbsent ?? 0) }}</h2>
            </div>
        </div>
    </div>

    <!-- ── TABLE SECTION: Session Records ── -->
    <div class="card border-0 rounded-4 p-4 shadow-sm bg-white">
        <!-- Header Section: Judul dan Filter Sejajar Horizontal -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3 mb-4">
            <div class="d-flex align-items-center gap-2">
                <div class="p-2 rounded-3 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; background-color: #eff6ff; color: #3b82f6;">
                    <i class="bi bi-clock-history fs-6"></i>
                </div>
                <h6 class="fw-bold text-dark mb-0">Session Records</h6>
            </div>

            <!-- Filter & Search Bar Sejajar -->
            <div class="d-flex align-items-center gap-2 flex-wrap justify-content-end">
                <!-- Search input -->
                <div class="input-group input-group-sm" style="max-width: 200px;">
                    <span class="input-group-text bg-light border-0 ps-3 text-muted"><i class="bi bi-search"></i></span>
                    <input type="text" class="form-control bg-light border-0 fs-7 shadow-none" placeholder="Search anything...">
                </div>

                <!-- Tombol All Classes (Warna Biru Aktif) -->
                <button class="btn btn-primary rounded-pill px-3 py-1.5 text-white fw-medium d-flex align-items-center gap-2 border-0 shadow-sm" style="font-size: 0.8rem; background-color: #2563eb;">
                    <i class="bi bi-display"></i> All Classes <i class="bi bi-chevron-down ms-1" style="font-size: 0.7rem;"></i>
                </button>

                <!-- Tombol Tanggal (Warna Biru) -->
                <button class="btn btn-primary rounded-pill px-3 py-1.5 text-white fw-medium d-flex align-items-center gap-2 border-0 shadow-sm" style="font-size: 0.8rem; background-color: #2563eb;">
                    <i class="bi bi-calendar3"></i> 29/08/2026
                </button>
            </div>
        </div>

        <!-- Table Data -->
        <div class="table-responsive">
            <table class="table align-middle mb-0" style="font-size: 0.85rem;">
                <thead class="text-muted border-bottom" style="font-size: 0.78rem;">
                    <tr>
                        <th class="fw-semibold pb-3 ps-2">Date</th>
                        <th class="fw-semibold pb-3">Class</th>
                        <th class="fw-semibold pb-3">Subject</th>
                        <th class="fw-semibold pb-3 text-center">Present</th>
                        <th class="fw-semibold pb-3 text-center">Late</th>
                        <th class="fw-semibold pb-3 text-center">Absent</th>
                        <th class="fw-semibold pb-3">AVG Attendance</th>
                        <th class="fw-semibold pb-3 text-end pe-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sessions ?? [] as $session)
                        @php
                            $presentCount = $session->presensis->where('status', 'hadir')->count();
                            $lateCount = $session->presensis->where('status', 'telat')->count();
                            $absentCount = $session->presensis->where('status', 'alpha')->count();
                            $totalStudentsInClass = max(1, $presentCount + $lateCount + $absentCount);
                            $avgPercentage = round((($presentCount + $lateCount) / $totalStudentsInClass) * 100);
                        @endphp
                        <tr>
                            <td class="ps-2 text-dark fw-medium">{{ $session->created_at->format('d-m-Y') }}</td>
                            <td>
                                <span class="badge bg-light text-dark fw-semibold px-2 py-1 border">
                                    {{ $session->kelas->kelas ?? '-' }} {{ $session->kelas->jurusan ?? '' }}
                                </span>
                            </td>
                            <td class="fw-bold text-dark">{{ $session->mapel }}</td>

                            {{-- UBAH: badge Present / Late / Absent dengan warna eksplisit sesuai desain --}}
                            <td class="text-center">
                                <span class="badge rounded-2 px-2 py-1 fw-bold d-inline-block" style="background-color: #dcfce7; color: #16a34a; min-width: 30px;">{{ $presentCount }}</span>
                            </td>
                            <td class="text-center">
                                <span class="badge rounded-2 px-2 py-1 fw-bold d-inline-block" style="background-color: #ffedd5; color: #ea580c; min-width: 30px;">{{ $lateCount }}</span>
                            </td>
                            <td class="text-center">
                                <span class="badge rounded-2 px-2 py-1 fw-bold d-inline-block" style="background-color: #fee2e2; color: #dc2626; min-width: 30px;">{{ $absentCount }}</span>
                            </td>

                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="progress flex-grow-1" style="height: 6px; background-color: #f1f5f9;">
                                        <div class="progress-bar rounded-pill" role="progressbar" style="width: {{ $avgPercentage }}%; background-color: #10b981;" aria-valuenow="{{ $avgPercentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <span class="fw-bold text-dark" style="font-size: 0.78rem; min-width: 32px;">{{ $avgPercentage }}%</span>
                                </div>
                            </td>
                            <td class="text-end pe-2">
                                <a href="#" class="text-primary text-decoration-none fw-semibold" style="font-size: 0.82rem;">Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-5">
                                <i class="bi bi-journal-x fs-2 d-block mb-2 text-secondary"></i>
                                <p class="mb-0 fw-medium">Belum ada riwayat sesi presensi yang tercatat.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection