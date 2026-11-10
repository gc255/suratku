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
                                    Gunakan NIP, NIK, NIM, atau Username terdaftar untuk memulai sesi kerja.
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

                                <!-- Input: NIP / NIK / NIM / USERNAME -->
                                <div>
                                    <label for="input_nip" class="block text-xs font-cartoon font-bold text-slate-800 uppercase tracking-wider mb-1.5">
                                        NIP / NIK / NIM / USERNAME <span class="text-red-500">*</span>
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
                                               placeholder="Masukkan NIP, NIK, atau username"
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

                            <!-- Badge Informasi Keamanan Bawah -->
                            <div class="mt-6 pt-5 border-t-2 border-slate-100 flex items-center justify-center gap-2 text-slate-600 text-[11px] font-semibold text-center">
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
