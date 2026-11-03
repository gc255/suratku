<section>
    <header>
        <h2 class="text-lg font-extrabold text-white">
            {{ __('Informasi Profil') }}
        </h2>

        <p class="mt-1 text-xs text-slate-400">
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
            <x-input-error class="mt-2 text-rose-400" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="nip" :value="__('NIP / NIK / NIM (ID Login Unik)')" />
            <x-text-input id="nip" name="nip" type="text" class="mt-1 block w-full opacity-60 cursor-not-allowed bg-slate-950/60" :value="old('nip', $user->nip)" readonly />
            <p class="text-[11px] text-slate-400 mt-1">ID Login unik akun Anda (hubungi Super Admin jika terdapat kesalahan ID).</p>
        </div>

        <div>
            <x-input-label for="no_wa" :value="__('Nomor WhatsApp')" />
            <x-text-input id="no_wa" name="no_wa" type="text" class="mt-1 block w-full" :value="old('no_wa', $user->no_wa)" placeholder="Cth: 081234567890" />
            <x-input-error class="mt-2 text-rose-400" :messages="$errors->get('no_wa')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Simpan Perubahan') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-xs font-bold text-emerald-400 flex items-center gap-1"
                ><span>✅</span> {{ __('Tersimpan.') }}</p>
            @endif
        </div>
    </form>
</section>
