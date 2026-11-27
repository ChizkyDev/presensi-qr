<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up Guru - Attendly</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts (Plus Jakarta Sans) -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-100 min-h-screen flex items-center justify-center p-4 lg:p-8">

    <!-- Container Utama Split Layout -->
    <div class="bg-white w-full max-w-6xl rounded-3xl shadow-2xl overflow-hidden flex flex-col lg:flex-row p-4 lg:p-6 gap-6">
        
        <!-- SECTION KIRI: Visual Banner / Gradient Card -->
        <div class="lg:w-1/2 rounded-2xl bg-gradient-to-tr from-blue-400 via-indigo-300 to-amber-100 p-8 lg:p-12 flex flex-col justify-end relative min-h-[380px] lg:min-h-[640px]">
            <div class="relative z-10 text-white space-y-3">
                <h2 class="text-3xl lg:text-4xl font-extrabold leading-tight tracking-tight drop-shadow-sm">
                    Siap Hemat Waktu Jam Pelajaran Setiap Hari?
                </h2>
                <p class="text-slate-100/90 text-sm font-normal leading-relaxed max-w-md">
                    Cukup scan QR Code, semua data kehadiran langsung tercatat rapi — memudahkan admin, guru, dan siswa dalam satu sistem.
                </p>
            </div>
        </div>

        <!-- SECTION KANAN: Form Registration -->
        <div class="lg:w-1/2 flex flex-col justify-center px-4 lg:px-8 py-4">
            
            <!-- Logo Brand Attendly -->
            <div class="flex items-center justify-center mb-6">
                <img src="{{ asset('assets/logo-attendly.png') }}" alt="Attendly Logo" class="h-6 w-auto object-contain">
            </div>

            <!-- Heading -->
            <div class="text-center mb-6">
                <h1 class="text-2xl lg:text-3xl font-extrabold text-slate-900 tracking-tight">
                    Mulai Permudah Absensi!
                </h1>
                <p class="text-xs text-slate-400 mt-1">
                    Masukkan email dan kata sandi Anda
                </p>
            </div>

            <!-- Alert Validation Errors -->
            @if ($errors->any())
                <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-xl text-xs text-red-600">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Tombol Google -->
            <button type="button" class="w-full flex items-center justify-center gap-3 py-2.5 px-4 rounded-full border border-slate-200 hover:bg-slate-50 transition duration-200 text-xs font-semibold text-slate-700 shadow-sm mb-5">
                <svg class="w-4 h-4" viewBox="0 0 24 24">
                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
                </svg>
                Google
            </button>

            <!-- Divider -->
            <div class="relative flex items-center justify-center mb-5">
                <div class="border-t border-slate-200 w-full"></div>
                <span class="bg-white px-3 text-[10px] text-slate-400 font-medium absolute whitespace-nowrap">
                    Atau lanjutkan masukkan data diri Anda
                </span>
            </div>

            <!-- Form Registrasi Guru -->
            <form action="{{ route('register.guru.store') }}" method="POST" class="space-y-3.5">
                @csrf

                <!-- Nama Lengkap -->
                <div>
                    <label for="fullname" class="block text-xs font-semibold text-slate-700 mb-1">Nama Lengkap</label>
                    <input type="text" id="fullname" name="fullname" value="{{ old('fullname') }}" placeholder="Masukkan Nama Lengkap Anda" required
                        class="w-full px-3.5 py-2.5 border border-blue-200 rounded-lg text-xs text-slate-800 placeholder-slate-300 focus:outline-none focus:border-blue-600 transition">
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-xs font-semibold text-slate-700 mb-1">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="Masukkan Email Anda" required
                        class="w-full px-3.5 py-2.5 border border-blue-200 rounded-lg text-xs text-slate-800 placeholder-slate-300 focus:outline-none focus:border-blue-600 transition">
                </div>

                <!-- Nomor Induk Pendidik (NIP) -->
                <div>
                    <label for="nip" class="block text-xs font-semibold text-slate-700 mb-1">Nomor Induk Pendidik</label>
                    <input type="text" id="nip" name="nip" value="{{ old('nip') }}" placeholder="Masukkan NIP" required
                        class="w-full px-3.5 py-2.5 border border-blue-200 rounded-lg text-xs text-slate-800 placeholder-slate-300 focus:outline-none focus:border-blue-600 transition">
                </div>

                <!-- Primary Subject & Homeroom (Grid 2 Kolom) -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <!-- Primary Subject -->
                    <div>
                        <label for="subject" class="block text-xs font-semibold text-slate-700 mb-1">Primary Subject</label>
                        <input type="text" id="subject" name="subject" value="{{ old('subject') }}" placeholder="Masukkan Mata Pelajaran" required
                            class="w-full px-3.5 py-2.5 border border-blue-200 rounded-lg text-xs text-slate-800 placeholder-slate-300 focus:outline-none focus:border-blue-600 transition">
                    </div>

                    <!-- Homeroom -->
                    <div>
                        <label for="homeroom" class="block text-xs font-semibold text-slate-700 mb-1">Homeroom</label>
                        <input type="text" id="homeroom" name="homeroom" value="{{ old('homeroom') }}" placeholder="Masukkan Kelas"
                            class="w-full px-3.5 py-2.5 border border-blue-200 rounded-lg text-xs text-slate-800 placeholder-slate-300 focus:outline-none focus:border-blue-600 transition">
                    </div>
                </div>

                <!-- Password & Confirm Password (Grid 2 Kolom) -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-xs font-semibold text-slate-700 mb-1">Password</label>
                        <div class="relative">
                            <input type="password" id="password" name="password" placeholder="Masukkan Password" required
                                class="w-full pl-3.5 pr-8 py-2.5 border border-blue-200 rounded-lg text-xs text-slate-800 placeholder-slate-300 focus:outline-none focus:border-blue-600 transition">
                            <button type="button" onclick="togglePasswordVisibility('password', 'iconPass')" class="absolute inset-y-0 right-0 flex items-center pr-2.5 text-slate-400 hover:text-slate-600">
                                <i id="iconPass" class="bi bi-eye text-xs"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Confirmation Password -->
                    <div>
                        <label for="password_confirmation" class="block text-xs font-semibold text-slate-700 mb-1">Confirmation Password</label>
                        <div class="relative">
                            <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Konfirmasi Password" required
                                class="w-full pl-3.5 pr-8 py-2.5 border border-blue-200 rounded-lg text-xs text-slate-800 placeholder-slate-300 focus:outline-none focus:border-blue-600 transition">
                            <button type="button" onclick="togglePasswordVisibility('password_confirmation', 'iconConfirm')" class="absolute inset-y-0 right-0 flex items-center pr-2.5 text-slate-400 hover:text-slate-600">
                                <i id="iconConfirm" class="bi bi-eye text-xs"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Checkbox & Forgot Password -->
                <div class="flex items-center justify-between pt-1">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="remember" class="w-3.5 h-3.5 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                        <span class="text-[11px] font-medium text-slate-600">Ingat Saya</span>
                    </label>
                    <a href="#" class="text-[11px] font-medium text-slate-600 hover:text-blue-600 transition">Forgot Password</a>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs rounded-full shadow-md shadow-blue-500/30 flex items-center justify-center gap-2 transition duration-200 active:scale-[0.99] mt-2">
                    <span>Daftar</span>
                    <i class="bi bi-arrow-right text-sm"></i>
                </button>
            </form>

            <!-- Link Login -->
            <p class="text-center text-[11px] text-slate-500 mt-5">
                Sudah punya akun? <a href="{{ route('login') }}" class="font-bold text-blue-600 hover:underline">Masuk Sekarang</a>
            </p>

        </div>
    </div>

    <!-- Toggle Password Script -->
    <script>
        function togglePasswordVisibility(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);

            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            }
        }
    </script>
</body>
</html>