<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        event(new Registered($user = User::create($validated)));

        // Berikan role 'siswa' secara otomatis ke akun yang baru mendaftar
        $user->assignRole('siswa');

        Auth::login($user);

        $this->redirect(route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div>
    <!-- Judul & Keterangan Form -->
    <div class="mb-6 text-center">
        <h2 class="text-2xl font-bold text-gray-900 tracking-tight">Daftar Akun Siswa</h2>
        <p class="text-sm text-gray-500 mt-1">Lengkapi formulir di bawah ini untuk membuat akun portal</p>
    </div>

    <form wire:submit="register" class="space-y-4">
        <!-- Nama Lengkap -->
        <div>
            <x-input-label for="name" :value="__('Nama Lengkap Siswa')" class="font-medium text-gray-700 text-xs uppercase tracking-wider" />
            <div class="relative mt-1">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <x-text-input wire:model="name" id="name" class="block w-full pl-10 py-2.5" type="text" name="name" placeholder="Masukkan nama lengkap" required autofocus autocomplete="name" />
            </div>
            <x-input-error :messages="$errors->get('name')" class="mt-1.5" />
        </div>

        <!-- Alamat Email -->
        <div>
            <x-input-label for="email" :value="__('Alamat Email Aktif')" class="font-medium text-gray-700 text-xs uppercase tracking-wider" />
            <div class="relative mt-1">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                    </svg>
                </div>
                <x-text-input wire:model="email" id="email" class="block w-full pl-10 py-2.5" type="email" name="email" placeholder="nama@sekolah.sch.id" required autocomplete="username" />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
        </div>

        <!-- Kata Sandi -->
        <div>
            <x-input-label for="password" :value="__('Kata Sandi Baru')" class="font-medium text-gray-700 text-xs uppercase tracking-wider" />
            <div class="relative mt-1">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
                <x-text-input wire:model="password" id="password" class="block w-full pl-10 py-2.5" type="password" name="password" placeholder="Minimal 8 karakter" required autocomplete="new-password" />
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-1.5" />
        </div>

        <!-- Konfirmasi Kata Sandi -->
        <div>
            <x-input-label for="password_confirmation" :value="__('Ulangi Kata Sandi')" class="font-medium text-gray-700 text-xs uppercase tracking-wider" />
            <div class="relative mt-1">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <x-text-input wire:model="password_confirmation" id="password_confirmation" class="block w-full pl-10 py-2.5" type="password" name="password_confirmation" placeholder="Ulangi kata sandi di atas" required autocomplete="new-password" />
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1.5" />
        </div>

        <!-- Tombol Daftar -->
        <div class="pt-2">
            <x-primary-button class="w-full py-3 shadow-md shadow-purple-500/20 text-sm">
                {{ __('Daftar Sekarang') }}
            </x-primary-button>
        </div>
    </form>

    <!-- Sudah Punya Akun -->
    <div class="mt-6 pt-6 border-t border-gray-100 text-center">
        <p class="text-xs text-gray-500">
            Sudah memiliki akun terdaftar?
            <a href="{{ route('login') }}" wire:navigate class="font-semibold text-purple-700 hover:text-purple-900 hover:underline ms-1">
                Masuk di Sini
            </a>
        </p>
    </div>
</div>
