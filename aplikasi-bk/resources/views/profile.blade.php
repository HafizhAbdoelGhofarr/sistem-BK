<x-app-layout>
    <x-slot name="header">
        <div class="text-center">
            <div class="flex items-center justify-center gap-2 text-xs text-gray-500 mb-1">
                <a href="{{ route('dashboard') }}" wire:navigate class="hover:text-purple-700">Dashboard</a>
                <span>&rsaquo;</span>
                <span class="text-purple-700 font-semibold">Pengaturan Akun</span>
            </div>
            <h2 class="font-extrabold text-2xl sm:text-3xl text-gray-900 leading-tight tracking-tight">
                {{ __('Profil & Pengaturan Akun') }}
            </h2>
            <p class="text-xs text-gray-500 mt-1">
                Kelola informasi identitas pengguna dan keamanan kredensial akun Anda
            </p>
        </div>
    </x-slot>

    <!-- Kontainer Utama: Berada di Tengah (Centered) dengan Switcher Tombol -->
    <div class="max-w-2xl mx-auto space-y-7" x-data="{ activeTab: 'biodata' }">
        
        <!-- 1. Header Card Profil Utama (Avatar Bulat & Identitas Pengguna Terpusat) -->
        <div class="bg-white/95 backdrop-blur-md border border-purple-100/90 rounded-3xl p-8 shadow-sm text-center relative overflow-hidden">
            <!-- Akses Dekoratif Gradien -->
            <div class="absolute -top-12 -right-12 w-40 h-40 bg-purple-100/50 rounded-full blur-2xl pointer-events-none"></div>
            <div class="absolute -bottom-12 -left-12 w-40 h-40 bg-indigo-50/50 rounded-full blur-2xl pointer-events-none"></div>

            <div class="relative z-10 flex flex-col items-center">
                <!-- Foto profil bulat besar dengan cincin gradien hijau mint -->
                <div class="relative mb-4">
                    <div class="w-24 h-24 sm:w-28 sm:h-28 rounded-full bg-gradient-to-tr from-purple-700 via-purple-600 to-indigo-600 text-white flex items-center justify-center font-black text-3xl sm:text-4xl shadow-xl shadow-purple-600/25 border-4 border-white ring-4 ring-purple-100 overflow-hidden">
                        @if (auth()->user()->avatar && \Illuminate\Support\Facades\Storage::disk('public')->exists(auth()->user()->avatar))
                            <img src="{{ asset('storage/' . auth()->user()->avatar) }}" class="w-full h-full object-cover" alt="{{ auth()->user()->name }}">
                        @else
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        @endif
                    </div>
                    <span class="absolute bottom-1 right-1 w-6 h-6 rounded-full bg-emerald-500 border-2 border-white shadow-xs flex items-center justify-center text-white text-[10px]" title="Akun Aktif Terverifikasi">
                        ✓
                    </span>
                </div>

                <!-- Nama Lengkap & Email -->
                <h2 class="text-xl sm:text-2xl font-extrabold text-gray-900 tracking-tight">
                    {{ auth()->user()->name }}
                </h2>
                <p class="text-xs sm:text-sm text-gray-500 mt-0.5 font-medium">
                    {{ auth()->user()->email }}
                </p>

                <!-- Role Badge Terpusat -->
                <div class="mt-3.5 inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full text-xs font-bold uppercase tracking-wider
                    @if(auth()->user()->hasRole('admin')) bg-purple-100 text-purple-800 border border-purple-200
                    @elseif(auth()->user()->hasRole('guru_bk')) bg-amber-100 text-amber-800 border border-amber-200
                    @else bg-emerald-100 text-emerald-800 border border-emerald-200 @endif">
                    <span class="w-2 h-2 rounded-full @if(auth()->user()->hasRole('admin')) bg-purple-600 @elseif(auth()->user()->hasRole('guru_bk')) bg-amber-600 @else bg-emerald-600 @endif"></span>
                    {{ auth()->user()->roles->first()->name ?? 'User' }}
                </div>

                <!-- Detail Spesifik Role Terpusat -->
                @if (auth()->user()->hasRole('siswa') && auth()->user()->siswa)
                    <div class="mt-6 pt-6 border-t border-gray-100 w-full max-w-lg grid grid-cols-2 sm:grid-cols-4 gap-2.5 text-center">
                        <div class="p-3 rounded-2xl bg-purple-50/60 border border-purple-100/70">
                            <span class="text-[10px] text-gray-500 font-semibold uppercase block">NIS Siswa</span>
                            <span class="text-xs font-bold text-gray-900 font-mono">{{ auth()->user()->siswa->nis }}</span>
                        </div>
                        <div class="p-3 rounded-2xl bg-purple-50/60 border border-purple-100/70">
                            <span class="text-[10px] text-gray-500 font-semibold uppercase block">Rombel</span>
                            <span class="text-xs font-bold text-gray-900">{{ auth()->user()->siswa->kelas?->nama ?? '-' }}</span>
                        </div>
                        <div class="p-3 rounded-2xl bg-purple-50/60 border border-purple-100/70">
                            <span class="text-[10px] text-gray-500 font-semibold uppercase block">No HP Ortu</span>
                            <span class="text-xs font-bold text-gray-900 font-mono truncate">{{ auth()->user()->siswa->no_hp_ortu ?: '-' }}</span>
                        </div>
                        <div class="p-3 rounded-2xl bg-purple-50/60 border border-purple-100/70">
                            <span class="text-[10px] text-gray-500 font-semibold uppercase block">Akumulasi Poin</span>
                            <span class="text-xs font-extrabold text-rose-600">+{{ auth()->user()->siswa->total_poin }} Poin</span>
                        </div>
                    </div>
                @elseif (auth()->user()->hasRole('guru_bk') && auth()->user()->guru)
                    <div class="mt-6 pt-6 border-t border-gray-100 w-full max-w-md grid grid-cols-3 gap-3 text-center">
                        <div class="p-3 rounded-2xl bg-amber-50/60 border border-amber-100/70">
                            <span class="text-[10px] text-gray-500 font-semibold uppercase block">NIP Guru</span>
                            <span class="text-xs font-bold text-gray-900 font-mono">{{ auth()->user()->guru->nip ?? '-' }}</span>
                        </div>
                        <div class="p-3 rounded-2xl bg-amber-50/60 border border-amber-100/70">
                            <span class="text-[10px] text-gray-500 font-semibold uppercase block">Kontak HP</span>
                            <span class="text-xs font-bold text-gray-900 font-mono">{{ auth()->user()->guru->no_hp ?: (auth()->user()->no_hp ?: '-') }}</span>
                        </div>
                        <div class="p-3 rounded-2xl bg-amber-50/60 border border-amber-100/70">
                            <span class="text-[10px] text-gray-500 font-semibold uppercase block">Tugas Utama</span>
                            <span class="text-xs font-bold text-amber-900">Guru BK</span>
                        </div>
                    </div>
                @elseif (auth()->user()->hasRole('admin'))
                    <div class="mt-6 pt-5 border-t border-gray-100 w-full max-w-sm text-center">
                        <span class="text-xs font-semibold text-gray-500 block">
                            Akses Pengelola Sistem BK & Data Induk Sekolah
                        </span>
                        @if(auth()->user()->no_hp)
                            <span class="text-xs font-bold text-purple-800 font-mono mt-1 block">
                                No HP: {{ auth()->user()->no_hp }}
                            </span>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        <!-- 2. Navigasi Tombol Terpisah: Edit Biodata vs Ganti Email vs Ganti Password -->
        <div class="flex items-center justify-center p-1.5 bg-white/90 backdrop-blur-md border border-purple-100/90 rounded-2xl shadow-xs max-w-md mx-auto gap-1">
            <!-- Tombol 1: Biodata & Foto Profil -->
            <button type="button"
                    @click="activeTab = 'biodata'"
                    :class="activeTab === 'biodata' ? 'bg-gradient-to-r from-purple-700 to-purple-600 text-white shadow-md shadow-purple-600/20 font-bold' : 'text-gray-600 hover:text-purple-700 hover:bg-purple-50/60 font-semibold'"
                    class="flex-1 py-2.5 px-3 rounded-xl text-xs transition duration-150 flex items-center justify-center gap-1.5">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <span>Biodata Diri</span>
            </button>

            <!-- Tombol 2: Ganti Email -->
            <button type="button"
                    @click="activeTab = 'email'"
                    :class="activeTab === 'email' ? 'bg-gradient-to-r from-purple-700 to-purple-600 text-white shadow-md shadow-purple-600/20 font-bold' : 'text-gray-600 hover:text-purple-700 hover:bg-purple-50/60 font-semibold'"
                    class="flex-1 py-2.5 px-3 rounded-xl text-xs transition duration-150 flex items-center justify-center gap-1.5">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
                <span>Ganti Email</span>
            </button>

            <!-- Tombol 3: Ganti Password -->
            <button type="button"
                    @click="activeTab = 'password'"
                    :class="activeTab === 'password' ? 'bg-gradient-to-r from-purple-700 to-purple-600 text-white shadow-md shadow-purple-600/20 font-bold' : 'text-gray-600 hover:text-purple-700 hover:bg-purple-50/60 font-semibold'"
                    class="flex-1 py-2.5 px-3 rounded-xl text-xs transition duration-150 flex items-center justify-center gap-1.5">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
                <span>Ganti Password</span>
            </button>
        </div>

        <!-- 3. Form Konten Berdasarkan Tombol Yang Dipilih -->
        
        <!-- Tab 1: Ubah Biodata & Foto Profil -->
        <div x-show="activeTab === 'biodata'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0" class="p-6 sm:p-8 bg-white/95 backdrop-blur-md border border-purple-100/90 shadow-sm rounded-3xl">
            <livewire:profile.update-biodata-form />
        </div>

        <!-- Tab 2: Ganti Alamat Email -->
        <div x-show="activeTab === 'email'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0" class="p-6 sm:p-8 bg-white/95 backdrop-blur-md border border-purple-100/90 shadow-sm rounded-3xl">
            <livewire:profile.update-profile-information-form />
        </div>

        <!-- Tab 3: Ganti Kata Sandi -->
        <div x-show="activeTab === 'password'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0" class="p-6 sm:p-8 bg-white/95 backdrop-blur-md border border-purple-100/90 shadow-sm rounded-3xl">
            <livewire:profile.update-password-form />
        </div>

        <!-- Opsi Hapus Akun -->
        <div class="p-6 sm:p-8 bg-white/95 backdrop-blur-md border border-rose-100 shadow-sm rounded-3xl">
            <livewire:profile.delete-user-form />
        </div>
    </div>
</x-app-layout>
