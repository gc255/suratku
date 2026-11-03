<nav x-data="{ open: false }" class="bg-slate-900/80 backdrop-blur-xl border-b border-white/10 text-white shadow-xl">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            
            <!-- Left Side: Brand Logo & Links -->
            <div class="flex items-center space-x-6">
                <!-- Brand / Logo -->
                <a href="{{ route('dashboard') }}" class="shrink-0 flex items-center space-x-3 group">
                    <img src="{{ asset('images/logo-pu.webp') }}" alt="Logo Politeknik Pekerjaan Umum" class="w-10 h-10 object-contain drop-shadow group-hover:scale-105 transition-transform duration-200">
                    <div class="hidden sm:block">
                        <div class="flex items-center gap-1.5">
                            <span class="text-base font-extrabold tracking-tight text-white">SURATKU</span>
                            <span class="px-1.5 py-0.5 text-[9px] font-extrabold uppercase tracking-wider rounded-md bg-amber-500/20 text-amber-300 border border-amber-400/40 shadow-xs">
                                BETA
                            </span>
                            <span class="px-1.5 py-0.5 text-[9px] font-bold uppercase tracking-wider rounded-full bg-blue-500/20 text-blue-300 border border-blue-400/30">
                                POLITEKNIK PEKERJAAN UMUM
                            </span>
                        </div>
                        <p class="text-[10px] text-slate-400 font-medium -mt-0.5">Sistem Unggul Rekapitulasi dan Tracking Surat</p>
                    </div>
                    <!-- Tampilan Brand Versi Mobile -->
                    <div class="sm:hidden flex items-center gap-1.5">
                        <span class="text-base font-extrabold tracking-tight text-white">SURATKU</span>
                        <span class="px-1.5 py-0.5 text-[9px] font-extrabold uppercase tracking-wider rounded-md bg-amber-500/20 text-amber-300 border border-amber-400/40 shadow-xs">
                            BETA
                        </span>
                    </div>
                </a>

                <!-- Navigation Links -->
                <div class="hidden md:flex items-center space-x-2">
                    @if(in_array(Auth::user()->role, ['super_admin', 'superadmin']))
                        <x-nav-link :href="route('pegawai.index')" :active="request()->routeIs('pegawai.*')">
                            <span class="mr-1.5">👥</span> {{ __('Kelola Pegawai') }}
                        </x-nav-link>
                    @endif

                    @if(in_array(Auth::user()->role, ['super_admin', 'superadmin']))
                        <x-nav-link :href="route('jenis-surat.index')" :active="request()->routeIs('jenis-surat.*')">
                            <span class="mr-1.5">📑</span> {{ __('Jenis Surat') }}
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- Right Side: Live Clock & Profile Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:space-x-4">
                
                <!-- WIDGET JAM & TANGGAL DENGAN KEDIPAN HIJAU (PULSING GREEN DOT) -->
                <div class="flex items-center space-x-2 text-xs font-semibold text-slate-300 glass-pill px-3.5 py-1.5 rounded-full"
                     x-data="{
                         currentTime: '',
                         updateTime() {
                             const now = new Date();
                             const options = { weekday: 'long', day: 'numeric', month: 'short', year: 'numeric' };
                             const timeStr = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }) + ' WIB';
                             this.currentTime = now.toLocaleDateString('id-ID', options) + ' • ' + timeStr;
                         }
                     }"
                     x-init="updateTime(); setInterval(() => updateTime(), 30000)">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                    <span class="w-2 h-2 rounded-full bg-emerald-500 -ml-3"></span>
                    <span x-text="currentTime"></span>
                </div>

                <!-- Settings Dropdown -->
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="glass-pill px-3 py-1.5 rounded-xl hover:bg-white/10 text-slate-200 focus:outline-none transition flex items-center space-x-2 cursor-pointer border border-white/15">
                            <div class="w-7 h-7 rounded-lg bg-gradient-to-tr from-blue-600 to-indigo-600 flex items-center justify-center text-white font-bold text-xs shadow-xs">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <div class="text-left hidden lg:block">
                                <div class="text-xs font-bold text-white truncate max-w-[120px]">{{ Auth::user()->name }}</div>
                                <div class="text-[10px] text-blue-300 font-semibold">{{ Auth::user()->role_label }}</div>
                            </div>
                            <svg class="fill-current h-3.5 w-3.5 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="px-3 py-2 border-b border-white/10 mb-1">
                            <div class="text-xs font-bold text-white">{{ Auth::user()->name }}</div>
                            <div class="text-[11px] text-slate-400">{{ Auth::user()->nip ? 'NIP: ' . Auth::user()->nip : Auth::user()->email }}</div>
                            <span class="mt-1 inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-blue-500/20 text-blue-300 border border-blue-400/30">
                                {{ Auth::user()->role_label }}
                            </span>
                        </div>

                        <x-dropdown-link :href="route('profile.edit')">
                            <span class="mr-1.5">👤</span> {{ __('Profil Akun') }}
                        </x-dropdown-link>

                        <!-- Authentication Logout -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();"
                                    class="text-red-400 hover:text-red-300 hover:bg-red-500/10">
                                <span class="mr-1.5">🚪</span> {{ __('Keluar Sistem') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger (Mobile) -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-lg text-slate-400 hover:text-white glass-pill focus:outline-none transition">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Mobile Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden border-t border-white/10 bg-slate-950/95 backdrop-blur-xl">
        <div class="pt-2 pb-3 space-y-1 px-3">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                <span class="mr-1.5">📊</span> {{ __('Dashboard') }}
            </x-responsive-nav-link>

            @if(in_array(Auth::user()->role, ['super_admin', 'superadmin']))
                <x-responsive-nav-link :href="route('pegawai.index')" :active="request()->routeIs('pegawai.*')">
                    <span class="mr-1.5">👥</span> {{ __('Kelola Pegawai') }}
                </x-responsive-nav-link>
            @endif

            @if(in_array(Auth::user()->role, ['super_admin', 'superadmin']))
                <x-responsive-nav-link :href="route('jenis-surat.index')" :active="request()->routeIs('jenis-surat.*')">
                    <span class="mr-1.5">📑</span> {{ __('Jenis Surat') }}
                </x-responsive-nav-link>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-3 border-t border-white/10 px-4">
            <div class="flex items-center gap-2 mb-3">
                <div class="w-8 h-8 rounded-lg bg-gradient-to-tr from-blue-600 to-indigo-600 flex items-center justify-center text-white font-bold text-xs">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div>
                    <div class="font-bold text-sm text-white">{{ Auth::user()->name }}</div>
                    <div class="text-xs text-blue-300">{{ Auth::user()->role_label }}</div>
                </div>
            </div>

            <div class="space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    <span class="mr-1.5">👤</span> {{ __('Profil Akun') }}
                </x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault(); this.closest('form').submit();"
                            class="text-red-400 hover:text-red-300">
                        <span class="mr-1.5">🚪</span> {{ __('Keluar Sistem') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
