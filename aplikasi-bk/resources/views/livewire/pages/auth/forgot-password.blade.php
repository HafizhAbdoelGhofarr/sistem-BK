<?php

use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $email = '';

    /**
     * Send a password reset link to the provided email address.
     */
    public function sendPasswordResetLink(): void
    {
        $this->validate([
            'email' => ['required', 'string', 'email'],
        ]);

        $status = Password::sendResetLink(
            $this->only('email')
        );

        if ($status != Password::RESET_LINK_SENT) {
            $this->addError('email', __($status));

            return;
        }

        $this->reset('email');

        session()->flash('status', __($status));
    }
}; ?>

<div>
    <!-- Judul & Keterangan Form -->
    <div class="mb-6 text-center">
        <div class="w-12 h-12 bg-purple-100 text-purple-700 rounded-2xl flex items-center justify-center mx-auto mb-3">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
            </svg>
        </div>
        <h2 class="text-2xl font-bold text-gray-900 tracking-tight">Lupa Kata Sandi?</h2>
        <p class="text-xs text-gray-500 mt-1 max-w-xs mx-auto">
            Masukkan alamat email akun Anda. Kami akan mengirimkan tautan untuk mengatur ulang kata sandi Anda.
        </p>
    </div>

    <!-- Status Sesi / Notifikasi -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form wire:submit="sendPasswordResetLink" class="space-y-4">
        <!-- Alamat Email -->
        <div>
            <x-input-label for="email" :value="__('Alamat Email Terdaftar')" class="font-medium text-gray-700 text-xs uppercase tracking-wider" />
            <div class="relative mt-1">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                    </svg>
                </div>
                <x-text-input wire:model="email" id="email" class="block w-full pl-10 py-2.5" type="email" name="email" placeholder="nama@sekolah.sch.id" required autofocus />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
        </div>

        <div class="pt-2">
            <x-primary-button class="w-full py-3 shadow-md shadow-purple-500/20 text-sm">
                {{ __('Kirim Tautan Reset') }}
            </x-primary-button>
        </div>
    </form>

    <!-- Kembali ke Login -->
    <div class="mt-6 pt-6 border-t border-gray-100 text-center">
        <a href="{{ route('login') }}" wire:navigate class="inline-flex items-center text-xs font-semibold text-purple-700 hover:text-purple-900 hover:underline">
            <svg class="w-3.5 h-3.5 me-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali ke Halaman Masuk
        </a>
    </div>
</div>
