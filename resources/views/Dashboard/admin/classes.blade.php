@extends('layouts.admin')

@section('title', 'Class Management - Attendly Admin')

@section('content')
<div class="class-management-container p-0">

    <!-- ── HEADER TITLE & SUBTITLE ── -->
    <div class="mb-4">
        <h4 class="fw-bold text-dark mb-0">Class Management</h4>
        <small class="text-muted">Manage Classes</small>
    </div>

    <!-- ── SUMMARY CARDS ── -->
    <div class="row g-3 mb-4">
        <!-- Total Classes -->
        <div class="col-md-4">
            <div class="card border-0 rounded-4 p-3 shadow-sm bg-white d-flex flex-row align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-medium" style="font-size: 0.85rem;">Total Classes</span>
                </div>
                <h3 class="fw-bold text-primary mb-0">{{ $totalClasses }}</h3>
            </div>
        </div>

        <!-- Total Enrolled -->
        <div class="col-md-4">
            <div class="card border-0 rounded-4 p-3 shadow-sm bg-white d-flex flex-row align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-medium" style="font-size: 0.85rem;">Total Enrolled</span>
                </div>
                <h3 class="fw-bold text-success mb-0">{{ $totalEnrolled }}</h3>
            </div>
        </div>

        <!-- Avg Attendance -->
        <div class="col-md-4">
            <div class="card border-0 rounded-4 p-3 shadow-sm bg-white d-flex flex-row align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-medium" style="font-size: 0.85rem;">Avg Attendance</span>
                </div>
                <h3 class="fw-bold text-primary mb-0" style="color: #8b5cf6 !important;">{{ $avgAttendance }}</h3>
            </div>
        </div>
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
        
        <!-- Table Header & Controls -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
            <div class="d-flex align-items-center gap-3">
                <div class="p-2 rounded-3 d-flex align-items-center justify-content-center" style="width: 42px; height: 42px; background-color: #eff6ff; color: #3b82f6;">
                    <i class="bi bi-book-fill fs-5"></i>
                </div>
                <div>
                    <h6 class="fw-bold text-dark mb-0">All Classes</h6>
                    <small class="text-muted" style="font-size: 0.78rem;">
                        {{ $classes->count() }} records
                    </small>
                </div>
            </div>

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

                <!-- Add New Button -->
                <button type="button" class="btn btn-primary rounded-pill px-3 py-2 fw-semibold d-flex align-items-center gap-1" style="font-size: 0.82rem;" data-bs-toggle="modal" data-bs-target="#addClassModal">
                    <i class="bi bi-plus-lg"></i> Add New
                </button>
            </div>
        </div>

        <!-- ── CLASSES TABLE ── -->
        <div class="table-responsive">
            <table class="table table-borderless align-middle mb-0" style="font-size: 0.85rem;">
                <thead>
                    <tr class="text-muted border-bottom">
                        <th class="fw-semibold pb-3" style="min-width: 150px;">Class</th>
                        <th class="fw-semibold pb-3" style="min-width: 220px;">Homeroom Teacher</th>
                        <th class="fw-semibold pb-3" style="min-width: 160px;">Capacity</th>
                        <th class="fw-semibold pb-3" style="min-width: 160px;">AVG Attendance</th>
                        <th class="fw-semibold pb-3">Status</th>
                        <th class="fw-semibold pb-3 text-end" style="width: 100px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($classes as $item)
                        <tr>
                            <!-- Class Name & Code -->
                            <td class="py-3">
                                <div style="line-height: 1.2;">
                                    <div class="fw-bold text-dark">{{ $item->nama_lengkap }}</div>
                                    <small class="text-muted" style="font-size: 0.72rem;">{{ $item->kode_kelas }}</small>
                                </div>
                            </td>

                            <!-- Homeroom Teacher -->
                            <td class="py-3">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="bg-primary-subtle text-primary fw-bold rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 32px; height: 32px; font-size: 0.75rem;">
                                        AD
                                    </div>
                                    <span class="fw-medium text-dark">-</span>
                                </div>
                            </td>

                            <!-- Capacity Progress Bar (jumlah siswa asli dari tabel siswas) -->
                            <td class="py-3">
                                @php
                                    $jumlahSiswa = $item->siswa_count ?? 0;
                                    $kapasitas   = 36;
                                    $persenKapasitas = $kapasitas > 0 ? min(100, round(($jumlahSiswa / $kapasitas) * 100)) : 0;
                                @endphp
                                <div class="d-flex align-items-center gap-2">
                                    <div class="progress flex-grow-1 rounded-pill" style="height: 6px; background-color: #f1f5f9;">
                                        <div class="progress-bar rounded-pill bg-primary" role="progressbar" style="width: {{ $persenKapasitas }}%;"></div>
                                    </div>
                                    <span class="text-muted" style="font-size: 0.78rem;">{{ $jumlahSiswa }}/{{ $kapasitas }}</span>
                                </div>
                            </td>

                            <!-- AVG Attendance Bar -->
                            <td class="py-3">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="progress flex-grow-1 rounded-pill" style="height: 6px; background-color: #f1f5f9;">
                                        <div class="progress-bar rounded-pill bg-success" role="progressbar" style="width: 0%;"></div>
                                    </div>
                                    <span class="fw-bold text-success" style="font-size: 0.78rem; min-width: 35px; text-align: right;">0%</span>
                                </div>
                            </td>

                            <!-- Status Badge -->
                            <td class="py-3">
                                <span class="badge rounded-pill fw-medium px-3 py-1 d-inline-flex align-items-center gap-1" style="font-size: 0.72rem; background-color: #ecfdf5; color: #10b981; border: 1px solid #a7f3d0;">
                                    <span class="rounded-circle" style="width: 5px; height: 5px; background-color: #10b981;"></span> Active
                                </span>
                            </td>

                            <!-- Actions -->
                            <td class="text-end py-3">
                                <div class="d-flex align-items-center justify-content-end gap-1">
                                    <button class="btn btn-primary bg-primary-subtle text-primary border-0 rounded-3 p-0 d-flex align-items-center justify-content-center" style="width: 28px; height: 28px; font-size: 0.85rem;" title="Edit Class">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    <form action="{{ route('admin.classes.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kelas ini?');" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger bg-danger-subtle text-danger border-0 rounded-3 p-0 d-flex align-items-center justify-content-center" style="width: 28px; height: 28px; font-size: 0.85rem;" title="Delete Class">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-5">
                                Belum ada data kelas di database. Klik tombol "Add New" untuk menambahkan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>

<!-- ── MODAL ADD NEW CLASS ── -->
<div class="modal fade" id="addClassModal" tabindex="-1" aria-labelledby="addClassModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-dark" id="addClassModalLabel">Add New Class</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.classes.store') }}" method="POST">
                @csrf
                <div class="modal-body py-3">
                    <div class="mb-3">
                        <label class="form-label fw-medium small">Tingkat Kelas</label>
                        <select name="kelas" class="form-select rounded-3" required>
                            <option value="" selected disabled>-- Pilih Tingkat Kelas --</option>
                            <option value="10">10</option>
                            <option value="11">11</option>
                            <option value="12">12</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-medium small">Jurusan</label>
                        <input type="text" name="jurusan" class="form-control rounded-3" placeholder="Contoh: RPL, TKJ, AKL" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-medium small">Nomor Kelas</label>
                        <input type="number" name="nomor_kelas" class="form-control rounded-3" placeholder="Contoh: 1, 2, 3" min="1" required>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection