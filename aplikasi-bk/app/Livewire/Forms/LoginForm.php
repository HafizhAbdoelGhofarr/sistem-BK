<?php

namespace App\Livewire\Forms;

use App\Models\User;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Validate;
use Livewire\Form;

class LoginForm extends Form
{
    #[Validate('required|string|email')]
    public string $email = '';

    #[Validate('required|string')]
    public string $password = '';

    #[Validate('boolean')]
    public bool $remember = false;

    /**
     * Deteksi apakah preferensi bahasa pengguna/browser adalah Bahasa Inggris.
     */
    public function isEnglish(): bool
    {
        $preferred = request()->getPreferredLanguage(['id', 'en']);
        if ($preferred && str_starts_with(strtolower($preferred), 'en')) {
            return true;
        }

        $acceptLang = request()->server('HTTP_ACCEPT_LANGUAGE', '');
        return str_starts_with(strtolower($acceptLang), 'en');
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $isEn = $this->isEnglish();

        // 1. Validasi keberadaan akun berdasarkan email
        $user = User::where('email', $this->email)->first();

        if (! $user) {
            RateLimiter::hit($this->throttleKey());

            $pesan = $isEn ? 'Incorrect email, please try again.' : 'Email salah, coba lagi.';
            throw ValidationException::withMessages([
                'form.email' => $pesan,
            ]);
        }

        // 2. Validasi kesesuaian kata sandi
        if (! Hash::check($this->password, $user->password)) {
            RateLimiter::hit($this->throttleKey());

            $pesan = $isEn ? 'Incorrect password, please try again.' : 'Password salah, coba lagi.';
            throw ValidationException::withMessages([
                'form.password' => $pesan,
            ]);
        }

        // 3. Validasi status keaktifan akun
        if (! $user->is_active) {
            RateLimiter::hit($this->throttleKey());

            $pesan = $isEn
                ? 'Your account is deactivated. Please contact the administrator.'
                : 'Akun Anda dinonaktifkan. Silakan hubungi administrator sekolah.';
            throw ValidationException::withMessages([
                'form.email' => $pesan,
            ]);
        }

        Auth::login($user, $this->remember);

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the authentication request is not rate limited.
     */
    protected function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout(request()));

        $seconds = RateLimiter::availableIn($this->throttleKey());
        $isEn = $this->isEnglish();

        $pesan = $isEn
            ? "Too many login attempts. Please try again in {$seconds} seconds."
            : "Terlalu banyak percobaan masuk. Silakan coba lagi dalam {$seconds} detik.";

        throw ValidationException::withMessages([
            'form.email' => $pesan,
        ]);
    }

    /**
     * Get the authentication rate limiting throttle key.
     */
    protected function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->email).'|'.request()->ip());
    }
}
