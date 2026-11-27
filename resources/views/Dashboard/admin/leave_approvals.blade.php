@extends('layouts.admin')

@section('title', 'Leave Approvals - Attendly Admin')

@section('content')
<div class="leave-approvals-container p-0" style="max-width: 1320px; margin: 0 auto;">

    <!-- ── HEADER TITLE & DATE ── -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-4 px-1">
        <div>
            <h4 class="fw-bold text-dark mb-1" style="font-size: 1.5rem;">Leave Approvals</h4>
            <p class="text-muted mb-0" style="font-size: 0.85rem;">Review Students Request</p>
        </div>
        <div>
            <button type="button" class="btn btn-white bg-white rounded-pill px-3 py-2 shadow-sm border-0 text-primary fw-medium d-flex align-items-center gap-2" style="font-size: 0.85rem;">
                <i class="bi bi-calendar3"></i> {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('d F Y') }}
            </button>
        </div>
    </div>

    <!-- ── ALERT FLASH MESSAGE ── -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- ── SUMMARY CARDS ── -->
    <div class="row g-3 mb-4">
        <!-- Pending Review -->
        <div class="col-md-4">
            <div class="card border-0 rounded-4 p-3 shadow-sm bg-white d-flex flex-row align-items-center gap-3">
                <div class="rounded-3 d-flex align-items-center justify-content-center p-2" style="background-color: #fff7ed; color: #f97316; width: 48px; height: 48px;">
                    <i class="bi bi-clock-history fs-4"></i>
                </div>
                <div>
                    <h3 class="fw-bold mb-0" style="color: #f97316 !important;">{{ $pendingCount ?? 0 }}</h3>
                    <span class="text-muted fw-medium" style="font-size: 0.82rem;">Pending Review</span>
                </div>
            </div>
        </div>

        <!-- Approved -->
        <div class="col-md-4">
            <div class="card border-0 rounded-4 p-3 shadow-sm bg-white d-flex flex-row align-items-center gap-3">
                <div class="rounded-3 d-flex align-items-center justify-content-center p-2" style="background-color: #ecfdf5; color: #10b981; width: 48px; height: 48px;">
                    <i class="bi bi-check-circle fs-4"></i>
                </div>
                <div>
                    <h3 class="fw-bold text-success mb-0">{{ $approvedCount ?? 0 }}</h3>
                    <span class="text-muted fw-medium" style="font-size: 0.82rem;">Approved</span>
                </div>
            </div>
        </div>

        <!-- Rejected -->
        <div class="col-md-4">
            <div class="card border-0 rounded-4 p-3 shadow-sm bg-white d-flex flex-row align-items-center gap-3">
                <div class="rounded-3 d-flex align-items-center justify-content-center p-2" style="background-color: #fef2f2; color: #ef4444; width: 48px; height: 48px;">
                    <i class="bi bi-slash-circle fs-4"></i>
                </div>
                <div>
                    <h3 class="fw-bold text-danger mb-0">{{ $rejectedCount ?? 0 }}</h3>
                    <span class="text-muted fw-medium" style="font-size: 0.82rem;">Rejected</span>
                </div>
            </div>
        </div>
    </div>


    <!-- ── LIST KARTU SESUAI  ── -->
    <div class="d-flex flex-column gap-3">
        @forelse($requests ?? collect() as $index => $item)
            @php
                $namaSiswa = $item->siswa->nama ?? 'Siswa';
                $words = preg_split('/\s+/', trim($namaSiswa)) ?: [];
                $initials = strtoupper(
                    (isset($words[0]) ? mb_substr($words[0], 0, 1) : '') .
                    (isset($words[1]) ? mb_substr($words[1], 0, 1) : (isset($words[0]) ? mb_substr($words[0], 1, 1) : 'S'))
                );

                // Warna variasi avatar inisial
                $avatarColors = [
                    'background-color: #eff6ff; color: #3b82f6;', // Biru lembut
                    'background-color: #fff7ed; color: #f97316;', // Oranye lembut
                    'background-color: #f3e8ff; color: #a855f7;', // Ungu lembut
                    'background-color: #ecfdf5; color: #10b981;', // Hijau lembut
                ];
                $selectedAvatarStyle = $avatarColors[$index % count($avatarColors)];

                $statusApprove = $item->status_approve ?? 'pending';
                $isApproved = in_array($statusApprove, ['diterima', 'approved'], true);
                $isRejected = in_array($statusApprove, ['ditolak', 'rejected'], true);

                // Warna border atas/stroke sesuai status
                $borderColor = '#e2e8f0';
                if ($statusApprove === 'pending') {
                    $borderColor = '#fed7aa'; // Oranye muda di atas
                } elseif ($isApproved) {
                    $borderColor = '#a7f3d0'; // Hijau muda di atas
                } elseif ($isRejected) {
                    $borderColor = '#fecaca'; // Merah muda di atas
                }

                $namaKelas = $item->siswa?->kelas?->nama_lengkap
                    ?? $item->siswa?->kelas?->nama_kelas
                    ?? $item->siswa?->kelas?->nama
                    ?? '-';
            @endphp

            <!-- Card dengan Border Aksen di Bagian Atas dan Background Putih -->
            <div class="card rounded-4 p-3 d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 shadow-sm bg-white" style="border: 1px solid #f1f5f9; border-top: 3px solid {{ $borderColor }};">
                
                <!-- Left: Avatar Inisial & Info Siswa + Garis Pemisah Vertikal -->
                <div class="d-flex align-items-center gap-3" style="min-width: 230px;">
                    <div class="rounded-3 d-flex align-items-center justify-content-center fw-bold flex-shrink-0 shadow-sm" style="width: 44px; height: 44px; font-size: 0.9rem; {{ $selectedAvatarStyle }}">
                        {{ $initials }}
                    </div>
                    <div>
                        <h6 class="fw-bold text-dark mb-0" style="font-size: 0.88rem;">{{ $namaSiswa }}</h6>
                        <span class="text-muted" style="font-size: 0.76rem;">{{ $namaKelas }}</span>
                    </div>
                    <!-- Garis Pemisah Vertikal (|) -->
                    <div class="vr text-muted opacity-25 ms-2 d-none d-md-block" style="height: 32px;"></div>
                </div>

                <!-- Middle Left: Jenis Badge (Sick / Permitted) & Durasi -->
                <div class="d-flex align-items-center gap-2">
                    @if($item->jenis === 'sakit')
                        <span class="badge rounded-pill fw-medium px-3 py-1" style="background-color: #eff6ff; color: #3b82f6; font-size: 0.73rem;">Sick</span>
                    @elseif($item->jenis === 'izin')
                        <span class="badge rounded-pill fw-medium px-3 py-1" style="background-color: #f3e8ff; color: #a855f7; font-size: 0.73rem;">Permitted</span>
                    @else
                        <span class="badge rounded-pill fw-medium px-3 py-1" style="background-color: #fef2f2; color: #ef4444; font-size: 0.73rem;">Alpha</span>
                    @endif
                    <span class="text-muted" style="font-size: 0.76rem;">1d</span>
                </div>

                <!-- Middle Right: Tanggal & Keterangan -->
                <div class="d-flex flex-column" style="min-width: 180px;">
                    <div class="d-flex align-items-center gap-1 text-dark fw-medium" style="font-size: 0.8rem;">
                        <i class="bi bi-calendar-event text-muted" style="font-size: 0.75rem;"></i> 
                        {{ $item->tanggal ? \Carbon\Carbon::parse($item->tanggal)->format('d M') : '-' }}
                    </div>
                    <span class="text-muted" style="font-size: 0.73rem;">{{ $item->keterangan ?? '-' }}</span>
                </div>

                <!-- Right Side: Status Badge, Doc & Action Buttons (Approve & Rejected Style) -->
                <div class="d-flex align-items-center gap-3 ms-md-auto flex-wrap">
                    
                    <!-- Status Badge -->
                    @if($statusApprove === 'pending')
                        <span class="badge rounded-pill fw-medium px-3 py-1 d-inline-flex align-items-center" style="background-color: #fff7ed; color: #f97316; border: 1px solid #ffedd5; font-size: 0.73rem;">
