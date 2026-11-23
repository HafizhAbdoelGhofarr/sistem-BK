<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Sistem BK') }} - Layanan Bimbingan Konseling & Kedisiplinan</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased bg-gradient-to-br from-purple-50/70 via-white to-purple-100/50 min-h-screen flex flex-col justify-between">
        
        <!-- Header Ringkas -->
        <header class="py-6 px-4 sm:px-8 flex justify-between items-center max-w-6xl mx-auto w-full">
            <a href="/" wire:navigate class="transition transform hover:scale-[1.02]">
                <x-application-logo />
            </a>
            <div class="text-xs text-gray-500 font-medium hidden sm:block">
                Pusat Tata Tertib & Bimbingan Konseling Sekolah
            </div>
        </header>

        <!-- Main Card Container -->
        <main class="flex-1 flex flex-col justify-center items-center px-4 py-8">
            <div class="w-full sm:max-w-md bg-white border border-purple-100/80 shadow-xl shadow-purple-900/5 rounded-2xl p-6 sm:p-8 transition-all">
                {{ $slot }}
            </div>

            <!-- Footer Bantuan -->
            <div class="mt-6 text-center text-xs text-gray-500">
                &copy; {{ date('Y') }} Sistem Informasi BK. Hak Cipta Dilindungi.
            </div>
        </main>

        <div class="h-4"></div>
    </body>
</html>
