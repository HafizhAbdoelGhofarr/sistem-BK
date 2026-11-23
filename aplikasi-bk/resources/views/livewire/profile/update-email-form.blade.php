<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Livewire\Volt\Component;

new class extends Component
{
    public string $current_email = '';
    public string $email = '';
    public string $current_password = '';

    public function mount(): void
    {
        $user = Auth::user();
        $this->current_email = $user->email;
        $this->email = $user->email;
    }

    public function updateEmail(): void
    {
        $user = Auth::user();

        // Validasi kata sandi saat ini untuk keamanan pergantian surel
        if (! Hash::check($this->current_password, $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => 'Kata sandi saat ini tidak cocok. Silakan coba lagi.',
            ]);
        }

        $validated = $this->validate([
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($user->id),
            ],
            'current_password' => ['required'],
        ]);

        $user->email = $validated['email'];
        $user->email_verified_at = null; // Butuh verifikasi jika ada
        $user->save();

        $this->current_email = $user->email;
        $this->current_password = '';

        $this->dispatch('profile-updated', name: $user->name);
        session()->flash('success_email', '✓ Alamat surel (email) akun Anda berhasil diperbarui!');
    }
}; ?>

<section>
    <header class="text-center pb-4 border-b border-gray-100">
        <h2 class="text-base font-extrabold text-gray-900">
            {{ __('Ganti Alamat Surel (Email)') }}
        </h2>
        <p class="mt-1 text-xs text-gray-500">
            {{ __('Perbarui alamat email resmi untuk login dan penerimaan notifikasi akun Anda') }}
        </p>
    </header>

    @if (session('success_email'))
        <div class="mt-4 p-3.5 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold text-center">
            {{ session('success_email') }}
        </div>
    @endif

    <form wire:submit="updateEmail" class="mt-6 space-y-5">
        <!-- Informasi Email Saat Ini -->
        <div class="p-3.5 rounded-2xl bg-purple-50/60 border border-purple-100 text-center">
            <span class="text-[11px] font-semibold text-gray-500 uppercase tracking-wider block">Email Aktif Saat Ini</span>
            <span class="text-xs font-extrabold text-purple-900 font-mono">{{ $current_email }}</span>
        </div>

        <!-- Alamat Email Baru -->
        <div>
            <x-input-label for="new_email" :value="__('Alamat Surel (Email) Baru')" class="font-bold text-xs text-gray-700 mb-1" />
            <x-text-input wire:model="email" id="new_email" type="email" placeholder="nama@sekolah.sch.id" class="block w-full py-2.5 text-xs font-semibold" required autocomplete="username" />
            <x-input-error class="mt-1" :messages="$errors->get('email')" />
        </div>

        <!-- Konfirmasi Kata Sandi untuk Otorisasi -->
        <div>
            <x-input-label for="confirm_password_for_email" :value="__('Konfirmasi Kata Sandi Saat Ini')" class="font-bold text-xs text-gray-700 mb-1" />
            <x-text-input wire:model="current_password" id="confirm_password_for_email" type="password" placeholder="Masukkan kata sandi akun Anda" class="block w-full py-2.5 text-xs font-semibold" required />
            <p class="text-[11px] text-gray-400 mt-1">
                Wajib memasukkan kata sandi saat ini untuk memverifikasi kepemilikan akun sebelum mengubah email.
            </p>
            <x-input-error class="mt-1" :messages="$errors->get('current_password')" />
        </div>

        <!-- Tombol Simpan Email Terpusat -->
        <div class="pt-3 text-center">
            <x-primary-button class="py-2.5 px-8 text-xs font-bold shadow-md shadow-purple-600/20">
                {{ __('Simpan Perubahan Email') }}
            </x-primary-button>
        </div>
    </form>
</section>
