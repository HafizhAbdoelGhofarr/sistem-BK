<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Sistem BK') }} - Portal Bimbingan Konseling & Tata Tertib Siswa</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased min-h-screen flex flex-col justify-between relative overflow-x-hidden bg-brand-50 selection:bg-purple-600 selection:text-white">
        
        <!-- Background Dekoratif Mewah (Mesh Gradient + Subtle Dot Grid) -->
        <div class="fixed inset-0 pointer-events-none z-0">
            <!-- Lingkaran cahaya dengan gradasi hijau mint -->
            <div class="absolute -top-40 -left-40 w-96 h-96 bg-purple-300/40 rounded-full blur-3xl"></div>
            <div class="absolute top-1/3 -right-32 w-96 h-96 bg-indigo-200/40 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-32 left-1/3 w-[30rem] h-[30rem] bg-purple-200/30 rounded-full blur-3xl"></div>
            
            <!-- Pola Dot Grid Halus -->
            <svg class="absolute inset-0 w-full h-full opacity-[0.25]" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <pattern id="dot-pattern" width="24" height="24" patternUnits="userSpaceOnUse">
                        <circle cx="2" cy="2" r="1.2" fill="#17736D" />
                    </pattern>
                </defs>
                <rect width="100%" height="100%" fill="url(#dot-pattern)" />
            </svg>
        </div>

        <!-- Header Institusional Sekolah -->
        <header class="relative z-10 w-full border-b border-purple-100/60 bg-white/70 backdrop-blur-md sticky top-0">
            <div class="max-w-6xl mx-auto px-4 sm:px-8 py-3.5 flex justify-between items-center">
                <a href="/" wire:navigate class="transition transform hover:scale-[1.02]">
                    <x-application-logo />
                </a>

                <div class="flex items-center gap-3">
                    <span class="hidden md:inline-flex items-center gap-1.5 text-xs font-semibold px-2.5 py-1 rounded-full bg-purple-100/80 text-purple-800 border border-purple-200/60">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span>
                        Portal Aktif
                    </span>
                    <a href="/" wire:navigate class="text-xs font-semibold text-gray-600 hover:text-purple-700 flex items-center gap-1 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Beranda
                    </a>
                </div>
            </div>
        </header>

        <!-- Main Card Container (Glassmorphism Modern) -->
        <main class="relative z-10 flex-1 flex flex-col justify-center items-center px-4 py-10 sm:py-14">
            <div class="w-full sm:max-w-lg bg-white/95 backdrop-blur-xl border border-purple-100/90 shadow-2xl shadow-purple-900/10 rounded-3xl p-7 sm:p-10 transition-all duration-300 hover:shadow-purple-900/15">
                {{ $slot }}
            </div>
        </main>

        <!-- Footer Institusional Sekolah -->
        <footer class="relative z-10 border-t border-purple-100/80 bg-white/80 backdrop-blur-md py-4 text-center">
            <div class="max-w-6xl mx-auto px-4 flex flex-col sm:flex-row items-center justify-between gap-2 text-xs text-gray-500">
                <div class="flex items-center gap-2">
                    <span class="font-bold text-gray-700">Layanan BK & Tata Tertib</span>
                    <span>&bull;</span>
                    <span>Membina Disiplin, Membangun Karakter</span>
                </div>
                <div>
                    &copy; {{ date('Y') }} Sistem Informasi Bimbingan Konseling Sekolah.
                </div>
            </div>
        </footer>
    </body>
</html>
