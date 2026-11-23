<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component
{
    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

<nav x-data="{ open: false }" class="bg-white/90 backdrop-blur-md border-b border-purple-100 shadow-xs sticky top-0 z-40 transition-all">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center gap-8">
                <!-- Logo Aplikasi -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" wire:navigate class="transition transform hover:scale-[1.02]">
                        <x-application-logo />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden md:flex items-center space-x-1">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" wire:navigate class="px-3 py-2 rounded-xl text-xs font-bold transition">
                        <svg class="w-4 h-4 me-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    @role('admin')
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-purple-100 text-purple-700 uppercase tracking-wider ms-2">
                            Mode Admin
                        </span>
                    @endrole

                    @role('guru_bk')
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-800 uppercase tracking-wider ms-2">
                            Mode Konselor BK
                        </span>
                    @endrole

                    @role('siswa')
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800 uppercase tracking-wider ms-2">
                            Portal Siswa
                        </span>
                    @endrole
                </div>
            </div>

            <!-- Settings & Profile Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6 gap-3">
                <!-- Indikator Gateway WA -->
                <div class="hidden lg:flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-emerald-50 border border-emerald-200/80 text-[11px] font-semibold text-emerald-700">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    Sidobe WA Aktif
                </div>

                <!-- Dropdown User -->
                <x-dropdown align="right" width="56">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center gap-2.5 px-3 py-1.5 border border-purple-100 text-sm leading-4 font-medium rounded-2xl text-gray-700 bg-purple-50/60 hover:bg-purple-100/70 hover:text-purple-900 focus:outline-none transition ease-in-out duration-150 shadow-xs">
                            <div class="w-8 h-8 rounded-xl bg-gradient-to-tr from-purple-700 to-purple-500 text-white flex items-center justify-center font-bold text-xs uppercase shadow-sm">
                                {{ substr(auth()->user()->name ?? 'U', 0, 1) }}
                            </div>
                            <div class="text-left">
                                <span class="block font-bold text-xs leading-tight text-gray-900 max-w-[130px] truncate">
                                    {{ auth()->user()->name ?? 'Pengguna' }}
                                </span>
                                <span class="text-[10px] text-purple-700 font-semibold uppercase tracking-wider">
                                    {{ auth()->user()->roles->first()->name ?? 'User' }}
                                </span>
                            </div>

                            <svg class="fill-current h-4 w-4 text-gray-400 ms-0.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <!-- Header info user di dropdown -->
                        <div class="px-4 py-3 border-b border-gray-100 bg-purple-50/40">
                            <p class="text-xs font-semibold text-gray-800 leading-none">{{ auth()->user()->name }}</p>
                            <p class="text-[11px] text-gray-500 mt-1 truncate">{{ auth()->user()->email }}</p>
                        </div>

                        <x-dropdown-link :href="route('profile')" wire:navigate class="hover:bg-purple-50 hover:text-purple-800 text-xs py-2.5 flex items-center gap-2">
                            <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            {{ __('Pengaturan Akun') }}
                        </x-dropdown-link>

                        <!-- Authentication Logout -->
                        <div class="border-t border-gray-100">
                            <button wire:click="logout" class="w-full text-start">
                                <x-dropdown-link class="text-rose-600 hover:bg-rose-50 hover:text-rose-700 text-xs py-2.5 flex items-center gap-2">
                                    <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                    {{ __('Keluar (Logout)') }}
                                </x-dropdown-link>
                            </button>
                        </div>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger Button (Mobile) -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-xl text-gray-500 hover:text-purple-700 hover:bg-purple-50 focus:outline-none transition duration-150">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu (Mobile) -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden border-t border-purple-100 bg-white/95 backdrop-blur-md">
        <div class="pt-2 pb-3 space-y-1 px-4">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" wire:navigate class="rounded-xl">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-3 border-t border-gray-100 bg-purple-50/50 px-4">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-purple-700 text-white flex items-center justify-center font-bold text-sm">
                    {{ substr(auth()->user()->name ?? 'U', 0, 1) }}
                </div>
                <div>
                    <div class="font-bold text-sm text-gray-800">{{ auth()->user()->name ?? 'User' }}</div>
                    <div class="font-medium text-xs text-purple-700 uppercase tracking-wide">{{ auth()->user()->roles->first()->name ?? 'User' }}</div>
                </div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile')" wire:navigate class="rounded-xl">
                    {{ __('Pengaturan Akun') }}
                </x-responsive-nav-link>

                <button wire:click="logout" class="w-full text-start">
                    <x-responsive-nav-link class="text-rose-600 rounded-xl">
                        {{ __('Keluar (Logout)') }}
                    </x-responsive-nav-link>
                </button>
            </div>
        </div>
    </div>
</nav>
