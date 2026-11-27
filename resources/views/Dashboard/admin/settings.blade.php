@extends('layouts.admin')

@section('title', 'System Settings - Attendly Admin')

@section('content')
<div class="reports-container p-0" style="max-width: 1400px; margin: 0 auto;">

    <!-- ── HEADER TITLE & SEARCH ── -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-4 px-1">
        <div>
            <h4 class="fw-bold text-dark mb-1" style="font-size: 1.5rem;">System Settings</h4>
            <p class="text-muted mb-0" style="font-size: 0.85rem;">Configuration</p>
        </div>
        
    </div>

    <!-- Alert Notifikasi Berhasil -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-4 mb-4 shadow-sm" role="alert" style="font-size: 0.85rem;">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close shadow-none" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @php $currentTab = $tab ?? 'general'; @endphp

    <div class="row g-4 align-items-start">
        
        <!-- ── KOLOM KIRI: WORKSPACE SIDEBAR ── -->
        <div class="col-xl-3 col-lg-4" style="position: sticky; top: 20px;">
            <div class="card border-0 rounded-4 p-3 shadow-sm bg-white" style="padding: 1.5rem !important;">
                <span class="text-muted fw-bold px-2 mb-3 text-uppercase d-block" style="font-size: 0.7rem; letter-spacing: 0.8px;">Workspace</span>
                
                <div class="d-flex flex-column gap-2 mb-4">
                    <!-- Menu General -->
                    <a href="{{ route('admin.settings.index', ['tab' => 'general']) }}" 
                       class="d-flex align-items-center gap-3 px-3 py-3 rounded-3 text-decoration-none transition {{ $currentTab == 'general' ? '' : 'text-dark hover-bg-light' }}" 
                       style="font-size: 0.88rem; {{ $currentTab == 'general' ? 'background-color: #eef2ff;' : '' }}">
                        <div class="{{ $currentTab == 'general' ? 'text-primary' : 'text-secondary' }} d-flex align-items-center justify-content-center">
                            <i class="bi bi-sliders fs-5"></i>
                        </div>
                        <div>
                            <div class="{{ $currentTab == 'general' ? 'fw-bold text-primary' : 'fw-semibold text-dark' }}" style="font-size: 0.9rem;">General</div>
                            <div class="{{ $currentTab == 'general' ? 'text-primary opacity-75' : 'text-muted' }}" style="font-size: 0.73rem;">School & attendance</div>
                        </div>
                    </a>

                    <!-- Menu Security -->
                    <a href="{{ route('admin.settings.index', ['tab' => 'security']) }}" 
                       class="d-flex align-items-center gap-3 px-3 py-3 rounded-3 text-decoration-none transition {{ $currentTab == 'security' ? '' : 'text-dark hover-bg-light' }}" 
                       style="font-size: 0.88rem; {{ $currentTab == 'security' ? 'background-color: #eef2ff;' : '' }}">
                        <div class="{{ $currentTab == 'security' ? 'text-primary' : 'text-secondary' }} d-flex align-items-center justify-content-center">
                            <i class="bi bi-shield-check fs-5"></i>
                        </div>
                        <div>
                            <div class="{{ $currentTab == 'security' ? 'fw-bold text-primary' : 'fw-semibold text-dark' }}" style="font-size: 0.9rem;">Security</div>
                            <div class="{{ $currentTab == 'security' ? 'text-primary opacity-75' : 'text-muted' }}" style="font-size: 0.73rem;">Access & verification</div>
                        </div>
                    </a>

                    <!-- Menu Notifications -->
                    <a href="{{ route('admin.settings.index', ['tab' => 'notifications']) }}" 
                       class="d-flex align-items-center gap-3 px-3 py-3 rounded-3 text-decoration-none transition {{ $currentTab == 'notifications' ? '' : 'text-dark hover-bg-light' }}" 
                       style="font-size: 0.88rem; {{ $currentTab == 'notifications' ? 'background-color: #eef2ff;' : '' }}">
                        <div class="{{ $currentTab == 'notifications' ? 'text-primary' : 'text-secondary' }} d-flex align-items-center justify-content-center">
                            <i class="bi bi-bell fs-5"></i>
                        </div>
                        <div>
                            <div class="{{ $currentTab == 'notifications' ? 'fw-bold text-primary' : 'fw-semibold text-dark' }}" style="font-size: 0.9rem;">Notifications</div>
                            <div class="{{ $currentTab == 'notifications' ? 'text-primary opacity-75' : 'text-muted' }}" style="font-size: 0.73rem;">Alerts & delivery</div>
                        </div>
                    </a>

                    <!-- Menu Integrations -->
                    <a href="{{ route('admin.settings.index', ['tab' => 'integrations']) }}" 
                       class="d-flex align-items-center gap-3 px-3 py-3 rounded-3 text-decoration-none transition {{ $currentTab == 'integrations' ? '' : 'text-dark hover-bg-light' }}" 
                       style="font-size: 0.88rem; {{ $currentTab == 'integrations' ? 'background-color: #eef2ff;' : '' }}">
                        <div class="{{ $currentTab == 'integrations' ? 'text-primary' : 'text-secondary' }} d-flex align-items-center justify-content-center">
                            <i class="bi bi-plug fs-5"></i>
                        </div>
                        <div>
                            <div class="{{ $currentTab == 'integrations' ? 'fw-bold text-primary' : 'fw-semibold text-dark' }}" style="font-size: 0.9rem;">Integrations</div>
                            <div class="{{ $currentTab == 'integrations' ? 'text-primary opacity-75' : 'text-muted' }}" style="font-size: 0.73rem;">Connected services</div>
                        </div>
                    </a>
                </div>

                <!-- Box Status Operational di Bawah Sidebar -->
                <div class="p-3 rounded-4" style="background-color: #f4fbf7; border: 1px solid #d1fae5;">
                    <div class="d-flex align-items-center gap-2 text-success fw-bold mb-1" style="font-size: 0.78rem;">
                        <span class="rounded-circle bg-success d-inline-block" style="width: 7px; height: 7px;"></span> All systems operational
                    </div>
                    <div class="text-muted" style="font-size: 0.72rem; line-height: 1.3;">Last configuration sync 2 minutes ago.</div>
                </div>
            </div>
        </div>

        <!-- ── KOLOM KANAN: KONTEN BERDASARKAN TAB ── -->
        <div class="col-xl-9 col-lg-8">
            
            @if($currentTab == 'general')
                <!-- TAB 1: GENERAL SETTINGS -->
                <form id="settingsForm" action="{{ route('admin.settings.update', ['tab' => 'general']) }}" method="POST">
                    @csrf
                    <div class="card border-0 rounded-4 shadow-sm bg-white overflow-hidden mb-4" style="padding: 2.2rem !important;">
                        
                        <div class="d-flex align-items-center gap-3 pb-4 border-bottom mb-4">
                            <div class="p-2.5 rounded-3 text-primary d-flex align-items-center justify-content-center" style="width: 42px; height: 42px; background-color: #eff6ff;">
                                <i class="bi bi-mortarboard fs-5"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold text-dark mb-0" style="font-size: 1.05rem;">General settings</h5>
                                <span class="text-muted" style="font-size: 0.8rem;">Configure school & attendance for your organization.</span>
                            </div>
                        </div>

                        <!-- School Identity -->
                        <div class="row align-items-center pb-4 border-bottom mb-4 g-3">
                            <div class="col-lg-5">
                                <label class="fw-bold text-dark mb-1" style="font-size: 0.88rem;">School Identity</label>
                                <p class="text-muted mb-0" style="font-size: 0.78rem; line-height: 1.4;">Shown on reports, student portals, and exported attendance records.</p>
                            </div>
                            <div class="col-lg-7">
                                <div class="row g-2">
                                    <div class="col-12">
                                        <input type="text" name="school_name" class="form-control rounded-3 px-3 py-2 bg-light border-0 text-dark fw-medium shadow-none" value="{{ $settings['school_name'] ?? '' }}" required style="font-size: 0.85rem;">
                                    </div>
                                    <div class="col-md-7">
                                        <input type="text" name="school_npsn" class="form-control rounded-3 px-3 py-2 bg-light border-0 text-dark fw-medium shadow-none" value="{{ $settings['school_npsn'] ?? '' }}" required style="font-size: 0.85rem;">
                                    </div>
                                    <div class="col-md-5">
                                        <select name="school_timezone" class="form-select rounded-3 px-3 py-2 bg-light border-0 text-dark fw-medium shadow-none" style="font-size: 0.85rem;">
                                            <option value="WIB (UTC+7)" {{ ($settings['school_timezone'] ?? '') == 'WIB (UTC+7)' ? 'selected' : '' }}>WIB (UTC+7)</option>
                                            <option value="WITA (UTC+8)" {{ ($settings['school_timezone'] ?? '') == 'WITA (UTC+8)' ? 'selected' : '' }}>WITA (UTC+8)</option>
                                            <option value="WIT (UTC+9)" {{ ($settings['school_timezone'] ?? '') == 'WIT (UTC+9)' ? 'selected' : '' }}>WIT (UTC+9)</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Academic Period -->
                        <div class="row align-items-center pb-4 border-bottom mb-4 g-3">
                            <div class="col-lg-5">
                                <label class="fw-bold text-dark mb-1" style="font-size: 0.88rem;">Academic period</label>
                                <p class="text-muted mb-0" style="font-size: 0.78rem; line-height: 1.4;">Controls the active period used for dashboards and report calculations.</p>
                            </div>
                            <div class="col-lg-7">
                                <div class="row g-2">
                                    <div class="col-md-6">
                                        <select name="academic_year" class="form-select rounded-3 px-3 py-2 bg-light border-0 text-dark fw-medium shadow-none" style="font-size: 0.85rem;">
                                            <option value="2025 / 2026" {{ ($settings['academic_year'] ?? '') == '2025 / 2026' ? 'selected' : '' }}>2025 / 2026</option>
                                            <option value="2026 / 2027" {{ ($settings['academic_year'] ?? '') == '2026 / 2027' ? 'selected' : '' }}>2026 / 2027</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <select name="academic_semester" class="form-select rounded-3 px-3 py-2 bg-light border-0 text-dark fw-medium shadow-none" style="font-size: 0.85rem;">
                                            <option value="Semester 1" {{ ($settings['academic_semester'] ?? '') == 'Semester 1' ? 'selected' : '' }}>Semester 1</option>
                                            <option value="Semester 2" {{ ($settings['academic_semester'] ?? '') == 'Semester 2' ? 'selected' : '' }}>Semester 2</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Default QR Duration -->
                        <div class="row align-items-center pb-4 border-bottom mb-4 g-3">
                            <div class="col-lg-8">
                                <label class="fw-bold text-dark mb-1" style="font-size: 0.88rem;">Default QR duration</label>
                                <p class="text-muted mb-0" style="font-size: 0.78rem;">New attendance sessions will inherit this expiration time.</p>
                            </div>
                            <div class="col-lg-4">
                                <div class="input-group">
                                    <input type="number" name="qr_duration" class="form-control rounded-3 px-3 py-2 bg-light border-0 text-dark fw-medium text-center shadow-none" value="{{ $settings['qr_duration'] ?? '30' }}" min="1" required style="font-size: 0.85rem;">
                                    <span class="input-group-text bg-transparent border-0 text-muted ps-2" style="font-size: 0.82rem;">minutes</span>
                                </div>
                            </div>
                        </div>

                        <!-- Late Attendance Threshold -->
                        <div class="row align-items-center mb-4 g-3">
                            <div class="col-lg-8">
                                <label class="fw-bold text-dark mb-1" style="font-size: 0.88rem;">Late attendance threshold</label>
                                <p class="text-muted mb-0" style="font-size: 0.78rem;">Students checking in after this window are automatically marked late.</p>
                            </div>
                            <div class="col-lg-4">
                                <div class="input-group">
                                    <input type="number" name="late_threshold" class="form-control rounded-3 px-3 py-2 bg-light border-0 text-dark fw-medium text-center shadow-none" value="{{ $settings['late_threshold'] ?? '15' }}" min="1" required style="font-size: 0.85rem;">
                                    <span class="input-group-text bg-transparent border-0 text-muted ps-2" style="font-size: 0.82rem;">minutes</span>
                                </div>
                            </div>
                        </div>

                        <!-- Footer Status & Save Button (Awalnya tersembunyi/hidden, muncul saat ada perubahan) -->
                        <div class="p-3 rounded-3 d-flex flex-column flex-md-row justify-content-between align-items-center gap-3 mt-4" style="background-color: #f8fafc; border: 1px solid #e2e8f0;">
                            <div class="d-flex align-items-center gap-2 text-primary" style="font-size: 0.82rem;">
                                <i class="bi bi-check-circle fs-5"></i>
                                <span class="fw-medium">Configuration is valid and ready to save</span>
                            </div>
                            <div class="d-flex align-items-center gap-3 w-100 w-md-auto justify-content-end">
                                <span class="text-muted" style="font-size: 0.75rem;">Last edited by Admin Sekolah</span>
                                <button type="submit" id="saveBtn" class="btn btn-primary rounded-pill px-4 py-2 text-white fw-semibold shadow-sm" style="background-color: #2563eb; font-size: 0.82rem; display: none;">
                                    Save Changes
                                </button>
                            </div>
                        </div>

                    </div>
                </form>

            @elseif($currentTab == 'security')
                <!-- TAB 2: SECURITY SETTINGS -->
                <form id="settingsForm" action="{{ route('admin.settings.update', ['tab' => 'security']) }}" method="POST">
                    @csrf
                    <div class="card border-0 rounded-4 shadow-sm bg-white overflow-hidden mb-4" style="padding: 2.2rem !important;">
                        
                        <div class="d-flex align-items-center gap-3 pb-4 border-bottom mb-4">
                            <div class="p-2.5 rounded-3 text-primary d-flex align-items-center justify-content-center" style="width: 42px; height: 42px; background-color: #eff6ff;">
                                <i class="bi bi-shield-check fs-5"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold text-dark mb-0" style="font-size: 1.05rem;">Security settings</h5>
                                <span class="text-muted" style="font-size: 0.8rem;">Configure access & verification for your organization.</span>
                            </div>
                        </div>

                        <!-- Item 1: Require location verification -->
                        <div class="d-flex justify-content-between align-items-center pb-4 border-bottom mb-4">
                            <div>
                                <h6 class="fw-bold text-dark mb-1" style="font-size: 0.9rem;">Require location verification</h6>
                                <p class="text-muted mb-0" style="font-size: 0.78rem;">Validate that students are within the configured campus radius before check-in.</p>
                            </div>
                            <div>
                                <div class="form-check form-switch fs-4">
                                    <input class="form-check-input shadow-none cursor-pointer" type="checkbox" name="security_location_verification" value="1" {{ ($settings['security_location_verification'] ?? '1') == '1' ? 'checked' : '' }} style="width: 50px; height: 26px;">
                                </div>
                            </div>
                        </div>

                        <!-- Item 2: Prevent duplicate scans -->
                        <div class="d-flex justify-content-between align-items-center pb-4 border-bottom mb-4">
                            <div>
                                <h6 class="fw-bold text-dark mb-1" style="font-size: 0.9rem;">Prevent duplicate scans</h6>
                                <p class="text-muted mb-0" style="font-size: 0.78rem;">Block multiple attendance entries from the same account and session.</p>
                            </div>
                            <div>
                                <div class="form-check form-switch fs-4">
                                    <input class="form-check-input shadow-none cursor-pointer" type="checkbox" name="security_prevent_duplicate_scans" value="1" {{ ($settings['security_prevent_duplicate_scans'] ?? '1') == '1' ? 'checked' : '' }} style="width: 50px; height: 26px;">
                                </div>
                            </div>
                        </div>

                        <!-- Item 3: Session auto-expiry -->
                        <div class="d-flex justify-content-between align-items-center pb-4 border-bottom mb-4">
                            <div>
                                <h6 class="fw-bold text-dark mb-1" style="font-size: 0.9rem;">Session auto-expiry</h6>
                                <p class="text-muted mb-0" style="font-size: 0.78rem;">Immediately invalidate QR codes when their configured duration ends.</p>
                            </div>
                            <div>
                                <div class="form-check form-switch fs-4">
                                    <input class="form-check-input shadow-none cursor-pointer" type="checkbox" name="security_session_auto_expiry" value="1" {{ ($settings['security_session_auto_expiry'] ?? '1') == '1' ? 'checked' : '' }} style="width: 50px; height: 26px;">
                                </div>
                            </div>
                        </div>

                        <!-- Item 4: Administrator two-factor authentication -->
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div>
                                <h6 class="fw-bold text-dark mb-1" style="font-size: 0.9rem;">Administrator two-factor authentication</h6>
                                <p class="text-muted mb-0" style="font-size: 0.78rem;">Require a time-based one-time code for privileged accounts.</p>
                            </div>
                            <div>
                                <div class="form-check form-switch fs-4">
                                    <input class="form-check-input shadow-none cursor-pointer" type="checkbox" name="security_admin_2fa" value="1" {{ ($settings['security_admin_2fa'] ?? '0') == '1' ? 'checked' : '' }} style="width: 50px; height: 26px;">
                                </div>
                            </div>
                        </div>

                        <!-- Footer Status & Save Button -->
                        <div class="p-3 rounded-3 d-flex flex-column flex-md-row justify-content-between align-items-center gap-3 mt-4" style="background-color: #f8fafc; border: 1px solid #e2e8f0;">
                            <div class="d-flex align-items-center gap-2 text-primary" style="font-size: 0.82rem;">
                                <i class="bi bi-check-circle fs-5"></i>
                                <span class="fw-medium">Configuration is valid and ready to save</span>
                            </div>
                            <div class="d-flex align-items-center gap-3 w-100 w-md-auto justify-content-end">
                                <span class="text-muted" style="font-size: 0.75rem;">Last edited by Admin Sekolah</span>
                                <button type="submit" id="saveBtn" class="btn btn-primary rounded-pill px-4 py-2 text-white fw-semibold shadow-sm" style="background-color: #2563eb; font-size: 0.82rem; display: none;">
                                    Save Changes
                                </button>
                            </div>
                        </div>

                    </div>
                </form>

            @elseif($currentTab == 'notifications')
                <!-- TAB 3: NOTIFICATIONS SETTINGS -->
                <form id="settingsForm" action="{{ route('admin.settings.update', ['tab' => 'notifications']) }}" method="POST">
                    @csrf
                    <div class="card border-0 rounded-4 shadow-sm bg-white overflow-hidden mb-4" style="padding: 2.2rem !important;">
                        
                        <div class="d-flex align-items-center gap-3 pb-4 border-bottom mb-4">
                            <div class="p-2.5 rounded-3 text-primary d-flex align-items-center justify-content-center" style="width: 42px; height: 42px; background-color: #eff6ff;">
                                <i class="bi bi-bell fs-5"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold text-dark mb-0" style="font-size: 1.05rem;">Notifications settings</h5>
                                <span class="text-muted" style="font-size: 0.8rem;">Configure alerts & delivery for your organization.</span>
                            </div>
                        </div>

                        <!-- Item 1: Low attendance alerts -->
                        <div class="d-flex justify-content-between align-items-center pb-4 border-bottom mb-4">
                            <div>
                                <h6 class="fw-bold text-dark mb-1" style="font-size: 0.9rem;">Low attendance alerts</h6>
                                <p class="text-muted mb-0" style="font-size: 0.78rem;">Notify advisors when a student's attendance falls below 75%.</p>
                            </div>
                            <div>
                                <div class="form-check form-switch fs-4">
                                    <input class="form-check-input shadow-none cursor-pointer" type="checkbox" name="notif_low_attendance" value="1" {{ ($settings['notif_low_attendance'] ?? '1') == '1' ? 'checked' : '' }} style="width: 50px; height: 26px;">
                                </div>
                            </div>
                        </div>

                        <!-- Item 2: Leave request digest -->
                        <div class="d-flex justify-content-between align-items-center pb-4 border-bottom mb-4">
                            <div>
                                <h6 class="fw-bold text-dark mb-1" style="font-size: 0.9rem;">Leave request digest</h6>
                                <p class="text-muted mb-0" style="font-size: 0.78rem;">Send administrators a daily summary of pending leave requests.</p>
                            </div>
                            <div>
                                <div class="form-check form-switch fs-4">
                                    <input class="form-check-input shadow-none cursor-pointer" type="checkbox" name="notif_leave_digest" value="1" {{ ($settings['notif_leave_digest'] ?? '1') == '1' ? 'checked' : '' }} style="width: 50px; height: 26px;">
                                </div>
                            </div>
                        </div>

                        <!-- Item 3: Session completion report -->
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div>
                                <h6 class="fw-bold text-dark mb-1" style="font-size: 0.9rem;">Session completion report</h6>
                                <p class="text-muted mb-0" style="font-size: 0.78rem;">Email teachers a summary immediately after each QR session closes.</p>
                            </div>
                            <div>
                                <div class="form-check form-switch fs-4">
                                    <input class="form-check-input shadow-none cursor-pointer" type="checkbox" name="notif_session_report" value="1" {{ ($settings['notif_session_report'] ?? '1') == '1' ? 'checked' : '' }} style="width: 50px; height: 26px;">
                                </div>
                            </div>
                        </div>

                        <!-- Footer Status & Save Button -->
                        <div class="p-3 rounded-3 d-flex flex-column flex-md-row justify-content-between align-items-center gap-3 mt-4" style="background-color: #f8fafc; border: 1px solid #e2e8f0;">
                            <div class="d-flex align-items-center gap-2 text-primary" style="font-size: 0.82rem;">
                                <i class="bi bi-check-circle fs-5"></i>
                                <span class="fw-medium">Configuration is valid and ready to save</span>
                            </div>
                            <div class="d-flex align-items-center gap-3 w-100 w-md-auto justify-content-end">
                                <span class="text-muted" style="font-size: 0.75rem;">Last edited by Admin Sekolah</span>
                                <button type="submit" id="saveBtn" class="btn btn-primary rounded-pill px-4 py-2 text-white fw-semibold shadow-sm" style="background-color: #2563eb; font-size: 0.82rem; display: none;">
                                    Save Changes
                                </button>
                            </div>
                        </div>

                    </div>
                </form>

            @elseif($currentTab == 'integrations')
                <!-- TAB 4: INTEGRATIONS SETTINGS -->
                <div class="card border-0 rounded-4 shadow-sm bg-white overflow-hidden mb-4" style="padding: 2.2rem !important;">
                    
                    <div class="d-flex align-items-center gap-3 pb-4 border-bottom mb-4">
                        <div class="p-2.5 rounded-3 text-primary d-flex align-items-center justify-content-center" style="width: 42px; height: 42px; background-color: #eff6ff;">
                            <i class="bi bi-plug fs-5"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold text-dark mb-0" style="font-size: 1.05rem;">Integrations settings</h5>
                            <span class="text-muted" style="font-size: 0.8rem;">Configure connected services for your organization.</span>
                        </div>
                    </div>

                    <!-- Item 1: Student Information System -->
                    <div class="d-flex justify-content-between align-items-center pb-4 border-bottom mb-4">
                        <div>
                            <h6 class="fw-bold text-dark mb-1" style="font-size: 0.9rem;">Student Information System</h6>
                            <p class="text-muted mb-0" style="font-size: 0.78rem;">Connected • synced 12 min ago</p>
                        </div>
                        <div>
                            <button type="button" class="btn btn-primary rounded-pill px-3 py-1.5 text-white fw-medium shadow-sm d-flex align-items-center gap-2" style="background-color: #2563eb; font-size: 0.8rem;">
                                <i class="bi bi-sliders" style="font-size: 0.75rem;"></i> Manage
                            </button>
                        </div>
                    </div>

                    <!-- Item 2: Google Workspace -->
                    <div class="d-flex justify-content-between align-items-center pb-4 border-bottom mb-4">
                        <div>
                            <h6 class="fw-bold text-dark mb-1" style="font-size: 0.9rem;">Google Workspace</h6>
                            <p class="text-muted mb-0" style="font-size: 0.78rem;">Connected • 324 accounts</p>
                        </div>
                        <div>
                            <button type="button" class="btn btn-primary rounded-pill px-3 py-1.5 text-white fw-medium shadow-sm d-flex align-items-center gap-2" style="background-color: #2563eb; font-size: 0.8rem;">
                                <i class="bi bi-sliders" style="font-size: 0.75rem;"></i> Manage
                            </button>
                        </div>
                    </div>

                    <!-- Item 3: Webhook endpoint -->
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h6 class="fw-bold text-dark mb-1" style="font-size: 0.9rem;">Webhook endpoint</h6>
                            <p class="text-muted mb-0" style="font-size: 0.78rem;">Not configured</p>
                        </div>
                        <div>
                            <button type="button" class="btn btn-light border rounded-pill px-3 py-1.5 text-primary fw-semibold shadow-sm d-flex align-items-center gap-2 bg-white" style="font-size: 0.8rem; border-color: #cbd5e1 !important;">
                                <i class="bi bi-diagram-3 text-primary" style="font-size: 0.75rem;"></i> Connect
                            </button>
                        </div>
                    </div>

                    <!-- Footer Status -->
                    <div class="p-3 rounded-3 d-flex flex-column flex-md-row justify-content-between align-items-center gap-3 mt-4" style="background-color: #f8fafc; border: 1px solid #e2e8f0;">
                        <div class="d-flex align-items-center gap-2 text-primary" style="font-size: 0.82rem;">
                            <i class="bi bi-check-circle fs-5"></i>
                            <span class="fw-medium">Configuration is valid and ready to save</span>
                        </div>
                        <div class="d-flex align-items-center gap-3 w-100 w-md-auto justify-content-end">
                            <span class="text-muted" style="font-size: 0.75rem;">Last edited by Admin Sekolah</span>
                        </div>
                    </div>

                </div>
            @endif

        </div>

    </div>
</div>

<!-- Script untuk mendeteksi perubahan pada form -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const form = document.getElementById("settingsForm");
        const saveBtn = document.getElementById("saveBtn");

        if (form && saveBtn) {
            // Deteksi perubahan input text, select, maupun checkbox/switch
            form.addEventListener("input", function () {
                saveBtn.style.display = "inline-block";
            });
            form.addEventListener("change", function () {
                saveBtn.style.display = "inline-block";
            });
        }
    });
</script>
@endsection