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

        <!-- Scripts & Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-[#090e1a] text-slate-100 font-sans antialiased relative overflow-x-hidden selection:bg-blue-600 selection:text-white flex flex-col">
        
        <!-- BACKGROUND AMBIENT GLOW & GRID PATTERN -->
        <div class="fixed inset-0 pointer-events-none z-0">
            <!-- Grid Pattern Overlay -->
            <div class="absolute inset-0 bg-grid-pattern opacity-40"></div>

            <!-- Glowing Spheres -->
            <div class="absolute -top-32 -left-32 w-[520px] h-[520px] rounded-full bg-blue-600/20 blur-[120px] animate-glow-1"></div>
            <div class="absolute top-1/3 -right-24 w-[460px] h-[460px] rounded-full bg-indigo-600/18 blur-[130px] animate-glow-2"></div>
            <div class="absolute -bottom-32 left-1/3 w-[500px] h-[500px] rounded-full bg-cyan-600/15 blur-[140px]"></div>
        </div>

        <!-- MAIN LAYOUT WRAPPER -->
        <div class="relative z-10 flex flex-col flex-1 min-h-screen">
            
            <!-- Top Navigation Bar -->
            <div class="relative z-30">
                @include('layouts.navigation')
            </div>

            <!-- Page Heading (Jika Ada) -->
            @isset($header)
                <header class="bg-slate-900/60 backdrop-blur-md border-b border-white/10 text-white shadow-lg relative z-20">
                    <div class="max-w-7xl mx-auto py-5 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main class="relative z-10 flex-1">
                {{ $slot }}
            </main>

            <!-- Unified Footer -->
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
        </div>
    </body>
</html>
