<!-- ========================================================================= -->
<!-- SIDEBAR MENU SUPER ADMIN (STATIS, FULL HEIGHT SAMPAI FOOTER, LANGSUNG MENU)-->
<!-- ========================================================================= -->

<!-- KONTEN SIDEBAR DESKTOP (STICKY DI DALAM ASIDE FULL HEIGHT) -->
<div class="flex flex-col h-full justify-between">
    <div class="flex flex-col flex-1 min-h-0">
        <!-- 1. Brand Logo Header -->
        <div class="h-16 flex items-center px-5 border-b-2 border-[#002244] bg-[#002852]/70 shrink-0">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group min-w-0">
                <div class="w-10 h-10 rounded-xl bg-white border-2 border-white shadow-[2px_2px_0px_#F5B800] p-1 flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                    <img src="{{ asset('images/logo-pu.webp') }}" alt="Logo Politeknik Pekerjaan Umum" class="w-full h-full object-contain">
                </div>
                <div class="min-w-0">
                    <div class="flex items-center gap-1.5">
                        <span class="text-base font-cartoon font-bold tracking-wide text-white">SURATKU</span>
                        <span class="px-1.5 py-0.5 text-[9px] font-black uppercase tracking-wider rounded-md bg-[#F5B800] text-[#002244] border-2 border-[#002244] shadow-[1px_1px_0px_#002244]">
                            BETA
                        </span>
                    </div>
                    <p class="text-[10px] text-blue-200 font-medium truncate">Politeknik Pekerjaan Umum</p>
                </div>
            </a>
        </div>

        <!-- 2. Langsung Menu Utama Super Admin (Informasi User Dihapus Sesuai Permintaan) -->
        <div class="flex-1 overflow-y-auto px-3.5 py-4 space-y-2.5">
            <div class="px-3 pb-1 text-[11px] font-black uppercase tracking-wider text-blue-200">
                Menu Utama Super Admin
            </div>

            <!-- Menu 1: Dashboard -->
            <a href="{{ route('dashboard') }}" 
               class="group flex items-center gap-3 px-3.5 py-2.5 rounded-2xl transition-all duration-150 {{ request()->routeIs('dashboard') ? 'bg-[#F5B800] text-[#002244] border-2 border-[#002244] shadow-[3px_3px_0px_#002244] font-cartoon font-black' : 'text-white hover:bg-white/10 border-2 border-transparent hover:border-white/20 font-cartoon font-bold' }}">
                <span class="text-xl shrink-0">📊</span>
                <div class="flex-1 min-w-0">
                    <div class="text-sm leading-tight truncate {{ request()->routeIs('dashboard') ? 'text-[#002244] font-black' : 'text-white font-bold' }}">Dashboard</div>
                    <div class="text-[10px] font-sans {{ request()->routeIs('dashboard') ? 'text-[#002244]/80 font-bold' : 'text-blue-100 font-semibold' }} truncate">Monitoring & Rekap Surat</div>
                </div>
                @if(request()->routeIs('dashboard'))
                    <span class="w-2.5 h-2.5 rounded-full bg-[#002244] shrink-0"></span>
                @endif
            </a>

            <!-- Menu 2: Kelola Pegawai -->
            <a href="{{ route('pegawai.index') }}" 
               class="group flex items-center gap-3 px-3.5 py-2.5 rounded-2xl transition-all duration-150 {{ request()->routeIs('pegawai.*') ? 'bg-[#F5B800] text-[#002244] border-2 border-[#002244] shadow-[3px_3px_0px_#002244] font-cartoon font-black' : 'text-white hover:bg-white/10 border-2 border-transparent hover:border-white/20 font-cartoon font-bold' }}">
                <span class="text-xl shrink-0">👥</span>
                <div class="flex-1 min-w-0">
                    <div class="text-sm leading-tight truncate {{ request()->routeIs('pegawai.*') ? 'text-[#002244] font-black' : 'text-white font-bold' }}">Kelola Pegawai</div>
                    <div class="text-[10px] font-sans {{ request()->routeIs('pegawai.*') ? 'text-[#002244]/80 font-bold' : 'text-blue-100 font-semibold' }} truncate">Manajemen Hak Akses & Akun</div>
                </div>
                @if(request()->routeIs('pegawai.*'))
                    <span class="w-2.5 h-2.5 rounded-full bg-[#002244] shrink-0"></span>
                @endif
            </a>

            <!-- Menu 3: Jenis Surat -->
            <a href="{{ route('jenis-surat.index') }}" 
               class="group flex items-center gap-3 px-3.5 py-2.5 rounded-2xl transition-all duration-150 {{ request()->routeIs('jenis-surat.*') ? 'bg-[#F5B800] text-[#002244] border-2 border-[#002244] shadow-[3px_3px_0px_#002244] font-cartoon font-black' : 'text-white hover:bg-white/10 border-2 border-transparent hover:border-white/20 font-cartoon font-bold' }}">
                <span class="text-xl shrink-0">📑</span>
                <div class="flex-1 min-w-0">
                    <div class="text-sm leading-tight truncate {{ request()->routeIs('jenis-surat.*') ? 'text-[#002244] font-black' : 'text-white font-bold' }}">Jenis Surat</div>
                    <div class="text-[10px] font-sans {{ request()->routeIs('jenis-surat.*') ? 'text-[#002244]/80 font-bold' : 'text-blue-100 font-semibold' }} truncate">Klasifikasi & Format Surat</div>
                </div>
                @if(request()->routeIs('jenis-surat.*'))
                    <span class="w-2.5 h-2.5 rounded-full bg-[#002244] shrink-0"></span>
                @endif
            </a>
        </div>
    </div>

    <!-- 3. Sidebar Bottom (Profile & Logout & Versi) -->
    <div class="p-3.5 border-t-2 border-[#002244] bg-[#002852]/70 space-y-2 shrink-0">
        <a href="{{ route('profile.edit') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs font-semibold text-blue-100 hover:text-white hover:bg-blue-900/70 transition">
            <span>👤</span>
            <span>Profil Akun</span>
        </a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs font-bold text-red-300 hover:text-red-200 hover:bg-red-500/20 transition cursor-pointer text-left">
                <span>🚪</span>
                <span>Keluar Sistem</span>
            </button>
        </form>
        <div class="pt-1 flex items-center justify-between text-[10px] text-blue-300/80 px-2 font-mono">
            <span>SURATKU SYSTEM</span>
            <span class="bg-[#F5B800] text-[#002244] px-1.5 py-0.5 rounded font-black">BETA</span>
        </div>
    </div>
