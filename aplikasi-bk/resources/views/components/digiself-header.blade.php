@props(['active' => 'beranda', 'title' => '', 'description' => ''])

<div class="space-y-4">
    <!-- Breadcrumb & Top Title -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 pb-2 border-b border-purple-100">
        <div>
            <div class="flex items-center gap-2 text-xs text-gray-500 mb-1">
                <a href="{{ route('dashboard') }}" wire:navigate class="hover:text-purple-700 transition">Dashboard</a>
                <span>&rsaquo;</span>
                <a href="{{ route('digiself.beranda') }}" wire:navigate class="hover:text-purple-700 transition">DIGISELF</a>
                <span>&rsaquo;</span>
                <span class="text-purple-700 font-semibold">{{ $title }}</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight flex items-center gap-2.5">
                <span>{{ $title }}</span>
                <span class="text-xs font-bold uppercase tracking-wider px-2.5 py-1 rounded-full bg-purple-100 text-purple-700 border border-purple-200">
                    DIGISELF Modul
                </span>
            </h1>
            @if($description)
                <p class="text-xs text-gray-500 mt-1">{{ $description }}</p>
            @endif
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('digiself.beranda') }}" wire:navigate class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold bg-purple-50 text-purple-700 hover:bg-purple-100 transition border border-purple-100">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                </svg>
                Alur DIGISELF
            </a>
            <a href="{{ route('digiself.bantuan') }}" wire:navigate class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold bg-amber-50 text-amber-700 hover:bg-amber-100 transition border border-amber-200">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                Konseling BK
            </a>
        </div>
    </div>

    <!-- Subnav Tabs (8 Menu Sesuai Proposal) -->
    <div class="overflow-x-auto pb-1 scrollbar-none">
        <nav class="inline-flex p-1 bg-white/90 backdrop-blur-md rounded-2xl border border-purple-100/90 shadow-xs gap-1 min-w-full sm:min-w-0">
            <!-- 1. Beranda -->
            <a href="{{ route('digiself.beranda') }}" wire:navigate
               class="px-3.5 py-2 rounded-xl text-xs font-bold whitespace-nowrap transition flex items-center gap-1.5 {{ $active === 'beranda' ? 'bg-gradient-to-r from-purple-700 to-purple-600 text-white shadow-sm' : 'text-gray-600 hover:text-purple-700 hover:bg-purple-50/70' }}">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                Beranda
            </a>

            <!-- 2. Literasi Digital -->
            <a href="{{ route('digiself.literasi') }}" wire:navigate
               class="px-3.5 py-2 rounded-xl text-xs font-bold whitespace-nowrap transition flex items-center gap-1.5 {{ $active === 'literasi' ? 'bg-gradient-to-r from-purple-700 to-purple-600 text-white shadow-sm' : 'text-gray-600 hover:text-purple-700 hover:bg-purple-50/70' }}">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
                Literasi Digital
            </a>

            <!-- 3. Refleksi Kebiasaan Digital -->
            <a href="{{ route('digiself.refleksi') }}" wire:navigate
               class="px-3.5 py-2 rounded-xl text-xs font-bold whitespace-nowrap transition flex items-center gap-1.5 {{ $active === 'refleksi' ? 'bg-gradient-to-r from-purple-700 to-purple-600 text-white shadow-sm' : 'text-gray-600 hover:text-purple-700 hover:bg-purple-50/70' }}">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Refleksi Kebiasaan
            </a>

            <!-- 4. Regulasi Diri -->
            <a href="{{ route('digiself.regulasi') }}" wire:navigate
               class="px-3.5 py-2 rounded-xl text-xs font-bold whitespace-nowrap transition flex items-center gap-1.5 {{ $active === 'regulasi' ? 'bg-gradient-to-r from-purple-700 to-purple-600 text-white shadow-sm' : 'text-gray-600 hover:text-purple-700 hover:bg-purple-50/70' }}">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
                Regulasi Diri
            </a>

            <!-- 5. Rencana Aksi -->
            <a href="{{ route('digiself.rencana-aksi') }}" wire:navigate
               class="px-3.5 py-2 rounded-xl text-xs font-bold whitespace-nowrap transition flex items-center gap-1.5 {{ $active === 'rencana-aksi' ? 'bg-gradient-to-r from-purple-700 to-purple-600 text-white shadow-sm' : 'text-gray-600 hover:text-purple-700 hover:bg-purple-50/70' }}">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                </svg>
                Rencana Aksi
            </a>

            <!-- 6. Jurnal dan Evaluasi Diri -->
            <a href="{{ route('digiself.jurnal') }}" wire:navigate
               class="px-3.5 py-2 rounded-xl text-xs font-bold whitespace-nowrap transition flex items-center gap-1.5 {{ $active === 'jurnal' ? 'bg-gradient-to-r from-purple-700 to-purple-600 text-white shadow-sm' : 'text-gray-600 hover:text-purple-700 hover:bg-purple-50/70' }}">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Jurnal & Evaluasi
            </a>

            <!-- 7. Bantuan BK -->
            <a href="{{ route('digiself.bantuan') }}" wire:navigate
               class="px-3.5 py-2 rounded-xl text-xs font-bold whitespace-nowrap transition flex items-center gap-1.5 {{ $active === 'bantuan' ? 'bg-gradient-to-r from-purple-700 to-purple-600 text-white shadow-sm' : 'text-gray-600 hover:text-purple-700 hover:bg-purple-50/70' }}">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                Bantuan BK
            </a>

            <!-- 8. Tentang dan Privasi -->
            <a href="{{ route('digiself.tentang') }}" wire:navigate
               class="px-3.5 py-2 rounded-xl text-xs font-bold whitespace-nowrap transition flex items-center gap-1.5 {{ $active === 'tentang' ? 'bg-gradient-to-r from-purple-700 to-purple-600 text-white shadow-sm' : 'text-gray-600 hover:text-purple-700 hover:bg-purple-50/70' }}">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Tentang & Privasi
            </a>
        </nav>
    </div>
</div>
