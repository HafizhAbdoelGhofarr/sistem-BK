<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-bold text-2xl text-gray-900 leading-tight tracking-tight">
                {{ __('Pengaturan Akun & Profil') }}
            </h2>
            <p class="text-xs text-gray-500 mt-1">
                Kelola informasi akun pengguna dan kata sandi keamanan Anda
            </p>
        </div>
    </x-slot>

    <div class="space-y-6 max-w-4xl">
        <!-- Informasi Profil -->
        <div class="p-6 sm:p-8 bg-white border border-gray-100 shadow-sm rounded-2xl">
            <div class="max-w-xl">
                <livewire:profile.update-profile-information-form />
            </div>
        </div>

        <!-- Pembaruan Kata Sandi -->
        <div class="p-6 sm:p-8 bg-white border border-gray-100 shadow-sm rounded-2xl">
            <div class="max-w-xl">
                <livewire:profile.update-password-form />
            </div>
        </div>

        <!-- Hapus Akun (Jika diizinkan) -->
        <div class="p-6 sm:p-8 bg-white border border-gray-100 shadow-sm rounded-2xl">
            <div class="max-w-xl">
                <livewire:profile.delete-user-form />
            </div>
        </div>
    </div>
</x-app-layout>
