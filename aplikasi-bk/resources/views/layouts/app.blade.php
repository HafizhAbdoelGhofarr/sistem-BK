<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'DIGISELF') }} - Media Digital Bimbingan dan Konseling</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased text-gray-800 min-h-screen flex flex-col relative bg-brand-50 selection:bg-purple-600 selection:text-white"
          x-data="{ mobileSidebarOpen: false }">
        
        <!-- Background Dekoratif Mewah (Mesh Gradient + Dots) -->
        <div class="fixed inset-0 pointer-events-none z-0">
            <div class="absolute -top-32 -left-32 w-96 h-96 bg-purple-200/30 rounded-full blur-3xl"></div>
            <div class="absolute top-1/2 -right-32 w-96 h-96 bg-indigo-100/40 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-32 left-1/4 w-[28rem] h-[28rem] bg-purple-100/40 rounded-full blur-3xl"></div>
            
            <svg class="absolute inset-0 w-full h-full opacity-[0.2]" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <pattern id="app-dot-pattern" width="28" height="28" patternUnits="userSpaceOnUse">
                        <circle cx="2" cy="2" r="1.2" fill="#17736D" />
                    </pattern>
                </defs>
                <rect width="100%" height="100%" fill="url(#app-dot-pattern)" />
            </svg>
        </div>

        @php
            $isAdminOrGuru = auth()->check() && auth()->user()->hasRole(['admin', 'guru_bk']);
        @endphp

        @if ($isAdminOrGuru)
            <!-- ======================================================== -->
            <!-- LAYOUT KHUSUS ADMIN & GURU BK: FIXED LEFT SIDEBAR HEADER -->
            <!-- ======================================================== -->
            <div class="relative z-10 flex min-h-screen">
                <!-- 1. Sidebar Kiri Fixed di Desktop (Tidak Ikut Ter-Scroll) -->
                <div class="hidden lg:block fixed inset-y-0 left-0 z-40 w-64">
                    <livewire:layout.sidebar />
                </div>

                <!-- 2. Drawer Sidebar Mobile dengan Overlay Backdrop -->
                <div x-show="mobileSidebarOpen" 
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-end="opacity-0"
                     class="fixed inset-0 z-50 lg:hidden bg-slate-900/60 backdrop-blur-xs"
                     style="display: none;">
                    <div @click.away="mobileSidebarOpen = false" class="fixed inset-y-0 left-0 w-64 max-w-xs shadow-2xl">
                        <livewire:layout.sidebar />
                    </div>
                </div>

                <!-- 3. Area Konten Utama di Sebelah Kanan Sidebar (Scroll Independen) -->
                <div class="flex-1 lg:pl-64 flex flex-col min-h-screen w-full">
                    <!-- Top Bar Mobile (Hanya di Layar Kecil) -->
                    <div class="lg:hidden sticky top-0 z-30 flex items-center justify-between p-4 bg-white/90 backdrop-blur-md border-b border-purple-100">
                        <button @click="mobileSidebarOpen = true" class="p-2 text-gray-600 hover:text-purple-700 hover:bg-purple-50 rounded-xl transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                        <x-application-logo />
                        <div class="w-8"></div>
                    </div>

                    <!-- Flash Alert Toast Container -->
                    @if (session('success') || session('status'))
                        <div class="px-6 sm:px-8 lg:px-10 pt-4 w-full">
                            <div class="flex items-center justify-between p-4 bg-emerald-50/95 border border-emerald-200 text-emerald-800 rounded-2xl shadow-sm text-sm backdrop-blur-md animate-fade-in">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-xl bg-emerald-500 text-white flex items-center justify-center flex-shrink-0 shadow-sm">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </div>
                                    <span class="font-medium">{{ session('success') ?? session('status') }}</span>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="px-6 sm:px-8 lg:px-10 pt-4 w-full">
                            <div class="flex items-center justify-between p-4 bg-rose-50/95 border border-rose-200 text-rose-800 rounded-2xl shadow-sm text-sm backdrop-blur-md">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-xl bg-rose-500 text-white flex items-center justify-center flex-shrink-0 shadow-sm">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </div>
                                    <span class="font-medium">{{ session('error') }}</span>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Page Heading (Jika Ada) -->
                    @if (isset($header))
                        <header class="bg-white/80 backdrop-blur-md border-b border-purple-100/80 shadow-xs">
                            <div class="py-5 px-6 sm:px-8 lg:px-10">
                                {{ $header }}
                            </div>
                        </header>
                    @endif

                    <!-- Main Page Content Admin/Guru -->
                    <main class="flex-1 py-8 px-6 sm:px-8 lg:px-10">
                        {{ $slot }}
                    </main>

                    <!-- Mini Footer Simpel Khusus Admin & Guru (Tanpa Footer Besar) -->
                    <footer class="mt-auto py-4 px-6 sm:px-8 lg:px-10 border-t border-purple-100/60 bg-white/40 backdrop-blur-xs text-xs text-gray-400 flex flex-col sm:flex-row items-center justify-between gap-2">
                        <span>&copy; {{ date('Y') }} DIGISELF &bull; Media Digital Bimbingan dan Konseling &bull; Panel Staff</span>
                        <span class="text-purple-700 font-semibold">DIGISELF BK</span>
                    </footer>
                </div>
            </div>
        @else
            <!-- ======================================================== -->
            <!-- LAYOUT KHUSUS SISWA: TOP NAVBAR & FOOTER LENGKAP -->
            <!-- ======================================================== -->
            <div class="relative z-10 flex flex-col min-h-screen">
                <!-- Top Navigation (Glassmorphic) -->
                <livewire:layout.navigation />

                <!-- Flash Alert Toast Container -->
                @if (session('success') || session('status'))
                    <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-10 pt-4 w-full">
                        <div class="flex items-center justify-between p-4 bg-emerald-50/95 border border-emerald-200 text-emerald-800 rounded-2xl shadow-sm text-sm backdrop-blur-md animate-fade-in">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-xl bg-emerald-500 text-white flex items-center justify-center flex-shrink-0 shadow-sm">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                                <span class="font-medium">{{ session('success') ?? session('status') }}</span>
                            </div>
                        </div>
                    </div>
                @endif

                @if (session('error'))
                    <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-10 pt-4 w-full">
                        <div class="flex items-center justify-between p-4 bg-rose-50/95 border border-rose-200 text-rose-800 rounded-2xl shadow-sm text-sm backdrop-blur-md">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-xl bg-rose-500 text-white flex items-center justify-center flex-shrink-0 shadow-sm">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </div>
                                <span class="font-medium">{{ session('error') }}</span>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Page Heading (Jika Ada) -->
                @if (isset($header))
                    <header class="bg-white/80 backdrop-blur-md border-b border-purple-100/80 shadow-xs">
                        <div class="max-w-7xl mx-auto py-5 px-6 sm:px-8 lg:px-10">
                            {{ $header }}
                        </div>
                    </header>
                @endif

                <!-- Main Page Content Siswa -->
                <main class="flex-1 max-w-7xl mx-auto py-8 sm:py-10 px-6 sm:px-8 lg:px-10 w-full">
                    {{ $slot }}
                </main>

                <!-- Footer Institusional Sekolah Lengkap -->
                <footer class="mt-auto border-t border-purple-100/90 bg-white/95 backdrop-blur-md pt-10 pb-8 text-gray-600">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-8 pb-8 border-b border-gray-100">
                            <!-- Kolom 1: Profil Layanan -->
                            <div class="md:col-span-2 space-y-3">
                                <x-application-logo />
                                <p class="text-xs text-gray-500 leading-relaxed max-w-md mt-2">
                                    DIGISELF: Media Digital Bimbingan dan Konseling untuk Penguatan Literasi Digital dan Regulasi Mahasiswa / Siswa. Mendukung pembelajaran mandiri, refleksi diri, dan pendampingan konseling profesional.
                                </p>
                            </div>

                            <!-- Kolom 2: Jam Pelayanan BK -->
                            <div>
                                <h4 class="text-xs font-bold uppercase tracking-wider text-gray-900 mb-3">Layanan Konsultasi</h4>
                                <ul class="text-xs text-gray-600 space-y-2">
                                    <li class="flex justify-between">
                                        <span>Senin - Kamis:</span>
                                        <span class="font-semibold text-gray-800">07.30 - 15.00 WIB</span>
                                    </li>
                                    <li class="flex justify-between">
                                        <span>Jumat:</span>
                                        <span class="font-semibold text-gray-800">07.30 - 11.30 WIB</span>
                                    </li>
                                    <li class="flex justify-between text-purple-700 font-medium">
                                        <span>Lokasi:</span>
                                        <span>Gedung Bimbingan Konseling</span>
                                    </li>
                                </ul>
                            </div>

                            <!-- Kolom 3: Bantuan & Darurat -->
                            <div>
                                <h4 class="text-xs font-bold uppercase tracking-wider text-gray-900 mb-3">Informasi & Pengaduan</h4>
                                <p class="text-xs text-gray-500 mb-3 leading-relaxed">
                                    Konfirmasi catatan kedisiplinan dan konsultasi perkembangan siswa bersama Guru BK.
                                </p>
                                <div class="text-xs font-semibold text-purple-700 flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                    bk@sekolah.sch.id
                                </div>
                            </div>
                        </div>

                        <div class="pt-6 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-gray-400">
                            <div>
                                &copy; {{ date('Y') }} DIGISELF (Digital Self-Development for Students) &bull; UIN Raden Intan Lampung. Hak Cipta Dilindungi.
                            </div>
                            <div class="flex items-center gap-4 text-gray-500 font-medium">
                                <a href="{{ route('digiself.tentang') }}" wire:navigate class="hover:text-purple-700">Tentang & Privasi</a>
                                <span>&bull;</span>
                                <a href="{{ route('digiself.bantuan') }}" wire:navigate class="hover:text-purple-700">Bantuan BK</a>
                                <span>&bull;</span>
                                <a href="{{ route('master.tata-tertib') }}" wire:navigate class="hover:text-purple-700">Tata Tertib</a>
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
        @endif
    </body>
</html>
