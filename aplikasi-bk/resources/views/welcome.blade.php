<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Sistem Informasi BK & Tata Tertib Siswa</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Styles / Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased font-sans bg-gradient-to-br from-purple-50/60 via-white to-purple-100/40 text-gray-800 min-h-screen flex flex-col justify-between">
        
        <!-- Navbar -->
        <header class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-5 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <x-application-logo />
            </div>

            <nav class="flex items-center gap-3">
                @auth
                    <a href="{{ route('dashboard') }}" wire:navigate class="inline-flex items-center px-4 py-2 bg-purple-700 hover:bg-purple-800 text-white text-xs font-semibold rounded-lg shadow-sm transition">
                        Masuk ke Dashboard &rarr;
                    </a>
                @else
                    <a href="{{ route('login') }}" wire:navigate class="px-4 py-2 text-xs font-semibold text-gray-700 hover:text-purple-700 transition">
                        Masuk (Login)
                    </a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" wire:navigate class="inline-flex items-center px-4 py-2 bg-purple-700 hover:bg-purple-800 text-white text-xs font-semibold rounded-lg shadow-sm shadow-purple-500/20 transition">
                            Daftar Siswa
                        </a>
                    @endif
                @endauth
            </nav>
        </header>

        <!-- Hero Section -->
        <main class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 flex-1 flex flex-col items-center justify-center text-center">
            <!-- Badge Info -->
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-purple-100 text-purple-800 text-xs font-semibold mb-6 border border-purple-200">
                <span class="w-2 h-2 rounded-full bg-purple-600 animate-pulse"></span>
                Terintegrasi WhatsApp Gateway Sidobe &bull; Notifikasi Realtime
            </div>

            <!-- Judul Utama -->
            <h1 class="text-3xl sm:text-5xl lg:text-6xl font-extrabold text-gray-900 tracking-tight max-w-4xl leading-tight">
                Layanan Bimbingan Konseling & <br class="hidden sm:inline" />
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-700 to-purple-500">
                    Kedisiplinan Siswa Modern
                </span>
            </h1>

            <p class="mt-5 text-sm sm:text-base text-gray-600 max-w-2xl leading-relaxed">
                Platform terpadu untuk pencatatan poin pelanggaran tata tertib, monitoring kedisiplinan, sesi konseling bimbingan siswa, serta pengiriman pemberitahuan otomatis ke orang tua/wali murid.
            </p>

            <!-- Tombol CTA -->
            <div class="mt-8 flex flex-col sm:flex-row items-center gap-3 w-full sm:w-auto">
                <a href="{{ route('login') }}" wire:navigate class="w-full sm:w-auto px-6 py-3 bg-purple-700 hover:bg-purple-800 text-white font-semibold text-sm rounded-xl shadow-md shadow-purple-500/25 transition transform hover:-translate-y-0.5">
                    Masuk ke Portal BK
                </a>
                <a href="{{ route('register') }}" wire:navigate class="w-full sm:w-auto px-6 py-3 bg-white hover:bg-purple-50/60 text-purple-700 border border-purple-200 font-semibold text-sm rounded-xl transition">
                    Pendaftaran Akun Siswa
                </a>
            </div>

            <!-- Fitur Unggulan Grid -->
            <div class="mt-16 grid grid-cols-1 md:grid-cols-3 gap-6 w-full text-left">
                <!-- Card 1 -->
                <div class="bg-white border border-purple-100/80 rounded-2xl p-6 shadow-sm hover:shadow-md transition">
                    <div class="w-10 h-10 rounded-xl bg-purple-100 text-purple-700 flex items-center justify-center mb-4">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <h3 class="font-bold text-gray-900 text-base">Pencatatan Poin Akurat</h3>
                    <p class="text-xs text-gray-500 mt-2 leading-relaxed">
                        Akumulasi poin otomatis dengan snapshot nilai pelanggaran yang transparan dan tidak dapat dimanipulasi.
                    </p>
                </div>

                <!-- Card 2 -->
                <div class="bg-white border border-purple-100/80 rounded-2xl p-6 shadow-sm hover:shadow-md transition">
                    <div class="w-10 h-10 rounded-xl bg-purple-100 text-purple-700 flex items-center justify-center mb-4">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    <h3 class="font-bold text-gray-900 text-base">Pembinaan Guru BK</h3>
                    <p class="text-xs text-gray-500 mt-2 leading-relaxed">
                        Manajemen sesi konseling, jadwal pertemuan bimbingan, dan rekam jejak tindak lanjut siswa binaan secara terstruktur.
                    </p>
                </div>

                <!-- Card 3 -->
                <div class="bg-white border border-purple-100/80 rounded-2xl p-6 shadow-sm hover:shadow-md transition">
                    <div class="w-10 h-10 rounded-xl bg-purple-100 text-purple-700 flex items-center justify-center mb-4">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                    </div>
                    <h3 class="font-bold text-gray-900 text-base">Notifikasi WhatsApp Sidobe</h3>
                    <p class="text-xs text-gray-500 mt-2 leading-relaxed">
                        Pemberitahuan otomatis terkirim langsung ke nomor WhatsApp orang tua/wali seketika pelanggaran dicatat.
                    </p>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="w-full border-t border-purple-100/80 bg-white/80 backdrop-blur-sm py-4 text-center text-xs text-gray-500">
            &copy; {{ date('Y') }} Sistem Informasi Bimbingan Konseling & Tata Tertib Siswa &bull; Tugas Akhir
        </footer>
    </body>
</html>
