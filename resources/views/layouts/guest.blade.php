<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Suratku (Beta)</title>

        <!-- Favicon Logo Tab -->
        <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}?v=1">
        <link rel="shortcut icon" href="{{ asset('favicon.ico') }}?v=1">

        <!-- Google Fonts: Plus Jakarta Sans -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-pu-canvas text-slate-800 font-sans antialiased relative overflow-x-hidden selection:bg-[#003366] selection:text-white flex flex-col justify-between items-center pt-8 pb-0">
        
        <div class="relative z-10 w-full max-w-md my-auto px-4 sm:px-6">
            <!-- Brand Logo -->
            <div class="flex flex-col items-center justify-center mb-6 text-center">
                <a href="/" class="group inline-flex flex-col items-center">
                    <div class="w-20 h-20 rounded-3xl bg-white border-2 border-[#003366] shadow-[4px_4px_0px_#003366] p-3 flex items-center justify-center group-hover:-translate-y-1 transition duration-200">
                        <img src="{{ asset('images/logo-pu.webp') }}" alt="Logo Politeknik Pekerjaan Umum" class="w-full h-full object-contain">
                    </div>
                    <div class="mt-3 flex items-center gap-1.5">
                        <span class="text-xl font-cartoon font-bold text-[#003366] tracking-wide">SURATKU</span>
                        <span class="px-2 py-0.5 text-[9px] font-black uppercase tracking-wider rounded-md bg-[#F5B800] text-[#002244] border-2 border-[#002244] shadow-[1.5px_1.5px_0px_#002244]">
                            BETA
                        </span>
                    </div>
                    <p class="text-[11px] font-medium text-slate-600 mt-0.5">Politeknik Pekerjaan Umum</p>
                </a>
            </div>

            <!-- Card Container -->
            <div class="cartoon-card rounded-3xl p-6 sm:p-8 bg-white text-slate-800">
                {{ $slot }}
            </div>
        </div>

        <!-- Footer -->
        <footer class="w-full bg-[#003366] border-t-2 border-[#002244] text-white py-5 relative z-20 mt-12 shadow-md">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-2 text-xs font-medium">
                <p>&copy; {{ date('Y') }} <strong class="text-[#F5B800]">SURATKU • POLITEKNIK PEKERJAAN UMUM</strong>. Seluruh Hak Cipta Dilindungi.</p>
                <div class="flex items-center gap-2 text-blue-200">
                    <span>Sistem Rekapitulasi & Tracking Surat</span>
                    <span>•</span>
                    <span class="font-mono font-bold text-[#002244] bg-[#F5B800] border-2 border-[#002244] px-2.5 py-0.5 rounded-md text-[11px] shadow-[1px_1px_0px_#002244]">Versi Beta GC-001</span>
                </div>
            </div>
        </footer>
    </body>
</html>

