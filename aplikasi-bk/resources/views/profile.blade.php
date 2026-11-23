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

    <!-- Kontainer Utama: Berada di Tengah (Centered) -->
    <div class="max-w-2xl mx-auto space-y-8">
        
        <!-- 1. Header Card Profil Utama (Avatar Bulat & Identitas Pengguna Terpusat) -->
        <div class="bg-white/95 backdrop-blur-md border border-purple-100/90 rounded-3xl p-8 shadow-sm text-center relative overflow-hidden">
            <!-- Akses Dekoratif Gradien -->
            <div class="absolute -top-12 -right-12 w-40 h-40 bg-purple-100/50 rounded-full blur-2xl pointer-events-none"></div>
            <div class="absolute -bottom-12 -left-12 w-40 h-40 bg-indigo-50/50 rounded-full blur-2xl pointer-events-none"></div>

            <div class="relative z-10 flex flex-col items-center">
                <!-- Foto Profil Bulat Besar dengan Cincin Gradien Ungu -->
                <div class="relative mb-4">
                    <div class="w-24 h-24 sm:w-28 sm:h-28 rounded-full bg-gradient-to-tr from-purple-700 via-purple-600 to-indigo-600 text-white flex items-center justify-center font-black text-3xl sm:text-4xl shadow-xl shadow-purple-600/25 border-4 border-white ring-4 ring-purple-100">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
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
                    <div class="mt-6 pt-6 border-t border-gray-100 w-full max-w-md grid grid-cols-3 gap-3 text-center">
                        <div class="p-3 rounded-2xl bg-purple-50/60 border border-purple-100/70">
                            <span class="text-[10px] text-gray-500 font-semibold uppercase block">NIS Siswa</span>
                            <span class="text-xs font-bold text-gray-900 font-mono">{{ auth()->user()->siswa->nis }}</span>
                        </div>
                        <div class="p-3 rounded-2xl bg-purple-50/60 border border-purple-100/70">
                            <span class="text-[10px] text-gray-500 font-semibold uppercase block">Rombel</span>
                            <span class="text-xs font-bold text-gray-900">{{ auth()->user()->siswa->kelas?->nama ?? '-' }}</span>
                        </div>
                        <div class="p-3 rounded-2xl bg-purple-50/60 border border-purple-100/70">
                            <span class="text-[10px] text-gray-500 font-semibold uppercase block">Akumulasi Poin</span>
                            <span class="text-xs font-extrabold text-rose-600">+{{ auth()->user()->siswa->total_poin }} Poin</span>
                        </div>
                    </div>
                @elseif (auth()->user()->hasRole('guru_bk') && auth()->user()->guru)
                    <div class="mt-6 pt-6 border-t border-gray-100 w-full max-w-sm flex items-center justify-center gap-8 text-center">
                        <div>
                            <span class="text-[10px] text-gray-500 font-semibold uppercase block">NIP Guru</span>
                            <span class="text-xs font-bold text-gray-900 font-mono">{{ auth()->user()->guru->nip ?? '-' }}</span>
                        </div>
                        <div class="w-px h-8 bg-gray-200"></div>
                        <div>
                            <span class="text-[10px] text-gray-500 font-semibold uppercase block">Tugas Utama</span>
                            <span class="text-xs font-bold text-purple-800">Guru Bimbingan Konseling</span>
                        </div>
                    </div>
                @elseif (auth()->user()->hasRole('admin'))
                    <div class="mt-6 pt-4 border-t border-gray-100 w-full max-w-sm text-center">
                        <span class="text-xs font-semibold text-gray-500">
                            Akses Pengelola Sistem BK & Data Induk Sekolah
                        </span>
                    </div>
                @endif
            </div>
        </div>

        <!-- 2. Informasi Profil Akun (Nama & Email) -->
        <div class="p-6 sm:p-8 bg-white/95 backdrop-blur-md border border-purple-100/90 shadow-sm rounded-3xl">
            <livewire:profile.update-profile-information-form />
        </div>

        <!-- 3. Pembaruan Kata Sandi -->
        <div class="p-6 sm:p-8 bg-white/95 backdrop-blur-md border border-purple-100/90 shadow-sm rounded-3xl">
            <livewire:profile.update-password-form />
        </div>

        <!-- 4. Hapus Akun -->
        @role('admin')
            <div class="p-6 sm:p-8 bg-white/95 backdrop-blur-md border border-rose-100 shadow-sm rounded-3xl">
                <livewire:profile.delete-user-form />
            </div>
        @endrole
    </div>
</x-app-layout>
