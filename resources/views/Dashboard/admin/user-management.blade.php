{{-- Sesuaikan path layout jika letak layout admin ada di resources/views/layouts/admin.blade.php --}}
@extends('layouts.admin')

@section('title', 'User Management - Attendly Admin')

@section('content')
<div class="user-management-container p-0">

    <!-- ── HEADER TITLE ── -->
    <div class="mb-4">
        <h4 class="fw-bold text-dark mb-0">User Management</h4>
        <small class="text-muted">Manage Teachers</small>
    </div>

    <!-- ── TABLE CARD WRAPPER ── -->
    <div class="card border-0 rounded-4 p-4 shadow-sm bg-white">
        
        <!-- Table Header & Action Controls -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
            <!-- Left Info -->
            <div class="d-flex align-items-center gap-3">
                <div class="bg-primary-subtle text-primary p-2 rounded-3 d-flex align-items-center justify-content-center" style="width: 42px; height: 42px;">
                    <i class="bi bi-people-fill fs-5"></i>
                </div>
                <div>
                    <h6 class="fw-bold text-dark mb-0">Teachers</h6>
                    <small class="text-muted" style="font-size: 0.78rem;">
                        {{ isset($teachers) ? count($teachers) : 0 }} records
                    </small>
                </div>
            </div>

            <!-- Right Controls (Search, Filter, Add Button) -->
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
                <button class="btn btn-primary rounded-pill px-3 py-2 fw-semibold d-flex align-items-center gap-1" style="font-size: 0.82rem;">
                    <i class="bi bi-plus-lg"></i> Add New
                </button>
            </div>
        </div>

        <!-- ── TABLE DATA ── -->
        <div class="table-responsive">
            {{-- Menggunakan table-borderless agar antar baris tidak ada garis --}}
            <table class="table table-borderless align-middle mb-0" style="font-size: 0.85rem;">
                <thead>
                    {{-- Garis tebal tipis pembatas hanya ada di bawah header tabel --}}
                    <tr class="text-muted border-bottom">
                        <th class="fw-semibold pb-3" style="min-width: 200px;">Teacher</th>
                        <th class="fw-semibold pb-3">NIP</th>
                        <th class="fw-semibold pb-3">Subject</th>
                        <th class="fw-semibold pb-3">Classes</th>
                        <th class="fw-semibold pb-3">Status</th>
                        <th class="fw-semibold pb-3 text-end" style="width: 100px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($teachers as $item)
                        <tr>
                            <!-- Teacher Profile -->
                            <td class="py-3">
                                <div class="d-flex align-items-center gap-2">
                                    <!-- Inisial Nama Otomatis dari Database -->
                                    <div class="bg-primary-subtle text-primary fw-bold rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 38px; height: 38px; font-size: 0.78rem;">
                                        @php
                                            $namaGuru = $item->guru->nama ?? $item->name ?? 'User';
                                            $words = explode(' ', $namaGuru);
                                            $initials = strtoupper(substr($words[0], 0, 1) . (isset($words[1]) ? substr($words[1], 0, 1) : ''));
                                        @endphp
                                        {{ $initials }}
                                    </div>
                                    <div style="line-height: 1.2;">
                                        <div class="fw-bold text-dark">{{ $namaGuru }}</div>
                                        <small class="text-muted" style="font-size: 0.72rem;">{{ $item->code ?? ($item->email ?? '-') }}</small>
                                    </div>
                                </div>
                            </td>

                            <!-- NIP -->
                            <td class="text-muted py-3">{{ $item->guru->nip ?? '-' }}</td>

                            <!-- Subject -->
                            <td class="text-muted py-3">
                                {{ $item->guru?->sesiPresensi->pluck('mapel')->filter()->unique()->implode(', ') ?: '-' }}
                            </td>

                            <!-- Classes -->
                            <td class="text-muted py-3">
                                @php
                                    $kelasList = $item->guru?->sesiPresensi
                                        ->map(fn($s) => $s->kelas
                                            ? trim($s->kelas->kelas . ' ' . $s->kelas->jurusan . ' ' . $s->kelas->nomor_kelas)
                                            : null)
                                        ->filter()
                                        ->unique()
                                        ->implode(', ');
                                @endphp
                                {{ $kelasList ?: '-' }}
                            </td>

                            <!-- Status Badge -->
                            <td class="py-3">
                                @php
                                    $status = strtolower($item->status ?? 'active');
                                @endphp

                                @if($status === 'active')
                                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill fw-medium px-3 py-1" style="font-size: 0.72rem;">
                                        &bull; Active
                                    </span>
                                @elseif($status === 'leave')
                                    <span class="badge rounded-pill fw-medium px-3 py-1" style="font-size: 0.72rem; background-color: #f3e8ff; color: #9333ea; border: 1px solid #e9d5ff;">
                                        &bull; Leave
                                    </span>
                                @elseif($status === 'resigned')
                                    <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle rounded-pill fw-medium px-3 py-1" style="font-size: 0.72rem;">
                                        &bull; Resigned
                                    </span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill fw-medium px-3 py-1" style="font-size: 0.72rem;">
                                        &bull; {{ ucfirst($status) }}
                                    </span>
                                @endif
                            </td>

                            <!-- Actions -->
                            <td class="text-end py-3">
                                <div class="d-flex align-items-center justify-content-end gap-1">
                                    <button class="btn btn-primary bg-primary-subtle text-primary border-0 rounded-3 p-1 px-2 d-flex align-items-center justify-content-center" style="font-size: 0.85rem;" title="Edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    <button class="btn btn-danger bg-danger-subtle text-danger border-0 rounded-3 p-1 px-2 d-flex align-items-center justify-content-center" style="font-size: 0.85rem;" title="Delete">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">No teacher records found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>
@endsection