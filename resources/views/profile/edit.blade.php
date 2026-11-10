<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="font-cartoon font-bold text-xl text-[#003366] leading-tight flex items-center gap-2">
                    <span>🔑</span> {{ __('Pengaturan Kata Sandi & Profil Akun') }}
                </h2>
                <p class="text-xs text-slate-600 mt-1 font-medium">Perbarui kata sandi akun untuk keamanan, serta informasi identitas pribadi Anda.</p>
            </div>
            <a href="{{ route('dashboard') }}" class="btn-pop-navy px-4 py-2 rounded-xl text-xs font-cartoon font-bold transition inline-flex items-center gap-1.5 self-start sm:self-auto cursor-pointer">
                <span>Ke Dashboard</span> <span>&rarr;</span>
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            @if (session('info'))
                <div class="cartoon-card rounded-3xl p-5 bg-amber-50 border-2 border-amber-500 shadow-[4px_4px_0px_#D97706] text-amber-950 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <span class="text-2xl">🔐</span>
                        <div>
                            <h4 class="font-cartoon font-bold text-sm text-[#002244]">Pemberitahuan Keamanan Akun</h4>
                            <p class="text-xs text-amber-900 font-medium mt-0.5">{{ session('info') }}</p>
                        </div>
                    </div>
                    <a href="{{ route('dashboard') }}" class="btn-pop-white px-4 py-2 rounded-xl text-xs font-cartoon font-bold inline-flex items-center gap-1.5 shrink-0">
                        <span>Lewati ke Dashboard</span> <span>&rarr;</span>
                    </a>
                </div>
            @endif

            <!-- 1. Form Ganti Kata Sandi (Utama) -->
            <div class="cartoon-card rounded-3xl p-6 sm:p-8 bg-white border-2 border-[#003366] shadow-[4px_4px_0px_#003366] text-slate-800 font-cartoon" id="password-section">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <!-- 2. Form Informasi Profil -->
            <div class="cartoon-card rounded-3xl p-6 sm:p-8 bg-white border-2 border-[#003366] shadow-[4px_4px_0px_#003366] text-slate-800 font-cartoon">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

