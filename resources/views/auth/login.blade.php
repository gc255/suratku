<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Suratku (Beta) - Politeknik Pekerjaan Umum</title>

    <!-- Progressive Web App (PWA) -->
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="#003366">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="Suratku">
    <link rel="apple-touch-icon" href="{{ asset('images/icons/icon-192x192.png') }}">

    <!-- Favicon Logo Tab -->
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}?v=1">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}?v=1">

    <!-- Google Fonts: Plus Jakarta Sans & Fredoka -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&family=Fredoka:wght@500;600;700&display=swap" rel="stylesheet">

    <!-- Styles & Scripts (Tailwind CSS, Alpine.js) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .font-cartoon {
            font-family: 'Fredoka', 'Plus Jakarta Sans', cursive, sans-serif;
        }
        .bg-pu-canvas {
            background-color: #FAF8F5;
            background-image: radial-gradient(#003366 0.85px, transparent 0.85px);
            background-size: 20px 20px;
        }
        .cartoon-card {
            border: 2px solid #003366;
            box-shadow: 4px 4px 0px 0px #003366;
            transition: all 0.2s cubic-bezier(0.34, 1.56, 0.64, 1);
        }
        .speech-bubble {
            position: relative;
            border: 2px solid #002244;
            box-shadow: 4px 4px 0px 0px #002244;
        }
        .speech-bubble::after {
            content: '';
            position: absolute;
            bottom: -7px;
            left: 24px;
            width: 12px;
            height: 12px;
            background: inherit;
            border-right: 2px solid #002244;
            border-bottom: 2px solid #002244;
            transform: rotate(45deg);
        }
        .speech-bubble-center::after {
            left: 50% !important;
            margin-left: -6px !important;
        }
        .btn-pop-gold {
            background-color: #F5B800 !important;
            color: #002244 !important;
            border: 2px solid #002244 !important;
            box-shadow: 3.5px 3.5px 0px 0px #002244 !important;
            font-weight: 800 !important;
            transition: all 0.1s ease !important;
        }
        .btn-pop-gold:hover {
            transform: translate(-1.5px, -1.5px) !important;
            box-shadow: 5px 5px 0px 0px #002244 !important;
            background-color: #FFC700 !important;
        }
        .btn-pop-gold:active {
            transform: translate(2px, 2px) !important;
            box-shadow: 1px 1px 0px 0px #002244 !important;
        }

        /* ======================================================= */
        /* ANIMASI PENGANTAR SURAT SEPEDA KLASIK                   */
        /* ======================================================= */
        .bike-rider-track {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 160px;
            height: 80px;
            animation: bike-ride-track 20s linear infinite;
            will-change: transform;
        }
        @keyframes bike-ride-track {
            0% {
                transform: translateX(-180px);
            }
            100% {
                transform: translateX(calc(100vw + 60px));
            }
        }
        .bike-wheel-spin {
            animation: bike-wheel-rotate 0.6s linear infinite;
        }
        @keyframes bike-wheel-rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        .bike-rider-bob {
            animation: bike-rider-bobbing 0.6s ease-in-out infinite alternate;
            transform-origin: bottom center;
        }
        @keyframes bike-rider-bobbing {
            0% { transform: translateY(0px) rotate(0deg); }
            100% { transform: translateY(-2px) rotate(-0.7deg); }
        }
        .bike-pedal-spin {
            animation: bike-pedal-rotate 0.6s linear infinite;
            transform-origin: 68px 62px;
        }
        @keyframes bike-pedal-rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        .bike-letter-flutter {
            animation: bike-flutter 0.8s ease-in-out infinite alternate;
            transform-origin: center;
        }
        @keyframes bike-flutter {
            0% { transform: translateY(0) rotate(-3deg); }
            100% { transform: translateY(-2.5px) rotate(3deg); }
        }
        .bike-dust-1 {
            animation: bike-dust-anim 0.7s ease-out infinite;
        }
        .bike-dust-2 {
            animation: bike-dust-anim 0.7s ease-out 0.35s infinite;
        }
        @keyframes bike-dust-anim {
            0% { opacity: 0.8; transform: translate(0, 0) scale(0.6); }
            100% { opacity: 0; transform: translate(-16px, -6px) scale(1.4); }
        }
    </style>
</head>
<body class="min-h-screen bg-pu-canvas text-slate-800 relative overflow-x-hidden selection:bg-[#003366] selection:text-white"
      x-data="{
          showPass: false,
          submitting: false
      }">

    <!-- WRAPPER KONTEN UTAMA -->
    <div class="relative z-10 flex flex-col min-h-screen justify-between">

        <!-- =================================================================== -->
        <!-- HEADER TOP NAVIGATION                                               -->
        <!-- =================================================================== -->
        <header class="w-full bg-[#003366] border-b-2 border-[#002244] text-white sticky top-0 z-30 shadow-md">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
                
                <!-- Brand / Logo -->
                <div class="flex items-center space-x-3.5">
                    <div class="w-10 h-10 rounded-xl bg-white border-2 border-white shadow-[2px_2px_0px_#F5B800] p-1 flex items-center justify-center shrink-0">
                        <img src="{{ asset('images/logo-pu.webp') }}" alt="Logo Politeknik Pekerjaan Umum" class="w-full h-full object-contain">
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="text-base sm:text-lg font-cartoon font-bold tracking-wide text-white">SURATKU</span>
                            <span class="px-2 py-0.5 text-[9px] font-black uppercase tracking-wider rounded-md bg-[#F5B800] text-[#002244] border-2 border-[#002244] shadow-[1.5px_1.5px_0px_#002244]">
                                BETA
                            </span>
                            <span class="hidden sm:inline-block px-2 py-0.5 text-[9px] font-bold uppercase tracking-wider rounded-full bg-blue-900/80 text-blue-200 border border-blue-400/40">
                                POLITEKNIK PEKERJAAN UMUM
                            </span>
                        </div>
                        <p class="text-[10px] text-blue-200 font-medium -mt-0.5">Sistem Rekapitulasi & Tracking Surat</p>
                    </div>
                </div>
            </div>
        </header>

        <!-- =================================================================== -->
        <!-- MAIN SECTION: DUA SISI (KIRI: SAMBUTAN & LOGO, KANAN: FORM LOGIN)   -->
        <!-- =================================================================== -->
        <main class="flex-1 flex items-center justify-center py-10 lg:py-16">
            <div class="max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-12 items-center">
                    
                    <!-- ======================================================= -->
                    <!-- SISI KIRI (LOGO & SAMBUTAN RESMI DENGAN BALON KOMIK)   -->
                    <!-- ======================================================= -->
                    <div class="lg:col-span-7 flex flex-col justify-center items-center text-center space-y-6">
                        
                        <!-- Logo Politeknik PU Pop Card (Center) -->
                        <div class="p-4 rounded-3xl bg-white border-2 border-[#003366] shadow-[4px_4px_0px_#003366] inline-flex items-center justify-center">
                            <img src="{{ asset('images/logo-pu.webp') }}" alt="Logo Politeknik Pekerjaan Umum" class="w-20 h-20 sm:w-24 sm:h-24 object-contain">
                        </div>

                        <!-- Sambutan Besar (Center) -->
                        <div>
                            <h1 class="text-3xl sm:text-5xl lg:text-6xl font-cartoon font-bold text-[#003366] tracking-tight leading-tight text-center">
                                Selamat Datang!
                            </h1>
                        </div>

                        <!-- BALON KOMIK MODEL 2 (SPEECH BUBBLE GREETING - CENTER) -->
                        <div class="speech-bubble speech-bubble-center rounded-3xl p-5 sm:p-6 bg-[#F5B800] text-[#002244] border-2 border-[#002244] shadow-[4px_4px_0px_#002244] max-w-lg mx-auto text-center">
                            <div class="font-cartoon font-bold text-base sm:text-lg">
                                "Halo Civitas Akademika!"
                            </div>
                            <p class="text-xs sm:text-sm text-slate-900 font-medium leading-relaxed mt-1.5">
                                Hari ini ceritanya mau monitor draf surat, memberi paraf, atau mau menandatangani dokumen? Yuk cuss masuk aja dengan akun kamu untuk memulai!
                            </p>
                        </div>

                    </div>

                    <!-- ======================================================= -->
                    <!-- SISI KANAN (FORM LOGIN CARTOON NEO-BRUTALIST) - 5 Kolom -->
                    <!-- ======================================================= -->
                    <div class="lg:col-span-5 w-full max-w-md mx-auto">
                        <div class="cartoon-card rounded-3xl p-7 sm:p-9 bg-white text-slate-800 relative">
                            
                            <!-- Header Form Card -->
                            <div class="mb-6">
                                <h2 class="text-2xl font-cartoon font-bold text-[#003366] tracking-tight">
                                    Portal Masuk Pegawai
                                </h2>
                                <p class="text-xs text-slate-600 font-medium mt-1">
                                    Gunakan Username terdaftar untuk memulai sesi kerja.
                                </p>
                            </div>

                            <!-- Session Status -->
                            @if (session('status'))
                                @php
                                    $isSessionNotice = str_contains(strtolower(session('status')), 'berakhir') || str_contains(strtolower(session('status')), 'sesi');
                                @endphp
                                <div class="mb-5 p-3.5 rounded-2xl {{ $isSessionNotice ? 'bg-amber-50 border-2 border-amber-500 text-amber-950 shadow-[2px_2px_0px_#D97706]' : 'bg-emerald-100 border-2 border-emerald-600 text-emerald-950 shadow-[2px_2px_0px_#059669]' }} text-xs font-bold flex items-center gap-2">
                                    <span>{{ $isSessionNotice ? '⏱️' : '✅' }}</span>
                                    <span>{{ session('status') }}</span>
                                </div>
                            @endif

                            <!-- Error Message Umum -->
                            @if (isset($errors) && $errors->any())
                                <div class="mb-5 p-3.5 rounded-2xl bg-rose-100 border-2 border-rose-600 text-rose-950 text-xs shadow-[2px_2px_0px_#E11D48]">
                                    <div class="font-bold flex items-center gap-1.5 mb-1 text-rose-800">
                                        <span>⚠️</span> Gagal Melakukan Autentikasi:
                                    </div>
                                    <ul class="list-disc list-inside space-y-0.5 text-rose-900 font-medium">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <!-- FORM LOGIN -->
                            <form method="POST" action="{{ route('login') }}" @submit="submitting = true" class="space-y-4">
                                @csrf

                                <!-- Input: USERNAME -->
                                <div>
                                    <label for="input_nip" class="block text-xs font-cartoon font-bold text-slate-800 uppercase tracking-wider mb-1.5">
                                        USERNAME <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                            <svg class="w-5 h-5 text-[#003366]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                                               placeholder="Masukkan username"
                                               class="w-full pl-10 pr-3.5 py-2.5 text-xs font-semibold text-slate-900 bg-slate-50 border-2 border-[#003366] rounded-xl focus:bg-white focus:border-[#003366] focus:ring-2 focus:ring-[#F5B800]/50 transition duration-200">
                                    </div>
                                </div>

                                <!-- Input: Password -->
                                <div>
                                    <label for="input_password" class="block text-xs font-cartoon font-bold text-slate-800 uppercase tracking-wider mb-1.5">
                                        Kata Sandi <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                            <svg class="w-5 h-5 text-[#003366]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                            </svg>
                                        </div>
                                        <input :type="showPass ? 'text' : 'password'" 
                                               id="input_password" 
                                               name="password" 
                                               required 
                                               autocomplete="current-password"
                                               placeholder="Masukkan kata sandi"
                                               class="w-full pl-10 pr-10 py-2.5 text-xs font-semibold text-slate-900 bg-slate-50 border-2 border-[#003366] rounded-xl focus:bg-white focus:border-[#003366] focus:ring-2 focus:ring-[#F5B800]/50 transition duration-200">
                                        
                                        <!-- Tombol Toggle Show/Hide Password -->
                                        <button type="button" 
                                                @click="showPass = !showPass"
                                                class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-500 hover:text-[#003366] transition cursor-pointer"
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
                                               class="w-4 h-4 text-[#003366] bg-slate-100 border-2 border-[#003366] rounded focus:ring-0 transition cursor-pointer">
                                        <span class="ms-2 text-xs font-bold text-slate-700">Ingat sesi saya</span>
                                    </label>
                                </div>

                                <!-- Tombol Masuk Submit -->
                                <div class="pt-2">
                                    <button type="submit" 
                                            :disabled="submitting"
                                            class="btn-pop-gold w-full py-3 px-5 rounded-2xl font-cartoon font-bold text-sm uppercase tracking-wide flex items-center justify-center gap-2 cursor-pointer disabled:opacity-75 disabled:cursor-not-allowed">
                                        <template x-if="!submitting">
                                            <span class="flex items-center gap-2">
                                                <span>Masuk ke Sistem</span>
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                                </svg>
                                            </span>
                                        </template>
                                        <template x-if="submitting">
                                            <span class="flex items-center gap-2">
                                                <svg class="animate-spin h-4 w-4 text-[#002244]" viewBox="0 0 24 24" fill="none">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                                                </svg>
                                                <span>Memproses Autentikasi...</span>
                                            </span>
                                        </template>
                                    </button>
                                </div>
                            </form>

                        </div>
                    </div>

                </div>
            </div>
        </main>

        <!-- =================================================================== -->
        <!-- ANIMASI PENGANTAR SURAT SEPEDA KLASIK (JALUR DI ATAS FOOTER)        -->
        <!-- =================================================================== -->
        <div class="w-full relative overflow-hidden pointer-events-none select-none z-20" style="height: 82px; margin-bottom: -2px;">
            <div class="bike-rider-track">
                <svg viewBox="0 0 160 80" width="160" height="80" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <!-- DEBU DI BELAKANG RODA BELAKANG -->
                    <g transform="translate(18, 68)">
                        <circle class="bike-dust-1" cx="0" cy="0" r="3" fill="#CBD5E1" opacity="0.7"/>
                        <circle class="bike-dust-2" cx="-5" cy="-2" r="2.2" fill="#E2E8F0" opacity="0.6"/>
                    </g>

                    <!-- RODA BELAKANG (X: 30, Y: 62, R: 14) -->
                    <g class="bike-wheel-spin" style="transform-origin: 30px 62px;">
                        <!-- Ban Luar -->
                        <circle cx="30" cy="62" r="14" fill="#002244" stroke="#002244" stroke-width="2"/>
                        <!-- Velg Putih / Chrome -->
                        <circle cx="30" cy="62" r="11" fill="#FFFFFF" stroke="#002244" stroke-width="1.5"/>
                        <!-- Jeruji Sepeda Klasik (6 Spoke) -->
                        <line x1="30" y1="48" x2="30" y2="76" stroke="#002244" stroke-width="1.2"/>
                        <line x1="16" y1="62" x2="44" y2="62" stroke="#002244" stroke-width="1.2"/>
                        <line x1="20" y1="52" x2="40" y2="72" stroke="#002244" stroke-width="1.2"/>
                        <line x1="20" y1="72" x2="40" y2="52" stroke="#002244" stroke-width="1.2"/>
                        <!-- Hub Tengah -->
                        <circle cx="30" cy="62" r="3.5" fill="#F5B800" stroke="#002244" stroke-width="1.2"/>
                    </g>

                    <!-- RODA DEPAN (X: 110, Y: 62, R: 14) -->
                    <g class="bike-wheel-spin" style="transform-origin: 110px 62px;">
                        <!-- Ban Luar -->
                        <circle cx="110" cy="62" r="14" fill="#002244" stroke="#002244" stroke-width="2"/>
                        <!-- Velg Putih / Chrome -->
                        <circle cx="110" cy="62" r="11" fill="#FFFFFF" stroke="#002244" stroke-width="1.5"/>
                        <!-- Jeruji Sepeda Klasik -->
                        <line x1="110" y1="48" x2="110" y2="76" stroke="#002244" stroke-width="1.2"/>
                        <line x1="96" y1="62" x2="124" y2="62" stroke="#002244" stroke-width="1.2"/>
                        <line x1="100" y1="52" x2="120" y2="72" stroke="#002244" stroke-width="1.2"/>
                        <line x1="100" y1="72" x2="120" y2="52" stroke="#002244" stroke-width="1.2"/>
                        <!-- Hub Tengah -->
                        <circle cx="110" cy="62" r="3.5" fill="#F5B800" stroke="#002244" stroke-width="1.2"/>
                    </g>

                    <!-- SPAKBOR BELAKANG KLASIK (Melengkung) -->
                    <path d="M 17 62 A 15 15 0 0 1 45 56" stroke="#003366" stroke-width="3" stroke-linecap="round" fill="none"/>
                    <path d="M 17 62 A 15 15 0 0 1 45 56" stroke="#002244" stroke-width="1" stroke-linecap="round" fill="none"/>

                    <!-- SPAKBOR DEPAN KLASIK -->
                    <path d="M 98 55 A 15 15 0 0 1 124 64" stroke="#003366" stroke-width="3" stroke-linecap="round" fill="none"/>
                    <path d="M 98 55 A 15 15 0 0 1 124 64" stroke="#002244" stroke-width="1" stroke-linecap="round" fill="none"/>

                    <!-- RANGKA SEPEDA ONTEL KLASIK -->
                    <!-- Garpu Belakang ke Hub -->
                    <line x1="30" y1="62" x2="52" y2="44" stroke="#002244" stroke-width="2.5" stroke-linecap="round"/>
                    <line x1="30" y1="62" x2="68" y2="62" stroke="#002244" stroke-width="2.5" stroke-linecap="round"/>
                    
                    <!-- Pipa Utama & Pipa Bawah -->
                    <line x1="52" y1="44" x2="68" y2="62" stroke="#002244" stroke-width="2.5" stroke-linecap="round"/>
                    <line x1="52" y1="44" x2="98" y2="42" stroke="#002244" stroke-width="2.8" stroke-linecap="round"/>
                    <line x1="68" y1="62" x2="98" y2="42" stroke="#002244" stroke-width="2.5" stroke-linecap="round"/>
                    
                    <!-- Garpu Depan & Leher Stang -->
                    <line x1="98" y1="42" x2="110" y2="62" stroke="#002244" stroke-width="2.5" stroke-linecap="round"/>
                    <line x1="98" y1="42" x2="96" y2="30" stroke="#002244" stroke-width="2.5" stroke-linecap="round"/>

                    <!-- BONCENGAN / BAGASI BELAKANG KLASIK DENGAN TAS SURAT KULIT -->
                    <line x1="24" y1="46" x2="48" y2="46" stroke="#002244" stroke-width="2" stroke-linecap="round"/>
                    <line x1="28" y1="46" x2="30" y2="62" stroke="#002244" stroke-width="1.8"/>
                    <line x1="44" y1="46" x2="30" y2="62" stroke="#002244" stroke-width="1.8"/>

                    <!-- TAS POS KULIT BESAR KLASIK (Cokelat Kulit Neo-Brutalist) -->
                    <g class="bike-letter-flutter">
                        <!-- Badan Tas -->
                        <rect x="22" y="38" width="22" height="15" rx="3" fill="#C26D28" stroke="#002244" stroke-width="2"/>
                        <!-- Penutup Tas -->
                        <path d="M 21 38 L 33 46 L 45 38 Z" fill="#9C4F15" stroke="#002244" stroke-width="1.8"/>
                        <!-- Gesper Tas Emas -->
                        <rect x="31" y="44.5" width="4" height="3" rx="1" fill="#F5B800" stroke="#002244" stroke-width="1"/>
                        
                        <!-- AMPLOP SURAT MUNCUL DARI TAS (Putih dengan cap merah) -->
                        <g transform="translate(32, 28) rotate(12)">
                            <rect x="0" y="0" width="13" height="9" rx="1" fill="#FFFFFF" stroke="#002244" stroke-width="1.5"/>
                            <path d="M 0 0 L 6.5 5 L 13 0" stroke="#002244" stroke-width="1.2" fill="none"/>
                            <circle cx="9.5" cy="6.5" r="1.5" fill="#EF4444"/>
                        </g>
                        <!-- Amplop kedua yang melayang kecil -->
                        <g transform="translate(18, 25) rotate(-15)">
                            <rect x="0" y="0" width="10" height="7" rx="1" fill="#FEF08A" stroke="#002244" stroke-width="1.2"/>
                            <path d="M 0 0 L 5 4 L 10 0" stroke="#002244" stroke-width="1" fill="none"/>
                        </g>
                    </g>

                    <!-- JOK ONTEL KLASIK PER -->
                    <path d="M 46 43 C 46 41, 58 41, 58 43 C 58 45, 52 46, 46 43 Z" fill="#451A03" stroke="#002244" stroke-width="2"/>
                    <line x1="52" y1="44" x2="52" y2="49" stroke="#002244" stroke-width="2.5"/>

                    <!-- LAMPU KLASIK ONTEL BULAT DI DEPAN (Menyala Kuning Terang) -->
                    <circle cx="106" cy="36" r="4.5" fill="#F5B800" stroke="#002244" stroke-width="1.8"/>
                    <path d="M 109 33 L 118 30 L 118 42 L 109 39 Z" fill="#FEF08A" opacity="0.45"/>

                    <!-- STANG SEPEDA ONTEL KLASIK -->
                    <path d="M 96 30 C 94 25, 90 24, 88 25" stroke="#002244" stroke-width="2.5" stroke-linecap="round" fill="none"/>
                    <!-- Bel Ting-Ting -->
                    <circle cx="92" cy="24" r="2.5" fill="#F5B800" stroke="#002244" stroke-width="1.2"/>

                    <!-- PEDAL & GEAR TENGAH -->
                    <circle cx="68" cy="62" r="5.5" fill="#E2E8F0" stroke="#002244" stroke-width="1.8"/>
                    <g class="bike-pedal-spin">
                        <line x1="68" y1="62" x2="68" y2="52" stroke="#002244" stroke-width="2" stroke-linecap="round"/>
                        <rect x="65" y="50" width="6" height="2.5" rx="1" fill="#002244"/>
                        <line x1="68" y1="62" x2="68" y2="72" stroke="#002244" stroke-width="2" stroke-linecap="round"/>
                        <rect x="65" y="71" width="6" height="2.5" rx="1" fill="#002244"/>
                    </g>

                    <!-- KARAKTER KURIR / PENGANTAR SURAT (BOBBING GENTLY) -->
                    <g class="bike-rider-bob">
                        <!-- Kaki Belakang / Kanan -->
                        <path d="M 54 44 L 62 54 L 68 52" stroke="#002244" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" fill="none"/>

                        <!-- Badan / Jaket PU (Biru PU #003366 dengan Aksen Kuning) -->
                        <path d="M 53 43 C 55 35, 62 30, 72 32 C 75 36, 76 43, 72 45 Z" fill="#003366" stroke="#002244" stroke-width="2"/>
                        <!-- Rompi / Kerah Kuning PU -->
                        <path d="M 64 30 L 68 37 L 72 32 Z" fill="#F5B800" stroke="#002244" stroke-width="1.2"/>

                        <!-- Tali Tas Selempang Surat Dada -->
                        <path d="M 58 32 Q 68 38 74 44" stroke="#854D0E" stroke-width="2" stroke-dasharray="1 1"/>

                        <!-- Tangan Memegang Stang -->
                        <path d="M 69 34 L 80 32 L 88 26" stroke="#003366" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
                        <!-- Sarung Tangan / Telapak Tangan Kuning PU -->
                        <circle cx="88" cy="25" r="2.5" fill="#F5B800" stroke="#002244" stroke-width="1.2"/>

                        <!-- Kaki Depan / Kiri -->
                        <path d="M 55 44 L 68 53 L 68 62" stroke="#003366" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
                        <path d="M 55 44 L 68 53 L 68 62" stroke="#002244" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
                        <!-- Sepatu Hitam -->
                        <rect x="66" y="61" width="7" height="3" rx="1.5" fill="#002244"/>

                        <!-- Leher & Kepala -->
                        <circle cx="73" cy="22" r="7.5" fill="#FED7AA" stroke="#002244" stroke-width="1.8"/>
                        <!-- Mata Ceria & Senyum -->
                        <circle cx="76" cy="21" r="1" fill="#002244"/>
                        <path d="M 75 24 Q 77 26 79 24" stroke="#002244" stroke-width="1.2" stroke-linecap="round" fill="none"/>

                        <!-- TOPI PET / HELM KLASIK PU (#F5B800 Emas PU dengan visor biru) -->
                        <path d="M 66 21 C 66 14, 78 13, 81 19 Z" fill="#F5B800" stroke="#002244" stroke-width="1.8"/>
                        <!-- Pin Lambang PU Mini -->
                        <circle cx="73" cy="17" r="1.5" fill="#003366"/>
                        <!-- Visor / Pet Topi Menghadap Depan -->
                        <path d="M 77 19 L 85 19 C 85 19, 83 22, 78 22 Z" fill="#003366" stroke="#002244" stroke-width="1.5"/>
                    </g>
                </svg>
            </div>
        </div>

        <!-- =================================================================== -->
        <!-- FOOTER                                                              -->
        <!-- =================================================================== -->
        <footer class="w-full bg-[#003366] border-t-2 border-[#002244] text-white py-5 relative z-20 shadow-md">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-2 text-xs font-medium">
                <p>&copy; {{ date('Y') }} <strong class="text-[#F5B800]">SURATKU • POLITEKNIK PEKERJAAN UMUM</strong>. Seluruh Hak Cipta Dilindungi.</p>
                <div class="flex items-center gap-2 text-blue-200">
                    <span>Sistem Rekapitulasi & Tracking Surat</span>
                    <span>•</span>
                    <span class="font-mono font-bold text-[#002244] bg-[#F5B800] border-2 border-[#002244] px-2.5 py-0.5 rounded-md text-[11px] shadow-[1px_1px_0px_#002244]">Versi Beta GC-001</span>
                </div>
            </div>
        </footer>

    </div>

</body>
</html>
