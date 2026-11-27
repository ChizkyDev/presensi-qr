<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up Admin - Attendly</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Bootstrap Icons CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Google Fonts (Plus Jakarta Sans) -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 min-h-screen flex items-center justify-center p-4 lg:p-8">

    <!-- Card Register Utama -->
    <div class="w-full max-w-6xl bg-white rounded-3xl shadow-xl border border-slate-100 p-4 lg:p-6 grid grid-cols-1 lg:grid-cols-12 gap-8 items-center min-h-[650px]">
        
        <!-- KOLOM KIRI: Banner Gradient & Text -->
        <div class="lg:col-span-5 rounded-3xl bg-gradient-to-tr from-blue-400 via-indigo-300 to-amber-100 p-8 lg:p-12 flex flex-col justify-end relative h-full min-h-[450px] lg:min-h-[580px]">
            <div class="relative z-10 text-white space-y-4">
                <h2 class="text-3xl lg:text-4xl font-extrabold leading-tight tracking-tight drop-shadow-sm">
                    Siap Hemat Waktu Jam Pelajaran Setiap Hari?
                </h2>
                <p class="text-slate-100/90 text-xs lg:text-sm font-normal leading-relaxed">
                    Cukup scan QR Code, semua data kehadiran langsung tercatat rapi — memudahkan admin, guru, dan siswa dalam satu sistem.
                </p>
            </div>
        </div>

        <!-- KOLOM KANAN: Form Register Admin -->
        <div class="lg:col-span-7 flex flex-col justify-center px-2 lg:px-8 py-2">
            
            <!-- Logo Brand Attendly -->
            <div class="flex items-center justify-center mb-6">
                <img src="{{ asset('assets/logo-attendly.png') }}" alt="Attendly Logo" class="h-6 w-auto object-contain">
            </div>

            <!-- Header Title -->
            <div class="text-center mb-6">
                <h1 class="text-2xl lg:text-3xl font-extrabold text-slate-900 tracking-tight">
                    Mulai Permudah Absensi!
                </h1>
                <p class="text-xs text-slate-400 mt-1 font-normal">
                    Masukkan nama, email, dan kata sandi Anda
                </p>
            </div>

            <!-- Google Sign-In Button -->
            <div class="mb-5">
                <button type="button" class="w-full flex items-center justify-center gap-2 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 text-xs font-semibold py-2.5 rounded-full transition shadow-sm">
                    <svg class="w-4 h-4" viewBox="0 0 24 24">
                        <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                        <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                        <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                        <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
                    </svg>
                    Google
                </button>
            </div>

            <!-- Separator -->
            <div class="relative flex items-center justify-center mb-5">
                <div class="border-t border-slate-200 w-full"></div>
                <span class="bg-white px-3 text-[11px] text-slate-400 whitespace-nowrap absolute">Atau lanjutkan masukkan data diri Anda</span>
            </div>

            <!-- Alert Error Ringkasan -->
            @if ($errors->any())
                <div class="mb-4 p-3 bg-red-50 border border-red-200 text-red-600 rounded-xl text-xs font-medium">
                    {{ $errors->first() }}
                </div>
            @endif

            <!-- Form Process -->
            <form action="{{ route('register.admin.submit') }}" method="POST" class="space-y-4">
                @csrf
                
                <!-- Input Nama Lengkap -->
                <div>
                    <label for="name" class="block text-xs font-semibold text-slate-700 mb-1">Nama Lengkap</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="Masukkan Nama Lengkap Anda" required
                        class="w-full px-3.5 py-2 text-xs rounded-lg border border-slate-200 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition placeholder:text-slate-300">
                    @error('name')
                        <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Input Email -->
                <div>
                    <label for="email" class="block text-xs font-semibold text-slate-700 mb-1">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="Masukkan Email Anda" required
                        class="w-full px-3.5 py-2 text-xs rounded-lg border border-slate-200 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition placeholder:text-slate-300">
                    @error('email')
                        <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Input Password & Confirmation -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label for="password" class="block text-xs font-semibold text-slate-700 mb-1">Password</label>
                        <div class="relative">
                            <input type="password" id="password" name="password" placeholder="Masukkan Password" required
                                class="w-full px-3.5 py-2 text-xs rounded-lg border border-slate-200 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition placeholder:text-slate-300 pr-8">
                            <button type="button" onclick="togglePassword('password', 'eyeIcon1')" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 focus:outline-none">
                                <i id="eyeIcon1" class="bi bi-eye text-xs"></i>
                            </button>
                        </div>
                        @error('password')
                            <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-xs font-semibold text-slate-700 mb-1">Confirmation Password</label>
                        <div class="relative">
                            <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Konfirmasi Password" required
                                class="w-full px-3.5 py-2 text-xs rounded-lg border border-slate-200 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition placeholder:text-slate-300 pr-8">
                            <button type="button" onclick="togglePassword('password_confirmation', 'eyeIcon2')" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 focus:outline-none">
                                <i id="eyeIcon2" class="bi bi-eye text-xs"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold py-3 rounded-full shadow-md shadow-blue-500/20 transition-all flex items-center justify-center gap-2 mt-4">
                    Daftar <i class="bi bi-arrow-right"></i>
                </button>
            </form>

            <!-- Tautan Kembali ke Login -->
            <p class="text-center text-xs text-slate-500 mt-6 font-normal">
                Sudah punya akun? 
                <button type="button" onclick="openLoginModal()" class="text-blue-600 font-bold hover:underline">
                    Masuk Sekarang
                </button>
            </p>

            <!-- Modal Masuk Sebagai -->
            <div id="loginModal" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm hidden flex items-center justify-center z-50 p-4 transition-all">
                <div class="bg-white w-full max-w-sm rounded-2xl p-6 shadow-2xl relative">
                    <div class="flex items-center justify-between mb-5">
                        <h3 class="font-bold text-slate-900 text-base">Masuk sebagai</h3>
                        <button type="button" onclick="closeLoginModal()" class="w-7 h-7 rounded-full border border-blue-500 text-blue-500 flex items-center justify-center hover:bg-blue-50 transition">
                            <i class="bi bi-x text-lg"></i>
                        </button>
                    </div>

                    <div class="space-y-3">
                        <button type="button" onclick="selectLoginRole('siswa')" class="w-full flex items-center gap-3.5 p-3 rounded-xl border border-slate-100 bg-white hover:border-blue-500 hover:bg-blue-50/50 transition duration-200 text-left group">
                            <div class="w-10 h-10 rounded-xl bg-blue-600 text-white flex items-center justify-center shrink-0 shadow-md shadow-blue-500/20">
                                <i class="bi bi-mortarboard-fill text-lg"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-sm text-slate-900 group-hover:text-blue-600 transition">Siswa</h4>
                                <p class="text-[11px] text-slate-400">Masuk ke portal siswa</p>
                            </div>
                        </button>

                        <button type="button" onclick="selectLoginRole('guru')" class="w-full flex items-center gap-3.5 p-3 rounded-xl border border-slate-100 bg-white hover:border-blue-500 hover:bg-blue-50/50 transition duration-200 text-left group">
                            <div class="w-10 h-10 rounded-xl bg-blue-600 text-white flex items-center justify-center shrink-0 shadow-md shadow-blue-500/20">
                                <i class="bi bi-person-workspace text-lg"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-sm text-slate-900 group-hover:text-blue-600 transition">Guru</h4>
                                <p class="text-[11px] text-slate-400">Masuk ke portal pendidik</p>
                            </div>
                        </button>

                        <button type="button" onclick="selectLoginRole('admin')" class="w-full flex items-center gap-3.5 p-3 rounded-xl border border-slate-100 bg-white hover:border-blue-500 hover:bg-blue-50/50 transition duration-200 text-left group">
                            <div class="w-10 h-10 rounded-xl bg-blue-600 text-white flex items-center justify-center shrink-0 shadow-md shadow-blue-500/20">
                                <i class="bi bi-shield-lock-fill text-lg"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-sm text-slate-900 group-hover:text-blue-600 transition">Admin</h4>
                                <p class="text-[11px] text-slate-400">Masuk ke portal pengelola</p>
                            </div>
                        </button>
                    </div>
                </div>
            </div>

        </div>

    </div>

    <!-- JavaScript Controls -->
    <script>
        const loginModal = document.getElementById('loginModal');

        function togglePassword(inputId, iconId) {
            const passwordInput = document.getElementById(inputId);
            const eyeIcon = document.getElementById(iconId);
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.replace('bi-eye', 'bi-eye-slash');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.replace('bi-eye-slash', 'bi-eye');
            }
        }

        function openLoginModal() {
            loginModal.classList.remove('hidden');
        }

        function closeLoginModal() {
            loginModal.classList.add('hidden');
        }

        function selectLoginRole(role) {
            closeLoginModal();
            if (role === 'siswa') {
                window.location.href = "{{ route('siswa.login') }}";
            } else if (role === 'guru') {
                window.location.href = "{{ route('guru.login') }}";
            } else if (role === 'admin') {
                window.location.href = "{{ route('admin.login') }}";
            }
        }

        window.onclick = function(event) {
            if (event.target === loginModal) {
                closeLoginModal();
            }
        }
    </script>
</body>
</html>