- Pending
                        </span>
                    @elseif($isApproved)
                        <span class="badge rounded-pill fw-medium px-3 py-1 d-inline-flex align-items-center" style="background-color: #ecfdf5; color: #10b981; border: 1px solid #a7f3d0; font-size: 0.73rem;">
- Approved
                        </span>
                    @else
                        <span class="badge rounded-pill fw-medium px-3 py-1 d-inline-flex align-items-center" style="background-color: #fef2f2; color: #ef4444; border: 1px solid #fecaca; font-size: 0.73rem;">
- Rejected
                        </span>
                    @endif

                    <!-- Attachment link (bukti_file) -->
                    <a href="{{ $item->bukti_file ? asset('storage/' . $item->bukti_file) : '#' }}" @if($item->bukti_file) target="_blank" @endif class="text-decoration-none text-muted d-flex align-items-center gap-1" style="font-size: 0.76rem;" title="View Document">
                        <i class="bi bi-file-earmark-text"></i> doc
                    </a>

                    <!-- Action Buttons Persis Seperti Figma (Tombol Biru Approve & Tombol Abu-abu Rejected) -->
                    <div class="d-flex align-items-center gap-2">
                        @if($statusApprove === 'pending')
                            <!-- Tombol Approve (Biru Solid) -->
                            <form action="{{ route('admin.leave-approvals.update-status', $item->id) }}" method="POST" class="d-inline">
                                @csrf
                                <input type="hidden" name="status_approve" value="diterima">
                                <button type="submit" class="btn btn-primary btn-sm rounded-pill px-3 py-1 text-white fw-medium border-0 d-flex align-items-center gap-1 shadow-sm" style="background-color: #2563eb; font-size: 0.73rem;">
                                    <i class="bi bi-check-circle"></i> Approve
                                </button>
                            </form>

                            <!-- Tombol Rejected (Light/Transparan) -->
                            <form action="{{ route('admin.leave-approvals.update-status', $item->id) }}" method="POST" class="d-inline">
                                @csrf
                                <input type="hidden" name="status_approve" value="ditolak">
                                <button type="submit" class="btn btn-light btn-sm rounded-pill px-3 py-1 text-primary fw-medium border" style="background-color: #eff6ff; border-color: #dbeafe !important; font-size: 0.73rem;">
                                    <i class="bi bi-x-circle"></i> Rejected
                                </button>
                            </form>
                        @else
                            <form action="{{ route('admin.leave-approvals.update-status', $item->id) }}" method="POST" class="d-inline">
                                @csrf
                                <input type="hidden" name="status_approve" value="pending">
                                <button type="submit" class="btn btn-light btn-sm rounded-pill px-3 py-1 text-muted fw-medium border" style="font-size: 0.73rem;">
                                    Reset
                                </button>
                            </form>
                        @endif
                    </div>

                </div>

            </div>
        @empty
            <div class="card border-0 rounded-4 p-5 text-center bg-white shadow-sm">
                <p class="text-muted mb-0" style="font-size: 0.85rem;">Belum ada pengajuan izin/sakit di database.</p>
            </div>
        @endforelse
    </div>

</div>
@endsection