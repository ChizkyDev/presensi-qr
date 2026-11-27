@extends('layouts.guru')

@section('title', 'Create Attendance Session - Attendly')

@section('content')
<div class="teacher-overview p-0">

    <!-- ── HEADER TITLE ── -->
    <div class="mb-4">
        <h3 class="fw-bold text-dark mb-1">Create Attendance Session</h3>
        <p class="text-muted mb-0" style="font-size: 0.88rem;">Generate QR Code</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-4 mb-4" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show rounded-4 mb-4" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show rounded-4 mb-4" role="alert">
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- ── STEPPER WIZARD BAR ── -->
    <div class="card border-0 rounded-4 p-4 shadow-sm bg-white mb-4">
        <div class="d-flex flex-column flex-md-row align-items-center justify-content-start text-center text-md-start px-2">
            
            <!-- Step 1 Indicator -->
            <div class="d-flex align-items-center gap-3">
                <div id="step1IndicatorCircle" class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold bg-primary shadow-sm flex-shrink-0" style="width: 32px; height: 32px; font-size: 0.85rem;">
                    1
                </div>
                <div class="text-start">
                    <div class="fw-bold text-dark" style="font-size: 0.85rem;">Session Details</div>
                    <small class="text-muted" style="font-size: 0.72rem;">Class and schedule</small>
                </div>
            </div>

            <!-- Garis Penghubung 1 -->
            <div class="d-none d-md-block mx-4" style="width: 140px;">
                <div id="step1Line" class="border-top" style="border-color: #cbd5e1 !important; border-width: 2px !important; transition: all 0.3s;"></div>
            </div>

            <!-- Step 2 Indicator -->
            <div class="d-flex align-items-center gap-3 mt-3 mt-md-0">
                <div id="step2IndicatorCircle" class="rounded-circle d-flex align-items-center justify-content-center fw-bold bg-light text-secondary border flex-shrink-0" style="width: 32px; height: 32px; font-size: 0.85rem;">
                    2
                </div>
                <div class="text-start">
                    <div id="step2Title" class="fw-medium text-secondary" style="font-size: 0.85rem;">Rules & Access</div>
                    <small class="text-muted" style="font-size: 0.72rem;">Security and attendance</small>
                </div>
            </div>

            <!-- Garis Penghubung 2 -->
            <div class="d-none d-md-block mx-4" style="width: 140px;">
                <div id="step2Line" class="border-top" style="border-color: #cbd5e1 !important; border-width: 2px !important; transition: all 0.3s;"></div>
            </div>

            <!-- Step 3 Indicator -->
            <div class="d-flex align-items-center gap-3 mt-3 mt-md-0">
                <div id="step3IndicatorCircle" class="rounded-circle d-flex align-items-center justify-content-center fw-bold bg-light text-secondary border flex-shrink-0" style="width: 32px; height: 32px; font-size: 0.85rem;">
                    3
                </div>
                <div class="text-start">
                    <div id="step3Title" class="fw-medium text-secondary" style="font-size: 0.85rem;">Review & Activate</div>
                    <small class="text-muted" style="font-size: 0.72rem;">Confirm and launch</small>
                </div>
            </div>

        </div>
    </div>

    <!-- ── FORM UTAMA & KARTU SUMMARY DALAM 1 FORM ── -->
    <form action="{{ route('guru.sessions.store') }}" method="POST" id="attendanceForm">
        @csrf
        <input type="hidden" name="session_code" id="hiddenSessionCode" value="">
        <input type="hidden" name="qr_payload" id="hiddenQrPayload" value="">

        <div class="row g-4 align-items-start">
            
            <!-- LEFT COLUMN: Multi-Step Forms Container -->
            <div class="col-lg-8">
                
                <!-- ════════ STEP 1: SESSION DETAILS ════════ -->
                <div class="card border-0 rounded-4 p-4 shadow-sm bg-white" id="step1FormContent">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-3 d-flex align-items-center justify-content-center p-2" style="background-color: #eff6ff; color: #2563eb; width: 40px; height: 40px;">
                                <i class="bi bi-file-earmark-text fs-5"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold text-dark mb-0">Session Details</h6>
                                <small class="text-muted" style="font-size: 0.75rem;">Choose the class context and schedule for this attendance session.</small>
                            </div>
                        </div>
                        <span class="badge rounded-pill px-3 py-1 bg-primary-subtle text-primary fw-bold" style="font-size: 0.72rem;">STEP 1 OF 3</span>
                    </div>

                    <div class="row g-3">
                        <!-- Class (Dropdown dari $listKelas Controller) -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-medium text-dark" style="font-size: 0.85rem;">Class</label>
                            <select name="kelas_id" id="inputKelas" class="form-select rounded-3 py-2 shadow-none" style="font-size: 0.88rem;" required>
                                <option value="" selected disabled>Select class...</option>
                                @foreach($listKelas as $k)
                                    <option value="{{ $k->id }}" {{ old('kelas_id') == $k->id ? 'selected' : '' }}>
                                        {{ trim(($k->kelas ?? '') . ' ' . ($k->jurusan ?? '') . ' ' . ($k->nomor_kelas ?? '')) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Subject -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-medium text-dark" style="font-size: 0.85rem;">Subject</label>
                            <input type="text" name="mapel" id="inputMapel" value="{{ old('mapel') }}" class="form-control rounded-3 py-2 shadow-none" placeholder="e.g. Mathematics" style="font-size: 0.88rem;" required>
                        </div>

                        <!-- Room -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-medium text-dark" style="font-size: 0.85rem;">Room</label>
                            <select name="ruangan" id="inputRuangan" class="form-select rounded-3 py-2 shadow-none" style="font-size: 0.88rem;" required>
                                <option value="Lab Komputer 1" {{ old('ruangan') == 'Lab Komputer 1' ? 'selected' : '' }}>Lab Komputer 1</option>
                                <option value="Lab RPL" {{ old('ruangan') == 'Lab RPL' ? 'selected' : '' }}>Lab RPL</option>
                                <option value="Ruang Teori 101" {{ old('ruangan') == 'Ruang Teori 101' ? 'selected' : '' }}>Ruang Teori 101</option>
                                <option value="Ruang Teori 102" {{ old('ruangan') == 'Ruang Teori 102' ? 'selected' : '' }}>Ruang Teori 102</option>
                            </select>
                        </div>

                        <!-- QR Validity -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-medium text-dark" style="font-size: 0.85rem;">QR Validity</label>
                            <select name="qr_duration" id="inputDuration" class="form-select rounded-3 py-2 shadow-none" style="font-size: 0.88rem;" required>
                                <option value="15" {{ old('qr_duration') == 15 ? 'selected' : '' }}>15 minutes</option>
                                <option value="30" {{ old('qr_duration') == 30 ? 'selected' : '' }}>30 minutes</option>
                                <option value="45" {{ old('qr_duration') == 45 ? 'selected' : '' }}>45 minutes</option>
                                <option value="60" {{ old('qr_duration') == 60 ? 'selected' : '' }}>60 minutes</option>
                            </select>
                        </div>

                        <!-- Session Date -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-medium text-dark" style="font-size: 0.85rem;">Session Date</label>
                            <input type="date" name="tanggal" id="inputDate" class="form-control rounded-3 py-2 shadow-none" value="{{ old('tanggal', date('Y-m-d')) }}" style="font-size: 0.88rem;" required>
                        </div>

                        <!-- Start Time -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-medium text-dark" style="font-size: 0.85rem;">Start Time</label>
                            <input type="time" name="jam_mulai" id="inputTime" class="form-control rounded-3 py-2 shadow-none" value="{{ old('jam_mulai', date('H:i')) }}" style="font-size: 0.88rem;" required>
                        </div>

                        <!-- Teacher Note (Optional) -->
                        <div class="col-12 mb-4">
                            <label class="form-label fw-medium text-dark" style="font-size: 0.85rem;">Teacher Note (Optional)</label>
                            <textarea name="catatan" rows="3" class="form-control rounded-3 shadow-none" placeholder="Add instructions students should see before check-in..." style="font-size: 0.88rem;">{{ old('catatan') }}</textarea>
                        </div>
                    </div>

                    <!-- Footer Action Bar Step 1 -->
                    <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                        <div class="text-muted d-flex align-items-center gap-1" style="font-size: 0.78rem;">
                            <i class="bi bi-shield-lock"></i> SESSION SETUP
                        </div>
                        <button type="button" id="btnToStep2" class="btn btn-primary rounded-pill px-4 py-2 text-white fw-medium d-flex align-items-center gap-2 shadow-sm" style="background-color: #2563eb; border: none; font-size: 0.88rem;">
                            Continue <i class="bi bi-chevron-right" style="font-size: 0.75rem;"></i>
                        </button>
                    </div>
                </div>

                <!-- ════════ STEP 2: RULES & ACCESS (Hidden by default) ════════ -->
                <div class="card border-0 rounded-4 p-4 shadow-sm bg-white d-none" id="step2FormContent">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-3 d-flex align-items-center justify-content-center p-2" style="background-color: #eff6ff; color: #2563eb; width: 40px; height: 40px;">
                                <i class="bi bi-shield-check fs-5"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold text-dark mb-0">Rules & Access</h6>
                                <small class="text-muted" style="font-size: 0.75rem;">Configure how students can access and complete check-in.</small>
                            </div>
                        </div>
                        <span class="badge rounded-pill px-3 py-1 bg-primary-subtle text-primary fw-bold" style="font-size: 0.72rem;">STEP 2 OF 3</span>
                    </div>

                    <div class="d-flex flex-column gap-3 mb-4">
                        <!-- Toggle 1 -->
                        <div class="p-3 rounded-4 d-flex align-items-center justify-content-between" style="background-color: #f8fafc; border: 1px solid #e2e8f0;">
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-3 p-2 bg-white shadow-sm text-primary" style="width: 38px; height: 38px; display: flex; align-items: center; justify-content: center;">
                                    <i class="bi bi-geo-alt fs-5"></i>
                                </div>
                                <div>
                                    <div class="fw-bold text-dark" style="font-size: 0.88rem;">School geo-fence</div>
                                    <small class="text-muted" style="font-size: 0.75rem;">Only accept scans within 150 m of Lab 1.</small>
                                </div>
                            </div>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" name="geo_fence" checked style="width: 2.5em; height: 1.25em; cursor: pointer;">
                            </div>
                        </div>

                        <!-- Toggle 2 -->
                        <div class="p-3 rounded-4 d-flex align-items-center justify-content-between" style="background-color: #f8fafc; border: 1px solid #e2e8f0;">
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-3 p-2 bg-white shadow-sm text-primary" style="width: 38px; height: 38px; display: flex; align-items: center; justify-content: center;">
                                    <i class="bi bi-clock fs-5"></i>
                                </div>
                                <div>
                                    <div class="fw-bold text-dark" style="font-size: 0.88rem;">Automatic late status</div>
                                    <small class="text-muted" style="font-size: 0.75rem;">Mark check-ins after scheduled time as late.</small>
                                </div>
                            </div>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" name="auto_late" checked style="width: 2.5em; height: 1.25em; cursor: pointer;">
                            </div>
                        </div>

                        <!-- Toggle 3 -->
                        <div class="p-3 rounded-4 d-flex align-items-center justify-content-between" style="background-color: #f8fafc; border: 1px solid #e2e8f0;">
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-3 p-2 bg-white shadow-sm text-primary" style="width: 38px; height: 38px; display: flex; align-items: center; justify-content: center;">
                                    <i class="bi bi-shield-lock fs-5"></i>
                                </div>
                                <div>
                                    <div class="fw-bold text-dark" style="font-size: 0.88rem;">Duplicate scan protection</div>
                                    <small class="text-muted" style="font-size: 0.75rem;">Allow one verified check-in per student account.</small>
                                </div>
                            </div>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" name="duplicate_protection" checked style="width: 2.5em; height: 1.25em; cursor: pointer;">
                            </div>
                        </div>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-medium text-dark" style="font-size: 0.85rem;">Late Threshold</label>
                            <select name="late_threshold" class="form-select rounded-3 py-2 shadow-none" style="font-size: 0.88rem;">
                                <option value="15">15 minutes after start</option>
                                <option value="30">30 minutes after start</option>
                                <option value="45">45 minutes after start</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium text-dark" style="font-size: 0.85rem;">Access Mode</label>
                            <select name="access_mode" id="inputAccessMode" class="form-select rounded-3 py-2 shadow-none" style="font-size: 0.88rem;">
                                <option value="qr_code">QR code + session code</option>
                                <option value="qr_only">QR code only</option>
                                <option value="manual_only">Manual code only</option>
                            </select>
                        </div>
                    </div>

                    <!-- Footer Action Bar Step 2 -->
                    <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                        <button type="button" id="btnBackToStep1" class="btn btn-light rounded-pill px-4 py-2 text-dark fw-medium d-flex align-items-center gap-2 shadow-sm" style="font-size: 0.88rem;">
                            <i class="bi bi-chevron-left" style="font-size: 0.75rem;"></i> Back
                        </button>
                        
                        <div class="d-flex align-items-center gap-2">
                            <span class="text-muted" style="font-size: 0.75rem;"><i class="bi bi-cloud-check text-success me-1"></i> YOUR PROGRESS IS SAVED</span>
                            <button type="button" id="btnToStep3" class="btn btn-primary rounded-pill px-4 py-2 text-white fw-medium d-flex align-items-center gap-2 shadow-sm" style="background-color: #2563eb; border: none; font-size: 0.88rem;">
                                Generate QR <i class="bi bi-check2" style="font-size: 0.85rem;"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- ════════ STEP 3: REVIEW & ACTIVATE (Hidden by default) ════════ -->
                <div class="card border-0 rounded-4 p-4 shadow-sm bg-white d-none" id="step3FormContent">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-3 d-flex align-items-center justify-content-center p-2" style="background-color: #eff6ff; color: #2563eb; width: 40px; height: 40px;">
                                <i class="bi bi-check2-circle fs-5"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold text-dark mb-0">Review & Activate</h6>
                                <small class="text-muted" style="font-size: 0.75rem;">Review the session details and share either access method.</small>
                            </div>
                        </div>
                        <span class="badge rounded-pill px-3 py-1 bg-primary-subtle text-primary fw-bold" style="font-size: 0.72rem;">STEP 3 OF 3</span>
                    </div>

                    <div class="row g-3 my-3">
                        <!-- QR Code Box -->
                        <div class="col-md-6">
                            <div class="p-4 rounded-4 h-100 d-flex flex-column justify-content-between" style="background-color: #f8fafc; border: 1px solid #e2e8f0;">
                                <div class="d-flex align-items-center gap-2 mb-3">
                                    <div class="rounded-2 bg-white border text-primary d-flex align-items-center justify-content-center" style="width: 30px; height: 30px;">
                                        <i class="bi bi-qr-code small"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark" style="font-size: 0.85rem;">QR code</div>
                                        <small class="text-muted" style="font-size: 0.72rem;">Best for in-room display</small>
                                    </div>
                                </div>

                                <div class="bg-white rounded-4 p-3 border d-flex flex-column align-items-center justify-content-center my-2" style="min-height: 210px;">
                                    <div id="qrcodeCanvasContainer" class="d-flex align-items-center justify-content-center"></div>
                                </div>

                                <div class="text-center mt-2">
                                    <small class="text-muted" style="font-size: 0.7rem;" id="qrPayloadCaption">Auto-generated session token</small>
                                </div>
                            </div>
                        </div>

                        <!-- Session Code Box -->
                        <div class="col-md-6">
                            <div class="p-4 rounded-4 h-100 d-flex flex-column justify-content-between" style="background-color: #f8fafc; border: 1px solid #e2e8f0;">
                                <div>
                                    <div class="d-flex align-items-center gap-2 mb-3">
                                        <div class="rounded-2 bg-white border text-primary d-flex align-items-center justify-content-center" style="width: 30px; height: 30px;">
                                            <i class="bi bi-credit-card-2-front small"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark" style="font-size: 0.85rem;">Session code</div>
                                            <small class="text-muted" style="font-size: 0.72rem;">Alternative access method</small>
                                        </div>
                                    </div>

                                    <p class="text-muted mb-3" style="font-size: 0.78rem;">
                                        Students can enter this code manually if their camera is unavailable.
                                    </p>

                                    <div class="rounded-4 p-4 text-center text-white mb-3" style="background-color: #0b132b;">
                                        <div id="sessionCodeDisplay" class="fw-bold" style="font-size: 1.45rem; letter-spacing: 0.35em;">X 7 K 9 Q P</div>
                                        <div class="text-white-50 mt-1" style="font-size: 0.65rem; letter-spacing: 0.12em;">6-CHARACTER ACCESS CODE</div>
                                    </div>
                                </div>

                                <div class="d-flex gap-2">
                                    <button type="button" id="btnCopyCode" class="btn btn-primary rounded-3 flex-fill py-2 fw-medium d-flex align-items-center justify-content-center gap-2" style="background-color: #2563eb; border: none; font-size: 0.82rem;">
                                        <i class="bi bi-copy"></i> <span id="copyCodeText">Copy Code</span>
                                    </button>
                                    <button type="button" id="btnRegenerateCode" class="btn btn-white bg-white border rounded-3 px-3 py-2 fw-medium text-dark d-flex align-items-center justify-content-center gap-2" style="font-size: 0.82rem;">
                                        <i class="bi bi-arrow-clockwise"></i> Regenerate
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer Action Bar Step 3 -->
                    <div class="d-flex justify-content-between align-items-center pt-3 border-top mt-3">
                        <button type="button" id="btnBackToStep2" class="btn btn-light rounded-pill px-4 py-2 text-dark fw-medium d-flex align-items-center gap-2 shadow-sm" style="font-size: 0.88rem;">
                            <i class="bi bi-chevron-left" style="font-size: 0.75rem;"></i> Back
                        </button>
                        
                        <div class="d-flex align-items-center gap-3">
                            <span class="text-muted fw-medium" style="font-size: 0.72rem; letter-spacing: 0.04em;">
                                <i class="bi bi-check-circle text-primary me-1"></i> READY TO GO LIVE
                            </span>
                            <button type="submit" class="btn btn-primary rounded-pill px-4 py-2 text-white fw-medium d-flex align-items-center gap-2 shadow-sm" style="background-color: #2563eb; border: none; font-size: 0.88rem;">
                                <i class="bi bi-lightning-charge-fill"></i> Activate Session
                            </button>
                        </div>
                    </div>
                </div>

            </div>

            <!-- RIGHT COLUMN: Session Summary Card (Fixed & Consistent) -->
            <div class="col-lg-4">
                <div class="card border-0 rounded-4 p-4 shadow-sm bg-white">
                    <div class="d-flex align-items-center gap-2 mb-4">
                        <i class="bi bi-file-earmark-text text-primary fs-5"></i>
                        <h6 class="fw-bold text-dark mb-0">Session summary</h6>
                    </div>

                    <!-- Live Dynamic Card Preview with Thick Blue Left Border -->
                    <div class="p-3 rounded-4 mb-4" style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-left: 4px solid #2563eb !important;">
                        <h6 class="fw-bold text-dark mb-1" id="summaryMapel">Mathematics</h6>
                        <small class="text-muted" id="summaryKelas" style="font-size: 0.78rem;">Select a class</small>
                    </div>

                    <div class="d-flex flex-column gap-3 mb-4" style="font-size: 0.82rem;">
                        <div class="d-flex justify-content-between text-muted">
                            <span>Starts</span>
                            <span class="fw-bold text-dark" id="summaryTime">Today, {{ date('H:i') }}</span>
                        </div>
                        <div class="d-flex justify-content-between text-muted">
                            <span>Duration</span>
                            <span class="fw-bold text-dark" id="summaryDuration">15 minutes</span>
                        </div>
                        <div class="d-flex justify-content-between text-muted">
                            <span>Expected</span>
                            <span class="fw-bold text-dark">32 students</span>
                        </div>
                        <div class="d-flex justify-content-between text-muted">
                            <span>Access</span>
                            <span class="fw-bold text-dark" id="summaryAccess">QR + code</span>
                        </div>
                    </div>

                    <div class="pt-3 border-top text-muted" style="font-size: 0.75rem;">
                        <i class="bi bi-info-circle me-1 text-primary"></i> QR and manual codes expire together when this session ends.
                    </div>
                </div>
            </div>

        </div>
    </form>
</div>

<!-- Library QRCode.js untuk generate QR otomatis di browser -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        let currentCode = "";
        let qrInstance = null;

        function generateRandomCode(length = 6) {
            const chars = "ABCDEFGHJKLMNPQRSTUVWXYZ23456789";
            let result = "";
            for (let i = 0; i < length; i++) {
                result += chars.charAt(Math.floor(Math.random() * chars.length));
            }
            return result;
        }

        function formatSpacedCode(code) {
            return code.split("").join(" ");
        }

        function buildQrPayload() {
            const kelasSelect = document.getElementById('inputKelas');
            const kelasText = kelasSelect.selectedIndex > 0 ? kelasSelect.options[kelasSelect.selectedIndex].text.trim() : 'CLASS';
            const mapel = document.getElementById('inputMapel').value.trim() || 'Subject';
            const ruangan = document.getElementById('inputRuangan').value;
            const tanggal = document.getElementById('inputDate').value;
            const jam = document.getElementById('inputTime').value;

            return JSON.stringify({
                app: "Attendly",
                code: currentCode,
                class: kelasText,
                subject: mapel,
                room: ruangan,
                date: tanggal,
                time: jam
            });
        }

        function renderQrAndCode(regenerate = false) {
            if (regenerate || !currentCode) {
                currentCode = generateRandomCode(6);
            }

            document.getElementById('sessionCodeDisplay').innerText = formatSpacedCode(currentCode);
            document.getElementById('hiddenSessionCode').value = currentCode;

            const payload = buildQrPayload();
            document.getElementById('hiddenQrPayload').value = payload;
            document.getElementById('qrPayloadCaption').innerText = "Code: " + currentCode;

            const container = document.getElementById('qrcodeCanvasContainer');
            container.innerHTML = "";

            qrInstance = new QRCode(container, {
                text: payload,
                width: 175,
                height: 175,
                colorDark: "#0b132b",
                colorLight: "#ffffff",
                correctLevel: QRCode.CorrectLevel.M
            });
        }

        function syncSummary() {
            const mapelVal = document.getElementById('inputMapel').value.trim();
            document.getElementById('summaryMapel').innerText = mapelVal || 'Mathematics';

            const kelasSelect = document.getElementById('inputKelas');
            const roomVal = document.getElementById('inputRuangan').value;
            if (kelasSelect.selectedIndex > 0) {
                const kelasText = kelasSelect.options[kelasSelect.selectedIndex].text.trim();
                document.getElementById('summaryKelas').innerText = kelasText + ' • ' + roomVal;
            } else {
                document.getElementById('summaryKelas').innerText = 'Select a class';
            }

            const durationVal = document.getElementById('inputDuration').value;
            document.getElementById('summaryDuration').innerText = durationVal + ' minutes';

            const timeVal = document.getElementById('inputTime').value;
            document.getElementById('summaryTime').innerText = timeVal ? 'Today, ' + timeVal : 'Today, 10:00';

            const accessVal = document.getElementById('inputAccessMode').value;
            const labels = {
                'qr_code': 'QR + code',
                'qr_only': 'QR only',
                'manual_only': 'Manual code'
            };
            document.getElementById('summaryAccess').innerText = labels[accessVal] || 'QR + code';
        }

        // Live Preview Binding
        document.getElementById('inputMapel').addEventListener('input', syncSummary);
        document.getElementById('inputKelas').addEventListener('change', syncSummary);
        document.getElementById('inputRuangan').addEventListener('change', syncSummary);
        document.getElementById('inputDuration').addEventListener('change', syncSummary);
        document.getElementById('inputTime').addEventListener('input', syncSummary);
        document.getElementById('inputAccessMode').addEventListener('change', syncSummary);
        syncSummary();

        // Elements Multi-Step
        const btnToStep2 = document.getElementById('btnToStep2');
        const btnBackToStep1 = document.getElementById('btnBackToStep1');
        const btnToStep3 = document.getElementById('btnToStep3');
        const btnBackToStep2 = document.getElementById('btnBackToStep2');

        const step1FormContent = document.getElementById('step1FormContent');
        const step2FormContent = document.getElementById('step2FormContent');
        const step3FormContent = document.getElementById('step3FormContent');

        const step1IndicatorCircle = document.getElementById('step1IndicatorCircle');
        const step1Line = document.getElementById('step1Line');
        const step2IndicatorCircle = document.getElementById('step2IndicatorCircle');
        const step2Title = document.getElementById('step2Title');
        const step2Line = document.getElementById('step2Line');
        const step3IndicatorCircle = document.getElementById('step3IndicatorCircle');
        const step3Title = document.getElementById('step3Title');

        function setStep1Active() {
            step1FormContent.classList.remove('d-none');
            step2FormContent.classList.add('d-none');
            step3FormContent.classList.add('d-none');

            step1IndicatorCircle.innerHTML = '1';
            step1IndicatorCircle.classList.remove('bg-success', 'bg-light', 'text-secondary', 'border');
            step1IndicatorCircle.classList.add('bg-primary', 'text-white');
            step1Line.style.setProperty('border-color', '#cbd5e1', 'important');

            step2IndicatorCircle.innerHTML = '2';
            step2IndicatorCircle.classList.add('bg-light', 'text-secondary', 'border');
            step2IndicatorCircle.classList.remove('bg-primary', 'bg-success', 'text-white');
            step2Title.classList.add('text-secondary', 'fw-medium');
            step2Title.classList.remove('text-dark', 'fw-bold');
            step2Line.style.setProperty('border-color', '#cbd5e1', 'important');

            step3IndicatorCircle.innerHTML = '3';
            step3IndicatorCircle.classList.add('bg-light', 'text-secondary', 'border');
            step3IndicatorCircle.classList.remove('bg-primary', 'text-white');
            step3Title.classList.add('text-secondary', 'fw-medium');
            step3Title.classList.remove('text-dark', 'fw-bold');
        }

        function setStep2Active() {
            step1FormContent.classList.add('d-none');
            step2FormContent.classList.remove('d-none');
            step3FormContent.classList.add('d-none');

            step1IndicatorCircle.innerHTML = '<i class="bi bi-check-lg"></i>';
            step1IndicatorCircle.classList.remove('bg-primary', 'bg-light', 'text-secondary', 'border');
            step1IndicatorCircle.classList.add('bg-success', 'text-white');
            step1Line.style.setProperty('border-color', '#10b981', 'important');

            step2IndicatorCircle.innerHTML = '2';
            step2IndicatorCircle.classList.remove('bg-light', 'text-secondary', 'border', 'bg-success');
            step2IndicatorCircle.classList.add('bg-primary', 'text-white');
            step2Title.classList.remove('text-secondary', 'fw-medium');
            step2Title.classList.add('text-dark', 'fw-bold');
            step2Line.style.setProperty('border-color', '#cbd5e1', 'important');

            step3IndicatorCircle.innerHTML = '3';
            step3IndicatorCircle.classList.add('bg-light', 'text-secondary', 'border');
            step3IndicatorCircle.classList.remove('bg-primary', 'text-white');
            step3Title.classList.add('text-secondary', 'fw-medium');
            step3Title.classList.remove('text-dark', 'fw-bold');
        }

        function setStep3Active() {
            step1FormContent.classList.add('d-none');
            step2FormContent.classList.add('d-none');
            step3FormContent.classList.remove('d-none');

            step1IndicatorCircle.innerHTML = '<i class="bi bi-check-lg"></i>';
            step1IndicatorCircle.classList.remove('bg-primary', 'bg-light', 'text-secondary', 'border');
            step1IndicatorCircle.classList.add('bg-success', 'text-white');
            step1Line.style.setProperty('border-color', '#10b981', 'important');

            step2IndicatorCircle.innerHTML = '<i class="bi bi-check-lg"></i>';
            step2IndicatorCircle.classList.remove('bg-primary', 'bg-light', 'text-secondary', 'border');
            step2IndicatorCircle.classList.add('bg-success', 'text-white');
            step2Title.classList.remove('text-secondary', 'fw-medium');
            step2Title.classList.add('text-dark', 'fw-bold');
            step2Line.style.setProperty('border-color', '#10b981', 'important');

            step3IndicatorCircle.classList.remove('bg-light', 'text-secondary', 'border');
            step3IndicatorCircle.classList.add('bg-primary', 'text-white');
            step3Title.classList.remove('text-secondary', 'fw-medium');
            step3Title.classList.add('text-dark', 'fw-bold');

            renderQrAndCode(false);
        }

        btnToStep2.addEventListener('click', function() {
            const kelas = document.getElementById('inputKelas').value;
            const mapel = document.getElementById('inputMapel').value.trim();
            if (!kelas || !mapel) {
                alert('Silakan pilih Kelas dan isi Mata Pelajaran terlebih dahulu!');
                return;
            }
            setStep2Active();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });

        btnBackToStep1.addEventListener('click', function() {
            setStep1Active();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });

        btnToStep3.addEventListener('click', function() {
            setStep3Active();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });

        btnBackToStep2.addEventListener('click', function() {
            setStep2Active();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });

        document.getElementById('btnRegenerateCode').addEventListener('click', function() {
            renderQrAndCode(true);
        });

        document.getElementById('btnCopyCode').addEventListener('click', function() {
            if (!currentCode) return;
            navigator.clipboard.writeText(currentCode).then(() => {
                const label = document.getElementById('copyCodeText');
                const original = label.innerText;
                label.innerText = "Copied!";
                setTimeout(() => {
                    label.innerText = original;
                }, 1500);
            });
        });

        document.getElementById('attendanceForm').addEventListener('submit', function() {
            if (!currentCode) {
                currentCode = generateRandomCode(6);
            }
            document.getElementById('hiddenSessionCode').value = currentCode;
            document.getElementById('hiddenQrPayload').value = buildQrPayload();
        });
    });
</script>
@endsection