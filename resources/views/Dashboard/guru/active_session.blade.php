@extends('layouts.guru')

@section('title', 'Active Session - Attendly')

@section('content')
@php
    $kelasLabel = $kelasLabel
        ?? ($session->kelas_label
            ?? (isset($session->kelas)
                ? trim(($session->kelas->kelas ?? '') . ' ' . ($session->kelas->jurusan ?? '') . ' ' . ($session->kelas->nomor_kelas ?? ''))
                : 'XII-A'));

    $mapelLabel = $mapelLabel ?? ($session->mapel ?? 'Mathematics');

    $decodedPayload = is_string($qrPayload ?? null) ? json_decode($qrPayload, true) : null;
    $ruanganLabel = $ruanganLabel
        ?? ($session->ruangan
            ?? ($decodedPayload['room'] ?? 'Lab Komputer 1'));

    $sessionCode = $kodeSesi
        ?? ($session->qr_token
            ?? ($session->session_code
                ?? ($decodedPayload['code'] ?? 'X7K9QP')));

    $initialRemainingSeconds = isset($remainingSeconds)
        ? max(0, (int) $remainingSeconds)
        : ((int) ($session->qr_duration ?? 15) * 60);

    $durationMinutes = max(1, (int) ceil($initialRemainingSeconds / 60));
    $totalStudents = (int) ($expectedStudents ?? 32);

    $defaultPayload = json_encode([
        'app'        => 'Attendly',
        'session_id' => $session->id ?? null,
        'code'       => $sessionCode,
        'class'      => $kelasLabel,
        'subject'    => $mapelLabel,
        'room'       => $ruanganLabel,
        'expired_at' => optional($session->expired_at ?? null)->toDateTimeString(),
    ]);

    $finalQrPayload = (!empty($qrPayload) && is_string($qrPayload))
        ? $qrPayload
        : ($session->qr_payload ?? $defaultPayload);

    $presentCount = (int) ($stats['present'] ?? 0);
    $lateCount = (int) ($stats['late'] ?? 0);
    $absentCount = (int) ($stats['absent'] ?? max(0, $totalStudents - ($presentCount + $lateCount)));
    $checkedInCount = $presentCount + $lateCount;
    $progressPercent = $totalStudents > 0 ? min(100, round(($checkedInCount / $totalStudents) * 100)) : 0;
@endphp

