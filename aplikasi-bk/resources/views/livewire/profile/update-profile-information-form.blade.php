<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;

new class extends Component
{
    public string $name = '';
    public string $email = '';

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $this->name = Auth::user()->name;
        $this->email = Auth::user()->email;
    }

    /**
     * Update the profile information for the currently authenticated user.
     */
    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
        ]);

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        $this->dispatch('profile-updated', name: $user->name);
    }

    /**
     * Send an email verification notification to the current user.
     */
    public function sendVerification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));

            return;
        }

        $user->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }
}; ?>

<section>
    <header class="text-center pb-4 border-b border-gray-100">
        <h2 class="text-base font-extrabold text-gray-900">
            {{ __('Ganti Alamat Surel (Email)') }}
        </h2>

        <p class="mt-1 text-xs text-gray-500">
            {{ __('Perbarui alamat email resmi untuk login dan penerimaan notifikasi sistem') }}
        </p>
    </header>

    <form wire:submit="updateProfileInformation" class="mt-6 space-y-5">
        <div>
            <x-input-label for="profile_name" :value="__('Nama Tampilan Akun')" class="font-bold text-xs text-gray-700 mb-1" />
            <x-text-input wire:model="name" id="profile_name" name="name" type="text" class="block w-full py-2.5 text-xs font-semibold" required autocomplete="name" />
            <x-input-error class="mt-1" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="profile_email" :value="__('Alamat Surel (Email) Baru')" class="font-bold text-xs text-gray-700 mb-1" />
            <x-text-input wire:model="email" id="profile_email" name="email" type="email" class="block w-full py-2.5 text-xs font-semibold font-mono" required autocomplete="username" />
            <x-input-error class="mt-1" :messages="$errors->get('email')" />

            @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! auth()->user()->hasVerifiedEmail())
                <div>
                    <p class="text-xs mt-2 text-gray-800">
                        {{ __('Alamat surel Anda belum diverifikasi.') }}

                        <button wire:click.prevent="sendVerification" class="underline text-xs text-purple-700 hover:text-purple-900 rounded-md focus:outline-none">
                            {{ __('Klik di sini untuk mengirim ulang surel verifikasi.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-xs text-emerald-600">
                            {{ __('Tautan verifikasi baru telah dikirimkan ke alamat surel Anda.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="pt-2 flex flex-col sm:flex-row items-center justify-center gap-3">
            <x-primary-button class="py-2.5 px-6 text-xs font-bold shadow-md shadow-purple-600/20">
                {{ __('Simpan Perubahan') }}
            </x-primary-button>

            <x-action-message class="text-xs text-emerald-600 font-bold" on="profile-updated">
                {{ __('✓ Berhasil disimpan.') }}
            </x-action-message>
        </div>
    </form>
</section>
