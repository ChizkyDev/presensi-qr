<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Attendly</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>
<body class="bg-slate-100 min-h-screen flex items-center justify-center p-4 lg:p-8">

    <!-- CONTAINER UTAMA -->
    <div class="w-full max-w-6xl bg-white rounded-3xl shadow-2xl p-4 lg:p-6 grid grid-cols-1 lg:grid-cols-12 gap-6 min-h-[640px]">
        
        <!-- KOLOM KIRI: Banner & Text -->
        <div class="lg:col-span-6 rounded-2xl bg-gradient-to-tr from-blue-400 via-indigo-300 to-amber-100 p-8 lg:p-12 flex flex-col justify-end relative min-h-[380px] lg:min-h-full">
            <div class="relative z-10 text-white space-y-3">
                <h2 class="text-3xl lg:text-4xl font-extrabold leading-tight tracking-tight drop-shadow-sm">
                    Siap Hemat Waktu Jam Pelajaran Setiap Hari?
                </h2>
                <p class="text-slate-100/90 text-sm font-normal leading-relaxed max-w-md">
                    Cukup scan QR Code, semua data kehadiran langsung tercatat rapi — memudahkan admin, guru, dan siswa dalam satu sistem.
                </p>
            </div>
        </div>

        <!-- KOLOM KANAN: Form Login Utama -->
        <div class="lg:col-span-6 flex flex-col justify-center px-4 lg:px-8 py-4">
            
            <!-- Logo Brand Attendly -->
            <div class="flex items-center justify-center mb-6">
                <img src="{{ asset('assets/logo-attendly.png') }}" alt="Attendly Logo" class="h-6 w-auto object-contain">
            </div>

            <!-- Header Text -->
            <div class="text-center mb-5">
                <h1 class="text-2xl lg:text-3xl font-extrabold text-slate-900 tracking-tight">Selamat Datang!</h1>
                <p class="text-xs text-slate-400 mt-1 font-normal">Masukkan email dan kata sandi Anda</p>
            </div>

            <!-- Display Validation Errors -->
            @if ($errors->any())
                <div class="mb-4 p-3 bg-red-100 border border-red-300 text-red-700 text-xs rounded-xl">
                    <p class="font-medium">{{ $errors->first() }}</p>
                </div>
            @endif

            <!-- TOMBOL ATAS (Google & NIS/NIP dengan 2 Pilihan: Siswa & Guru) -->
            <div class="grid grid-cols-2 gap-3 mb-4">
                <!-- Google Login Button -->
                <a href="#" onclick="alert('Fitur login Google belum tersedia'); return false;" class="flex items-center justify-center gap-2 py-2.5 px-3 rounded-full border border-blue-200 bg-white text-xs font-semibold text-slate-700 hover:border-blue-600 hover:text-blue-600 transition duration-200 shadow-sm">
                    <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24">
                        <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                        <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                        <path fill="#FBBC05" d="M5.84 14.1c-.22-.66-.35-1.36-.35-2.1s.13-1.44.35-2.1V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.62z"/>
                        <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
                    </svg>
                    <span>Google</span>
                </a>

                <!-- Tombol NIS / NIP (Modal dengan 2 Pilihan: Siswa & Guru) -->
                <button type="button" onclick="openNisNipModal()" class="flex items-center justify-center gap-2 py-2.5 px-3 rounded-full border border-blue-200 bg-white text-xs font-semibold text-slate-700 hover:border-blue-600 hover:text-blue-600 transition duration-200 shadow-sm">
                    <i class="bi bi-fingerprint text-blue-600 text-sm"></i>
                    <span>NIS / NIP</span>
                </button>
            </div>

            <!-- Divider -->
            <div class="relative flex items-center justify-center mb-5">
                <div class="border-t border-slate-200 w-full"></div>
                <span class="bg-white px-3 text-[10px] text-slate-400 absolute">Atau masuk dengan kredensial Anda</span>
            </div>

            <!-- Form Inputs Utama -->
            <form id="mainLoginForm" action="{{ Route::has('login.submit') ? route('login.submit') : '#' }}" method="POST" class="space-y-3.5">
                @csrf
                <!-- Input Hidden Role -->
                <input type="hidden" name="role" id="roleInput" value="">

                <div>
                    <input type="text" name="login" id="loginInput" value="{{ old('login') ?? old('email') }}" placeholder="Masukkan Email / NIS / NIP Anda" required class="w-full px-3.5 py-2.5 text-xs rounded-lg border border-slate-200 focus:outline-none focus:border-blue-600 transition">
                </div>

                <div class="relative">
                    <input type="password" name="password" id="passwordInput" placeholder="Masukkan Password" required class="w-full px-3.5 py-2.5 text-xs rounded-lg border border-slate-200 focus:outline-none focus:border-blue-600 transition pr-10">
                    <i class="bi bi-eye-slash absolute right-3.5 top-2.5 text-slate-400 text-sm cursor-pointer" id="toggleIcon" onclick="togglePassword()"></i>
                </div>

                <div class="flex items-center justify-between text-xs pt-1">
                    <label class="flex items-center gap-2 text-slate-600 cursor-pointer">
                        <input type="checkbox" name="remember" class="w-3.5 h-3.5 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                        <span class="text-[11px] font-medium">Ingat Saya</span>
                    </label>
                    
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-[11px] font-medium text-slate-600 hover:text-blue-600 transition">Forgot Password?</a>
                    @else
                        <a href="#" onclick="alert('Fitur reset password belum tersedia.'); return false;" class="text-[11px] font-medium text-slate-600 hover:text-blue-600 transition">Forgot Password?</a>
                    @endif
                </div>

                <!-- Tombol MASUK (Modal dengan 3 Pilihan: Siswa, Guru, Admin) -->
                <button type="button" onclick="openLoginRoleModal()" class="w-full bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold py-3 rounded-full shadow-md shadow-blue-500/30 transition flex items-center justify-center gap-2 mt-2">
                    <span>Masuk</span>
                    <i class="bi bi-arrow-right text-sm"></i>
                </button>
            </form>

            <!-- Link Modal Register -->
            <p class="text-center text-[11px] text-slate-500 mt-5 font-normal">
                Belum punya akun? 
                <button type="button" onclick="openRegisterModal()" class="text-blue-600 font-bold hover:underline focus:outline-none">
                    Daftar Sekarang
                </button>
            </p>
        </div>
    </div>

    <!-- ================= MODAL 1: TOMBOL NIS / NIP (2 Pilihan: Siswa & Guru) ================= -->
    <div id="nisNipModal" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm hidden flex items-center justify-center z-50 p-4 transition-all">
        <div class="bg-white w-full max-w-sm rounded-2xl p-6 shadow-2xl relative">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h3 class="font-bold text-slate-900 text-base">Masuk dengan NIS / NIP</h3>
                    <p class="text-[11px] text-slate-400">Pilih kategori akun Anda</p>
                </div>
                <button type="button" onclick="closeNisNipModal()" class="w-7 h-7 rounded-full border border-blue-500 text-blue-500 flex items-center justify-center hover:bg-blue-50 transition">
                    <i class="bi bi-x text-lg"></i>
                </button>
            </div>

            <div class="space-y-3">
                <button type="button" onclick="selectNisNipRole('siswa')" class="w-full flex items-center gap-3.5 p-3 rounded-xl border border-slate-100 bg-white hover:border-blue-500 hover:bg-blue-50/50 transition duration-200 text-left group">
                    <div class="w-10 h-10 rounded-xl bg-blue-600 text-white flex items-center justify-center shrink-0 shadow-md shadow-blue-500/20">
                        <i class="bi bi-mortarboard-fill text-lg"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-sm text-slate-900 group-hover:text-blue-600 transition">Siswa</h4>
                        <p class="text-[11px] text-slate-400">Gunakan NIS untuk masuk</p>
                    </div>
                </button>

                <button type="button" onclick="selectNisNipRole('guru')" class="w-full flex items-center gap-3.5 p-3 rounded-xl border border-slate-100 bg-white hover:border-blue-500 hover:bg-blue-50/50 transition duration-200 text-left group">
                    <div class="w-10 h-10 rounded-xl bg-blue-600 text-white flex items-center justify-center shrink-0 shadow-md shadow-blue-500/20">
                        <i class="bi bi-person-workspace text-lg"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-sm text-slate-900 group-hover:text-blue-600 transition">Guru</h4>
                        <p class="text-[11px] text-slate-400">Gunakan NIP untuk masuk</p>
                    </div>
                </button>
            </div>
        </div>
    </div>

    <!-- ================= MODAL 2: TOMBOL MASUK (3 Pilihan: Siswa, Guru, Admin) ================= -->
    <div id="loginRoleModal" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm hidden flex items-center justify-center z-50 p-4 transition-all">
        <div class="bg-white w-full max-w-sm rounded-2xl p-6 shadow-2xl relative">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h3 class="font-bold text-slate-900 text-base">Masuk Sebagai</h3>
                    <p class="text-[11px] text-slate-400">Pilih peran akun Anda untuk melanjutkan</p>
                </div>
                <button type="button" onclick="closeLoginRoleModal()" class="w-7 h-7 rounded-full border border-blue-500 text-blue-500 flex items-center justify-center hover:bg-blue-50 transition">
                    <i class="bi bi-x text-lg"></i>
                </button>
            </div>

            <div class="space-y-3">
                <!-- Option 1: Siswa -->
                <button type="button" onclick="submitWithRole('siswa')" class="w-full flex items-center gap-3.5 p-3 rounded-xl border border-slate-100 bg-white hover:border-blue-500 hover:bg-blue-50/50 transition duration-200 text-left group">
                    <div class="w-10 h-10 rounded-xl bg-blue-600 text-white flex items-center justify-center shrink-0 shadow-md shadow-blue-500/20">
                        <i class="bi bi-mortarboard-fill text-lg"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-sm text-slate-900 group-hover:text-blue-600 transition">Siswa</h4>
                        <p class="text-[11px] text-slate-400">Masuk sebagai Akun Siswa</p>
                    </div>
                </button>

                <!-- Option 2: Guru -->
                <button type="button" onclick="submitWithRole('guru')" class="w-full flex items-center gap-3.5 p-3 rounded-xl border border-slate-100 bg-white hover:border-blue-500 hover:bg-blue-50/50 transition duration-200 text-left group">
                    <div class="w-10 h-10 rounded-xl bg-blue-600 text-white flex items-center justify-center shrink-0 shadow-md shadow-blue-500/20">
                        <i class="bi bi-person-workspace text-lg"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-sm text-slate-900 group-hover:text-blue-600 transition">Guru</h4>
                        <p class="text-[11px] text-slate-400">Masuk sebagai Akun Pendidik</p>
                    </div>
                </button>

                <!-- Option 3: Admin (Diubah warnanya jadi biru bg-blue-600) -->
                <button type="button" onclick="submitWithRole('admin')" class="w-full flex items-center gap-3.5 p-3 rounded-xl border border-slate-100 bg-white hover:border-blue-500 hover:bg-blue-50/50 transition duration-200 text-left group">
                    <div class="w-10 h-10 rounded-xl bg-blue-600 text-white flex items-center justify-center shrink-0 shadow-md shadow-blue-500/20">
                        <i class="bi bi-shield-lock-fill text-lg"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-sm text-slate-900 group-hover:text-blue-600 transition">Admin</h4>
                        <p class="text-[11px] text-slate-400">Masuk sebagai Pengelola Sistem</p>
                    </div>
                </button>
            </div>
        </div>
    </div>

    <!-- ================= MODAL: DAFTAR SEBAGAI ================= -->
    <div id="registerModal" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm hidden flex items-center justify-center z-50 p-4 transition-all">
        <div class="bg-white w-full max-w-sm rounded-2xl p-6 shadow-2xl relative">
            <div class="flex items-center justify-between mb-5">
                <h3 class="font-bold text-slate-900 text-base">Daftar sebagai</h3>
                <button type="button" onclick="closeRegisterModal()" class="w-7 h-7 rounded-full border border-blue-500 text-blue-500 flex items-center justify-center hover:bg-blue-50 transition">
                    <i class="bi bi-x text-lg"></i>
                </button>
            </div>

            <div class="space-y-3">
                <button type="button" onclick="selectRegisterRole('siswa')" class="w-full flex items-center gap-3.5 p-3 rounded-xl border border-slate-100 bg-white hover:border-blue-500 hover:bg-blue-50/50 transition duration-200 text-left group">
                    <div class="w-10 h-10 rounded-xl bg-blue-600 text-white flex items-center justify-center shrink-0 shadow-md shadow-blue-500/20">
                        <i class="bi bi-mortarboard-fill text-lg"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-sm text-slate-900 group-hover:text-blue-600 transition">Siswa</h4>
                        <p class="text-[11px] text-slate-400">Buat akun siswa baru</p>
                    </div>
                </button>

                <button type="button" onclick="selectRegisterRole('guru')" class="w-full flex items-center gap-3.5 p-3 rounded-xl border border-slate-100 bg-white hover:border-blue-500 hover:bg-blue-50/50 transition duration-200 text-left group">
                    <div class="w-10 h-10 rounded-xl bg-blue-600 text-white flex items-center justify-center shrink-0 shadow-md shadow-blue-500/20">
                        <i class="bi bi-person-workspace text-lg"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-sm text-slate-900 group-hover:text-blue-600 transition">Guru</h4>
                        <p class="text-[11px] text-slate-400">Buat akun pendidik baru</p>
                    </div>
                </button>

                <button type="button" onclick="selectRegisterRole('admin')" class="w-full flex items-center gap-3.5 p-3 rounded-xl border border-slate-100 bg-white hover:border-blue-500 hover:bg-blue-50/50 transition duration-200 text-left group">
                    <div class="w-10 h-10 rounded-xl bg-blue-600 text-white flex items-center justify-center shrink-0 shadow-md shadow-blue-500/20">
                        <i class="bi bi-shield-lock-fill text-lg"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-sm text-slate-900 group-hover:text-blue-600 transition">Admin</h4>
                        <p class="text-[11px] text-slate-400">Buat akun pengelola sistem</p>
                    </div>
                </button>
            </div>
        </div>
    </div>

    <!-- SCRIPT JAVASCRIPT -->
    <script>
        const nisNipModal = document.getElementById('nisNipModal');
        const loginRoleModal = document.getElementById('loginRoleModal');
        const registerModal = document.getElementById('registerModal');
        const mainLoginForm = document.getElementById('mainLoginForm');
        const roleInput = document.getElementById('roleInput');

        // Fungsi untuk Modal NIS / NIP (2 Pilihan: Siswa & Guru)
        function openNisNipModal() {
            nisNipModal.classList.remove('hidden');
        }

        function closeNisNipModal() {
            nisNipModal.classList.add('hidden');
        }

        function selectNisNipRole(role) {
            closeNisNipModal();
            const routes = {
                siswa: "{{ Route::has('siswa.login') ? route('siswa.login') : '' }}",
                guru: "{{ Route::has('guru.login') ? route('guru.login') : '' }}"
            };
            if(routes[role]) {
                window.location.href = routes[role];
            } else {
                roleInput.value = role;
                mainLoginForm.submit();
            }
        }

        // Fungsi untuk Tombol Masuk (3 Pilihan: Siswa, Guru, Admin)
        function openLoginRoleModal() {
            const loginInput = document.getElementById('loginInput');
            const passwordInput = document.getElementById('passwordInput');

            if (!loginInput.value || !passwordInput.value) {
                mainLoginForm.reportValidity();
                return;
            }

            loginRoleModal.classList.remove('hidden');
        }

        function closeLoginRoleModal() {
            loginRoleModal.classList.add('hidden');
        }

        function submitWithRole(role) {
            roleInput.value = role;
            closeLoginRoleModal();
            mainLoginForm.submit();
        }

        function togglePassword() {
            const pwd = document.getElementById('passwordInput');
            const icon = document.getElementById('toggleIcon');
            if (pwd.type === 'password') {
                pwd.type = 'text';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            } else {
                pwd.type = 'password';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            }
        }

        function openRegisterModal() {
            registerModal.classList.remove('hidden');
        }

        function closeRegisterModal() {
            registerModal.classList.add('hidden');
        }

        function selectRegisterRole(role) {
            closeRegisterModal();
            const registerRoutes = {
                siswa: "{{ Route::has('register.siswa') ? route('register.siswa') : '' }}",
                guru: "{{ Route::has('register.guru') ? route('register.guru') : '' }}",
                admin: "{{ Route::has('register.admin') ? route('register.admin') : '' }}"
            };

            const targetUrl = registerRoutes[role];
            if (targetUrl !== "") {
                window.location.href = targetUrl;
            } else {
                alert(`Halaman pendaftaran untuk ${role} belum siap.`);
            }
        }

        window.onclick = function(event) {
            if (event.target === nisNipModal) closeNisNipModal();
            if (event.target === loginRoleModal) closeLoginRoleModal();
            if (event.target === registerModal) closeRegisterModal();
        }
    </script>
</body>
</html>