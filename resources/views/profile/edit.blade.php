<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="font-extrabold text-xl text-white leading-tight flex items-center gap-2">
                    <span>👤</span> {{ __('Pengaturan Profil & Akun') }}
                </h2>
                <p class="text-xs text-slate-400 mt-1">Perbarui informasi identitas pribadi, nomor WhatsApp, dan kata sandi akun Anda.</p>
            </div>
            <a href="{{ route('dashboard') }}" class="glass-pill px-3.5 py-1.5 rounded-xl hover:bg-white/10 text-xs font-bold text-slate-300 hover:text-white transition inline-flex items-center gap-2 self-start sm:self-auto border border-white/15">
                <span>&larr;</span> Kembali ke Dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <div class="glass-panel rounded-2xl p-6 sm:p-8 border border-white/10 shadow-2xl text-slate-100">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="glass-panel rounded-2xl p-6 sm:p-8 border border-white/10 shadow-2xl text-slate-100">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

