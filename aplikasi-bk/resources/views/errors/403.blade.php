<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>403 - Akses Ditolak | Sistem BK</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-white text-gray-800 min-h-screen flex items-center justify-center p-4 relative overflow-hidden selection:bg-purple-600 selection:text-white">
        
        <div class="relative z-10 w-full max-w-lg text-center">
            <!-- Card 403 -->
            <div class="bg-white/95 backdrop-blur-xl border border-purple-100/90 rounded-3xl p-8 sm:p-10 shadow-2xl shadow-purple-950/10">
                <!-- Logo Aplikasi -->
                <div class="flex justify-center mb-6">
                    <x-application-logo />
                </div>

                <!-- Ilustrasi Ikon Gembok Proteksi -->
                <div class="relative mx-auto w-20 h-20 mb-5">
                    <div class="w-20 h-20 rounded-3xl bg-purple-50 text-purple-700 border border-purple-100 flex items-center justify-center shadow-inner">
                        <svg class="w-10 h-10 text-purple-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <span class="absolute -bottom-1 -right-1 w-7 h-7 rounded-full bg-rose-500 text-white font-black text-xs flex items-center justify-center border-2 border-white shadow-xs">
                        !
                    </span>
                </div>

                <!-- Angka Error -->
                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-rose-50 border border-rose-200/80 text-rose-700 text-xs font-bold uppercase tracking-wider mb-2">
                    Kode Status: 403 &bull; Akses Ditolak (Forbidden)
                </div>

                <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight mt-2">
                    Area Khusus / Dibatasi
                </h1>

                <p class="text-xs sm:text-sm text-gray-500 mt-2.5 leading-relaxed max-w-sm mx-auto">
                    Mohon maaf, akun Anda tidak memiliki hak akses untuk membuka halaman ini. Fitur ini hanya dapat diakses oleh peran tertentu (Guru BK / Administrator).
                </p>

                <!-- Tombol Navigasi -->
                <div class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-3">
                    <a href="{{ auth()->check() ? route('dashboard') : url('/') }}"
                       class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 bg-gradient-to-r from-purple-700 to-purple-600 hover:from-purple-800 hover:to-purple-700 text-white text-xs font-bold rounded-2xl shadow-md shadow-purple-600/25 transition transform hover:-translate-y-0.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        Kembali ke Dashboard
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