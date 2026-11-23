<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component
{
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

<aside class="h-full flex flex-col justify-between bg-white/95 backdrop-blur-xl border-r border-purple-100/90 shadow-sm select-none">
    <div class="flex-1 flex flex-col overflow-y-auto">
        <!-- 1. Brand Logo -->
        <div class="p-5 border-b border-purple-100/70">
            <a href="{{ route('dashboard') }}" wire:navigate class="flex items-center gap-3 transition transform hover:scale-[1.01]">
                <x-application-logo />
            </a>
        </div>

        <!-- 2. Ringkasan Profil Pengguna di Sidebar -->
        <div class="p-4 mx-3 my-3 rounded-2xl bg-purple-50/60 border border-purple-100/80 flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-purple-700 to-purple-500 text-white flex items-center justify-center font-bold text-sm uppercase shadow-xs flex-shrink-0 overflow-hidden">
                @if (auth()->user()->avatar && \Illuminate\Support\Facades\Storage::disk('public')->exists(auth()->user()->avatar))
                    <img src="{{ asset('storage/' . auth()->user()->avatar) }}" class="w-full h-full object-cover" alt="Avatar">
                @else
                    {{ substr(auth()->user()->name ?? 'U', 0, 1) }}
                @endif
            </div>
            <div class="min-w-0 flex-1">
                <span class="block font-bold text-xs text-gray-900 truncate">
                    {{ auth()->user()->name ?? 'Pengguna' }}
                </span>
                <span class="inline-flex items-center gap-1 text-[10px] font-bold text-purple-700 uppercase tracking-wider">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    {{ auth()->user()->roles->first()->name ?? 'User' }}
                </span>
            </div>
        </div>

        <!-- 3. Daftar Navigasi Menu Vertikal Berdasarkan Role -->
        <nav class="px-3 py-2 space-y-1 text-xs font-semibold">
            <div class="px-3 pt-2 pb-1.5 text-[10px] font-extrabold uppercase tracking-wider text-gray-400">
                Menu Utama
            </div>

            <!-- Dashboard -->
            <a href="{{ route('dashboard') }}" wire:navigate
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition duration-150 {{ request()->routeIs('dashboard') ? 'bg-gradient-to-r from-purple-700 to-purple-600 text-white font-bold shadow-md shadow-purple-600/20' : 'text-gray-600 hover:text-purple-700 hover:bg-purple-50/70' }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span>Dashboard</span>
            </a>

            <!-- Pelanggaran Siswa -->
            <a href="{{ route('pelanggaran.index') }}" wire:navigate
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition duration-150 {{ request()->routeIs('pelanggaran.index') ? 'bg-gradient-to-r from-purple-700 to-purple-600 text-white font-bold shadow-md shadow-purple-600/20' : 'text-gray-600 hover:text-purple-700 hover:bg-purple-50/70' }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                <span>Pelanggaran Siswa</span>
            </a>

            @if(auth()->user()->hasRole('guru_bk'))
                <!-- Pembinaan BK (Khusus Guru BK) -->
                <a href="{{ route('pembinaan.index') }}" wire:navigate
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition duration-150 {{ request()->routeIs('pembinaan.index') ? 'bg-gradient-to-r from-purple-700 to-purple-600 text-white font-bold shadow-md shadow-purple-600/20' : 'text-gray-600 hover:text-purple-700 hover:bg-purple-50/70' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <span>Pembinaan BK</span>
                </a>

                <div class="px-3 pt-4 pb-1.5 text-[10px] font-extrabold uppercase tracking-wider text-gray-400">
                    Tindakan & Konseling
                </div>

                <!-- Catat Kasus Baru (Guru BK) -->
                <a href="{{ route('pelanggaran.create') }}" wire:navigate
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition duration-150 {{ request()->routeIs('pelanggaran.create') ? 'bg-gradient-to-r from-purple-700 to-purple-600 text-white font-bold shadow-md shadow-purple-600/20' : 'text-purple-700 bg-purple-50/80 hover:bg-purple-100 font-bold' }}">
                    <svg class="w-4 h-4 flex-shrink-0 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>Catat Pelanggaran</span>
                </a>

                <!-- Buka Sesi Konseling Baru (Guru BK) -->
                <a href="{{ route('pembinaan.create') }}" wire:navigate
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition duration-150 {{ request()->routeIs('pembinaan.create') ? 'bg-gradient-to-r from-purple-700 to-purple-600 text-white font-bold shadow-md shadow-purple-600/20' : 'text-gray-600 hover:text-purple-700 hover:bg-purple-50/70' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span>Sesi Konseling Baru</span>
                </a>
            @endif

            @if(auth()->user()->hasRole('admin'))
                <div class="px-3 pt-4 pb-1.5 text-[10px] font-extrabold uppercase tracking-wider text-gray-400">
                    Administrasi & Sistem
                </div>

                <!-- Data Siswa (Khusus Admin) -->
                <a href="{{ route('master.siswa') }}" wire:navigate
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition duration-150 {{ request()->routeIs('master.siswa') ? 'bg-gradient-to-r from-purple-700 to-purple-600 text-white font-bold shadow-md shadow-purple-600/20' : 'text-gray-600 hover:text-purple-700 hover:bg-purple-50/70' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span>Data Siswa</span>
                </a>

                <!-- Log WhatsApp Sidobe (Khusus Admin) -->
                <a href="{{ route('notifikasi-wa.index') }}" wire:navigate
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition duration-150 {{ request()->routeIs('notifikasi-wa.index') ? 'bg-gradient-to-r from-purple-700 to-purple-600 text-white font-bold shadow-md shadow-purple-600/20' : 'text-gray-600 hover:text-purple-700 hover:bg-purple-50/70' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                    <span>Log WhatsApp</span>
                </a>

                <!-- Laporan Rekapitulasi (Khusus Admin) -->
                <a href="{{ route('laporan.index') }}" wire:navigate
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition duration-150 {{ request()->routeIs('laporan.index') ? 'bg-gradient-to-r from-purple-700 to-purple-600 text-white font-bold shadow-md shadow-purple-600/20' : 'text-gray-600 hover:text-purple-700 hover:bg-purple-50/70' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span>Laporan Rekapitulasi</span>
                </a>
            @endif

            <div class="px-3 pt-4 pb-1.5 text-[10px] font-extrabold uppercase tracking-wider text-gray-400">
                Pedoman
            </div>

            <!-- Tata Tertib (Bisa Diakses Admin & Guru BK) -->
            <a href="{{ route('master.tata-tertib') }}" wire:navigate
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition duration-150 {{ request()->routeIs('master.tata-tertib') ? 'bg-gradient-to-r from-purple-700 to-purple-600 text-white font-bold shadow-md shadow-purple-600/20' : 'text-gray-600 hover:text-purple-700 hover:bg-purple-50/70' }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
                <span>Tata Tertib & Poin</span>
            </a>
        </nav>
    </div>

    <!-- 4. Bagian Bawah: Profil & Keluar -->
    <div class="p-3 border-t border-purple-100/70 space-y-1">
        <!-- Pengaturan Akun -->
        <a href="{{ route('profile') }}" wire:navigate
           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold text-gray-600 hover:text-purple-700 hover:bg-purple-50/70 transition">
            <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
            <span>Pengaturan Akun</span>
        </a>

        <!-- Logout -->
        <button wire:click="logout" class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold text-rose-600 hover:bg-rose-50 transition">
            <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
            </svg>
            <span>Keluar Akun</span>
        </button>

        <div class="pt-2 text-center text-[10px] text-gray-400">
            Sistem Informasi BK &bull; Panel Staff
        </div>
    </div>
</aside>