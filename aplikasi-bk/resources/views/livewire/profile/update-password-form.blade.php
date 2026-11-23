<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Livewire\Volt\Component;

new class extends Component
{
    public string $current_password = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Update the password for the currently authenticated user.
     */
    public function updatePassword(): void
    {
        try {
            $validated = $this->validate([
                'current_password' => ['required', 'string', 'current_password'],
                'password' => ['required', 'string', Password::defaults(), 'confirmed'],
            ]);
        } catch (ValidationException $e) {
            $this->reset('current_password', 'password', 'password_confirmation');

            throw $e;
        }

        Auth::user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        $this->reset('current_password', 'password', 'password_confirmation');

        $this->dispatch('password-updated');
    }
}; ?>

<section>
    <header class="text-center pb-4 border-b border-gray-100">
        <h2 class="text-base font-extrabold text-gray-900">
            {{ __('Perbarui Kata Sandi') }}
        </h2>

        <p class="mt-1 text-xs text-gray-500">
            {{ __('Pastikan akun Anda menggunakan kata sandi yang aman dan terjaga kerahasiaannya') }}
        </p>
    </header>

    <form wire:submit="updatePassword" class="mt-6 space-y-5">
        <div>
            <x-input-label for="update_password_current_password" :value="__('Kata Sandi Saat Ini')" class="font-bold text-xs text-gray-700 mb-1" />
            <x-text-input wire:model="current_password" id="update_password_current_password" name="current_password" type="password" class="block w-full py-2.5" autocomplete="current-password" />
            <x-input-error :messages="$errors->get('current_password')" class="mt-1" />
        </div>

        <div>
            <x-input-label for="update_password_password" :value="__('Kata Sandi Baru')" class="font-bold text-xs text-gray-700 mb-1" />
            <x-text-input wire:model="password" id="update_password_password" name="password" type="password" class="block w-full py-2.5" autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        <div>
            <x-input-label for="update_password_password_confirmation" :value="__('Konfirmasi Kata Sandi Baru')" class="font-bold text-xs text-gray-700 mb-1" />
            <x-text-input wire:model="password_confirmation" id="update_password_password_confirmation" name="password_confirmation" type="password" class="block w-full py-2.5" autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
        </div>

        <div class="pt-2 flex flex-col sm:flex-row items-center justify-center gap-3">
            <x-primary-button class="py-2.5 px-6 text-xs font-bold shadow-md shadow-purple-600/20">
                {{ __('Simpan Kata Sandi') }}
            </x-primary-button>

            <x-action-message class="text-xs text-emerald-600 font-bold" on="password-updated">
                {{ __('✓ Kata sandi berhasil diperbarui.') }}
            </x-action-message>
        </div>
    </form>
</section>
