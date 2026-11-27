<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendly - Presensi Digital Sekolah</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        poppins: ['Poppins', 'sans-serif']
                    },
                    colors: {
                        primary: '#2563EB',
                        primaryDark: '#1D4ED8',
                    }
                }
            }
        };
    </script>

    <!-- Google Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <style>
        * { scroll-behavior: smooth; }
        body { font-family: 'Poppins', sans-serif; }

        /* Gradien Campur (Soft Mesh Gradient) Sesuai Figma */
        .bg-mesh-gradient {
            background-color: #F8FAFC;
            background-image: 
                radial-gradient(at 95% 5%, rgba(254, 240, 138, 0.45) 0px, transparent 50%),
                radial-gradient(at 5% 95%, rgba(254, 215, 170, 0.35) 0px, transparent 50%),
                radial-gradient(at 50% 50%, rgba(239, 246, 255, 0.6) 0px, transparent 70%);
        }
    </style>
</head>

<body class="bg-white text-slate-900 antialiased">

    <!-- NAVBAR -->
    <header class="bg-white">
        <nav class="max-w-7xl mx-auto px-6 lg:px-12 py-6 flex items-center justify-between">
            <a href="{{ url('/') }}" class="flex items-center">
                <img src="{{ asset('assets/logo-attendly.png') }}" alt="Attendly Logo" class="h-9 w-auto object-contain">
            </a>

            <div class="hidden md:flex items-center gap-8 text-xs font-semibold text-slate-500">
                <a href="#cara-kerja" class="hover:text-primary transition">How it Works</a>
                <a href="#fitur" class="hover:text-primary transition">Features</a>
                <a href="#role" class="hover:text-primary transition">Roles</a>
                <a href="#faq" class="hover:text-primary transition">FAQ</a>
            </div>

            <a href="{{ route('login') }}" class="bg-primary hover:bg-primaryDark text-white text-xs font-semibold px-6 py-3 rounded-full shadow-md shadow-blue-500/20 flex items-center gap-2 transition">
                Login Now <i class="bi bi-arrow-right"></i>
            </a>
        </nav>
    </header>

    <!-- HERO SECTION -->
    <section class="max-w-4xl mx-auto px-6 pt-12 pb-16 text-center">
        <h1 class="text-4xl md:text-6xl font-extrabold leading-tight text-slate-900 tracking-tight">
            Presensi Digital, Sekolah<br>Lebih Tertib
        </h1>

        <p class="max-w-xl mx-auto mt-6 text-xs md:text-sm leading-relaxed text-slate-400">
            Sistem absensi digital berbasis QR Code yang membantu admin, guru, dan siswa mencatat kehadiran dengan lebih cepat, akurat, dan efisien.
        </p>

        <a href="#login" class="inline-flex items-center gap-2 mt-8 px-7 py-3.5 rounded-full bg-primary text-white text-xs font-semibold shadow-lg shadow-blue-500/25 hover:bg-primaryDark transition">
            Schedule Call <i class="bi bi-arrow-right"></i>
        </a>

        <!-- MOCKUP HP -->
        <div class="relative flex justify-center mt-12">
            <div class="absolute left-[12%] md:left-[18%] top-1/2 -translate-y-1/2 flex items-center gap-2 bg-primary text-white rounded-full px-5 py-2 text-xs font-medium shadow-xl">
                Attendly <i class="bi bi-cursor-fill text-[10px] transform rotate-90"></i>
            </div>

            <div class="relative w-64 h-[430px] bg-slate-900 rounded-[40px] p-3 shadow-2xl border-4 border-slate-800">
                <div class="relative w-full h-full bg-white rounded-[30px] overflow-hidden flex flex-col items-center justify-center p-6 border border-slate-100">
                    <div class="absolute top-3 left-1/2 -translate-x-1/2 w-20 h-4 bg-slate-900 rounded-full"></div>
                    
                    <div class="w-48 h-48 border-4 border-slate-900 rounded-2xl p-3 flex flex-col items-center justify-center bg-white shadow-inner">
                        <i class="bi bi-qr-code text-[140px] text-slate-900 leading-none"></i>
                    </div>
                    <p class="mt-6 font-black tracking-[6px] text-lg text-slate-900">SCAN ME</p>
                </div>
            </div>
        </div>

        <!-- CLIENT LOGOS -->
        <div class="mt-14">
            <p class="text-[11px] text-slate-400 font-medium tracking-wide">Digunakan oleh</p>
            <div class="flex items-center justify-center gap-8 md:gap-12 mt-4 text-slate-400 text-xs md:text-sm font-semibold grayscale opacity-70">
                <span class="flex items-center gap-1.5"><i class="bi bi-square-half"></i> afterpay</span>
                <span class="flex items-center gap-1.5"><i class="bi bi-triangle-fill"></i> Basecamp</span>
                <span class="flex items-center gap-1.5"><i class="bi bi-circle-fill"></i> maze</span>
            </div>
        </div>
    </section>

   <!-- CARA KERJA SECTION (MESH GRADIENT DIAGONAL + CARD SHADOW TEGAS) -->
