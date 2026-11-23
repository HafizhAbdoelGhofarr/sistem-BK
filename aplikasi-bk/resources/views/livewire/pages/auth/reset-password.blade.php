<?php

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    #[Locked]
    public string $token = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Mount the component.
     */
    public function mount(string $token): void
    {
        $this->token = $token;

        $this->email = request()->string('email');
    }

    /**
     * Reset the password for the given user.
     */
    public function resetPassword(): void
    {
        $this->validate([
            'token' => ['required'],
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $status = Password::reset(
            $this->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) {
                $user->forceFill([
                    'password' => Hash::make($this->password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status != Password::PASSWORD_RESET) {
            $this->addError('email', __($status));

            return;
        }

        Session::flash('status', __($status));

        $this->redirectRoute('login', navigate: true);
    }
}; ?>

<div>
    <!-- Judul & Keterangan Form -->
    <div class="mb-6 text-center">
        <h2 class="text-2xl font-bold text-gray-900 tracking-tight">Atur Ulang Kata Sandi</h2>
        <p class="text-xs text-gray-500 mt-1">Masukkan kata sandi baru yang aman untuk akun Anda</p>
    </div>

    <form wire:submit="resetPassword" class="space-y-4">
        <!-- Alamat Email -->
        <div>
            <x-input-label for="email" :value="__('Alamat Email')" class="font-medium text-gray-700 text-xs uppercase tracking-wider" />
            <div class="relative mt-1">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                    </svg>
                </div>
                <x-text-input wire:model="email" id="email" class="block w-full pl-10 py-2.5" type="email" name="email" required autofocus autocomplete="username" />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
        </div>

        <!-- Kata Sandi Baru -->
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

        <!-- Konfirmasi Kata Sandi Baru -->
        <div>
            <x-input-label for="password_confirmation" :value="__('Konfirmasi Kata Sandi Baru')" class="font-medium text-gray-700 text-xs uppercase tracking-wider" />
            <div class="relative mt-1">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <x-text-input wire:model="password_confirmation" id="password_confirmation" class="block w-full pl-10 py-2.5" type="password" name="password_confirmation" placeholder="Ulangi kata sandi baru" required autocomplete="new-password" />
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1.5" />
        </div>

        <div class="pt-2">
            <x-primary-button class="w-full py-3 shadow-md shadow-purple-500/20 text-sm">
                {{ __('Perbarui Kata Sandi') }}
            </x-primary-button>
        </div>
    </form>
</div>
