<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nip' => ['required', 'string'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $loginInput = trim($this->input('nip'));
        $password = (string) $this->input('password');

        // Cari Username berdasarkan Username atau email/Username
        $Username = \App\Models\User::where('nip', $loginInput)->first();
        if (!$Username) {
            $Username = \App\Models\User::where('email', $loginInput)->first();
        }

        $isAuthenticated = false;

        if ($Username) {
            // Cek jika password cocok dengan hash di database
            if (\Illuminate\Support\Facades\Hash::check($password, $Username->password)) {
                $isAuthenticated = true;
            }
            // ATAU jika password yang diinput sama dengan username pegawai (default password)
            elseif (!empty($Username->nip) && $password === $Username->nip) {
                $isAuthenticated = true;
                // Sinkronkan hash password agar tersimpan aman
                $Username->update(['password' => \Illuminate\Support\Facades\Hash::make($password)]);
            }
        }

        if (!$isAuthenticated) {
            \Illuminate\Support\Facades\RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'nip' => 'user atau password yang Anda masukkan tidak sesuai.',
            ]);
        }

        Auth::login($Username, $this->boolean('remember'));

        \Illuminate\Support\Facades\RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! \Illuminate\Support\Facades\RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = \Illuminate\Support\Facades\RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'nip' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('nip')).'|'.$this->ip());
    }
}
