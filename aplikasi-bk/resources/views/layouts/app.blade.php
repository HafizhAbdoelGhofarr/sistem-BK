<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Sistem BK') }} - Layanan Bimbingan Konseling Sekolah</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased text-gray-800 min-h-screen flex flex-col relative bg-slate-50 selection:bg-purple-600 selection:text-white">
        
        <!-- Background Dekoratif Mewah (Mesh Gradient + Dots) -->
        <div class="fixed inset-0 pointer-events-none z-0">
            <div class="absolute -top-32 -left-32 w-96 h-96 bg-purple-200/30 rounded-full blur-3xl"></div>
            <div class="absolute top-1/2 -right-32 w-96 h-96 bg-indigo-100/40 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-32 left-1/4 w-[28rem] h-[28rem] bg-purple-100/40 rounded-full blur-3xl"></div>
            
            <svg class="absolute inset-0 w-full h-full opacity-[0.2]" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <pattern id="app-dot-pattern" width="28" height="28" patternUnits="userSpaceOnUse">
                        <circle cx="2" cy="2" r="1.2" fill="#6B46C1" />
                    </pattern>
                </defs>
                <rect width="100%" height="100%" fill="url(#app-dot-pattern)" />
            </svg>
        </div>

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

            <!-- Main Page Content -->
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
                                Portal Terpadu Bimbingan Konseling dan Pengawasan Tata Tertib Siswa. Menghubungkan Guru BK, Wali Kelas, dan Orang Tua untuk mewujudkan iklim belajar yang tertib, positif, dan berkarakter.
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
                            &copy; {{ date('Y') }} Sistem Informasi Bimbingan Konseling Sekolah &bull; Hak Cipta Dilindungi Undang-Undang.
                        </div>
                        <div class="flex items-center gap-4 text-gray-500 font-medium">
                            <span class="hover:text-purple-700">Kode Etik Konseling</span>
                            <span>&bull;</span>
                            <span class="hover:text-purple-700">Buku Pedoman Tata Tertib</span>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </body>
</html>
