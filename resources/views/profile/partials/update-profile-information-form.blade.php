<section>
    <header>
        <h2 class="text-lg font-cartoon font-bold text-[#003366]">
            {{ __('Informasi Profil') }}
        </h2>

        <p class="mt-1 text-xs text-slate-600 font-medium">
            {{ __("Perbarui nama lengkap dan informasi nomor WhatsApp akun Anda.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" :value="__('Nama Lengkap')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2 text-rose-600 font-bold" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="nip" :value="__('NIP / NIK / NIM (ID Login Unik)')" />
            <x-text-input id="nip" name="nip" type="text" class="mt-1 block w-full opacity-70 cursor-not-allowed bg-slate-100 font-mono font-bold" :value="old('nip', $user->nip)" readonly />
            <p class="text-[11px] text-slate-500 mt-1 font-medium">ID Login unik akun Anda (hubungi Super Admin jika terdapat kesalahan ID).</p>
        </div>

        <div>
            <x-input-label for="no_wa" :value="__('Nomor WhatsApp')" />
            <x-text-input id="no_wa" name="no_wa" type="text" class="mt-1 block w-full" :value="old('no_wa', $user->no_wa)" placeholder="Cth: 081234567890" />
            <x-input-error class="mt-2 text-rose-600 font-bold" :messages="$errors->get('no_wa')" />
        </div>

        <div class="flex items-center gap-4">
            <button type="submit" class="btn-pop-gold inline-flex items-center gap-2 font-bold py-2.5 px-6 rounded-xl text-xs cursor-pointer">
                <span>💾</span> {{ __('Simpan Perubahan') }}
            </button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-xs font-bold text-emerald-700 flex items-center gap-1"
                ><span>✅</span> {{ __('Tersimpan.') }}</p>
            @endif
        </div>
    </form>
</section>
