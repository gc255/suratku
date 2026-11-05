@php
    $isSuperAdmin = Auth::check() && in_array(Auth::user()->role ?? '', ['super_admin', 'superadmin']);
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Suratku (Beta) - Politeknik Pekerjaan Umum</title>

        <!-- Favicon Logo Tab -->
        <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}?v=1">
        <link rel="shortcut icon" href="{{ asset('favicon.ico') }}?v=1">

        <!-- Google Fonts: Plus Jakarta Sans & Fredoka -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&family=Fredoka:wght@500;600;700&display=swap" rel="stylesheet">

        <!-- Scripts & Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-[#F4F6F9] text-slate-800 font-sans antialiased relative overflow-x-hidden selection:bg-[#003366] selection:text-white"
          x-data="{ mobileSidebarOpen: false }">
        
        @if($isSuperAdmin)
            <!-- =============================================================== -->
            <!-- SUPERADMIN LAYOUT DENGAN SIDEBAR STATIS FULL-HEIGHT KE FOOTER   -->
            <!-- =============================================================== -->
            <div class="min-h-screen flex w-full bg-[#F4F6F9]">
                <!-- Sidebar Column (Statis & Full Height ke Bawah sampai Footer) -->
                <aside class="hidden lg:flex lg:flex-col lg:w-68 shrink-0 bg-[#003366] border-r-2 border-[#002244] text-white select-none self-stretch">
                    <div class="sticky top-0 flex flex-col h-screen justify-between overflow-y-auto">
                        @include('layouts.sidebar')
                    </div>
                </aside>

                <!-- Main Content Area (Kanan) -->
                <div class="flex-1 flex flex-col min-w-0 min-h-screen justify-between bg-[#F4F6F9]">
                    <div>
                        <!-- Top Navigation Bar -->
                        <div class="relative z-20">
                            @include('layouts.navigation')
                        </div>

                        <!-- Page Heading (Jika Ada) -->
                        @isset($header)
                            <header class="bg-white border-b-2 border-[#003366] text-[#003366] shadow-xs relative z-10">
                                <div class="max-w-7xl mx-auto py-5 px-4 sm:px-6 lg:px-8">
                                    {{ $header }}
                                </div>
                            </header>
                        @endisset

                        <!-- Page Content -->
                        <main class="relative z-10">
                            {{ $slot }}
                        </main>
                    </div>

                    <!-- Unified Footer -->
                    <footer class="w-full bg-[#003366] border-t-2 border-[#002244] text-white py-5 relative z-10 mt-12 shadow-md">
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
            </div>
        @else
            <!-- =============================================================== -->
            <!-- NON-SUPERADMIN LAYOUT (TOP NAVIGATION STANDAR TANPA SIDEBAR)     -->
            <!-- =============================================================== -->
            <div class="relative z-10 flex flex-col flex-1 min-h-screen justify-between bg-[#F4F6F9]">
                <div>
                    <!-- Top Navigation Bar -->
                    <div class="relative z-30">
                        @include('layouts.navigation')
                    </div>

                    <!-- Page Heading (Jika Ada) -->
                    @isset($header)
                        <header class="bg-white border-b-2 border-[#003366] text-[#003366] shadow-xs relative z-20">
                            <div class="max-w-7xl mx-auto py-5 px-4 sm:px-6 lg:px-8">
                                {{ $header }}
                            </div>
                        </header>
                    @endisset

                    <!-- Page Content -->
                    <main class="relative z-10">
                        {{ $slot }}
                    </main>
                </div>

                <!-- Unified Footer -->
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
            </div>
        @endif
    </body>
</html>
