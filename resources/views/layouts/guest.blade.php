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
    <body class="min-h-screen bg-[#090e1a] text-slate-100 font-sans antialiased relative overflow-x-hidden selection:bg-blue-600 selection:text-white flex flex-col justify-between items-center pt-12 pb-0">
        
        <!-- Ambient Glow & Grid -->
        <div class="fixed inset-0 pointer-events-none z-0">
            <div class="absolute inset-0 bg-grid-pattern opacity-40"></div>
            <div class="absolute -top-32 -left-32 w-[520px] h-[520px] rounded-full bg-blue-600/25 blur-[120px] animate-glow-1"></div>
            <div class="absolute top-1/3 -right-24 w-[460px] h-[460px] rounded-full bg-indigo-600/20 blur-[130px] animate-glow-2"></div>
            <div class="absolute -bottom-32 left-1/3 w-[500px] h-[500px] rounded-full bg-cyan-600/15 blur-[140px]"></div>
        </div>

        <div class="relative z-10 w-full max-w-md my-auto px-4 sm:px-6">
            <!-- Brand Logo -->
            <div class="flex justify-center mb-6">
                <a href="/" class="flex items-center space-x-3 group">
                    <img src="{{ asset('images/logo-pu.webp') }}" alt="Logo Politeknik Pekerjaan Umum" class="w-16 h-16 object-contain drop-shadow-lg group-hover:scale-105 transition-transform duration-200">
                </a>
            </div>

            <!-- Card Container -->
            <div class="glass-panel rounded-2xl p-6 sm:p-8 border border-white/10 shadow-2xl text-slate-100">
                {{ $slot }}
            </div>
        </div>

        <!-- Footer -->
        <footer class="w-full border-t border-white/10 py-6 text-center text-xs text-slate-400 bg-slate-900/40 relative z-20 mt-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-2">
                <p>&copy; {{ date('Y') }} <strong>SURATKU - POLITEKNIK PEKERJAAN UMUM</strong>. Seluruh Hak Cipta Dilindungi.</p>
                <div class="flex items-center gap-2 text-slate-400">
                    <span>Sistem Unggul Rekapitulasi dan Tracking Surat</span>
                    <span>•</span>
                    <span class="font-mono font-bold text-amber-300 bg-amber-500/15 border border-amber-400/30 px-2.5 py-0.5 rounded-md text-[11px]">Versi Beta GC-001</span>
                </div>
            </div>
        </footer>
    </body>
</html>

