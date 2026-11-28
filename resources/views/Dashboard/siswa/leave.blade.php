@extends('layouts.siswa')

@section('title', 'Submit Leave - Attendly')

@section('content')
@php
    $types = [
        'sakit'   => ['title' => 'Sick Leave',       'sub' => 'Sakit',            'desc' => 'Medical absence',   'icon' => 'bi-heart-pulse'],
        'izin'    => ['title' => 'Personal Reason',  'sub' => 'Izin',             'desc' => 'Permission leave',  'icon' => 'bi-calendar-event'],
        'darurat' => ['title' => 'Emergency',        'sub' => 'Keadaan Mendesak', 'desc' => 'Urgent absence',    'icon' => 'bi-exclamation-circle'],
    ];
    $selectedType = old('jenis', 'sakit');
    $relations = ['Father', 'Mother', 'Guardian', 'Sibling', 'Relative', 'Other'];
    $statusStyle = [
        'pending'  => ['label' => 'Pending',  'bg' => '#fff7ed', 'color' => '#ea580c'],
        'diterima' => ['label' => 'Approved', 'bg' => '#ecfdf5', 'color' => '#10b981'],
        'ditolak'  => ['label' => 'Rejected', 'bg' => '#fef2f2', 'color' => '#ef4444'],
    ];
@endphp

