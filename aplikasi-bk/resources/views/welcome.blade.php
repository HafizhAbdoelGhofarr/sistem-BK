<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Sistem Informasi Bimbingan Konseling & Tata Tertib Siswa</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Styles / Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased font-sans bg-slate-50 text-gray-800 min-h-screen flex flex-col justify-between relative overflow-x-hidden selection:bg-purple-600 selection:text-white"
          x-data="{ activeTab: 'siswa', simPoin: 15 }">
        
        <!-- Background Dekoratif Mewah (Mesh Gradient + Subtle Dot Grid) -->
        <div class="fixed inset-0 pointer-events-none z-0">
            <div class="absolute -top-40 -left-40 w-[32rem] h-[32rem] bg-purple-300/35 rounded-full blur-3xl animate-pulse"></div>
            <div class="absolute top-1/3 -right-32 w-[30rem] h-[30rem] bg-indigo-200/40 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-32 left-1/3 w-[36rem] h-[36rem] bg-purple-200/35 rounded-full blur-3xl"></div>
            
            <svg class="absolute inset-0 w-full h-full opacity-[0.25]" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <pattern id="welcome-dot-pattern" width="28" height="28" patternUnits="userSpaceOnUse">
                        <circle cx="2" cy="2" r="1.2" fill="#7C3AED" />
                    </pattern>
                </defs>
                <rect width="100%" height="100%" fill="url(#welcome-dot-pattern)" />
            </svg>
        </div>

        <div class="relative z-10 flex flex-col min-h-screen">
            <!-- Header / Navbar Institusional -->
            <header class="w-full border-b border-purple-100/70 bg-white/80 backdrop-blur-md sticky top-0 z-50">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3.5 flex items-center justify-between">
                    <a href="/" wire:navigate class="transition transform hover:scale-[1.01]">
                        <x-application-logo />
                    </a>

                    <nav class="flex items-center gap-3">
                        @auth
                            <a href="{{ route('dashboard') }}" wire:navigate class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-purple-700 to-purple-600 hover:from-purple-800 hover:to-purple-700 text-white text-xs font-bold rounded-xl shadow-md shadow-purple-500/20 transition transform hover:-translate-y-0.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                </svg>
                                Buka Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" wire:navigate class="px-4 py-2 text-xs font-bold text-gray-700 hover:text-purple-700 rounded-lg hover:bg-purple-50 transition">
                                Masuk (Login)
                            </a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" wire:navigate class="inline-flex items-center gap-1.5 px-4 py-2 bg-purple-700 hover:bg-purple-800 text-white text-xs font-bold rounded-xl shadow-sm shadow-purple-500/20 transition transform hover:-translate-y-0.5">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                    </svg>
                                    Daftar Siswa
                                </a>
                            @endif
                        @endauth
                    </nav>
                </div>
            </header>

            <!-- Hero Section -->
            <main class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16 flex-1 flex flex-col items-center justify-center text-center">
                <!-- Badge Akreditasi / Gateway WhatsApp -->
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-purple-100/90 text-purple-800 text-xs font-semibold mb-6 border border-purple-200/80 shadow-xs backdrop-blur-sm">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span>
                    <span>Sistem Terintegrasi Sidobe WhatsApp Gateway &bull; Notifikasi Orang Tua Real-Time</span>
                </div>

                <!-- Judul Utama -->
                <h1 class="text-3xl sm:text-5xl lg:text-6xl font-extrabold text-gray-900 tracking-tight max-w-4xl leading-tight">
                    Membina Kedisiplinan, <br class="hidden sm:inline" />
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-700 via-purple-600 to-indigo-600">
                        Membangun Karakter Unggul
                    </span>
                </h1>

                <p class="mt-5 text-sm sm:text-base text-gray-600 max-w-2xl leading-relaxed">
                    Portal terpadu layanan Bimbingan Konseling (BK) dan monitoring tata tertib siswa. Menghubungkan Guru BK, Siswa, dan Orang Tua melalui sistem poin transparan dan notifikasi otomatis.
                </p>

                <!-- Tombol CTA Interaktif -->
                <div class="mt-8 flex flex-col sm:flex-row items-center gap-3.5 w-full sm:w-auto">
                    <a href="{{ route('login') }}" wire:navigate class="w-full sm:w-auto px-7 py-3.5 bg-gradient-to-r from-purple-700 to-purple-600 hover:from-purple-800 hover:to-purple-700 text-white font-bold text-sm rounded-2xl shadow-lg shadow-purple-600/25 transition transform hover:-translate-y-0.5 flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        Masuk ke Portal Sekolah
                    </a>
                    <a href="{{ route('register') }}" wire:navigate class="w-full sm:w-auto px-7 py-3.5 bg-white/90 hover:bg-purple-50/80 text-purple-700 border border-purple-200 font-bold text-sm rounded-2xl shadow-sm transition flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Registrasi Siswa Baru
                    </a>
                </div>

                <!-- Bagian Interaktif 1: Tab Fitur Sesuai Pengguna (Alpine.js) -->
                <div class="mt-16 w-full max-w-4xl bg-white/95 backdrop-blur-xl border border-purple-100 rounded-3xl p-6 sm:p-8 shadow-xl shadow-purple-900/5 text-left">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-6 border-b border-gray-100 gap-4">
                        <div>
                            <span class="text-xs font-bold uppercase tracking-wider text-purple-700">Fitur Terintegrasi</span>
                            <h2 class="text-xl font-extrabold text-gray-900 mt-0.5">Didesain Khusus untuk Civitas Sekolah</h2>
                        </div>

                        <!-- Tab Selector Buttons -->
                        <div class="inline-flex p-1 bg-purple-50/80 rounded-2xl border border-purple-100">
                            <button @click="activeTab = 'siswa'"
                                    :class="activeTab === 'siswa' ? 'bg-purple-700 text-white shadow-xs font-bold' : 'text-gray-600 hover:text-purple-700 font-semibold'"
                                    class="px-4 py-1.5 rounded-xl text-xs transition duration-150">
                                Untuk Siswa
                            </button>
                            <button @click="activeTab = 'gurubk'"
                                    :class="activeTab === 'gurubk' ? 'bg-purple-700 text-white shadow-xs font-bold' : 'text-gray-600 hover:text-purple-700 font-semibold'"
                                    class="px-4 py-1.5 rounded-xl text-xs transition duration-150">
                                Untuk Guru BK
                            </button>
                            <button @click="activeTab = 'ortu'"
                                    :class="activeTab === 'ortu' ? 'bg-purple-700 text-white shadow-xs font-bold' : 'text-gray-600 hover:text-purple-700 font-semibold'"
                                    class="px-4 py-1.5 rounded-xl text-xs transition duration-150">
                                Untuk Orang Tua
                            </button>
                        </div>
                    </div>

                    <!-- Tab Content 1: Siswa -->
                    <div x-show="activeTab === 'siswa'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" class="pt-6 grid grid-cols-1 md:grid-cols-3 gap-5">
                        <div class="p-4 rounded-2xl bg-purple-50/50 border border-purple-100/80">
                            <div class="w-8 h-8 rounded-xl bg-purple-700 text-white flex items-center justify-center font-bold text-xs mb-3">01</div>
                            <h3 class="font-bold text-sm text-gray-900">Transparansi Poin</h3>
                            <p class="text-xs text-gray-600 mt-1">Cek akumulasi poin dan riwayat pelanggaran pribadi secara mandiri tanpa menunggu teguran lisan.</p>
                        </div>
                        <div class="p-4 rounded-2xl bg-purple-50/50 border border-purple-100/80">
                            <div class="w-8 h-8 rounded-xl bg-purple-700 text-white flex items-center justify-center font-bold text-xs mb-3">02</div>
                            <h3 class="font-bold text-sm text-gray-900">Jadwal Bimbingan</h3>
                            <p class="text-xs text-gray-600 mt-1">Melihat jadwal temu konseling atau sesi pembinaan bersama Guru BK secara terencana.</p>
                        </div>
                        <div class="p-4 rounded-2xl bg-purple-50/50 border border-purple-100/80">
                            <div class="w-8 h-8 rounded-xl bg-purple-700 text-white flex items-center justify-center font-bold text-xs mb-3">03</div>
                            <h3 class="font-bold text-sm text-gray-900">Self-Monitoring</h3>
                            <p class="text-xs text-gray-600 mt-1">Status kedisiplinan visual (Aman, Perhatian, Pembinaan, Kritis) memotivasi perubahan sikap positif.</p>
                        </div>
                    </div>

                    <!-- Tab Content 2: Guru BK -->
                    <div x-show="activeTab === 'gurubk'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" class="pt-6 grid grid-cols-1 md:grid-cols-3 gap-5">
                        <div class="p-4 rounded-2xl bg-amber-50/50 border border-amber-100">
                            <div class="w-8 h-8 rounded-xl bg-amber-600 text-white flex items-center justify-center font-bold text-xs mb-3">01</div>
                            <h3 class="font-bold text-sm text-gray-900">Input Pelanggaran Cepat</h3>
                            <p class="text-xs text-gray-600 mt-1">Pilih kelas, siswa, dan jenis pelanggaran; poin dan status dihitung otomatis oleh sistem.</p>
                        </div>
                        <div class="p-4 rounded-2xl bg-amber-50/50 border border-amber-100">
                            <div class="w-8 h-8 rounded-xl bg-amber-600 text-white flex items-center justify-center font-bold text-xs mb-3">02</div>
                            <h3 class="font-bold text-sm text-gray-900">Manajemen Pembinaan</h3>
                            <p class="text-xs text-gray-600 mt-1">Dokumentasi hasil konseling, surat kesepakatan siswa, dan rekam jejak tindak lanjut kasus.</p>
                        </div>
                        <div class="p-4 rounded-2xl bg-amber-50/50 border border-amber-100">
                            <div class="w-8 h-8 rounded-xl bg-amber-600 text-white flex items-center justify-center font-bold text-xs mb-3">03</div>
                            <h3 class="font-bold text-sm text-gray-900">Siswa Prioritas</h3>
                            <p class="text-xs text-gray-600 mt-1">Deteksi dini siswa dengan poin kritis agar segera mendapat pendampingan konselor.</p>
                        </div>
                    </div>

                    <!-- Tab Content 3: Orang Tua -->
                    <div x-show="activeTab === 'ortu'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" class="pt-6 grid grid-cols-1 md:grid-cols-3 gap-5">
                        <div class="p-4 rounded-2xl bg-emerald-50/50 border border-emerald-100">
                            <div class="w-8 h-8 rounded-xl bg-emerald-600 text-white flex items-center justify-center font-bold text-xs mb-3">01</div>
                            <h3 class="font-bold text-sm text-gray-900">WhatsApp Instan</h3>
                            <p class="text-xs text-gray-600 mt-1">Pesan detail pelanggaran langsung terkirim ke WhatsApp saat peristiwa dicatat sekolah.</p>
                        </div>
                        <div class="p-4 rounded-2xl bg-emerald-50/50 border border-emerald-100">
                            <div class="w-8 h-8 rounded-xl bg-emerald-600 text-white flex items-center justify-center font-bold text-xs mb-3">02</div>
                            <h3 class="font-bold text-sm text-gray-900">Kolaborasi Pembinaan</h3>
                            <p class="text-xs text-gray-600 mt-1">Memungkinkan orang tua dan pihak sekolah bersama-sama membina anak di rumah dan di sekolah.</p>
                        </div>
                        <div class="p-4 rounded-2xl bg-emerald-50/50 border border-emerald-100">
                            <div class="w-8 h-8 rounded-xl bg-emerald-600 text-white flex items-center justify-center font-bold text-xs mb-3">03</div>
                            <h3 class="font-bold text-sm text-gray-900">Keamanan Informasi</h3>
                            <p class="text-xs text-gray-600 mt-1">Informasi bersifat privat dan hanya dikirimkan ke nomor resmi orang tua/wali murid.</p>
                        </div>
                    </div>
                </div>

                <!-- Bagian Interaktif 2: Simulator Poin Kedisiplinan Siswa (Alpine.js) -->
                <div class="mt-12 w-full max-w-4xl bg-gradient-to-br from-purple-900 via-purple-800 to-indigo-900 text-white rounded-3xl p-6 sm:p-8 shadow-2xl shadow-purple-950/20 text-left relative overflow-hidden">
                    <div class="relative z-10">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-white/10">
                            <div>
                                <span class="text-xs font-bold uppercase tracking-wider text-purple-300">Simulasi Interaktif</span>
                                <h3 class="text-lg sm:text-xl font-bold">Kalkulator Tingkat Kedisiplinan Siswa</h3>
                                <p class="text-xs text-purple-200 mt-0.5">Geser poin untuk melihat bagaimana sistem mengkategorikan status kedisiplinan</p>
                            </div>

                            <div class="text-right">
                                <span class="text-xs text-purple-300 block">Akumulasi Poin:</span>
                                <span class="text-3xl font-extrabold text-white" x-text="simPoin + ' Poin'"></span>
                            </div>
                        </div>

                        <!-- Slider Interaktif -->
                        <div class="mt-6">
                            <input type="range" min="0" max="80" step="5" x-model="simPoin"
                                   class="w-full h-2.5 bg-white/20 rounded-lg appearance-none cursor-pointer accent-purple-400">
                            <div class="flex justify-between text-[11px] text-purple-300 mt-2 font-medium">
                                <span>0 Poin (Awal)</span>
                                <span>25 Poin (Perhatian)</span>
                                <span>40 Poin (Pembinaan)</span>
                                <span>55+ Poin (Kritis)</span>
                            </div>
                        </div>

                        <!-- Dynamic Status Output -->
                        <div class="mt-6 p-4 rounded-2xl bg-white/10 backdrop-blur-md border border-white/15 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl flex items-center justify-center font-bold text-sm flex-shrink-0"
                                     :class="{
                                         'bg-emerald-500 text-white': simPoin < 25,
                                         'bg-amber-500 text-white': simPoin >= 25 && simPoin < 40,
                                         'bg-orange-500 text-white': simPoin >= 40 && simPoin < 55,
                                         'bg-rose-500 text-white': simPoin >= 55
                                     }">
                                    <span x-show="simPoin < 25">✓</span>
                                    <span x-show="simPoin >= 25 && simPoin < 40">!</span>
                                    <span x-show="simPoin >= 40 && simPoin < 55">⚠</span>
                                    <span x-show="simPoin >= 55">✕</span>
                                </div>
                                <div>
                                    <div class="text-xs text-purple-200 font-medium">Status Kedisiplinan:</div>
                                    <div class="text-base font-extrabold"
                                         :class="{
                                             'text-emerald-300': simPoin < 25,
                                             'text-amber-300': simPoin >= 25 && simPoin < 40,
                                             'text-orange-300': simPoin >= 40 && simPoin < 55,
                                             'text-rose-300': simPoin >= 55
                                         }"
                                         x-text="simPoin < 25 ? 'Status Aman (Disiplin Terjaga)' : (simPoin < 40 ? 'Status Perhatian (Peringatan Lisan)' : (simPoin < 55 ? 'Status Pembinaan (Bimbingan Konseling)' : 'Status Kritis (Panggilan Resmi Orang Tua)'))">
                                    </div>
                                </div>
                            </div>

                            <div class="text-xs text-purple-200 text-right max-w-xs">
                                <span x-show="simPoin < 25">Siswa memiliki catatan tertib dan bebas sanksi kedisiplinan.</span>
                                <span x-show="simPoin >= 25 && simPoin < 40">Mendapat pendampingan wali kelas untuk evaluasi sikap.</span>
                                <span x-show="simPoin >= 40 && simPoin < 55">Diwajibkan hadir pada sesi konseling khusus bersama Guru BK.</span>
                                <span x-show="simPoin >= 55">Surat panggilan resmi dikirimkan dan orang tua wajib hadir ke sekolah.</span>
                            </div>
                        </div>
                    </div>
                </div>
            </main>

            <!-- Footer Institusional Sekolah Lengkap -->
            <footer class="w-full border-t border-purple-100/90 bg-white/95 backdrop-blur-md pt-10 pb-6 text-gray-600">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-8 pb-8 border-b border-gray-100">
                        <div class="md:col-span-2 space-y-3">
                            <x-application-logo />
                            <p class="text-xs text-gray-500 leading-relaxed max-w-md">
                                Sistem Bimbingan Konseling dan Tata Tertib Siswa merupakan instrumen terintegrasi sekolah dalam mewujudkan lingkungan belajar yang aman, kondusif, dan berkarakter budi pekerti luhur.
                            </p>
                        </div>

                        <div>
                            <h4 class="text-xs font-bold uppercase tracking-wider text-gray-900 mb-3">Informasi BK</h4>
                            <ul class="text-xs space-y-2 text-gray-500">
                                <li>Ruang Konseling Pribadi</li>
                                <li>Layanan Bimbingan Karir</li>
                                <li>Pencegahan Perundungan (Anti-Bullying)</li>
                                <li>Integrasi WhatsApp Sidobe</li>
                            </ul>
                        </div>

                        <div>
                            <h4 class="text-xs font-bold uppercase tracking-wider text-gray-900 mb-3">Kontak Piket BK</h4>
                            <p class="text-xs text-gray-500 leading-relaxed">
                                Gedung Bimbingan Konseling, Lantai 2.<br />
                                Jam Operasional: 07.30 - 15.00 WIB.<br />
                                Surel: bk@sekolah.sch.id
                            </p>
                        </div>
                    </div>

                    <div class="pt-6 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-gray-400">
                        <div>
                            &copy; {{ date('Y') }} Sistem Informasi Bimbingan Konseling Sekolah &bull; Dikembangkan oleh Farrel (Frontend) & Hafiz (Backend).
                        </div>
                        <div class="flex items-center gap-4 text-purple-700 font-semibold">
                            <a href="{{ route('login') }}" class="hover:underline">Masuk Portal</a>
                            <span>&bull;</span>
                            <a href="{{ route('register') }}" class="hover:underline">Registrasi Siswa</a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </body>
</html>
