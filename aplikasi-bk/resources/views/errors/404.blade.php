<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>404 - Halaman Tidak Ditemukan | Sistem BK</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-brand-50 text-gray-800 min-h-screen flex items-center justify-center p-4 relative overflow-hidden selection:bg-purple-600 selection:text-white">
        
        <!-- Background Dekoratif Mewah (Mesh Gradient + Dots) -->
        <div class="fixed inset-0 pointer-events-none z-0">
            <div class="absolute -top-32 -left-32 w-[32rem] h-[32rem] bg-purple-300/30 rounded-full blur-3xl"></div>
            <div class="absolute top-1/2 -right-32 w-96 h-96 bg-indigo-200/35 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-32 left-1/3 w-[30rem] h-[30rem] bg-purple-200/30 rounded-full blur-3xl"></div>
            
            <svg class="absolute inset-0 w-full h-full opacity-[0.22]" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <pattern id="dot-pattern-404" width="28" height="28" patternUnits="userSpaceOnUse">
                        <circle cx="2" cy="2" r="1.2" fill="#17736D" />
                    </pattern>
                </defs>
                <rect width="100%" height="100%" fill="url(#dot-pattern-404)" />
            </svg>
        </div>

        <div class="relative z-10 w-full max-w-lg text-center">
            <!-- Card 404 -->
            <div class="bg-white/95 backdrop-blur-xl border border-purple-100/90 rounded-3xl p-8 sm:p-10 shadow-2xl shadow-purple-950/10">
                <!-- Logo Aplikasi -->
                <div class="flex justify-center mb-6">
                    <x-application-logo />
                </div>

                <!-- Angka 404 dengan gradien hijau mint -->
                <div class="relative mx-auto mb-4">
                    <span class="text-7xl sm:text-8xl font-black text-transparent bg-clip-text bg-gradient-to-r from-purple-800 via-purple-600 to-indigo-600 tracking-tighter">
                        404
                    </span>
                </div>

                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-purple-50 border border-purple-200/80 text-purple-700 text-xs font-bold uppercase tracking-wider mb-2">
                    Kode Status: 404 &bull; Tidak Ditemukan (Not Found)
                </div>

                <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight mt-2">
                    Halaman Tidak Ditemukan
                </h1>

                <p class="text-xs sm:text-sm text-gray-500 mt-2.5 leading-relaxed max-w-sm mx-auto">
                    Tautan yang Anda tuju mungkin salah ketik, telah dipindahkan, atau sudah tidak tersedia lagi di dalam sistem portal bimbingan konseling.
                </p>

                <!-- Tombol Navigasi -->
                <div class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-3">
                    <a href="{{ auth()->check() ? route('dashboard') : url('/') }}"
                       class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 bg-gradient-to-r from-purple-700 to-purple-600 hover:from-purple-800 hover:to-purple-700 text-white text-xs font-bold rounded-2xl shadow-md shadow-purple-600/25 transition transform hover:-translate-y-0.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        Kembali ke Beranda
                    </a>

                    <button onclick="window.history.back()"
                            class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-3 bg-purple-50/70 hover:bg-purple-100/80 text-purple-700 text-xs font-bold rounded-2xl border border-purple-200/70 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Halaman Sebelumnya
                    </button>
                </div>
            </div>

            <!-- Footer kecil -->
            <p class="text-xs text-gray-400 mt-6">
                &copy; {{ date('Y') }} Sistem Informasi Bimbingan Konseling &bull; Layanan Terpadu Sekolah
            </p>
        </div>
    </body>
</html>