<div class="teacher-overview p-0">

    <!-- ── HEADER TITLE & LIVE ACTION ── -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1">Active Session</h3>
            <p class="text-muted text-uppercase mb-0 fw-semibold" style="font-size: 0.78rem; letter-spacing: 0.04em;">
                {{ $kelasLabel }} • {{ $mapelLabel }} • {{ $ruanganLabel }}
            </p>
        </div>

        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-3 py-2 fw-semibold d-inline-flex align-items-center gap-1" style="font-size: 0.78rem;">
                <span class="rounded-circle bg-danger d-inline-block" style="width: 7px; height: 7px;"></span>
                LIVE
            </span>

            @if(isset($session->id))
                <form action="{{ route('guru.sessions.end', $session->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-danger rounded-3 px-3 py-2 fw-semibold shadow-sm" style="font-size: 0.85rem;">
                        End Session
                    </button>
                </form>
            @else
                <a href="{{ route('guru.sessions.create') }}" class="btn btn-danger rounded-3 px-3 py-2 fw-semibold shadow-sm" style="font-size: 0.85rem;">
                    End Session
                </a>
            @endif
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-4 mb-4" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-4 align-items-start">

        <!-- ── LEFT COLUMN: QR CODE & SESSION STATS ── -->
        <div class="col-lg-7">
            <div class="card border-0 rounded-4 p-4 shadow-sm bg-white">

                <div class="d-flex flex-wrap justify-content-between align-items-start gap-2 mb-4">
                    <div>
                        <h5 class="fw-bold text-dark mb-1" style="font-size: 1.05rem;">Scan to check in</h5>
                        <p class="text-muted mb-0" style="font-size: 0.82rem;">
                            Point the camera at the code — no app download required.
                        </p>
                    </div>

                    <!-- UBAH: status koneksi nyata (diperbarui oleh polling) -->
                    <span id="connStatus" class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill px-3 py-2 fw-semibold" style="font-size: 0.72rem;">
                        <i class="bi bi-wifi me-1"></i> CONNECTING...
                    </span>
                </div>

                <div class="row g-4 align-items-center my-2">
                    <!-- QR Frame -->
                    <div class="col-sm-6 d-flex justify-content-center">
                        <div class="position-relative p-4 d-flex align-items-center justify-content-center bg-white" style="width: 245px; height: 245px;">
                            <!-- Sudut Frame Biru -->
                            <div class="position-absolute top-0 start-0 border-top border-start border-primary border-3" style="width: 22px; height: 22px;"></div>
                            <div class="position-absolute top-0 end-0 border-top border-end border-primary border-3" style="width: 22px; height: 22px;"></div>
                            <div class="position-absolute bottom-0 start-0 border-bottom border-start border-primary border-3" style="width: 22px; height: 22px;"></div>
                            <div class="position-absolute bottom-0 end-0 border-bottom border-end border-primary border-3" style="width: 22px; height: 22px;"></div>

                            <!-- Container QR Otomatis -->
                            <div id="activeSessionQr" class="d-flex align-items-center justify-content-center"></div>
                        </div>
                    </div>

                    <!-- Countdown & Checked-in Counter -->
                    <div class="col-sm-6">
                        <div class="text-uppercase text-muted fw-bold mb-1" style="font-size: 0.7rem; letter-spacing: 0.06em;">
                            TIME REMAINING
                        </div>
                        <div id="sessionCountdown" class="fw-bold text-dark mb-3" style="font-size: 2.35rem; line-height: 1.1; font-variant-numeric: tabular-nums;">
                            {{ sprintf('%02d:%02d', floor($initialRemainingSeconds / 60), $initialRemainingSeconds % 60) }}
                        </div>

                        <div class="progress rounded-pill mb-4" style="height: 6px; background-color: #e2e8f0;">
                            <div id="sessionProgressBar" class="progress-bar bg-primary rounded-pill" role="progressbar" style="width: {{ $progressPercent }}%;"></div>
                        </div>

                        <div class="mb-2">
                            <span class="fw-bold text-dark" style="font-size: 1.65rem;" id="checkedInCount">{{ $checkedInCount }}</span>
                            <span class="text-muted fw-semibold" style="font-size: 1rem;">/ <span id="totalStudentsCount">{{ $totalStudents }}</span></span>
                        </div>
                        <div class="text-muted small mb-3">students checked in</div>

                        <div id="sessionStatusBadge" class="{{ $initialRemainingSeconds > 0 ? 'text-success' : 'text-danger' }} fw-semibold d-flex align-items-center gap-1" style="font-size: 0.75rem;">
                            <span class="rounded-circle {{ $initialRemainingSeconds > 0 ? 'bg-success' : 'bg-danger' }} d-inline-block" style="width: 6px; height: 6px;"></span>
                            <span id="sessionStatusText">CODE: {{ $sessionCode }} • {{ $initialRemainingSeconds > 0 ? 'READY TO SCAN' : 'EXPIRED' }}</span>
                        </div>
                    </div>
                </div>

                <div class="text-center text-muted mt-4 pt-3 border-top" style="font-size: 0.72rem; letter-spacing: 0.04em;">
                    <i class="bi bi-lock me-1"></i> SECURE TOKEN • SESSION CODE: <strong class="text-dark">{{ $sessionCode }}</strong>
                </div>

            </div>
        </div>

        <!-- ── RIGHT COLUMN: SUMMARY & LIVE SCAN FEED ── -->
        <div class="col-lg-5">
            <div class="card border-0 rounded-4 p-4 shadow-sm bg-white">

                <!-- 3 Stat Boxes -->
                <div class="row g-2 text-center mb-4">
                    <div class="col-4">
                        <div class="p-3 rounded-3" style="background-color: #ecfdf5; border: 1px solid #a7f3d0;">
                            <div class="fw-bold text-success fs-4" id="statPresent">{{ $presentCount }}</div>
                            <div class="text-success small fw-medium">Present</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="p-3 rounded-3" style="background-color: #fffbeb; border: 1px solid #fde68a;">
                            <div class="fw-bold text-warning fs-4" id="statLate">{{ $lateCount }}</div>
                            <div class="text-warning small fw-medium">Late</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="p-3 rounded-3" style="background-color: #fef2f2; border: 1px solid #fecaca;">
                            <div class="fw-bold text-danger fs-4" id="statAbsent">{{ $absentCount }}</div>
                            <div class="text-danger small fw-medium">Absent</div>
                        </div>
                    </div>
                </div>

                <!-- Live Scan Feed Header -->
                <div class="d-flex align-items-center gap-2 mb-3">
                    <i class="bi bi-qr-code-scan text-primary fs-5"></i>
                    <h6 class="fw-bold text-dark mb-0">Live scan feed</h6>
                </div>

                <!-- Filter Pills -->
                <div class="d-flex flex-wrap gap-2 mb-3" id="feedFilterGroup">
                    <button type="button" class="btn btn-sm btn-primary rounded-pill px-3 fw-semibold feed-filter-btn" data-filter="all">All</button>
                    <button type="button" class="btn btn-sm btn-light border rounded-pill px-3 fw-medium text-muted feed-filter-btn" data-filter="present">Present</button>
                    <button type="button" class="btn btn-sm btn-light border rounded-pill px-3 fw-medium text-muted feed-filter-btn" data-filter="late">Late</button>
                    <button type="button" class="btn btn-sm btn-light border rounded-pill px-3 fw-medium text-muted feed-filter-btn" data-filter="absent">Absent</button>
                </div>

                <!-- Scan Feed List -->
                <div class="d-flex flex-column gap-2" id="liveScanFeedList">
                    @forelse($attendances ?? [] as $item)
                        @php
                            $status = strtolower($item->status ?? 'present');
                            $studentName = $item->siswa->nama ?? ($item->student_name ?? 'Student');
                            $studentCode = $item->siswa->nis ?? ($item->student_code ?? '-');
                            $initials = collect(explode(' ', $studentName))->map(fn($w) => strtoupper(substr($w, 0, 1)))->take(2)->implode('');
                            $scanTime = $item->waktu_absen ?? ($item->scanned_at ?? now()->format('H:i:s'));
                        @endphp
                        <div class="p-2 rounded-3 d-flex align-items-center justify-content-between feed-item {{ $status === 'late' ? 'bg-warning bg-opacity-10' : 'bg-light' }}" data-status="{{ $status }}">
                            <div class="d-flex align-items-center gap-2">
                                <div class="rounded-3 bg-primary bg-opacity-10 text-primary fw-bold d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; font-size: 0.78rem;">
                                    {{ $initials }}
                                </div>
                                <div>
                                    <div class="fw-semibold text-dark" style="font-size: 0.85rem;">{{ $studentName }}</div>
                                    <div class="text-muted" style="font-size: 0.72rem;">{{ $studentCode }}</div>
                                </div>
                            </div>

                            <div class="d-flex align-items-center gap-2">
                                <span class="text-muted" style="font-size: 0.75rem; font-variant-numeric: tabular-nums;">
                                    <i class="bi bi-qr-code me-1"></i>{{ $scanTime }}
                                </span>
                                @if($status === 'late')
                                    <span class="badge bg-warning-subtle text-warning-emphasis rounded-pill px-2 py-1" style="font-size: 0.7rem;">• Late</span>
                                @elseif($status === 'absent')
                                    <span class="badge bg-danger-subtle text-danger rounded-pill px-2 py-1" style="font-size: 0.7rem;">• Absent</span>
                                @else
                                    <span class="badge bg-success-subtle text-success rounded-pill px-2 py-1" style="font-size: 0.7rem;">• Present</span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted py-4 small" id="emptyFeedNotice">
                            Belum ada siswa yang melakukan check-in pada sesi ini.
                        </div>
                    @endforelse
                </div>

            </div>
        </div>

    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // 1. Generate QR Code Otomatis dari data sesi aktif
        const qrPayload = @json($finalQrPayload);
        const sessionCode = @json($sessionCode);
        const qrContainer = document.getElementById("activeSessionQr");
        if (qrContainer) {
            qrContainer.innerHTML = "";
            new QRCode(qrContainer, {
                text: qrPayload,
                width: 185,
                height: 185,
                colorDark: "#0b132b",
                colorLight: "#ffffff",
                correctLevel: QRCode.CorrectLevel.M
            });
        }

        // 2. Countdown Timer Sesi berdasarkan sisa waktu aktual
        let remainingSeconds = {{ $initialRemainingSeconds }};
        const countdownEl = document.getElementById("sessionCountdown");
        const statusBadgeEl = document.getElementById("sessionStatusBadge");
        const statusTextEl = document.getElementById("sessionStatusText");

        function updateTimer() {
            if (!countdownEl) return;
            const safeSeconds = Math.max(0, remainingSeconds);
            const mins = Math.floor(safeSeconds / 60);
            const secs = safeSeconds % 60;
            countdownEl.textContent = `${String(mins).padStart(2, '0')}:${String(secs).padStart(2, '0')}`;

            if (safeSeconds <= 0 && statusTextEl && statusBadgeEl) {
                statusTextEl.textContent = `CODE: ${sessionCode} • EXPIRED`;
                statusBadgeEl.classList.remove("text-success");
                statusBadgeEl.classList.add("text-danger");
                const dot = statusBadgeEl.querySelector("span.rounded-circle");
                if (dot) {
                    dot.classList.remove("bg-success");
                    dot.classList.add("bg-danger");
                }
            }

            if (remainingSeconds > 0) {
                remainingSeconds--;
            }
        }
        updateTimer();
        setInterval(updateTimer, 1000);

        // 3. Filter Feed (All / Present / Late / Absent)
        const filterButtons = document.querySelectorAll(".feed-filter-btn");

        function currentFilter() {
            const active = document.querySelector(".feed-filter-btn.btn-primary");
            return active ? active.getAttribute("data-filter") : "all";
        }

        function applyFilter(filter) {
            // UBAH: ambil item saat dipanggil, supaya item hasil polling ikut terfilter
            document.querySelectorAll(".feed-item").forEach(item => {
                const status = item.getAttribute("data-status");
                item.style.display = (filter === "all" || status === filter) ? "flex" : "none";
            });
        }

        filterButtons.forEach(btn => {
            btn.addEventListener("click", function () {
                const filter = this.getAttribute("data-filter");

                filterButtons.forEach(b => {
                    b.classList.remove("btn-primary", "fw-semibold");
                    b.classList.add("btn-light", "border", "text-muted", "fw-medium");
                });
                this.classList.remove("btn-light", "border", "text-muted", "fw-medium");
                this.classList.add("btn-primary", "fw-semibold");

                applyFilter(filter);
            });
        });

        // 4. BARU: Ambil data live dari server setiap 5 detik
        const feedUrl = @json(isset($session->id) ? route('guru.sessions.feed', $session->id) : null);
        const feedList = document.getElementById("liveScanFeedList");
        const connEl = document.getElementById("connStatus");

        function esc(s) {
            const d = document.createElement("div");
            d.textContent = s ?? "";
            return d.innerHTML;
        }

        function setConn(ok) {
            if (!connEl) return;
            connEl.className = "badge rounded-pill px-3 py-2 fw-semibold border " + (ok
                ? "bg-success-subtle text-success border-success-subtle"
                : "bg-danger-subtle text-danger border-danger-subtle");
            connEl.innerHTML = ok
                ? '<i class="bi bi-wifi me-1"></i> CONNECTED'
                : '<i class="bi bi-wifi-off me-1"></i> RECONNECTING...';
        }

        function renderFeed(items) {
            if (!feedList) return;
            if (!items.length) {
                feedList.innerHTML = '<div class="text-center text-muted py-4 small" id="emptyFeedNotice">Belum ada siswa yang melakukan check-in pada sesi ini.</div>';
                return;
            }
            feedList.innerHTML = items.map(it => {
                const initials = (it.name || "").split(" ").map(w => w.charAt(0).toUpperCase()).slice(0, 2).join("");
                const badge = it.status === "late"
                    ? '<span class="badge bg-warning-subtle text-warning-emphasis rounded-pill px-2 py-1" style="font-size: 0.7rem;">• Late</span>'
                    : it.status === "absent"
                    ? '<span class="badge bg-danger-subtle text-danger rounded-pill px-2 py-1" style="font-size: 0.7rem;">• Absent</span>'
                    : '<span class="badge bg-success-subtle text-success rounded-pill px-2 py-1" style="font-size: 0.7rem;">• Present</span>';
                const bg = it.status === "late" ? "bg-warning bg-opacity-10" : "bg-light";
                return `
                <div class="p-2 rounded-3 d-flex align-items-center justify-content-between feed-item ${bg}" data-status="${it.status}">
                    <div class="d-flex align-items-center gap-2">
                        <div class="rounded-3 bg-primary bg-opacity-10 text-primary fw-bold d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; font-size: 0.78rem;">${esc(initials)}</div>
                        <div>
                            <div class="fw-semibold text-dark" style="font-size: 0.85rem;">${esc(it.name)}</div>
                            <div class="text-muted" style="font-size: 0.72rem;">${esc(it.code)}</div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <span class="text-muted" style="font-size: 0.75rem; font-variant-numeric: tabular-nums;"><i class="bi bi-qr-code me-1"></i>${esc(it.time)}</span>
                        ${badge}
                    </div>
                </div>`;
            }).join("");
            applyFilter(currentFilter());
        }

        async function pollFeed() {
            if (!feedUrl) return;
            try {
                const res = await fetch(feedUrl, { headers: { "Accept": "application/json" } });
                if (!res.ok) { setConn(false); return; }
                const data = await res.json();

                document.getElementById("statPresent").textContent = data.present;
                document.getElementById("statLate").textContent = data.late;
                document.getElementById("statAbsent").textContent = data.absent;
                document.getElementById("totalStudentsCount").textContent = data.total;

                const checkedIn = data.present + data.late;
                document.getElementById("checkedInCount").textContent = checkedIn;
                const pct = data.total > 0 ? Math.min(100, Math.round(checkedIn / data.total * 100)) : 0;
                document.getElementById("sessionProgressBar").style.width = pct + "%";

                // sinkronkan countdown dengan expired_at di database
                remainingSeconds = data.remaining;

                renderFeed(data.items);
                setConn(true);
            } catch (e) {
                setConn(false);
            }
        }
        pollFeed();
        setInterval(pollFeed, 5000);
    });
</script>
@endsection