<section id="cara-kerja" 
         class="py-20 border-y border-slate-100 relative overflow-hidden"
         style="background: 
            radial-gradient(ellipse 60% 50% at 90% 10%, rgba(253, 230, 138, 0.6) 0%, rgba(255, 255, 255, 0) 70%),
            radial-gradient(ellipse 70% 60% at 50% 50%, rgba(219, 234, 254, 0.65) 0%, rgba(255, 255, 255, 0) 75%),
            radial-gradient(ellipse 60% 50% at 10% 90%, rgba(253, 230, 138, 0.6) 0%, rgba(255, 255, 255, 0) 70%),
            #f8fafc;">
    
    <div class="max-w-7xl mx-auto px-6 lg:px-12 relative z-10">
        
        <!-- HEADER SECTION -->
        <div class="grid md:grid-cols-12 gap-8 items-center mb-16">
            <div class="md:col-span-7">
                <span class="inline-flex items-center gap-2 bg-white text-blue-600 border border-blue-100 px-4 py-1.5 rounded-full text-xs font-semibold shadow-sm">
                    <i class="bi bi-lightbulb-fill text-blue-600"></i> How it Works
                </span>
                <h2 class="text-3xl md:text-5xl font-extrabold text-slate-900 leading-[1.2] mt-4 tracking-tight">
                    Langkah Mudah Presensi<br class="hidden md:block">Digital Siswa dengan<br class="hidden md:block">Pindai QR Code
                </h2>
            </div>
            <div class="md:col-span-5 flex items-center">
                <p class="text-xs md:text-sm leading-relaxed text-slate-500 font-normal">
                    Sistem kami dirancang agar proses pencatatan kehadiran menjadi lebih fleksibel, mencegah kecurangan absensi, dan menghemat waktu kegiatan belajar mengajar di sekolah.
                </p>
            </div>
        </div>

        <!-- Steps Row 1 (3 Cards) -->
        <div class="grid md:grid-cols-3 gap-6">
            <!-- Step 1 -->
            <div class="bg-white/90 backdrop-blur-md rounded-[28px] p-6 shadow-lg shadow-slate-200/60 border border-slate-200/80 flex flex-col justify-between hover:-translate-y-1 transition duration-300">
                <div class="h-44 rounded-2xl bg-[#EEF2FF] flex items-center justify-center p-4">
                    <div class="w-36 bg-white rounded-2xl shadow-sm p-4 border border-slate-100/80">
                        <div class="w-10 h-10 rounded-full bg-blue-500 text-white mx-auto flex items-center justify-center mb-3">
                            <i class="bi bi-person-fill text-lg"></i>
                        </div>
                        <div class="h-1.5 bg-slate-100 rounded mb-2"></div>
                        <div class="h-1.5 bg-slate-100 rounded w-2/3 mx-auto"></div>
                        <div class="mt-3 h-5 bg-blue-600 rounded-md"></div>
                    </div>
                </div>
                <div class="mt-6">
                    <div class="flex items-center justify-between text-xs text-slate-400 mb-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span>
                        <span class="font-medium">Step 1</span>
                    </div>
                    <h3 class="font-bold text-lg text-slate-900">Login Sesuai Role</h3>
                    <p class="text-xs text-slate-400 mt-2 leading-relaxed">Admin, guru, dan siswa login menggunakan akun masing-masing. Sistem otomatis mengarahkan ke dashboard sesuai peran yang dimiliki.</p>
                </div>
            </div>

            <!-- Step 2 -->
            <div class="bg-white/90 backdrop-blur-md rounded-[28px] p-6 shadow-lg shadow-slate-200/60 border border-slate-200/80 flex flex-col justify-between hover:-translate-y-1 transition duration-300">
                <div class="h-44 rounded-2xl bg-[#EEF2FF] flex items-center justify-center p-4">
                    <div class="w-44 bg-white rounded-xl shadow-sm p-3 border border-slate-100/80">
                        <div class="h-3 bg-blue-600 rounded-t-sm mb-2"></div>
                        <div class="grid grid-cols-3 gap-1.5">
                            <div class="h-6 bg-blue-100/70 rounded-sm"></div>
                            <div class="h-6 bg-blue-200/70 rounded-sm"></div>
                            <div class="h-6 bg-blue-100/70 rounded-sm"></div>
                            <div class="h-6 bg-blue-200/70 rounded-sm"></div>
                            <div class="h-6 bg-blue-100/70 rounded-sm"></div>
                            <div class="h-6 bg-blue-200/70 rounded-sm"></div>
                        </div>
                    </div>
                </div>
                <div class="mt-6">
                    <div class="flex items-center justify-between text-xs text-slate-400 mb-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-orange-500"></span>
                        <span class="font-medium">Step 2</span>
                    </div>
                    <h3 class="font-bold text-lg text-slate-900">Guru Membuka Sesi Presensi</h3>
                    <p class="text-xs text-slate-400 mt-2 leading-relaxed">Di setiap jam pelajaran, guru membuka sesi presensi untuk kelas yang diajar. Sistem otomatis menghasilkan QR Code khusus untuk sesi tersebut.</p>
                </div>
            </div>

            <!-- Step 3 -->
            <div class="bg-white/90 backdrop-blur-md rounded-[28px] p-6 shadow-lg shadow-slate-200/60 border border-slate-200/80 flex flex-col justify-between hover:-translate-y-1 transition duration-300">
                <div class="h-44 rounded-2xl bg-[#EEF2FF] flex items-center justify-center p-4">
                    <div class="w-24 h-32 bg-white rounded-2xl shadow-sm border border-slate-200 p-2 flex flex-col items-center justify-center">
                        <i class="bi bi-qr-code text-4xl text-blue-600"></i>
                        <div class="w-8 h-1 bg-slate-200 rounded-full mt-2"></div>
                    </div>
                </div>
                <div class="mt-6">
                    <div class="flex items-center justify-between text-xs text-slate-400 mb-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-purple-600"></span>
                        <span class="font-medium">Step 3</span>
                    </div>
                    <h3 class="font-bold text-lg text-slate-900">Siswa Scan QR Code</h3>
                    <p class="text-xs text-slate-400 mt-2 leading-relaxed">Siswa memindai QR Code yang ditampilkan guru menggunakan HP masing-masing. Kehadiran langsung tercatat secara otomatis dan real-time.</p>
                </div>
            </div>
        </div>

        <!-- Steps Row 2 (2 Wide Cards) -->
        <div class="grid md:grid-cols-2 gap-6 mt-6">
            <!-- Step 4 -->
            <div class="bg-white/90 backdrop-blur-md rounded-[28px] p-6 shadow-lg shadow-slate-200/60 border border-slate-200/80 flex gap-6 items-center hover:-translate-y-1 transition duration-300">
                <div class="w-36 h-36 shrink-0 rounded-2xl bg-[#EEF2FF] flex items-center justify-center">
                    <div class="w-20 h-24 bg-white rounded-xl shadow-sm border border-slate-100/80 p-2 flex flex-col items-center justify-center relative">
                        <i class="bi bi-file-earmark-check-fill text-3xl text-blue-600"></i>
                        <div class="w-10 h-1.5 bg-slate-100 rounded mt-2"></div>
                        <div class="w-6 h-1.5 bg-slate-100 rounded mt-1"></div>
                        <div class="absolute -bottom-2 -right-2 w-7 h-7 bg-blue-600 text-white rounded-full flex items-center justify-center text-xs shadow-md">
                            <i class="bi bi-check"></i>
                        </div>
                    </div>
                </div>
                <div>
                    <div class="flex items-center justify-between text-xs text-slate-400 mb-1">
                        <span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span>
                        <span class="font-medium">Step 4</span>
                    </div>
                    <h3 class="font-bold text-lg text-slate-900">Pengajuan Izin/Sakit</h3>
                    <p class="text-xs text-slate-400 mt-1.5 leading-relaxed">Siswa yang berhalangan hadir dapat mengajukan izin atau sakit beserta bukti pendukung, lalu diverifikasi langsung oleh guru.</p>
                </div>
            </div>

            <!-- Step 5 -->
            <div class="bg-white/90 backdrop-blur-md rounded-[28px] p-6 shadow-lg shadow-slate-200/60 border border-slate-200/80 flex gap-6 items-center hover:-translate-y-1 transition duration-300">
                <div class="w-36 h-36 shrink-0 rounded-2xl bg-[#EEF2FF] flex items-center justify-center p-3">
                    <div class="w-full h-full bg-white rounded-xl shadow-sm border border-slate-100/80 p-2 flex items-end justify-center gap-1.5">
                        <div class="w-3 bg-blue-200 h-8 rounded-t-sm"></div>
                        <div class="w-3 bg-blue-600 h-14 rounded-t-sm"></div>
                        <div class="w-3 bg-blue-300 h-10 rounded-t-sm"></div>
                        <div class="w-3 bg-blue-400 h-16 rounded-t-sm"></div>
                    </div>
                </div>
                <div>
                    <div class="flex items-center justify-between text-xs text-slate-400 mb-1">
                        <span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span>
                        <span class="font-medium">Step 5</span>
                    </div>
                    <h3 class="font-bold text-lg text-slate-900">Pantau & Laporan</h3>
                    <p class="text-xs text-slate-400 mt-1.5 leading-relaxed">Guru memantau kehadiran kelas yang diajar, sementara admin memantau seluruh sekolah. Laporan dapat dicetak dalam format PDF atau Excel.</p>
                </div>
            </div>
        </div>

    </div>
