<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="font-cartoon font-bold text-xl text-[#003366] leading-tight flex items-center gap-2">
                    <span>👤</span> {{ __('Pengaturan Profil & Akun') }}
                </h2>
                <p class="text-xs text-slate-600 mt-1 font-medium">Perbarui informasi identitas pribadi, nomor WhatsApp, dan kata sandi akun Anda.</p>
            </div>
            <a href="{{ route('dashboard') }}" class="btn-pop-white px-3.5 py-1.5 rounded-xl text-xs font-cartoon font-bold transition inline-flex items-center gap-2 self-start sm:self-auto cursor-pointer">
                <span>&larr;</span> Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <div class="cartoon-card rounded-3xl p-6 sm:p-8 bg-white border-2 border-[#003366] shadow-[4px_4px_0px_#003366] text-slate-800 font-cartoon">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="cartoon-card rounded-3xl p-6 sm:p-8 bg-white border-2 border-[#003366] shadow-[4px_4px_0px_#003366] text-slate-800 font-cartoon">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

