<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div>
    @php
        $preferredLang = request()->getPreferredLanguage(['id', 'en']) ?? 'id';
        $isEn = str_starts_with(strtolower($preferredLang), 'en') || str_starts_with(strtolower(request()->server('HTTP_ACCEPT_LANGUAGE', '')), 'en');
    @endphp

    <!-- Judul & Keterangan Form -->
    <div class="mb-6 text-center">
        <h2 class="text-2xl font-bold text-gray-900 tracking-tight">
            {{ $isEn ? 'Welcome Back!' : 'Selamat Datang!' }}
        </h2>
        <p class="text-sm text-gray-500 mt-1">
            {{ $isEn ? 'Sign in to the Counseling & Student Discipline Portal' : 'Masuk ke portal Bimbingan Konseling & Tata Tertib Siswa' }}
        </p>
    </div>

    <!-- Status Sesi / Notifikasi -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <!-- Warning Alert Box Jika Email / Password Salah -->
    @if ($errors->has('form.email') || $errors->has('form.password'))
        <div class="mb-5 p-4 rounded-2xl bg-rose-50 border border-rose-200/90 text-rose-800 text-xs font-semibold flex items-start gap-3 shadow-xs animate-shake">
            <div class="w-6 h-6 rounded-lg bg-rose-600 text-white flex items-center justify-center font-bold text-xs flex-shrink-0 mt-0.5 shadow-2xs">
                !
            </div>
            <div class="flex-1">
                <span class="block font-bold text-rose-900 mb-0.5">
                    {{ $isEn ? 'Authentication Notice' : 'Peringatan Masuk' }}
                </span>
                <span class="text-rose-700">
                    {{ $errors->first('form.email') ?: $errors->first('form.password') }}
                </span>
            </div>
        </div>
    @endif

    <form wire:submit="login" class="space-y-4">
        <!-- Alamat Email -->
        <div>
            <x-input-label for="email" :value="$isEn ? 'Email Address' : 'Alamat Email'" class="font-medium text-gray-700 text-xs uppercase tracking-wider" />
            <div class="relative mt-1">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                    </svg>
                </div>
                <x-text-input wire:model="form.email" id="email" class="block w-full pl-10 py-2.5" type="email" name="email" placeholder="nama@sekolah.sch.id" required autofocus autocomplete="username" />
            </div>
            <x-input-error :messages="$errors->get('form.email')" class="mt-1.5" />
        </div>

        <!-- Password -->
        <div>
            <div class="flex items-center justify-between">
                <x-input-label for="password" :value="$isEn ? 'Password' : 'Kata Sandi'" class="font-medium text-gray-700 text-xs uppercase tracking-wider" />
                @if (Route::has('password.request'))
                    <a class="text-xs font-semibold text-purple-700 hover:text-purple-900 hover:underline transition" href="{{ route('password.request') }}" wire:navigate>
                        {{ $isEn ? 'Forgot Password?' : 'Lupa Kata Sandi?' }}
                    </a>
                @endif
            </div>

            <div class="relative mt-1">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
                <x-text-input wire:model="form.password" id="password" class="block w-full pl-10 py-2.5" type="password" name="password" placeholder="••••••••" required autocomplete="current-password" />
            </div>
            <x-input-error :messages="$errors->get('form.password')" class="mt-1.5" />
        </div>

        <!-- Ingat Saya -->
        <div class="flex items-center justify-between pt-1">
            <label for="remember" class="inline-flex items-center cursor-pointer">
                <input wire:model="form.remember" id="remember" type="checkbox" class="w-4 h-4 rounded border-gray-300 text-purple-700 shadow-sm focus:ring-purple-500" name="remember">
                <span class="ms-2 text-xs text-gray-600 font-medium">{{ $isEn ? 'Remember me on this device' : 'Ingat saya di perangkat ini' }}</span>
            </label>
        </div>

        <!-- Tombol Masuk -->
        <div class="pt-2">
            <x-primary-button class="w-full py-3 shadow-md shadow-purple-500/20 text-sm">
                {{ $isEn ? 'Sign In to Portal' : 'Masuk ke Portal' }}
            </x-primary-button>
        </div>
    </form>

    <!-- Pembatas & Opsi Registrasi Siswa -->
    <div class="mt-6 pt-6 border-t border-gray-100 text-center">
        <p class="text-xs text-gray-500">
            {{ $isEn ? "Don't have a student account yet?" : 'Belum memiliki akun siswa?' }}
            <a href="{{ route('register') }}" wire:navigate class="font-semibold text-purple-700 hover:text-purple-900 hover:underline ms-1">
                {{ $isEn ? 'Register New Student' : 'Daftar Akun Siswa Baru' }}
            </a>
        </p>
    </div>
</div>