</section>
    
    <!-- FITUR SECTION -->
    <section id="fitur" class="py-24 px-6 lg:px-12 bg-white">
        <div class="max-w-7xl mx-auto">
            
            <div class="text-center">
                <span class="inline-flex items-center gap-2 text-xs font-semibold text-blue-600 bg-blue-50/80 px-4 py-1.5 rounded-full border border-blue-100/50">
                    <i class="bi bi-stars text-blue-500"></i> Features
                </span>
                <h2 class="text-3xl md:text-5xl font-extrabold text-slate-900 mt-5 tracking-tight">
                    Kelola Presensi Sekolah<br>Tanpa Ribet
                </h2>
                <p class="text-xs md:text-sm text-slate-400 max-w-xl mx-auto mt-4 leading-relaxed font-normal">
                    Cukup scan QR Code, semua data kehadiran langsung tercatat rapi — memudahkan admin, guru, dan siswa dalam satu sistem
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-x-12 gap-y-16 mt-20">
                
                <div class="flex flex-col items-start text-left">
                    <div class="w-11 h-11 rounded-full bg-blue-600 text-white flex items-center justify-center shadow-md shadow-blue-500/20 mb-5">
                        <i class="bi bi-qr-code-scan text-lg"></i>
                    </div>
                    <h3 class="font-bold text-xl text-slate-900 tracking-tight">Scan QR Code</h3>
                    <p class="text-xs text-slate-400 leading-relaxed mt-2.5 font-normal">
                        Presensi cepat dan akurat hanya dengan memindai QR Code pribadi siswa.
                    </p>
                </div>

                <div class="flex flex-col items-start text-left">
                    <div class="w-11 h-11 rounded-full bg-blue-600 text-white flex items-center justify-center shadow-md shadow-blue-500/20 mb-5">
                        <i class="bi bi-bar-chart-fill text-lg"></i>
                    </div>
                    <h3 class="font-bold text-xl text-slate-900 tracking-tight">Dashboard Real-Time</h3>
                    <p class="text-xs text-slate-400 leading-relaxed mt-2.5 font-normal">
                        Statistik dan grafik kehadiran dapat dipantau langsung oleh admin maupun guru.
                    </p>
                </div>

                <div class="flex flex-col items-start text-left">
                    <div class="w-11 h-11 rounded-full bg-blue-600 text-white flex items-center justify-center shadow-md shadow-blue-500/20 mb-5">
                        <i class="bi bi-file-earmark-text-fill text-lg"></i>
                    </div>
                    <h3 class="font-bold text-xl text-slate-900 tracking-tight">Rekap & Laporan Otomatis</h3>
                    <p class="text-xs text-slate-400 leading-relaxed mt-2.5 font-normal">
                        Data kehadiran otomatis terekap dan siap dicetak dalam format PDF atau Excel.
                    </p>
                </div>

                <div class="flex flex-col items-start text-left">
                    <div class="w-11 h-11 rounded-full bg-blue-600 text-white flex items-center justify-center shadow-md shadow-blue-500/20 mb-5">
                        <i class="bi bi-file-earmark-arrow-down-fill text-lg"></i>
                    </div>
                    <h3 class="font-bold text-xl text-slate-900 tracking-tight">Kelola Data Siswa & Kelas</h3>
                    <p class="text-xs text-slate-400 leading-relaxed mt-2.5 font-normal">
                        Admin dapat mengelola data siswa dan kelas (mengubah, menambah, dan menghapus data siswa dan kelas).
                    </p>
                </div>

                <div class="flex flex-col items-start text-left">
                    <div class="w-11 h-11 rounded-full bg-blue-600 text-white flex items-center justify-center shadow-md shadow-blue-500/20 mb-5">
                        <i class="bi bi-pencil-fill text-lg"></i>
                    </div>
                    <h3 class="font-bold text-xl text-slate-900 tracking-tight">Pengajuan Izin & Sakit Online</h3>
                    <p class="text-xs text-slate-400 leading-relaxed mt-2.5 font-normal">
                        Siswa dapat mengajukan izin/sakit beserta bukti, lalu diverifikasi guru secara digital.
                    </p>
                </div>

                <div class="flex flex-col items-start text-left">
                    <div class="w-11 h-11 rounded-full bg-blue-600 text-white flex items-center justify-center shadow-md shadow-blue-500/20 mb-5">
                        <i class="bi bi-people-fill text-lg"></i>
                    </div>
                    <h3 class="font-bold text-xl text-slate-900 tracking-tight">Multi Role Akses</h3>
                    <p class="text-xs text-slate-400 leading-relaxed mt-2.5 font-normal">
                        Tiga level akses (Admin, Guru, Siswa) dengan dashboard dan hak akses yang berbeda.
                    </p>
                </div>

            </div>
        </div>
    </section>