</div>

<!-- MOBILE DRAWER SIDEBAR (TAMPIL HANYA PADA LAYAR MOBILE < lg KETIKA DIBUKA) -->
<div x-show="mobileSidebarOpen" 
     class="relative z-50 lg:hidden select-none" 
     style="display: none;"
     x-description="Mobile drawer sidebar untuk Super Admin">
    
    <!-- Backdrop Overlay -->
    <div x-show="mobileSidebarOpen" 
         x-transition:enter="transition-opacity ease-linear duration-300" 
         x-transition:enter-start="opacity-0" 
         x-transition:enter-end="opacity-100" 
         x-transition:leave="transition-opacity ease-linear duration-300" 
         x-transition:leave-start="opacity-100" 
         x-transition:leave-end="opacity-0" 
         @click="mobileSidebarOpen = false" 
         class="fixed inset-0 bg-slate-950/70 backdrop-blur-xs"></div>

    <div class="fixed inset-0 flex">
        <!-- Sidebar Sliding Panel -->
        <div x-show="mobileSidebarOpen" 
             x-transition:enter="transition ease-in-out duration-300 transform" 
             x-transition:enter-start="-translate-x-full" 
             x-transition:enter-end="translate-x-0" 
             x-transition:leave="transition ease-in-out duration-300 transform" 
             x-transition:leave-start="translate-x-0" 
             x-transition:leave-end="-translate-x-full" 
             class="relative mr-16 flex w-full max-w-xs flex-1 flex-col bg-[#003366] border-r-2 border-[#002244] text-white shadow-2xl justify-between">
            
            <!-- Close Button (X) di Luar Panel -->
            <div class="absolute right-0 top-0 -mr-12 pt-4">
                <button @click="mobileSidebarOpen = false" type="button" class="ml-1 flex h-10 w-10 items-center justify-center rounded-full text-white bg-blue-900/80 border border-blue-400/40 hover:bg-blue-800 focus:outline-none cursor-pointer">
                    <span class="sr-only">Tutup sidebar</span>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="flex flex-col flex-1 min-h-0">
                <!-- 1. Header Drawer Mobile -->
                <div class="h-16 flex items-center px-5 border-b-2 border-[#002244] bg-[#002852]/70 gap-3 shrink-0">
                    <div class="w-10 h-10 rounded-xl bg-white border-2 border-white shadow-[2px_2px_0px_#F5B800] p-1 flex items-center justify-center shrink-0">
                        <img src="{{ asset('images/logo-pu.webp') }}" alt="Logo Politeknik Pekerjaan Umum" class="w-full h-full object-contain">
                    </div>
                    <div class="min-w-0">
                        <div class="flex items-center gap-1.5">
                            <span class="text-base font-cartoon font-bold tracking-wide text-white">SURATKU</span>
                            <span class="px-1.5 py-0.5 text-[9px] font-black uppercase tracking-wider rounded-md bg-[#F5B800] text-[#002244] border-2 border-[#002244] shadow-[1px_1px_0px_#002244]">
                                BETA
                            </span>
                        </div>
                        <p class="text-[10px] text-blue-200 font-medium truncate">Politeknik Pekerjaan Umum</p>
                    </div>
                </div>

                <!-- 2. Langsung Menu Utama Super Admin -->
                <div class="flex-1 overflow-y-auto px-3.5 py-4 space-y-2.5">
                    <div class="px-3 pb-1 text-[11px] font-black uppercase tracking-wider text-blue-200">
                        Menu Utama Super Admin
                    </div>

                    <a href="{{ route('dashboard') }}" 
                       @click="mobileSidebarOpen = false"
                       class="group flex items-center gap-3 px-3.5 py-2.5 rounded-2xl transition-all duration-150 {{ request()->routeIs('dashboard') ? 'bg-[#F5B800] text-[#002244] border-2 border-[#002244] shadow-[3px_3px_0px_#002244] font-cartoon font-black' : 'text-white hover:bg-white/10 border-2 border-transparent hover:border-white/20 font-cartoon font-bold' }}">
                        <span class="text-xl shrink-0">📊</span>
                        <div class="flex-1 min-w-0">
                            <div class="text-sm leading-tight truncate {{ request()->routeIs('dashboard') ? 'text-[#002244] font-black' : 'text-white font-bold' }}">Dashboard</div>
                            <div class="text-[10px] font-sans {{ request()->routeIs('dashboard') ? 'text-[#002244]/80 font-bold' : 'text-blue-100 font-semibold' }} truncate">Monitoring & Rekap Surat</div>
                        </div>
                        @if(request()->routeIs('dashboard'))
                            <span class="w-2.5 h-2.5 rounded-full bg-[#002244] shrink-0"></span>
                        @endif
                    </a>

                    <a href="{{ route('pegawai.index') }}" 
                       @click="mobileSidebarOpen = false"
                       class="group flex items-center gap-3 px-3.5 py-2.5 rounded-2xl transition-all duration-150 {{ request()->routeIs('pegawai.*') ? 'bg-[#F5B800] text-[#002244] border-2 border-[#002244] shadow-[3px_3px_0px_#002244] font-cartoon font-black' : 'text-white hover:bg-white/10 border-2 border-transparent hover:border-white/20 font-cartoon font-bold' }}">
                        <span class="text-xl shrink-0">👥</span>
                        <div class="flex-1 min-w-0">
                            <div class="text-sm leading-tight truncate {{ request()->routeIs('pegawai.*') ? 'text-[#002244] font-black' : 'text-white font-bold' }}">Kelola Pegawai</div>
                            <div class="text-[10px] font-sans {{ request()->routeIs('pegawai.*') ? 'text-[#002244]/80 font-bold' : 'text-blue-100 font-semibold' }} truncate">Manajemen Hak Akses & Akun</div>
                        </div>
                        @if(request()->routeIs('pegawai.*'))
                            <span class="w-2.5 h-2.5 rounded-full bg-[#002244] shrink-0"></span>
                        @endif
                    </a>

                    <a href="{{ route('jenis-surat.index') }}" 
                       @click="mobileSidebarOpen = false"
                       class="group flex items-center gap-3 px-3.5 py-2.5 rounded-2xl transition-all duration-150 {{ request()->routeIs('jenis-surat.*') ? 'bg-[#F5B800] text-[#002244] border-2 border-[#002244] shadow-[3px_3px_0px_#002244] font-cartoon font-black' : 'text-white hover:bg-white/10 border-2 border-transparent hover:border-white/20 font-cartoon font-bold' }}">
                        <span class="text-xl shrink-0">📑</span>
                        <div class="flex-1 min-w-0">
                            <div class="text-sm leading-tight truncate {{ request()->routeIs('jenis-surat.*') ? 'text-[#002244] font-black' : 'text-white font-bold' }}">Jenis Surat</div>
                            <div class="text-[10px] font-sans {{ request()->routeIs('jenis-surat.*') ? 'text-[#002244]/80 font-bold' : 'text-blue-100 font-semibold' }} truncate">Klasifikasi & Format Surat</div>
                        </div>
                        @if(request()->routeIs('jenis-surat.*'))
                            <span class="w-2.5 h-2.5 rounded-full bg-[#002244] shrink-0"></span>
                        @endif
                    </a>
                </div>
            </div>

            <!-- 3. Footer Drawer Mobile -->
            <div class="p-3.5 border-t-2 border-[#002244] bg-[#002852]/70 space-y-2 shrink-0">
                <a href="{{ route('profile.edit') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs font-semibold text-blue-100 hover:text-white hover:bg-blue-900/70 transition">
                    <span>👤</span>
                    <span>Profil Akun</span>
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs font-bold text-red-300 hover:text-red-200 hover:bg-red-500/20 transition cursor-pointer text-left">
                        <span>🚪</span>
                        <span>Keluar Sistem</span>
                    </button>
                </form>
                <div class="pt-1 flex items-center justify-between text-[10px] text-blue-300/80 px-2 font-mono">
                    <span>SURATKU SYSTEM</span>
                    <span class="bg-[#F5B800] text-[#002244] px-1.5 py-0.5 rounded font-black">BETA</span>
                </div>
            </div>
        </div>
    </div>
</div>
