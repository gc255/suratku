@php
    $isSuperAdmin = Auth::check() && in_array(Auth::user()->role ?? '', ['super_admin', 'superadmin']);
@endphp

<nav x-data="{ open: false }" class="bg-[#003366] border-b-2 border-[#002244] text-white shadow-md">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            
            <!-- Left Side: Header Super Admin ATAU Brand Logo & Links Non-Superadmin -->
            <div class="flex items-center space-x-3 sm:space-x-4">
                @if($isSuperAdmin)
                    <!-- Tombol Hamburger Mobile Drawer Super Admin (Hanya tampil di layar kecil < lg) -->
                    <button @click="mobileSidebarOpen = true" 
                            type="button" 
                            class="lg:hidden inline-flex items-center justify-center p-2 rounded-xl text-white bg-blue-900/70 border border-blue-400/40 hover:bg-blue-800 focus:outline-none transition cursor-pointer shadow-xs"
                            title="Buka Menu Sidebar">
                        <span class="sr-only">Buka Menu Sidebar</span>
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>

                    <!-- Mobile Brand Logo (Hanya tampil di layar kecil < lg pada Superadmin) -->
                    <a href="{{ route('dashboard') }}" class="lg:hidden flex items-center space-x-2.5 group">
                        <div class="w-8 h-8 rounded-lg bg-white border border-white shadow-[1.5px_1.5px_0px_#F5B800] p-1 flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                            <img src="{{ asset('images/logo-pu.webp') }}" alt="Logo Politeknik Pekerjaan Umum" class="w-full h-full object-contain">
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="text-sm font-cartoon font-bold tracking-wide text-white">SURATKU</span>
                            <span class="px-1.5 py-0.2 text-[8px] font-black uppercase tracking-wider rounded bg-[#F5B800] text-[#002244] border border-[#002244]">
                                BETA
                            </span>
                        </div>
                    </a>

                    <!-- Desktop Header Indicator (Hanya tampil pada Desktop) -->
                    <div class="hidden lg:flex items-center gap-2.5">
                        <span class="px-2.5 py-1 text-[10px] font-black uppercase tracking-wider rounded-md bg-[#F5B800] text-[#002244] border-2 border-[#002244] shadow-[1.5px_1.5px_0px_#002244]">
                            ADMIN PANEL
                        </span>
                        <span class="text-xs font-cartoon font-bold text-white tracking-wide">
                            Portal Pengelolaan Terpadu
                        </span>
                    </div>
                @else
                    <!-- Brand / Logo (Tampilan Normal untuk Non-Superadmin) -->
                    <a href="{{ route('dashboard') }}" class="shrink-0 flex items-center space-x-3 group">
                        <div class="w-10 h-10 rounded-xl bg-white border-2 border-white shadow-[2px_2px_0px_#F5B800] p-1 flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                            <img src="{{ asset('images/logo-pu.webp') }}" alt="Logo Politeknik Pekerjaan Umum" class="w-full h-full object-contain">
                        </div>
                        <div class="hidden sm:block">
                            <div class="flex items-center gap-1.5">
                                <span class="text-base font-cartoon font-bold tracking-wide text-white">SURATKU</span>
                                <span class="px-1.5 py-0.5 text-[9px] font-black uppercase tracking-wider rounded-md bg-[#F5B800] text-[#002244] border-2 border-[#002244] shadow-[1px_1px_0px_#002244]">
                                    BETA
                                </span>
                                <span class="px-2 py-0.5 text-[9px] font-bold uppercase tracking-wider rounded-full bg-blue-900/80 text-blue-200 border border-blue-400/40">
                                    POLITEKNIK PEKERJAAN UMUM
                                </span>
                            </div>
                            <p class="text-[10px] text-blue-200 font-medium -mt-0.5">Sistem Rekapitulasi & Tracking Surat</p>
                        </div>
                        <div class="sm:hidden flex items-center gap-1.5">
                            <span class="text-base font-cartoon font-bold tracking-wide text-white">SURATKU</span>
                            <span class="px-1.5 py-0.5 text-[9px] font-black uppercase tracking-wider rounded-md bg-[#F5B800] text-[#002244] border-2 border-[#002244] shadow-[1px_1px_0px_#002244]">
                                BETA
                            </span>
                        </div>
                    </a>
                @endif
            </div>

            <!-- Right Side: Profile Dropdown -->
            <div class="flex items-center space-x-2.5 sm:space-x-4">
                <!-- Tombol Notifikasi Web Push (PWA) -->
                <div x-data="{
                    supported: ('Notification' in window) && ('serviceWorker' in navigator),
                    permission: ('Notification' in window) ? Notification.permission : 'denied',
                    loading: false,
                    init() {
                        if (this.supported && Notification.permission === 'granted') {
                            this.permission = 'granted';
                        }
                    },
                    async toggle() {
                        if (!this.supported) {
                            alert('Browser Anda belum mendukung notifikasi Web Push.');
                            return;
                        }
                        this.loading = true;
                        try {
                            if (this.permission === 'granted') {
                                const isSub = await window.SuratkuPush.isSubscribed();
                                if (isSub) {
                                    if (confirm('Nonaktifkan notifikasi di perangkat HP/browser ini?')) {
                                        await window.SuratkuPush.unsubscribe();
                                        alert('Notifikasi di perangkat ini telah dinonaktifkan.');
                                    }
                                } else {
                                    await window.SuratkuPush.subscribe();
                                    alert('Notifikasi di perangkat ini telah diaktifkan kembali.');
                                }
                            } else if (this.permission === 'denied') {
                                alert('Izin notifikasi diblokir di browser Anda. Silakan ubah izin situs di setelan browser HP Anda untuk mengizinkan notifikasi.');
                            } else {
                                const ok = await window.SuratkuPush.requestPermissionAndSubscribe();
                                this.permission = Notification.permission;
                                if (ok) {
                                    alert('Sukses! Notifikasi surat dan tanda antrean di HP Anda telah aktif.');
                                }
                            }
                        } finally {
                            this.loading = false;
                        }
                    }
                }" x-show="supported" class="relative">
                    <button @click="toggle()"
                            :disabled="loading"
                            type="button"
                            :title="permission === 'granted' ? 'Notifikasi HP Aktif (Klik untuk kelola)' : (permission === 'denied' ? 'Izin notifikasi diblokir di browser' : 'Aktifkan Notifikasi di HP')"
                            class="relative p-2 rounded-xl border-2 transition duration-150 cursor-pointer flex items-center justify-center select-none"
                            :class="permission === 'granted' ? 'bg-[#002244] border-emerald-400 text-emerald-300 hover:bg-[#001830] shadow-[2px_2px_0px_#10B981]' : (permission === 'denied' ? 'bg-[#002244] border-slate-500 text-slate-400 shadow-[2px_2px_0px_#475569]' : 'bg-[#002244] border-[#F5B800] text-[#F5B800] hover:bg-[#001830] shadow-[2px_2px_0px_#F5B800]')">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        
                        <!-- Indikator aktif (centang kecil hijau) -->
                        <span x-show="permission === 'granted'" class="absolute -top-1 -right-1 flex h-3.5 w-3.5 items-center justify-center rounded-full bg-emerald-500 border border-[#002244] text-[8px] font-black text-white">✓</span>

                        <!-- Indikator belum aktif (titik kuning berkedip) -->
                        <span x-show="permission === 'default'" class="absolute -top-1 -right-1 flex h-3.5 w-3.5">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-3.5 w-3.5 bg-amber-500 border border-white"></span>
                        </span>
                    </button>
                </div>

                <!-- Settings Dropdown -->
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="px-3 py-1.5 rounded-xl bg-[#002244] hover:bg-[#001830] text-white focus:outline-none transition flex items-center space-x-2.5 cursor-pointer border-2 border-[#F5B800] shadow-[2px_2px_0px_#F5B800]">
                            <div class="w-7 h-7 rounded-lg bg-[#F5B800] text-[#002244] border-2 border-[#002244] flex items-center justify-center font-cartoon font-black text-xs shadow-[1px_1px_0px_#002244]">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <div class="text-left hidden lg:block font-cartoon">
                                <div class="text-xs font-black text-white truncate max-w-[130px] leading-tight">{{ Auth::user()->name }}</div>
                                <div class="text-[10px] text-amber-300 font-bold leading-tight">{{ Auth::user()->role_label }}</div>
                            </div>
                            <svg class="fill-current h-3.5 w-3.5 text-amber-300 ml-0.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <!-- User Info Header Box -->
                        <div class="px-4 py-3 border-b-2 border-slate-200 bg-amber-50/90">
                            <div class="text-xs font-black text-[#003366] truncate font-cartoon">{{ Auth::user()->name }}</div>
                            <div class="text-[11px] font-semibold text-slate-500 truncate mt-0.5">{{ Auth::user()->nip ? 'NIP: ' . Auth::user()->nip : Auth::user()->email }}</div>
                            <div class="mt-2">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-lg text-[10px] font-black bg-[#F5B800] text-[#002244] border-2 border-[#002244] shadow-[1px_1px_0px_#002244]">
                                    {{ Auth::user()->role_label }}
                                </span>
                            </div>
                        </div>

                        <!-- Dropdown Links -->
                        <div class="py-1">
                            <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-2.5 text-xs font-bold text-slate-700 hover:text-[#003366] hover:bg-amber-100/60 transition font-cartoon cursor-pointer">
                                <span class="mr-2 text-sm">👤</span> {{ __('Profil Akun') }}
                            </a>

                            <!-- Authentication Logout -->
                            <form method="POST" action="{{ route('logout') }}" class="border-t-2 border-slate-100">
                                @csrf
                                <button type="submit" class="w-full text-left flex items-center px-4 py-2.5 text-xs font-black text-rose-600 hover:text-rose-700 hover:bg-rose-50 transition font-cartoon cursor-pointer">
                                    <span class="mr-2 text-sm">🚪</span> {{ __('Keluar Sistem') }}
                                </button>
                            </form>
                        </div>
                    </x-slot>
                </x-dropdown>

                @if(!$isSuperAdmin)
                    <!-- Hamburger Mobile untuk Non-Superadmin -->
                    <div class="-me-2 flex items-center sm:hidden">
                        <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-xl text-blue-200 hover:text-white bg-blue-900/60 border border-blue-400/40 focus:outline-none transition">
                            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @if(!$isSuperAdmin)
        <!-- Responsive Mobile Menu untuk Non-Superadmin -->
        <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden border-t-2 border-blue-800 bg-[#002852]">
            <!-- Responsive Settings Options -->
            <div class="pt-3 pb-3 border-t border-blue-800 px-4">
                <div class="flex items-center gap-2 mb-3">
                    <div class="w-8 h-8 rounded-lg bg-[#F5B800] text-[#002244] border-2 border-[#002244] flex items-center justify-center font-cartoon font-bold text-xs shadow-[1px_1px_0px_#002244]">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div>
                        <div class="font-bold text-sm text-white font-cartoon">{{ Auth::user()->name }}</div>
                        <div class="text-xs text-amber-300 font-cartoon">{{ Auth::user()->role_label }}</div>
                    </div>
                </div>

                <div class="space-y-1">
                    <x-responsive-nav-link :href="route('profile.edit')" class="text-blue-100 hover:text-white font-cartoon">
                        <span class="mr-1.5">👤</span> {{ __('Profil Akun') }}
                    </x-responsive-nav-link>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left flex items-center px-4 py-2 text-sm font-bold text-rose-300 hover:text-rose-100 hover:bg-rose-900/30 rounded-xl transition font-cartoon cursor-pointer">
                            <span class="mr-1.5">🚪</span> {{ __('Keluar Sistem') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @endif
</nav>