<!-- ROLE SECTION -->
<section id="role" class="py-24 px-6 lg:px-12 bg-white">
    <div class="max-w-7xl mx-auto">
        
        <div class="text-center mb-16">
            <span class="inline-flex items-center gap-2 text-xs font-semibold text-blue-600 bg-blue-50/80 px-4 py-1.5 rounded-full border border-blue-100/50">
                <i class="bi bi-people-fill text-blue-500"></i> Roles
            </span>
            <h2 class="text-3xl md:text-5xl font-extrabold text-slate-900 mt-5 tracking-tight">
                Satu Sistem, Tiga Peran<br>Berbeda
            </h2>
            <p class="text-xs md:text-sm text-slate-400 max-w-xl mx-auto mt-4 leading-relaxed font-normal">
                Setiap pengguna punya akses dan tugasnya masing-masing, sesuai kebutuhan di sekolah
            </p>
        </div>

        <div class="grid lg:grid-cols-12 gap-8 items-stretch">
            
            <div class="lg:col-span-4 flex flex-col justify-between py-2">
                
                <div class="flex gap-4 items-start relative">
                    <div class="flex flex-col items-center shrink-0">
                        <div class="w-11 h-11 rounded-full bg-blue-600 text-white flex items-center justify-center shadow-md shadow-blue-500/20 z-10">
                            <i class="bi bi-person-fill text-xl"></i>
                        </div>
                        <div class="w-[2px] bg-slate-200 h-20 mt-2"></div>
                    </div>
                    <div>
                        <h3 class="font-bold text-lg text-slate-900">Admin</h3>
                        <span class="text-[11px] font-medium text-slate-400 block -mt-0.5 mb-1.5">Role 1</span>
                        <p class="text-xs text-slate-400 leading-relaxed font-normal">
                            Mengelola data siswa, guru, dan kelas, serta memantau seluruh presensi dan laporan kehadiran sekolah.
                        </p>
                    </div>
                </div>

                <div class="flex gap-4 items-start relative">
                    <div class="flex flex-col items-center shrink-0">
                        <div class="w-11 h-11 rounded-full bg-blue-600 text-white flex items-center justify-center shadow-md shadow-blue-500/20 z-10">
                            <i class="bi bi-person-workspace text-lg"></i>
                        </div>
                        <div class="w-[2px] bg-slate-200 h-20 mt-2"></div>
                    </div>
                    <div>
                        <h3 class="font-bold text-lg text-slate-900">Guru</h3>
                        <span class="text-[11px] font-medium text-slate-400 block -mt-0.5 mb-1.5">Role 2</span>
                        <p class="text-xs text-slate-400 leading-relaxed font-normal">
                            Membuka sesi presensi di setiap jam pelajaran dan memverifikasi pengajuan izin atau sakit siswa.
                        </p>
                    </div>
                </div>

                <div class="flex gap-4 items-start relative">
                    <div class="flex flex-col items-center shrink-0">
                        <div class="w-11 h-11 rounded-full bg-blue-600 text-white flex items-center justify-center shadow-md shadow-blue-500/20 z-10">
                            <i class="bi bi-mortarboard-fill text-lg"></i>
                        </div>
                        <div class="w-[2px] bg-slate-200 h-20 mt-2"></div>
                    </div>
                    <div>
                        <h3 class="font-bold text-lg text-slate-900">Siswa</h3>
                        <span class="text-[11px] font-medium text-slate-400 block -mt-0.5 mb-1.5">Role 3</span>
                        <p class="text-xs text-slate-400 leading-relaxed font-normal">
                            Melakukan presensi dengan scan QR Code dan mengajukan izin/sakit secara online.
                        </p>
                    </div>
                </div>

            </div>

            <div class="lg:col-span-4 flex justify-center items-center">
                <div class="w-full max-w-[360px] h-full min-h-[580px] rounded-[36px] bg-gradient-to-b from-amber-100/60 via-orange-50/30 to-blue-100/50 p-6 flex items-center justify-center shadow-sm relative overflow-hidden border border-amber-100/50">
                    <div class="w-full h-full min-h-[530px] bg-black rounded-[30px] p-3 shadow-2xl relative flex flex-col justify-between">
                        <div class="w-24 h-4 bg-black rounded-full mx-auto absolute top-5 left-1/2 -translate-x-1/2 z-20"></div>
                        
                        <div class="w-full h-full bg-white rounded-[24px] overflow-hidden flex flex-col justify-between items-center p-5 relative pt-10">
                            <div class="w-full flex-1 flex items-center justify-center py-6">
                                <i class="bi bi-qr-code text-[200px] text-slate-900 leading-none"></i>
                            </div>
                            <div class="w-full bg-black text-white text-center py-3 rounded-xl">
                                <span class="font-black text-base tracking-[5px]">SCAN ME</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- RIGHT SIDE SECTION -->
            <div class="lg:col-span-4 flex flex-col justify-between">
                
                <div class="flex-1 flex flex-col justify-center">
                    <!-- Judul di Luar Kotak -->
                    <h4 class="font-bold text-lg text-slate-900 mb-4">Statistik Kehadiran</h4>
                    
                    <!-- Card Statistik -->
                    <div class="bg-white rounded-3xl p-6 shadow-lg shadow-slate-100 border border-blue-100/80">
                        <div class="flex items-center gap-5">
                            <div class="relative w-16 h-16 flex items-center justify-center shrink-0">
                                <svg class="w-full h-full transform -rotate-90" viewBox="0 0 36 36">
                                    <path class="text-slate-100" stroke-width="3.5" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                                    <path class="text-blue-600" stroke-dasharray="85, 100" stroke-width="3.5" stroke-linecap="round" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                                </svg>
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <div class="w-10 h-10 rounded-full bg-slate-100/80 flex items-center justify-center text-blue-600">
                                        <i class="bi bi-person-fill text-sm"></i>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <span class="text-2xl font-extrabold text-slate-900 block leading-tight">567</span>
                                <span class="text-xs text-slate-400 font-medium">Hadir</span>
                            </div>
                        </div>

                        <hr class="border-blue-100/60 my-6">

                        <div class="flex items-center gap-5">
                            <div class="relative w-16 h-16 flex items-center justify-center shrink-0">
                                <svg class="w-full h-full transform -rotate-90" viewBox="0 0 36 36">
                                    <path class="text-slate-100" stroke-width="3.5" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                                    <path class="text-amber-400" stroke-dasharray="40, 100" stroke-width="3.5" stroke-linecap="round" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                                </svg>
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <div class="w-10 h-10 rounded-full bg-amber-50/80 flex items-center justify-center text-amber-500">
                                        <i class="bi bi-pencil-square text-xs"></i>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <span class="text-2xl font-extrabold text-slate-900 block leading-tight">33</span>
                                <span class="text-xs text-slate-400 font-medium">Izin/Sakit</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pt-6">
                    <p class="text-xs text-slate-400 leading-relaxed font-normal mb-5">
                        Ingin tahu bagaimana sistem ini mengelola presensi dengan lebih mudah dan efisien setiap harinya?
                    </p>
                    <a href="#login" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold px-6 py-3.5 rounded-full shadow-lg shadow-blue-500/25 transition">
                        Coba Sekarang <i class="bi bi-arrow-right"></i>
                    </a>
                </div>

            </div>

        </div>
    </div>
