<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-purple-100 text-purple-800 border border-purple-200">
                        Semester Genap &bull; TA 2026/2027
                    </span>
                    <span class="inline-flex items-center gap-1.5 text-xs text-emerald-600 font-semibold bg-emerald-50 px-2.5 py-0.5 rounded-full border border-emerald-200">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-ping"></span>
                        Sidobe WA Aktif
                    </span>
                </div>
                <h2 class="font-extrabold text-2xl sm:text-3xl text-gray-900 leading-tight tracking-tight mt-1.5">
                    Dashboard Bimbingan Konseling
                </h2>
                <p class="text-xs text-gray-500">
                    Pusat Monitoring Kedisiplinan Siswa, Pembinaan Karakter, dan Notifikasi Otomatis Orang Tua
                </p>
            </div>

            <!-- Jam & Tanggal Interaktif -->
            <div class="hidden sm:flex flex-col items-end text-right">
                <span class="text-xs font-bold text-gray-800 uppercase tracking-wider">
                    {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
                </span>
                <span class="text-[11px] text-purple-700 font-semibold mt-0.5">
                    Ruang Bimbingan Konseling Digital
                </span>
            </div>
        </div>
    </x-slot>

    <div class="space-y-7" x-data="{ 
        filterStatus: 'semua',
        searchQuery: '',
        showGuideModal: false
    }">

        <!-- 1. Banner Sambutan Premium (Gradien Ungu & Aksen Emas) -->
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-purple-900 via-purple-800 to-indigo-900 text-white p-7 sm:p-9 shadow-xl shadow-purple-950/15">
            <div class="relative z-10 max-w-2xl">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/15 backdrop-blur-md text-xs font-semibold text-purple-200 mb-4 border border-white/10">
                    <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                    <span>Hak Akses Terverifikasi: 
                        <strong class="uppercase tracking-wider text-white">
                            {{ auth()->user()->roles->first()->name ?? 'User' }}
                        </strong>
                    </span>
                </div>

                <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold tracking-tight leading-tight">
                    Selamat Datang, {{ auth()->user()->name }}! 👋
                </h1>
                
                <p class="mt-2.5 text-xs sm:text-sm text-purple-100/90 leading-relaxed">
                    Sistem ini terintegrasi penuh dengan <strong>WhatsApp API Sidobe</strong>. Setiap pencatatan pelanggaran siswa akan secara otomatis memicu pengiriman pesan pemberitahuan ke nomor WhatsApp orang tua/wali murid tanpa jeda.
                </p>

                <!-- Tombol Aksi Cepat di Banner -->
                <div class="mt-6 flex flex-wrap items-center gap-3">
                    @role('admin|guru_bk')
                        <a href="{{ route('pelanggaran.create') }}" wire:navigate class="inline-flex items-center gap-2 px-4 py-2.5 bg-white text-purple-900 hover:bg-purple-50 text-xs font-bold rounded-xl shadow-md transition transform hover:-translate-y-0.5">
                            <svg class="w-4 h-4 text-purple-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Input Pelanggaran Baru
                        </a>
                        <a href="{{ route('pelanggaran.index') }}" wire:navigate class="inline-flex items-center gap-2 px-4 py-2.5 bg-white/15 hover:bg-white/25 text-white border border-white/20 text-xs font-bold rounded-xl backdrop-blur-sm transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            Data Pelanggaran Siswa
                        </a>
                    @else
                        <a href="{{ route('pelanggaran.index') }}" wire:navigate class="inline-flex items-center gap-2 px-4 py-2.5 bg-white text-purple-900 hover:bg-purple-50 text-xs font-bold rounded-xl shadow-md transition">
                            <svg class="w-4 h-4 text-purple-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Lihat Catatan Poin Saya
                        </a>
                    @endrole
                </div>
            </div>

            <!-- Pola Hiasan Latar Banner -->
            <div class="absolute -right-16 -bottom-16 w-80 h-80 bg-purple-500/20 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -top-12 right-24 w-48 h-48 bg-indigo-500/20 rounded-full blur-2xl pointer-events-none"></div>
            <div class="absolute right-10 top-1/2 -translate-y-1/2 hidden xl:block opacity-15">
                <svg class="w-64 h-64 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
            </div>
        </div>

        <!-- 2. Grid 4 Kartu Statistik Interaktif -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
            <!-- Kartu 1: Total Pelanggaran -->
            <div class="bg-white/95 backdrop-blur-md border border-purple-100/90 rounded-2xl p-5 shadow-sm hover:shadow-lg hover:border-purple-300 transition duration-200">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Total Pelanggaran</span>
                    <div class="w-10 h-10 rounded-xl bg-purple-100 text-purple-700 flex items-center justify-center shadow-xs">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                </div>
                <div class="mt-4 flex items-baseline justify-between">
                    <div class="text-3xl font-extrabold text-gray-900">{{ \App\Models\Pelanggaran::count() }}</div>
                    <span class="text-[11px] font-semibold text-purple-700 bg-purple-50 px-2 py-0.5 rounded-md">Snapshot Poin</span>
                </div>
                <p class="text-xs text-gray-400 mt-1.5 flex items-center gap-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-purple-500"></span>
                    Tercatat dalam sistem sekolah
                </p>
            </div>

            <!-- Kartu 2: Siswa Status Kritis -->
            <div class="bg-white/95 backdrop-blur-md border border-rose-100 rounded-2xl p-5 shadow-sm hover:shadow-lg hover:border-rose-300 transition duration-200">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Siswa Kritis</span>
                    <div class="w-10 h-10 rounded-xl bg-rose-100 text-rose-600 flex items-center justify-center shadow-xs">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-4 flex items-baseline justify-between">
                    <div class="text-3xl font-extrabold text-rose-600">{{ \App\Models\Siswa::where('status_disiplin', 'kritis')->count() }}</div>
                    <span class="text-[11px] font-semibold text-rose-700 bg-rose-50 px-2 py-0.5 rounded-md">Poin &ge; 55</span>
                </div>
                <p class="text-xs text-rose-500/80 mt-1.5 flex items-center gap-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                    Perlu panggilan resmi ortu
                </p>
            </div>

            <!-- Kartu 3: Sesi Pembinaan Aktif -->
            <div class="bg-white/95 backdrop-blur-md border border-amber-100 rounded-2xl p-5 shadow-sm hover:shadow-lg hover:border-amber-300 transition duration-200">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Sesi Pembinaan</span>
                    <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center shadow-xs">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-4 flex items-baseline justify-between">
                    <div class="text-3xl font-extrabold text-gray-900">{{ \App\Models\Pembinaan::where('status', '!=', 'selesai')->count() }}</div>
                    <span class="text-[11px] font-semibold text-amber-800 bg-amber-50 px-2 py-0.5 rounded-md">Kasus Aktif</span>
                </div>
                <p class="text-xs text-gray-400 mt-1.5 flex items-center gap-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                    Pendampingan konselor BK
                </p>
            </div>

            <!-- Kartu 4: Notifikasi WhatsApp Sidobe -->
            <div class="bg-white/95 backdrop-blur-md border border-emerald-100 rounded-2xl p-5 shadow-sm hover:shadow-lg hover:border-emerald-300 transition duration-200">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Gateway Sidobe</span>
                    <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center shadow-xs">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-4 flex items-baseline justify-between">
                    <div class="text-2xl font-extrabold text-emerald-600">{{ \App\Models\NotifikasiWa::count() }} Pesan</div>
                    <span class="text-[11px] font-semibold text-emerald-800 bg-emerald-50 px-2 py-0.5 rounded-md">Queue Driver</span>
                </div>
                <p class="text-xs text-emerald-700/80 mt-1.5 flex items-center gap-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    Otomatis terkirim ke no. ortu
                </p>
            </div>
                    <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Sesi Pembinaan</span>
                    <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center shadow-xs">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-4 flex items-baseline justify-between">
                    <div class="text-3xl font-extrabold text-gray-900">0</div>
                    <span class="text-[11px] font-semibold text-amber-800 bg-amber-50 px-2 py-0.5 rounded-md">Kasus Aktif</span>
                </div>
                <p class="text-xs text-gray-400 mt-1.5 flex items-center gap-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                    Pendampingan konselor BK
                </p>
            </div>

            <!-- Kartu 4: Notifikasi WhatsApp Sidobe -->
            <div class="bg-white/95 backdrop-blur-md border border-emerald-100 rounded-2xl p-5 shadow-sm hover:shadow-lg hover:border-emerald-300 transition duration-200">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Gateway Sidobe</span>
                    <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center shadow-xs">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-4 flex items-baseline justify-between">
                    <div class="text-2xl font-extrabold text-emerald-600">Siap Kirim</div>
                    <span class="text-[11px] font-semibold text-emerald-800 bg-emerald-50 px-2 py-0.5 rounded-md">Queue Driver</span>
                </div>
                <p class="text-xs text-emerald-700/80 mt-1.5 flex items-center gap-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    Otomatis terkirim ke no. ortu
                </p>
            </div>
        </div>

        <!-- 3. Bagian Interaktif: Indikator Batas Kedisiplinan & Tab Filter -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Kolom 1 & 2: Panduan Lengkap Kategori Status Kedisiplinan -->
            <div class="lg:col-span-2 bg-white/95 backdrop-blur-md border border-purple-100/90 rounded-3xl p-6 sm:p-7 shadow-sm">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-5 border-b border-gray-100 gap-3">
                    <div>
                        <span class="text-xs font-bold text-purple-700 uppercase tracking-wider">Standar Tata Tertib</span>
                        <h3 class="text-lg font-extrabold text-gray-900 mt-0.5">Klasifikasi Status Kedisiplinan Siswa</h3>
                    </div>
                    
                    <!-- Filter Tombol Tab Interaktif -->
                    <div class="inline-flex p-1 bg-purple-50/70 rounded-xl border border-purple-100 text-xs">
                        <button @click="filterStatus = 'semua'" :class="filterStatus === 'semua' ? 'bg-white font-bold text-purple-800 shadow-xs' : 'text-gray-500 hover:text-purple-700'" class="px-2.5 py-1 rounded-lg transition">Semua</button>
                        <button @click="filterStatus = 'aman'" :class="filterStatus === 'aman' ? 'bg-emerald-600 text-white font-bold shadow-xs' : 'text-gray-500 hover:text-emerald-700'" class="px-2.5 py-1 rounded-lg transition">Aman</button>
                        <button @click="filterStatus = 'perhatian'" :class="filterStatus === 'perhatian' ? 'bg-amber-500 text-white font-bold shadow-xs' : 'text-gray-500 hover:text-amber-700'" class="px-2.5 py-1 rounded-lg transition">Perhatian</button>
                        <button @click="filterStatus = 'pembinaan'" :class="filterStatus === 'pembinaan' ? 'bg-orange-500 text-white font-bold shadow-xs' : 'text-gray-500 hover:text-orange-700'" class="px-2.5 py-1 rounded-lg transition">Pembinaan</button>
                        <button @click="filterStatus = 'kritis'" :class="filterStatus === 'kritis' ? 'bg-rose-600 text-white font-bold shadow-xs' : 'text-gray-500 hover:text-rose-700'" class="px-2.5 py-1 rounded-lg transition">Kritis</button>
                    </div>
                </div>

                <div class="mt-5 grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Status 1: Aman -->
                    <div x-show="filterStatus === 'semua' || filterStatus === 'aman'" x-transition class="p-4 rounded-2xl border border-emerald-100 bg-emerald-50/40 hover:bg-emerald-50/70 transition">
                        <div class="flex items-center justify-between mb-2">
                            <x-status-badge status="aman" />
                            <span class="text-xs font-bold text-emerald-800 bg-emerald-100/80 px-2 py-0.5 rounded-md">0 - 24 Poin</span>
                        </div>
                        <h4 class="font-bold text-sm text-gray-900 mt-2">Disiplin Positif</h4>
                        <p class="text-xs text-gray-600 mt-1 leading-relaxed">
                            Siswa tidak memiliki pelanggaran tata tertib serius. Memperoleh hak penuh dalam kegiatan intrakurikuler dan ekstrakurikuler.
                        </p>
                    </div>

                    <!-- Status 2: Perhatian -->
                    <div x-show="filterStatus === 'semua' || filterStatus === 'perhatian'" x-transition class="p-4 rounded-2xl border border-amber-100 bg-amber-50/40 hover:bg-amber-50/70 transition">
                        <div class="flex items-center justify-between mb-2">
                            <x-status-badge status="perhatian" />
                            <span class="text-xs font-bold text-amber-800 bg-amber-100/80 px-2 py-0.5 rounded-md">25 - 39 Poin</span>
                        </div>
                        <h4 class="font-bold text-sm text-gray-900 mt-2">Peringatan Lisan</h4>
                        <p class="text-xs text-gray-600 mt-1 leading-relaxed">
                            Mendapatkan bimbingan awal dari Wali Kelas dan Guru BK untuk mengevaluasi komitmen tata tertib di lingkungan sekolah.
                        </p>
                    </div>

                    <!-- Status 3: Pembinaan -->
                    <div x-show="filterStatus === 'semua' || filterStatus === 'pembinaan'" x-transition class="p-4 rounded-2xl border border-orange-100 bg-orange-50/40 hover:bg-orange-50/70 transition">
                        <div class="flex items-center justify-between mb-2">
                            <x-status-badge status="pembinaan" />
                            <span class="text-xs font-bold text-orange-800 bg-orange-100/80 px-2 py-0.5 rounded-md">40 - 54 Poin</span>
                        </div>
                        <h4 class="font-bold text-sm text-gray-900 mt-2">Siswa Binaan Khusus</h4>
                        <p class="text-xs text-gray-600 mt-1 leading-relaxed">
                            Wajib mengikuti jadwal konseling berkala, penandatanganan surat perjanjian siswa, dan sanksi edukatif yang mendidik.
                        </p>
                    </div>

                    <!-- Status 4: Kritis -->
                    <div x-show="filterStatus === 'semua' || filterStatus === 'kritis'" x-transition class="p-4 rounded-2xl border border-rose-100 bg-rose-50/40 hover:bg-rose-50/70 transition">
                        <div class="flex items-center justify-between mb-2">
                            <x-status-badge status="kritis" />
                            <span class="text-xs font-bold text-rose-800 bg-rose-100/80 px-2 py-0.5 rounded-md">&ge; 55 Poin</span>
                        </div>
                        <h4 class="font-bold text-sm text-gray-900 mt-2">Panggilan Orang Tua</h4>
                        <p class="text-xs text-gray-600 mt-1 leading-relaxed">
                            Kasus diteruskan ke Kepala Sekolah. Surat pemanggilan resmi dikirim ke rumah dan orang tua wajib hadir untuk penanganan terpadu.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Kolom 3: Layanan Cepat & Prosedur Pengaduan -->
            <div class="bg-white/95 backdrop-blur-md border border-purple-100/90 rounded-3xl p-6 sm:p-7 shadow-sm flex flex-col justify-between">
                <div>
                    <span class="text-xs font-bold text-purple-700 uppercase tracking-wider">Layanan BK</span>
                    <h3 class="text-base font-extrabold text-gray-900 pb-3 border-b border-gray-100 mt-0.5">
                        Alur Bimbingan & Konseling
                    </h3>

                    <div class="mt-4 space-y-3.5">
                        <!-- Langkah 1 -->
                        <div class="flex items-start gap-3 p-3.5 bg-purple-50/60 rounded-2xl border border-purple-100">
                            <div class="w-8 h-8 rounded-xl bg-purple-700 text-white flex items-center justify-center font-bold text-xs flex-shrink-0 shadow-xs">
                                1
                            </div>
                            <div class="text-xs">
                                <span class="font-bold text-gray-900 block">Pencatatan & Notifikasi</span>
                                <span class="text-gray-500">Pelanggaran dicatat dan orang tua menerima pesan WA otomatis dari sistem.</span>
                            </div>
                        </div>

                        <!-- Langkah 2 -->
                        <div class="flex items-start gap-3 p-3.5 bg-slate-50 rounded-2xl border border-gray-200/70">
                            <div class="w-8 h-8 rounded-xl bg-purple-600 text-white flex items-center justify-center font-bold text-xs flex-shrink-0 shadow-xs">
                                2
                            </div>
                            <div class="text-xs">
                                <span class="font-bold text-gray-900 block">Konseling Pribadi</span>
                                <span class="text-gray-500">Guru BK memanggil siswa untuk sesi pendampingan dan mencari solusi atas kendala sikap.</span>
                            </div>
                        </div>

                        <!-- Langkah 3 -->
                        <div class="flex items-start gap-3 p-3.5 bg-slate-50 rounded-2xl border border-gray-200/70">
                            <div class="w-8 h-8 rounded-xl bg-emerald-600 text-white flex items-center justify-center font-bold text-xs flex-shrink-0 shadow-xs">
                                3
                            </div>
                            <div class="text-xs">
                                <span class="font-bold text-gray-900 block">Tindak Lanjut & Evaluasi</span>
                                <span class="text-gray-500">Mencatat komitmen perubahan perilaku siswa hingga kasus dinyatakan selesai.</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-6 pt-4 border-t border-gray-100 flex items-center justify-between text-[11px] text-gray-400">
                    <span>Sistem BK &bull; v1.0</span>
                    <span class="text-purple-700 font-semibold">Tugas Akhir Farrel & Hafiz</span>
                </div>
            </div>
        </div>

        <!-- Modal Panduan Tata Tertib (Interaktif Alpine.js) -->
        <div x-show="showGuideModal" 
             x-transition:enter="transition ease-out duration-200" 
             x-transition:enter-start="opacity-0" 
             x-transition:leave="transition ease-in duration-150" 
             x-transition:leave-end="opacity-0" 
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-xs"
             style="display: none;">
            <div @click.away="showGuideModal = false" class="bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl border border-purple-100">
                <div class="flex items-center justify-between pb-4 border-b border-gray-100">
                    <h3 class="font-extrabold text-base text-gray-900">Buku Saku Tata Tertib Siswa</h3>
                    <button @click="showGuideModal = false" class="text-gray-400 hover:text-gray-700 text-lg font-bold">&times;</button>
                </div>
                <div class="py-4 text-xs text-gray-600 space-y-3">
                    <p>Tata tertib sekolah dirancang bukan semata untuk menghukum, melainkan untuk membina kedisiplinan dan rasa tanggung jawab siswa.</p>
                    <div class="p-3 bg-purple-50 rounded-xl border border-purple-100 text-purple-800">
                        <strong>Kategori Pelanggaran:</strong>
                        <ul class="list-disc list-inside mt-1 space-y-1">
                            <li>Ringan: Keterlambatan, ketidakrapian seragam (5 - 10 poin).</li>
                            <li>Sedang: Membolos jam pelajaran, meninggalkan sekolah (15 - 25 poin).</li>
                            <li>Berat: Perundungan, perkelahian, merusak fasilitas (30 - 55+ poin).</li>
                        </ul>
                    </div>
                </div>
                <div class="pt-3 border-t border-gray-100 text-right">
                    <button @click="showGuideModal = false" class="px-4 py-2 bg-purple-700 hover:bg-purple-800 text-white font-bold text-xs rounded-xl shadow-xs">
                        Mengerti
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
