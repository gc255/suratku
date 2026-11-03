<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Suratku</title>

    <!-- Favicon Logo Tab -->
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}?v=1">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}?v=1">

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Styles & Scripts (Tailwind CSS, Alpine.js) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        /* Background Grid Pattern */
        .bg-grid-pattern {
            background-image: radial-gradient(rgba(255, 255, 255, 0.08) 1px, transparent 1px);
            background-size: 28px 28px;
        }

        /* Ambient Glow Animations */
        @keyframes pulseGlow {
            0%, 100% {
                transform: scale(1) translate(0, 0);
                opacity: 0.45;
            }
            50% {
                transform: scale(1.1) translate(20px, -15px);
                opacity: 0.7;
            }
        }

        .animate-glow-1 {
            animation: pulseGlow 12s ease-in-out infinite alternate;
        }

        .animate-glow-2 {
            animation: pulseGlow 15s ease-in-out infinite alternate-reverse;
        }

        /* Glassmorphism Card Effect */
        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.4);
        }

        .glass-pill {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.15);
        }

        /* Custom Input Glow */
        .input-glow:focus {
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.25);
        }
    </style>
</head>
<body class="min-h-screen bg-[#090e1a] text-slate-100 relative overflow-x-hidden selection:bg-blue-600 selection:text-white"
      x-data="{
          showPass: false,
          submitting: false,
          currentTime: '',
          updateTime() {
              const now = new Date();
              const options = { weekday: 'long', day: 'numeric', month: 'short', year: 'numeric' };
              const timeStr = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }) + ' WIB';
              this.currentTime = now.toLocaleDateString('id-ID', options) + ' • ' + timeStr;
          }
      }"
      x-init="updateTime(); setInterval(() => updateTime(), 30000)">

    <!-- BACKGROUND AMBIENT GLOW & GRID -->
    <div class="fixed inset-0 pointer-events-none z-0">
        <!-- Grid Pattern Overlay -->
        <div class="absolute inset-0 bg-grid-pattern opacity-40"></div>

        <!-- Glowing Spheres -->
        <div class="absolute -top-32 -left-32 w-[520px] h-[520px] rounded-full bg-blue-600/25 blur-[120px] animate-glow-1"></div>
        <div class="absolute top-1/3 -right-24 w-[460px] h-[460px] rounded-full bg-indigo-600/20 blur-[130px] animate-glow-2"></div>
        <div class="absolute -bottom-32 left-1/3 w-[500px] h-[500px] rounded-full bg-cyan-600/15 blur-[140px]"></div>
    </div>

    <!-- WRAPPER KONTEN UTAMA -->
    <div class="relative z-10 flex flex-col min-h-screen">

        <!-- =================================================================== -->
        <!-- HEADER TOP NAVIGATION                                               -->
        <!-- =================================================================== -->
        <header class="w-full border-b border-white/10 bg-slate-900/40 backdrop-blur-md">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
                
                <!-- Brand / Logo -->
                <div class="flex items-center space-x-3.5">
                    <img src="{{ asset('images/logo-pu.webp') }}" alt="Logo Politeknik Pekerjaan Umum" class="w-11 h-11 object-contain drop-shadow">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="text-xl font-extrabold tracking-tight text-white">SURATKU</span>
                            <span class="px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider rounded-full bg-blue-500/20 text-blue-300 border border-blue-400/30">
                                POLITEKNIK PEKERJAAN UMUM
                            </span>
                        </div>
                        <p class="text-[11px] font-medium text-slate-400">Sistem Unggul Rekapitulasi dan Tracking Surat</p>
                    </div>
                </div>

                <!-- Status Jam & Kedipan Hijau Live -->
                <div class="flex items-center space-x-3">
                    <div class="flex items-center space-x-2 text-xs font-semibold text-slate-300 glass-pill px-3.5 py-1.5 rounded-full">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                        <span class="w-2 h-2 rounded-full bg-emerald-500 -ml-3"></span>
                        <span x-text="currentTime"></span>
                    </div>
                </div>
            </div>
        </header>

        <!-- =================================================================== -->
        <!-- MAIN SECTION: DUA SISI (KIRI: SAMBUTAN & LOGO, KANAN: FORM LOGIN)   -->
        <!-- =================================================================== -->
        <main class="flex-1 flex items-center justify-center py-10 lg:py-16">
            <div class="max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-10 items-center">
                    
                    <!-- ======================================================= -->
                    <!-- SISI KIRI (LOGO & SAMBUTAN RESMI) - 7 Kolom            -->
                    <!-- ======================================================= -->
                    <div class="lg:col-span-7 flex flex-col justify-center items-center text-center space-y-6">
                        
                        <!-- Logo Politeknik Pekerjaan Umum -->
                        <div class="flex justify-center items-center">
                            <div class="p-4 rounded-3xl bg-white/[0.04] border border-white/10 backdrop-blur-md shadow-2xl inline-flex items-center justify-center">
                                <img src="{{ asset('images/logo-pu.webp') }}" alt="Logo Politeknik Pekerjaan Umum" class="w-24 h-24 sm:w-28 sm:h-28 object-contain drop-shadow-[0_10px_25px_rgba(234,179,8,0.3)]">
                            </div>
                        </div>

                        <!-- Sambutan Besar & Paragraf Baru -->
                        <div class="space-y-3 flex flex-col items-center text-center">
                            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-white tracking-tight leading-tight">
                                Selamat Datang
                            </h1>
                            <p class="text-base sm:text-lg text-slate-300 leading-relaxed font-normal max-w-lg mx-auto">
                                Portal resmi SURATKU Politeknik Pekerjaan Umum
                            </p>
                        </div>

                    </div>

                    <!-- ======================================================= -->
                    <!-- SISI KANAN (FORM LOGIN ELEGAN & PROFESIONAL) - 5 Kolom  -->
                    <!-- ======================================================= -->
                    <div class="lg:col-span-5 w-full max-w-md mx-auto">
                        <div class="glass-card rounded-3xl p-7 sm:p-9 shadow-2xl text-slate-800 relative overflow-hidden border border-white/60">
                            
                            <!-- Header Form Card -->
                            <div class="mb-6">
                                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-50 text-blue-700 text-xs font-extrabold uppercase tracking-wider mb-2.5 border border-blue-200">
                                    <span>🔐</span> Portal Masuk Pegawai
                                </div>
                                <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">
                                    Masuk ke Akun Anda
                                </h2>
                                <p class="text-xs text-slate-500 mt-1">
                                    Gunakan NIP, NIK, NIM, atau Username terdaftar untuk memulai sesi.
                                </p>
                            </div>

                            <!-- Session Status (Misal: Logout Berhasil / Reset Password) -->
                            @if (session('status'))
                                <div class="mb-5 p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold flex items-center gap-2 shadow-xs">
                                    <span>✅</span>
                                    <span>{{ session('status') }}</span>
                                </div>
                            @endif

                            <!-- Error Message Umum -->
                            @if (isset($errors) && $errors->any())
                                <div class="mb-5 p-3.5 rounded-xl bg-red-50 border border-red-200 text-red-800 text-xs shadow-xs">
                                    <div class="font-bold flex items-center gap-1.5 mb-1">
                                        <span>⚠️</span> Gagal Melakukan Autentikasi:
                                    </div>
                                    <ul class="list-disc list-inside space-y-0.5 text-red-700">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <!-- FORM LOGIN -->
                            <form method="POST" action="{{ route('login') }}" @submit="submitting = true" class="space-y-4">
                                @csrf

                                <!-- Input: NIP / NIK / NIM / USERNAME -->
                                <div>
                                    <label for="input_nip" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                        NIP / NIK / NIM / USERNAME <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                            </svg>
                                        </div>
                                        <input type="text" 
                                               id="input_nip" 
                                               name="nip" 
                                               value="{{ old('nip') }}" 
                                               required 
                                               autofocus
                                               autocomplete="username"
                                               placeholder="Masukkan NIP, NIK, NIM, atau username"
                                               class="w-full pl-10 pr-3.5 py-2.5 text-xs font-medium text-slate-900 bg-slate-50/80 border border-slate-300 rounded-xl focus:bg-white focus:border-blue-600 focus:ring-0 input-glow transition duration-200 shadow-2xs">
                                    </div>
                                </div>

                                <!-- Input: Password -->
                                <div>
                                    <label for="input_password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                        Kata Sandi <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                            </svg>
                                        </div>
                                        <input :type="showPass ? 'text' : 'password'" 
                                               id="input_password" 
                                               name="password" 
                                               required 
                                               autocomplete="current-password"
                                               placeholder="Masukkan kata sandi"
                                               class="w-full pl-10 pr-10 py-2.5 text-xs font-medium text-slate-900 bg-slate-50/80 border border-slate-300 rounded-xl focus:bg-white focus:border-blue-600 focus:ring-0 input-glow transition duration-200 shadow-2xs">
                                        
                                        <!-- Tombol Toggle Show/Hide Password -->
                                        <button type="button" 
                                                @click="showPass = !showPass"
                                                class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 transition cursor-pointer"
                                                title="Lihat / Sembunyikan Kata Sandi">
                                            <svg x-show="!showPass" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            <svg x-show="showPass" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                                <!-- Checkbox Remember Me -->
                                <div class="flex items-center justify-between pt-1">
                                    <label for="remember_me" class="inline-flex items-center cursor-pointer select-none">
                                        <input id="remember_me" 
                                               type="checkbox" 
                                               name="remember" 
                                               class="w-4 h-4 text-blue-600 bg-slate-100 border-slate-300 rounded focus:ring-blue-500 focus:ring-offset-0 transition cursor-pointer">
                                        <span class="ms-2 text-xs font-semibold text-slate-600">Ingat sesi saya</span>
                                    </label>
                                </div>

                                <!-- Tombol Masuk Submit -->
                                <div class="pt-2">
                                    <button type="submit" 
                                            :disabled="submitting"
                                            class="w-full py-3 px-5 rounded-xl font-bold text-xs uppercase tracking-wider text-white bg-gradient-to-r from-blue-600 via-indigo-600 to-blue-700 hover:from-blue-700 hover:to-indigo-800 shadow-lg shadow-blue-500/30 hover:shadow-blue-500/40 transform hover:-translate-y-0.5 active:translate-y-0 transition-all duration-200 flex items-center justify-center gap-2 cursor-pointer disabled:opacity-75 disabled:cursor-not-allowed">
                                        <template x-if="!submitting">
                                            <span class="flex items-center gap-2">
                                                <span>Masuk ke Sistem</span>
                                                <svg class="w-4 h-4 transition-transform duration-200 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                                </svg>
                                            </span>
                                        </template>
                                        <template x-if="submitting">
                                            <span class="flex items-center gap-2">
                                                <svg class="animate-spin h-4 w-4 text-white" viewBox="0 0 24 24" fill="none">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                                                </svg>
                                                <span>Memproses Autentikasi...</span>
                                            </span>
                                        </template>
                                    </button>
                                </div>
                            </form>

                            <!-- Badge Informasi Keamanan Bawah -->
                            <div class="mt-6 pt-5 border-t border-slate-200 flex items-center justify-center gap-2 text-slate-500 text-[11px] font-medium text-center">
                                <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                                <span>Koneksi Aman & Terproteksi SSL 256-Bit</span>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </main>

        <!-- =================================================================== -->
        <!-- FOOTER                                                              -->
        <!-- =================================================================== -->
        <footer class="w-full border-t border-white/10 py-6 text-center text-xs text-slate-400 bg-slate-900/30">
            <div class="max-w-7xl mx-auto px-4 flex flex-col sm:flex-row items-center justify-between gap-2">
                <p>&copy; {{ date('Y') }} <strong>SURATKU - POLITEKNIK PEKERJAAN UMUM</strong>. Seluruh Hak Cipta Dilindungi.</p>
                <div class="flex items-center gap-2 text-slate-400">
                    <span>Sistem Unggul Rekapitulasi dan Tracking Surat</span>
                    <span>•</span>
                    <span class="font-mono font-bold text-slate-300 bg-white/5 border border-white/10 px-2.5 py-0.5 rounded-md text-[11px]">Versi GC-001</span>
                </div>
            </div>
        </footer>

    </div>

</body>
</html>