</section>
    <!-- STATS / METRICS SECTION -->
    <section id="stats" class="py-20 px-6 lg:px-12 bg-white">
        <div class="max-w-7xl mx-auto">
            
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-5xl font-extrabold text-slate-900 tracking-tight">
                    Kehadiran yang Terpantau
                </h2>
                <p class="text-xs md:text-sm text-slate-400 max-w-lg mx-auto mt-4 leading-relaxed font-normal">
                    Setiap presensi tercatat untuk membantu memantau kehadiran siswa dengan lebih mudah.
                </p>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                
                <div class="flex flex-col items-center">
                    <div class="text-4xl md:text-5xl font-extrabold text-slate-900 mb-3 tracking-tight">
                        500<span class="text-blue-600 font-bold ml-0.5">+</span>
                    </div>
                    <span class="text-xs text-slate-400 font-normal">Siswa Terdaftar</span>
                </div>

                <div class="flex flex-col items-center">
                    <div class="text-4xl md:text-5xl font-extrabold text-slate-900 mb-3 tracking-tight">
                        10K<span class="text-blue-600 font-bold ml-0.5">+</span>
                    </div>
                    <span class="text-xs text-slate-400 font-normal">Total Presensi</span>
                </div>

                <div class="flex flex-col items-center">
                    <div class="text-4xl md:text-5xl font-extrabold text-slate-900 mb-3 tracking-tight">
                        98<span class="text-blue-600 font-bold ml-0.5">%</span>
                    </div>
                    <span class="text-xs text-slate-400 font-normal">Tingkat Kehadiran</span>
                </div>

                <div class="flex flex-col items-center">
                    <div class="text-4xl md:text-5xl font-extrabold text-slate-900 mb-3 tracking-tight">
                        100<span class="text-blue-600 font-bold ml-0.5">%</span>
                    </div>
                    <span class="text-xs text-slate-400 font-normal">Akurat & Real-Time</span>
                </div>

            </div>
        </div>
    </section>

    <!-- FAQ SECTION (SESUAI DESAIN GAMBAR) -->
    <section id="faq" class="py-24 px-6 lg:px-12 bg-white">
        <div class="max-w-7xl mx-auto">
            <div class="grid md:grid-cols-12 gap-12">
                
                <!-- Sisi Kiri Header & Contact -->
                <div class="md:col-span-5 flex flex-col justify-between">
                    <div>
                        <span class="inline-flex items-center gap-2 text-xs font-semibold text-blue-600 bg-blue-50/80 px-4 py-1.5 rounded-full border border-blue-100/50 mb-6">
                            <i class="bi bi-people-fill text-blue-500"></i> FAQ
                        </span>
                        <h2 class="text-4xl md:text-5xl font-extrabold text-slate-900 tracking-tight leading-tight">
                            Frequently Asked<br>Questions
                        </h2>
                    </div>

                    <div class="mt-12 md:mt-0">
                        <h3 class="font-bold text-slate-900 text-sm">Masih butuh bantuan?</h3>
                        <p class="text-xs text-slate-400 mt-2 mb-6 leading-relaxed">
                            Jangan ragu untuk menghubungi kami jika Anda membutuhkan bantuan lebih lanjut.
                        </p>
                        <a href="#" class="inline-flex items-center gap-3 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold px-6 py-3 rounded-full shadow-md shadow-blue-500/20 transition">
                            Contact Us <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>

                <!-- Sisi Kanan Accordion List -->
                <div class="md:col-span-7 space-y-4">
                    
                    <!-- Accordion Item 1 (Open) -->
                    <div class="bg-[#F3F7FF] rounded-2xl p-6 transition">
                        <div class="flex items-center justify-between cursor-pointer">
                            <h3 class="font-bold text-slate-900 text-sm md:text-base pr-4">
                                Bagaimana cara melakukan presensi menggunakan QR Code?
                            </h3>
                            <button class="w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center shrink-0">
                                <i class="bi bi-plus text-lg"></i>
                            </button>
                        </div>
                        <p class="text-xs text-slate-400 mt-4 leading-relaxed font-normal">
                            Siswa cukup membuka menu Pindai QR pada aplikasi/website, lalu arahkan kamera ponsel ke kode QR presensi yang disediakan oleh sekolah atau guru di kelas.
                        </p>
                    </div>

                    <!-- Accordion Item 2 -->
                    <div class="bg-[#F3F7FF] rounded-2xl p-6 flex items-center justify-between cursor-pointer hover:bg-blue-50/80 transition">
                        <h3 class="font-bold text-slate-900 text-sm md:text-base pr-4">
                            Apa yang harus dilakukan jika QR Code gagal dipindai?
                        </h3>
                        <button class="w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center shrink-0">
                            <i class="bi bi-plus text-lg"></i>
                        </button>
                    </div>

                    <!-- Accordion Item 3 -->
                    <div class="bg-[#F3F7FF] rounded-2xl p-6 flex items-center justify-between cursor-pointer hover:bg-blue-50/80 transition">
                        <h3 class="font-bold text-slate-900 text-sm md:text-base pr-4">
                            Bagaimana prosedur pengajuan izin atau sakit secara online?
                        </h3>
                        <button class="w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center shrink-0">
                            <i class="bi bi-plus text-lg"></i>
                        </button>
                    </div>

                    <!-- Accordion Item 4 -->
                    <div class="bg-[#F3F7FF] rounded-2xl p-6 flex items-center justify-between cursor-pointer hover:bg-blue-50/80 transition">
                        <h3 class="font-bold text-slate-900 text-sm md:text-base pr-4">
                            Bagaimana jika durasi QR Code di proyektor sudah habis?
                        </h3>
                        <button class="w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center shrink-0">
                            <i class="bi bi-plus text-lg"></i>
                        </button>
                    </div>

                </div>

            </div>
        </div>
    </section>