<div class="container-fluid px-0">

    <!-- ── HEADER + KUOTA ── -->
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-start gap-3 mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1">Submit Leave Request</h3>
            <p class="text-muted mb-0" style="font-size: 0.85rem;">Absence Notification</p>
        </div>

        <div class="card border-0 rounded-4 shadow-sm bg-white px-4 py-3">
            <div class="d-flex gap-4 text-center">
                <div>
                    <div class="fw-bold fs-4" style="color: #2563eb; line-height: 1.1;">{{ sprintf('%02d', $quota) }}</div>
                    <small class="text-muted" style="font-size: 0.7rem;">Total Allowed</small>
                </div>
                <div>
                    <div class="fw-bold fs-4" style="color: #f97316; line-height: 1.1;">{{ sprintf('%02d', $daysUsed) }}</div>
                    <small class="text-muted" style="font-size: 0.7rem;">Days Used</small>
                </div>
                <div>
                    <div class="fw-bold fs-4" style="color: #10b981; line-height: 1.1;">{{ sprintf('%02d', $remaining) }}</div>
                    <small class="text-muted" style="font-size: 0.7rem;">Remaining</small>
                </div>
            </div>
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

    <div class="row g-4">

        <!-- ── KOLOM KIRI: FORM ── -->
        <div class="col-lg-8">
            <form action="{{ route('siswa.leave.store') }}" method="POST" enctype="multipart/form-data" id="leaveForm">
                @csrf

                <div class="card border-0 rounded-4 shadow-sm bg-white p-4">

                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div class="rounded-3 d-flex align-items-center justify-content-center" style="width: 38px; height: 38px; background-color: #eff6ff; color: #3b82f6;">
                            <i class="bi bi-file-earmark-text fs-5"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold text-dark mb-0">Submit Leave</h6>
                            <small class="text-muted" style="font-size: 0.75rem;">Fill in the form below</small>
                        </div>
                    </div>

                    <!-- 01 · JENIS IZIN -->
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="fw-bold text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.04em;"><span class="text-primary me-1">01</span> Choose leave type <span class="text-danger">*</span></span>
                        <small class="text-muted" style="font-size: 0.7rem;">Select one category</small>
                    </div>
                    <div class="row g-3 mb-4">
                        @foreach($types as $value => $t)
                        <div class="col-md-4">
                            <label class="type-option w-100 h-100 mb-0" style="cursor: pointer;">
                                <input type="radio" name="jenis" value="{{ $value }}" class="visually-hidden" {{ $selectedType === $value ? 'checked' : '' }}>
                                <div class="type-body rounded-3 p-3 h-100 position-relative">
                                    <span class="type-radio position-absolute"></span>
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <i class="bi {{ $t['icon'] }} text-primary"></i>
                                        <span class="fw-bold text-dark" style="font-size: 0.82rem;">{{ $t['title'] }}</span>
                                    </div>
                                    <div class="text-primary fw-semibold" style="font-size: 0.72rem;">{{ $t['sub'] }}</div>
                                    <div class="text-muted" style="font-size: 0.7rem;">{{ $t['desc'] }}</div>
                                </div>
                            </label>
                        </div>
                        @endforeach
                    </div>

                    <!-- 02 · TANGGAL & DURASI -->
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="fw-bold text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.04em;"><span class="text-primary me-1">02</span> Date &amp; duration <span class="text-danger">*</span></span>
                        <span class="badge rounded-pill px-3 py-2 fw-semibold" style="background-color: #eff6ff; color: #2563eb; font-size: 0.7rem;">
                            TOTAL DAYS · <span id="totalDays">-</span>
                        </span>
                    </div>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label text-muted mb-1" style="font-size: 0.72rem;">Start Date</label>
                            <input type="date" name="tanggal" id="startDate" class="form-control rounded-3 bg-light border-0 py-2 @error('tanggal') is-invalid @enderror"
                                   value="{{ old('tanggal') }}" min="{{ now()->subDays(7)->toDateString() }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted mb-1" style="font-size: 0.72rem;">End Date</label>
                            <input type="date" name="tanggal_selesai" id="endDate" class="form-control rounded-3 bg-light border-0 py-2 @error('tanggal_selesai') is-invalid @enderror"
                                   value="{{ old('tanggal_selesai') }}" min="{{ now()->subDays(7)->toDateString() }}" required>
                        </div>
                    </div>

                    <!-- 03 · ALASAN -->
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="fw-bold text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.04em;"><span class="text-primary me-1">03</span> Detailed reason <span class="text-danger">*</span></span>
                        <small class="text-muted" style="font-size: 0.7rem;"><span id="reasonCount">0</span> / 500</small>
                    </div>
                    <textarea name="keterangan" id="reason" rows="4" maxlength="500" required
                              class="form-control rounded-3 bg-light border-0 @error('keterangan') is-invalid @enderror"
                              placeholder="Please explain why you need to be absent, including relevant details for your teacher...">{{ old('keterangan') }}</textarea>
                    <div class="d-flex flex-wrap align-items-center gap-2 mt-2 mb-4">
                        <small class="text-muted" style="font-size: 0.7rem;">Suggestions</small>
                        @foreach(['Doctor Visit', 'Family Event', 'Medical Need', 'Urgent Matter'] as $chip)
                            <button type="button" class="btn btn-sm rounded-2 reason-chip" data-text="{{ $chip }}"
                                    style="background-color: #eff6ff; color: #2563eb; font-size: 0.68rem; border: 1px solid #bfdbfe;">+ {{ $chip }}</button>
                        @endforeach
                    </div>

                    <!-- 04 · DOKUMEN -->
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="fw-bold text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.04em;"><span class="text-primary me-1">04</span> Supporting document <span class="text-danger">*</span></span>
                        <small class="text-muted" style="font-size: 0.7rem;">MAX 5 MB</small>
                    </div>
                    <div id="dropzone" class="rounded-3 text-center p-4 mb-1" style="border: 2px dashed #bfdbfe; background-color: #f8fbff; cursor: pointer;">
                        <div class="rounded-3 d-inline-flex align-items-center justify-content-center mb-2" style="width: 42px; height: 42px; background-color: #eff6ff; color: #2563eb;">
                            <i class="bi bi-upload fs-5"></i>
                        </div>
                        <div class="text-dark" style="font-size: 0.82rem;">Drop your document here or <span class="text-primary fw-semibold">browse files</span></div>
                        <div class="text-muted mt-1" style="font-size: 0.68rem; letter-spacing: 0.03em;">PDF, JPG, PNG · MEDICAL CERTIFICATE / SUPPORTING PROOF</div>
                        <div id="fileInfo" class="mt-2 fw-semibold text-success" style="font-size: 0.78rem;"></div>
                    </div>
                    <input type="file" name="bukti_file" id="bukti_file" accept=".pdf,.jpg,.jpeg,.png" class="d-none">
                    <div id="fileError" class="text-danger mb-4" style="font-size: 0.75rem;"></div>

                    <!-- 05 · KONTAK DARURAT -->
                    <div class="mb-2">
                        <span class="fw-bold text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.04em;"><span class="text-primary me-1">05</span> Emergency contact <span class="text-danger">*</span></span>
                    </div>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label text-muted mb-1" style="font-size: 0.72rem;">Contact Number</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0 text-muted"><i class="bi bi-telephone"></i></span>
                                <input type="text" name="kontak_darurat" class="form-control bg-light border-0 py-2 @error('kontak_darurat') is-invalid @enderror"
                                       value="{{ old('kontak_darurat') }}" placeholder="+62 812 3456 7890" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted mb-1" style="font-size: 0.72rem;">Relation to Student</label>
                            <select name="hubungan_kontak" class="form-select bg-light border-0 py-2 @error('hubungan_kontak') is-invalid @enderror" required>
                                <option value="" disabled {{ old('hubungan_kontak') ? '' : 'selected' }}>Select relation</option>
                                @foreach($relations as $rel)
                                    <option value="{{ $rel }}" {{ old('hubungan_kontak') === $rel ? 'selected' : '' }}>{{ $rel }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary rounded-pill px-4 py-2 fw-semibold shadow-sm border-0 d-flex align-items-center gap-2" style="background-color: #2563eb; font-size: 0.85rem;">
                            Submit Application <i class="bi bi-arrow-right"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- ── KOLOM KANAN: RIWAYAT & INFO ── -->
        <div class="col-lg-4 d-flex flex-column gap-4">

            <div class="card border-0 rounded-4 shadow-sm bg-white p-4">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="rounded-3 d-flex align-items-center justify-content-center" style="width: 34px; height: 34px; background-color: #eff6ff; color: #3b82f6;">
                        <i class="bi bi-clock-history"></i>
                    </div>
                    <h6 class="fw-bold text-dark mb-0">Request History</h6>
                </div>

                <div class="d-flex flex-column gap-2">
                    @forelse($history as $req)
                        @php
                            $st    = $statusStyle[$req->status_approve] ?? $statusStyle['pending'];
                            $start = \Carbon\Carbon::parse($req->tanggal);
                            $end   = $req->tanggal_selesai ? \Carbon\Carbon::parse($req->tanggal_selesai) : $start;
                            $range = $start->isSameDay($end)
                                ? $start->format('M d, Y')
                                : $start->format('M d') . ' – ' . $end->format('M d, Y');
                        @endphp
                        <div class="rounded-3 p-3" style="border: 1px solid #e2e8f0;">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <small class="text-muted" style="font-size: 0.68rem;">{{ $req->kode }}</small>
                                <span class="badge rounded-pill px-2 py-1 fw-semibold" style="background-color: {{ $st['bg'] }}; color: {{ $st['color'] }}; font-size: 0.66rem;">• {{ $st['label'] }}</span>
                            </div>
                            <div class="fw-bold text-dark" style="font-size: 0.85rem;">{{ $req->jenis_label }}</div>
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted" style="font-size: 0.72rem;">{{ $range }} · {{ $req->jumlah_hari }} day{{ $req->jumlah_hari > 1 ? 's' : '' }}</small>
                                @if($req->bukti_file)
                                    <a href="{{ route('leave.document', $req->id) }}" target="_blank" class="text-primary" style="font-size: 0.7rem;" title="Lihat dokumen"><i class="bi bi-paperclip"></i></a>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="text-muted small mb-0">Belum ada pengajuan izin.</p>
                    @endforelse
                </div>

                @if($showAll)
                    <div class="mt-3">{{ $history->links() }}</div>
                    <a href="{{ route('siswa.leave') }}" class="text-center text-primary fw-semibold text-decoration-none mt-3 d-block" style="font-size: 0.72rem; letter-spacing: 0.04em;">SHOW LATEST ONLY</a>
                @else
                    <a href="{{ route('siswa.leave', ['all' => 1]) }}" class="text-center text-primary fw-semibold text-decoration-none mt-3 d-block" style="font-size: 0.72rem; letter-spacing: 0.04em;">VIEW ALL REQUESTS</a>
                @endif
            </div>

            <div class="card border-0 rounded-4 shadow-sm bg-white p-4">
                <div class="d-flex gap-3 align-items-start">
                    <i class="bi bi-shield-check text-primary fs-5"></i>
                    <div>
                        <h6 class="fw-bold text-dark mb-1" style="font-size: 0.9rem;">Before You Submit</h6>
                        <p class="text-muted mb-0" style="font-size: 0.75rem;">
                            Requests longer than 3 days require additional admin verification. Once submitted, a request cannot be edited.
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@push('js')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const startEl = document.getElementById('startDate');
        const endEl = document.getElementById('endDate');
        const totalEl = document.getElementById('totalDays');

        // Hitung durasi (inklusif) dan jaga agar tanggal selesai tidak sebelum tanggal mulai
        function updateDuration() {
            if (startEl.value) endEl.min = startEl.value;
            if (startEl.value && endEl.value) {
                const s = new Date(startEl.value), e = new Date(endEl.value);
                const days = Math.round((e - s) / 86400000) + 1;
                totalEl.textContent = days > 0 ? days : '-';
            } else {
                totalEl.textContent = '-';
            }
        }
        startEl.addEventListener('change', updateDuration);
        endEl.addEventListener('change', updateDuration);
        updateDuration();

        // Penghitung karakter + chip saran
        const reason = document.getElementById('reason');
        const counter = document.getElementById('reasonCount');
        function updateCount() { counter.textContent = reason.value.length; }
        reason.addEventListener('input', updateCount);
        updateCount();

        document.querySelectorAll('.reason-chip').forEach(btn => {
            btn.addEventListener('click', function () {
                const text = this.dataset.text;
                const next = reason.value.trim() ? reason.value.trim() + ' ' + text + '.' : text + '.';
                reason.value = next.slice(0, 500);
                updateCount();
                reason.focus();
            });
        });

        // Dropzone dokumen
        const dropzone = document.getElementById('dropzone');
        const fileInput = document.getElementById('bukti_file');
        const fileInfo = document.getElementById('fileInfo');
        const fileError = document.getElementById('fileError');
        const allowed = ['pdf', 'jpg', 'jpeg', 'png'];

        function showFile(file) {
            fileError.textContent = '';
            fileInfo.textContent = '';
            if (!file) return;

            const ext = file.name.split('.').pop().toLowerCase();
            if (!allowed.includes(ext)) {
                fileError.textContent = 'Dokumen harus berformat PDF, JPG, atau PNG.';
                fileInput.value = '';
                return;
            }
            if (file.size > 5 * 1024 * 1024) {
                fileError.textContent = 'Ukuran dokumen maksimal 5 MB.';
                fileInput.value = '';
                return;
            }
            fileInfo.textContent = file.name + ' (' + (file.size / 1024).toFixed(0) + ' KB)';
        }

        dropzone.addEventListener('click', () => fileInput.click());
        fileInput.addEventListener('change', () => showFile(fileInput.files[0]));

        ['dragenter', 'dragover'].forEach(evt => dropzone.addEventListener(evt, e => {
            e.preventDefault();
            dropzone.style.backgroundColor = '#eff6ff';
        }));
        ['dragleave', 'drop'].forEach(evt => dropzone.addEventListener(evt, e => {
            e.preventDefault();
            dropzone.style.backgroundColor = '#f8fbff';
        }));
        dropzone.addEventListener('drop', e => {
            if (e.dataTransfer.files.length) {
                fileInput.files = e.dataTransfer.files;
                showFile(fileInput.files[0]);
            }
        });

        // Dokumen wajib sebelum submit
        document.getElementById('leaveForm').addEventListener('submit', function (e) {
            if (!fileInput.files.length) {
                e.preventDefault();
                fileError.textContent = 'Dokumen pendukung wajib diunggah.';
                dropzone.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        });
    });
</script>

<style>
    .type-body { border: 1px solid #e2e8f0; background-color: #fff; transition: all 0.15s ease; }
    .type-option:hover .type-body { border-color: #93c5fd; }
    .type-radio { top: 12px; right: 12px; width: 16px; height: 16px; border-radius: 50%; border: 2px solid #cbd5e1; background: #fff; }
    .type-option input:checked + .type-body { border-color: #2563eb; background-color: #eff6ff; }
    .type-option input:checked + .type-body .type-radio { border-color: #2563eb; background: radial-gradient(#2563eb 45%, #fff 50%); }
</style>
@endpush