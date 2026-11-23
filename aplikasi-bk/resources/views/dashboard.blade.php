<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-900 leading-tight tracking-tight">
                    {{ __('Dashboard Sistem BK') }}
                </h2>
                <p class="text-xs text-gray-500 mt-1">
                    Pusat Informasi & Rekapitulasi Tata Tertib Kedisiplinan Siswa
                </p>
            </div>
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-purple-100 text-purple-800 border border-purple-200">
                    <span class="w-2 h-2 rounded-full bg-purple-600 me-1.5 animate-pulse"></span>
                    Tahun Ajaran Aktif
                </span>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">
        <!-- 1. Banner Sambutan Pengguna -->
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-purple-800 via-purple-700 to-purple-600 text-white p-6 sm:p-8 shadow-lg shadow-purple-900/10">
            <div class="relative z-10 max-w-2xl">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/15 backdrop-blur-sm text-xs font-medium text-purple-100 mb-3">
                    <svg class="w-4 h-4 text-purple-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Masuk sebagai: <strong>{{ ucfirst(auth()->user()->roles->first()->name ?? 'Pengguna') }}</strong></span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">
                    Selamat Datang, {{ auth()->user()->name }}! 👋
                </h1>
                <p class="mt-2 text-sm text-purple-100/90 leading-relaxed">
                    Sistem ini terintegrasi dengan WhatsApp Gateway Sidobe untuk notifikasi real-time kedisiplinan siswa kepada orang tua/wali.
                </p>
            </div>

            <!-- Hiasan Geometris di Banner -->
            <div class="absolute -right-12 -bottom-12 w-64 h-64 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>
            <div class="absolute right-8 top-1/2 -translate-y-1/2 hidden lg:block opacity-20">
                <svg class="w-40 h-40 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
            </div>
        </div>

        <!-- 2. Grid Kartu Ringkasan Statistik -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
            <!-- Kartu 1: Pelanggaran Bulan Ini -->
            <div class="bg-white border border-gray-100 rounded-2xl p-5 shadow-sm hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Pelanggaran</span>
                    <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-700 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="text-2xl font-bold text-gray-900">0</div>
                    <p class="text-xs text-gray-400 mt-1">Riwayat pelanggaran tercatat</p>
                </div>
            </div>

            <!-- Kartu 2: Siswa Status Kritis -->
            <div class="bg-white border border-gray-100 rounded-2xl p-5 shadow-sm hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Siswa Kritis</span>
                    <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="text-2xl font-bold text-rose-600">0</div>
                    <p class="text-xs text-rose-500/80 mt-1">Poin &ge; 55 (Perlu Panggilan Ortu)</p>
                </div>
            </div>

            <!-- Kartu 3: Sesi Pembinaan Aktif -->
            <div class="bg-white border border-gray-100 rounded-2xl p-5 shadow-sm hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Sesi Bimbingan</span>
                    <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="text-2xl font-bold text-gray-900">0</div>
                    <p class="text-xs text-gray-400 mt-1">Dalam proses pendampingan BK</p>
                </div>
            </div>

            <!-- Kartu 4: Notifikasi WhatsApp Sidobe -->
            <div class="bg-white border border-gray-100 rounded-2xl p-5 shadow-sm hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">WhatsApp Sidobe</span>
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="text-2xl font-bold text-emerald-600">Siap</div>
                    <p class="text-xs text-gray-400 mt-1">Antrean gateway otomatis aktif</p>
                </div>
            </div>
        </div>

        <!-- 3. Grid Informasi & Panduan Tingkat Kedisiplinan -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Kolom 1 & 2: Panduan Kategori Status Kedisiplinan Siswa -->
            <div class="lg:col-span-2 bg-white border border-gray-100 rounded-2xl p-6 shadow-sm">
                <div class="flex items-center justify-between pb-4 border-b border-gray-100">
                    <div>
                        <h3 class="text-base font-bold text-gray-900">Indikator Batas Status Kedisiplinan</h3>
                        <p class="text-xs text-gray-500 mt-0.5">Aturan pembagian tingkat sanksi berdasarkan akumulasi poin siswa</p>
                    </div>
                    <span class="text-xs font-medium text-purple-700 bg-purple-50 px-2.5 py-1 rounded-lg">Standar Tata Tertib</span>
                </div>

                <div class="mt-5 grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                    <!-- Status 1: Aman -->
                    <div class="p-4 rounded-xl border border-emerald-100 bg-emerald-50/40">
                        <div class="flex items-center justify-between mb-2">
                            <x-status-badge status="aman" />
                            <span class="text-xs font-bold text-emerald-800">0 - 24 Poin</span>
                        </div>
                        <p class="text-xs text-gray-600 leading-relaxed">
                            Siswa berkategori disiplin dan tidak memiliki kendala tata tertib serius di sekolah.
                        </p>
                    </div>

                    <!-- Status 2: Perhatian -->
                    <div class="p-4 rounded-xl border border-amber-100 bg-amber-50/40">
                        <div class="flex items-center justify-between mb-2">
                            <x-status-badge status="perhatian" />
                            <span class="text-xs font-bold text-amber-800">25 - 39 Poin</span>
                        </div>
                        <p class="text-xs text-gray-600 leading-relaxed">
                            Mendapatkan peringatan lisan dari Wali Kelas atau Guru BK untuk menjaga sikap disiplin.
                        </p>
                    </div>

                    <!-- Status 3: Pembinaan -->
                    <div class="p-4 rounded-xl border border-orange-100 bg-orange-50/40">
                        <div class="flex items-center justify-between mb-2">
                            <x-status-badge status="pembinaan" />
                            <span class="text-xs font-bold text-orange-800">40 - 54 Poin</span>
                        </div>
                        <p class="text-xs text-gray-600 leading-relaxed">
                            Wajib mengikuti sesi bimbingan konseling dan membuat surat perjanjian siswa binaan.
                        </p>
                    </div>

                    <!-- Status 4: Kritis -->
                    <div class="p-4 rounded-xl border border-rose-100 bg-rose-50/40">
                        <div class="flex items-center justify-between mb-2">
                            <x-status-badge status="kritis" />
                            <span class="text-xs font-bold text-rose-800">&ge; 55 Poin</span>
                        </div>
                        <p class="text-xs text-gray-600 leading-relaxed">
                            Status kritis; pemanggilan resmi orang tua/wali murid ke sekolah dan evaluasi status siswa.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Kolom 3: Navigasi Cepat & Informasi Bantuan -->
            <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm flex flex-col justify-between">
                <div>
                    <h3 class="text-base font-bold text-gray-900 pb-3 border-b border-gray-100">
                        Informasi Bimbingan Konseling
                    </h3>
                    <div class="mt-4 space-y-3">
                        <div class="flex items-start gap-3 p-3 bg-purple-50/60 rounded-xl border border-purple-100/80">
                            <div class="w-8 h-8 rounded-lg bg-purple-700 text-white flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="text-xs">
                                <span class="font-bold text-gray-900 block">Ruang BK Ramah Siswa</span>
                                <span class="text-gray-500">Konsultasi pribadi dan pembinaan dapat dijadwalkan langsung bersama Guru BK.</span>
                            </div>
                        </div>

                        <div class="flex items-start gap-3 p-3 bg-slate-50 rounded-xl border border-gray-200/60">
                            <div class="w-8 h-8 rounded-lg bg-emerald-600 text-white flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <div class="text-xs">
                                <span class="font-bold text-gray-900 block">Pemberitahuan Otomatis</span>
                                <span class="text-gray-500">Setiap catatan pelanggaran dikirim otomatis ke WhatsApp wali murid.</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-6 pt-4 border-t border-gray-100 text-center">
                    <span class="text-[11px] text-gray-400">
                        Sistem Informasi BK &bull; Versi 1.0 (Tema Putih & Ungu)
                    </span>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