<!-- CALL TO ACTION (CTA) BANNER (SESUAI GRADIENT & CTA DESAIN GAMBAR) -->
<section class="py-20 px-6 lg:px-12 bg-gradient-to-r from-amber-100 via-orange-50/40 to-blue-100 border-t border-slate-100">
    <div class="max-w-4xl mx-auto text-center">
        <h2 class="text-3xl md:text-5xl font-extrabold text-slate-900 tracking-tight leading-tight">
            Siap Hemat Waktu Jam<br>Pelajaran Setiap Hari?
        </h2>
        <p class="text-xs md:text-sm text-slate-600 max-w-xl mx-auto mt-4 leading-relaxed font-normal">
            Cukup scan QR Code, semua data kehadiran langsung tercatat rapi — memudahkan admin, guru, dan siswa dalam satu sistem
        </p>
        <a href="{{ route('login') }}" class="inline-flex items-center gap-2 mt-8 px-8 py-3.5 rounded-full bg-blue-600 text-white text-xs font-semibold shadow-lg shadow-blue-500/25 hover:bg-blue-700 transition">
            Login Now <i class="bi bi-arrow-right"></i>
        </a>
    </div>
</section>

    <!-- FOOTER -->
    <footer class="bg-white border-t border-slate-100 pt-16 pb-12">
        <div class="max-w-7xl mx-auto px-6 lg:px-12">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-10">
                
                <!-- Brand Deskripsi & Sosmed -->
                <div class="md:col-span-5 pr-0 md:pr-8">
                    <a href="{{ url('/') }}" class="inline-block mb-6">
                        <img src="{{ asset('assets/logo-attendly.png') }}" alt="Attendly Logo" class="h-9 w-auto object-contain">
                    </a>
                    <p class="text-xs text-slate-400 leading-relaxed font-normal mb-8">
                        Attendly adalah sistem presensi berbasis QR Code modern yang dirancang untuk mempermudah tata kelola kehadiran di sekolah. Terintegrasi langsung dalam satu platform yang dapat diakses oleh admin untuk kelola data, guru untuk pemantauan akurat, serta siswa untuk pencatatan presensi yang cepat dan praktis.
                    </p>
                    <div class="flex items-center gap-5 text-slate-700 text-sm">
                        <a href="#" class="hover:text-blue-600 transition"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="hover:text-blue-600 transition"><i class="bi bi-twitter-x"></i></a>
                        <a href="#" class="hover:text-blue-600 transition"><i class="bi bi-linkedin"></i></a>
                        <a href="#" class="hover:text-blue-600 transition"><i class="bi bi-instagram"></i></a>
                    </div>
                </div>

                <!-- Navigation Links -->
                <div class="md:col-span-2">
                    <h4 class="font-bold text-sm text-slate-900 mb-5">Navigation</h4>
                    <ul class="space-y-3 text-xs text-slate-400">
                        <li><a href="#" class="hover:text-blue-600 transition">Home</a></li>
                        <li><a href="#cara-kerja" class="hover:text-blue-600 transition">How It Works</a></li>
                        <li><a href="#fitur" class="hover:text-blue-600 transition">Features</a></li>
                        <li><a href="#role" class="hover:text-blue-600 transition">Roles</a></li>
                        <li><a href="#faq" class="hover:text-blue-600 transition">FAQ</a></li>
                    </ul>
                </div>

                <!-- Licence Links -->
                <div class="md:col-span-2">
                    <h4 class="font-bold text-sm text-slate-900 mb-5">Licence</h4>
                    <ul class="space-y-3 text-xs text-slate-400">
                        <li><a href="#" class="hover:text-blue-600 transition">Privacy Policy</a></li>
                        <li><a href="#" class="hover:text-blue-600 transition">Copyright</a></li>
                        <li><a href="#" class="hover:text-blue-600 transition">Email Address</a></li>
                    </ul>
                </div>

                <!-- Contact Info -->
                <div class="md:col-span-3">
                    <h4 class="font-bold text-sm text-slate-900 mb-5">Contact</h4>
                    <ul class="space-y-4 text-xs text-slate-400">
                        <li class="flex items-center gap-3">
                            <i class="bi bi-telephone-fill text-slate-800 text-sm"></i>
                            <span>+62 - 8214 - 3618 - 251</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <i class="bi bi-envelope-fill text-slate-800 text-sm"></i>
                            <span>attendly@gmail.com</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="bi bi-geo-alt-fill text-slate-800 text-sm mt-0.5"></i>
                            <span class="leading-relaxed">Jl. Raya Purwosari, Polerejo, Purwosari, Pasuruan, Jawa Timur 67162</span>
                        </li>
                    </ul>
                </div>

            </div>
        </div>
    </footer>

</body>
</html>