<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Attendly Student')</title>
    <!-- Bootstrap CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 0;
            background: 
                radial-gradient(ellipse 60% 50% at 90% 10%, rgba(253, 230, 138, 0.6) 0%, rgba(255, 255, 255, 0) 70%),
                radial-gradient(ellipse 70% 60% at 50% 50%, rgba(219, 234, 254, 0.65) 0%, rgba(255, 255, 255, 0) 75%),
                radial-gradient(ellipse 60% 50% at 10% 90%, rgba(253, 230, 138, 0.6) 0%, rgba(255, 255, 255, 0) 70%),
                #f8fafc;
            background-attachment: fixed;
            min-height: 100vh;
        }

        .sidebar-wrapper, .topbar-wrapper {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(10px);
            border-radius: 20px;
        }

        .sidebar-link {
            color: #64748b;
            font-weight: 500;
            border-left: 4px solid transparent;
            transition: all 0.2s ease-in-out;
            text-decoration: none;
        }

        .sidebar-link:hover {
            color: #2563eb;
            background-color: rgba(239, 246, 255, 0.6);
        }

        .sidebar-link.active-menu {
            color: #2563eb !important;
            background-color: #eff6ff !important;
            border-left: 4px solid #2563eb !important;
            font-weight: 600 !important;
            border-top-left-radius: 0 !important;
            border-bottom-left-radius: 0 !important;
        }
    </style>
    @stack('styles')
</head>
<body class="p-3">

    <div class="d-flex gap-3" style="min-height: calc(100vh - 2rem);">
        <!-- ── SIDEBAR ── -->
        <aside class="sidebar-wrapper p-3 shadow-sm d-flex flex-column justify-content-between" style="width: 260px; flex-shrink: 0;">
            <div>
                <!-- Brand Logo -->
                <div class="d-flex align-items-center gap-2 mb-4 px-2">
                <a href="{{ url('/') }}" class="flex items-center">
                 <img src="{{ asset('assets/logo-attendly.png') }}" alt="Attendly Logo" class="h-9 w-auto object-contain">
                </a>
                </div>

                <!-- Navigation Links Siswa -->
                <nav class="nav nav-pills flex-column gap-1">
                    <a href="{{ Route::has('student.overview') ? route('student.overview') : '#' }}" 
                       class="nav-link sidebar-link d-flex align-items-center gap-3 px-3 py-2 rounded-3 {{ request()->routeIs('student.overview*') || request()->routeIs('student.dashboard*') || request()->is('student') || request()->is('student/dashboard') ? 'active-menu' : '' }}">
                        <i class="bi bi-grid-fill fs-5"></i>
                        <span>Overview</span>
                    </a>

                    <a href="{{ Route::has('siswa.scan') ? route('siswa.scan') : '#' }}" 
                        class="nav-link sidebar-link d-flex align-items-center gap-3 px-3 py-2 rounded-3 {{ request()->routeIs('siswa.scan*') || request()->is('siswa/scan') ? 'active-menu' : '' }}">
                        <i class="bi bi-qr-code-scan fs-5"></i>
                        <span>Scan QR</span>
                    </a>

                    <a href="#" 
                       class="nav-link sidebar-link d-flex align-items-center gap-3 px-3 py-2 rounded-3 {{ request()->routeIs('student.attendance*') ? 'active-menu' : '' }}">
                        <i class="bi bi-calendar-check fs-5"></i>
                        <span>My Attendance</span>
                    </a>

                    <a href="{{ Route::has('siswa.leave') ? route('siswa.leave') : '#' }}"
                    class="nav-link sidebar-link d-flex align-items-center gap-3 px-3 py-2 rounded-3 {{ request()->routeIs('siswa.leave*') ? 'active-menu' : '' }}">
                        <i class="bi bi-calendar-plus-fill fs-5"></i>
                        <span>Submit Leave</span>
                    </a>

                    <a href="{{ Route::has('siswa.profile') ? route('siswa.profile') : '#' }}" 
                        class="nav-link sidebar-link d-flex align-items-center gap-3 px-3 py-2 rounded-3 {{ request()->routeIs('siswa.profile*') || request()->is('siswa/profile') ? 'active-menu' : '' }}">
                        <i class="bi bi-person-fill fs-5"></i>
                        <span>Profile</span>
                    </a>
                </nav>
            </div>

            <!-- Profile Info Footer Sidebar -->
            <div class="d-flex align-items-center justify-content-between p-2 rounded-3 bg-light">
                <div class="d-flex align-items-center gap-2">
                    <div class="bg-primary text-white rounded-circle fw-bold d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; font-size: 0.8rem;">
                        {{ strtoupper(substr(Auth::user()->name ?? 'ST', 0, 2)) }}
                    </div>
                    <div style="line-height: 1.2;">
                        <div class="fw-bold text-dark text-truncate" style="font-size: 0.8rem; max-width: 110px;">
                            {{ Auth::user()->name ?? 'Siswa' }}
                        </div>
                        <small class="text-muted" style="font-size: 0.7rem;">
                            {{ Auth::user()->siswa->kelas->kelas ?? '' }} {{ Auth::user()->siswa->kelas->jurusan ?? '' }} {{ Auth::user()->siswa->kelas->nomor_kelas ?? 'Siswa' }}
                        </small>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-link p-0 text-primary border-0" title="Logout">
                        <i class="bi bi-box-arrow-right fs-5"></i>
                    </button>
                </form>
            </div>
        </aside>

        <!-- ── MAIN CONTENT AREA ── -->
        <main class="flex-grow-1 d-flex flex-column gap-3">
            <!-- TOPBAR / HEADER -->
            <header class="topbar-wrapper px-4 py-3 shadow-sm d-flex align-items-center justify-content-between">
                <div class="input-group" style="max-width: 300px;">
                    <span class="input-group-text bg-light border-0 rounded-start-pill ps-3 text-muted"><i class="bi bi-search"></i></span>
                    <input type="text" class="form-control bg-light border-0 rounded-end-pill fs-7" placeholder="Search anything ...">
                </div>

                <div class="d-flex align-items-center gap-3">
                    <button class="btn btn-light rounded-circle p-2 border-0"><i class="bi bi-bell text-muted"></i></button>
                    <button class="btn btn-light rounded-circle p-2 border-0"><i class="bi bi-gear text-muted"></i></button>
                    <div class="d-flex align-items-center gap-2 ms-2">
                        <div class="bg-primary text-white rounded-circle fw-bold d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; font-size: 0.8rem;">
                            {{ strtoupper(substr(Auth::user()->name ?? 'ST', 0, 2)) }}
                        </div>
                        <div style="line-height: 1.2;">
                            <div class="fw-bold text-dark" style="font-size: 0.8rem;">{{ Auth::user()->name ?? 'Siswa' }}</div>
                            <small class="text-muted" style="font-size: 0.7rem;">
                                {{ Auth::user()->siswa->kelas->kelas ?? '' }} {{ Auth::user()->siswa->kelas->jurusan ?? '' }} {{ Auth::user()->siswa->kelas->nomor_kelas ?? 'Siswa' }}
                            </small>
                        </div>
                    </div>
                </div>
            </header>

            <!-- PAGE CONTENT -->
            <div class="flex-grow-1">
                @yield('content')
            </div>
        </main>
    </div>

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @stack('js')
</body>
</html>