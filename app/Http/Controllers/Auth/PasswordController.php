<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PasswordController extends Controller
{
    /**
     * Update the user's password.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $user = $request->user();

        // Validasi agar password baru tidak sama dengan username / password default
        if (!empty($user->nip) && $validated['password'] === $user->nip) {
            return back()->withErrors(['password' => 'Kata sandi baru tidak boleh sama dengan username/password default Anda.'], 'updatePassword');
        }

        $user->update([
            'password' => Hash::make($validated['password']),
            'password_changed_at' => now(),
        ]);

        return redirect()->route('dashboard')->with('success', 'Kata sandi berhasil diperbarui! Anda dapat melanjutkan aktivitas kerja.');
    }
}
