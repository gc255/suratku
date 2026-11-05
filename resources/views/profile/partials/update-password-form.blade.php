<section>
    <header>
        <h2 class="text-lg font-cartoon font-bold text-[#003366]">
            {{ __('Perbarui Kata Sandi (Password)') }}
        </h2>

        <p class="mt-1 text-xs text-slate-600 font-medium">
            {{ __('Pastikan akun Anda menggunakan kata sandi yang aman dan sulit ditebak.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        <div>
            <x-input-label for="update_password_current_password" :value="__('Kata Sandi Saat Ini')" />
            <x-text-input id="update_password_current_password" name="current_password" type="password" class="mt-1 block w-full" autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2 text-rose-600 font-bold" />
        </div>

        <div>
            <x-input-label for="update_password_password" :value="__('Kata Sandi Baru')" />
            <x-text-input id="update_password_password" name="password" type="password" class="mt-1 block w-full" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2 text-rose-600 font-bold" />
        </div>

        <div>
            <x-input-label for="update_password_password_confirmation" :value="__('Konfirmasi Kata Sandi Baru')" />
            <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2 text-rose-600 font-bold" />
        </div>

        <div class="flex items-center gap-4">
            <button type="submit" class="btn-pop-navy inline-flex items-center gap-2 font-bold py-2.5 px-6 rounded-xl text-xs cursor-pointer">
                <span>🔑</span> {{ __('Perbarui Password') }}
            </button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-xs font-bold text-emerald-700 flex items-center gap-1"
                ><span>✅</span> {{ __('Kata sandi berhasil diperbarui.') }}</p>
            @endif
        </div>
    </form>
</section>
