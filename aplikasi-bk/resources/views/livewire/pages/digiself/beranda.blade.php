<?php

use App\Models\DigiselfRefleksi;
use App\Models\DigiselfRencanaAksi;
use App\Models\DigiselfJurnalEvaluasi;
use App\Models\DigiselfBantuanBk;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component
{
    public function with(): array
    {
        $userId = auth()->id();
        return [
            'totalRefleksi' => DigiselfRefleksi::where('user_id', $userId)->count(),
            'rencanaAktif' => DigiselfRencanaAksi::where('user_id', $userId)->where('status', 'dalam_proses')->count(),
            'rencanaTercapai' => DigiselfRencanaAksi::where('user_id', $userId)->where('status', 'tercapai')->count(),
            'totalJurnal' => DigiselfJurnalEvaluasi::where('user_id', $userId)->count(),
            'totalBantuan' => DigiselfBantuanBk::where('user_id', $userId)->count(),
        ];
    }
}; ?>

<div class="space-y-8">
    <!-- Header Navigasi Submodul -->
    <x-digiself-header 
        active="beranda" 
        title="Beranda DIGISELF" 
        description="Media Digital Bimbingan dan Konseling untuk Penguatan Literasi Digital dan Regulasi Mahasiswa / Siswa" 
    />

    <!-- Hero Banner Utama DIGISELF -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-purple-900 via-purple-800 to-indigo-900 text-white p-7 sm:p-10 shadow-xl shadow-purple-950/15">
        <div class="absolute -right-16 -top-16 w-64 h-64 bg-purple-500/20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute right-1/4 -bottom-16 w-56 h-56 bg-indigo-500/20 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 max-w-3xl">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/15 backdrop-blur-md text-xs font-semibold text-purple-200 mb-4 border border-white/10">
                <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                <span>DIGISELF &bull; Digital Self-Development for Students</span>
            </div>

            <h2 class="text-2xl sm:text-4xl font-extrabold tracking-tight leading-tight">
                Kembangkan Literasi Digital & Kendalikan Kebiasaan Teknologi Anda
            </h2>
            
            <p class="mt-3 text-xs sm:text-sm text-purple-100/90 leading-relaxed">
                DIGISELF dirancang secara sistematis untuk mendampingi mahasiswa/siswa dalam mengenali kebiasaan penggunaan gawai, memilah informasi digital secara kritis, mengendalikan distraksi layar, dan menyusun rencana aksi regulasi diri yang terukur.
            </p>

            <div class="mt-6 flex flex-wrap items-center gap-3">
                <a href="{{ route('digiself.literasi') }}" wire:navigate class="inline-flex items-center gap-2 px-5 py-2.5 bg-white text-purple-900 hover:bg-purple-50 text-xs font-bold rounded-xl shadow-md transition transform hover:-translate-y-0.5">
                    <svg class="w-4 h-4 text-purple-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    Mulai Belajar: Literasi Digital
                </a>
                <a href="{{ route('digiself.refleksi') }}" wire:navigate class="inline-flex items-center gap-2 px-5 py-2.5 bg-white/20 hover:bg-white/30 text-white border border-white/25 text-xs font-bold rounded-xl backdrop-blur-sm transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Isi Refleksi Diri
                </a>
            </div>
        </div>
    </div>

    <!-- Ringkasan Progres Regulasi Diri Siswa -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="bg-white/95 backdrop-blur-md border border-purple-100 rounded-3xl p-5 shadow-xs flex flex-col justify-between">
            <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Refleksi Diisi</span>
            <div class="mt-2 flex items-baseline justify-between">
                <span class="text-3xl font-extrabold text-purple-700">{{ $totalRefleksi }}</span>
                <span class="text-[11px] font-semibold text-purple-700 bg-purple-50 px-2 py-0.5 rounded-lg">Catatan</span>
            </div>
            <p class="text-[11px] text-gray-400 mt-2">Pola kebiasaan teknologi yang telah dicatat</p>
        </div>

        <div class="bg-white/95 backdrop-blur-md border border-indigo-100 rounded-3xl p-5 shadow-xs flex flex-col justify-between">
            <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Rencana Aktif</span>
            <div class="mt-2 flex items-baseline justify-between">
                <span class="text-3xl font-extrabold text-indigo-700">{{ $rencanaAktif }}</span>
                <span class="text-[11px] font-semibold text-indigo-700 bg-indigo-50 px-2 py-0.5 rounded-lg">Target</span>
            </div>
            <p class="text-[11px] text-gray-400 mt-2">Komitmen aksi yang sedang dijalankan</p>
        </div>

        <div class="bg-white/95 backdrop-blur-md border border-emerald-100 rounded-3xl p-5 shadow-xs flex flex-col justify-between">
            <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Target Tercapai</span>
            <div class="mt-2 flex items-baseline justify-between">
                <span class="text-3xl font-extrabold text-emerald-600">{{ $rencanaTercapai }}</span>
                <span class="text-[11px] font-semibold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-lg">Berhasil</span>
            </div>
            <p class="text-[11px] text-gray-400 mt-2">Kebiasaan positif yang sukses dibangun</p>
        </div>

        <div class="bg-white/95 backdrop-blur-md border border-amber-100 rounded-3xl p-5 shadow-xs flex flex-col justify-between">
            <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Log Jurnal</span>
            <div class="mt-2 flex items-baseline justify-between">
                <span class="text-3xl font-extrabold text-amber-600">{{ $totalJurnal }}</span>
                <span class="text-[11px] font-semibold text-amber-700 bg-amber-50 px-2 py-0.5 rounded-lg">Evaluasi</span>
            </div>
            <p class="text-[11px] text-gray-400 mt-2">Jurnal pemantauan diri harian/mingguan</p>
        </div>
    </div>

    <!-- Flowchart Alur Penggunaan Sesuai Proposal (Lampiran 1) -->
    <div class="bg-white/95 backdrop-blur-md border border-purple-100/90 rounded-3xl p-6 sm:p-8 shadow-sm space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-gray-100 pb-4">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-purple-700">Lampiran 1 Proposal</span>
                <h3 class="text-xl font-extrabold text-gray-900">Alur Penggunaan Media DIGISELF</h3>
                <p class="text-xs text-gray-500 mt-0.5">Langkah bertahap dari pemahaman edukasi, refleksi diri, hingga evaluasi perkembangan</p>
            </div>
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-purple-50 text-purple-700 border border-purple-100">
                Model ADDIE
            </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-3">
            <!-- Tahap 1: Beranda -->
            <div class="p-4 rounded-2xl bg-purple-50/70 border border-purple-100 flex flex-col justify-between relative group hover:border-purple-300 transition">
                <div>
                    <div class="w-7 h-7 rounded-lg bg-purple-700 text-white font-extrabold text-xs flex items-center justify-center mb-2">1</div>
                    <h4 class="font-bold text-xs text-gray-900">Beranda</h4>
                    <p class="text-[11px] text-gray-500 mt-1 leading-relaxed">Membaca pengenalan, tujuan, dan petunjuk penggunaan DIGISELF.</p>
                </div>
                <div class="mt-3 pt-2 border-t border-purple-100/80">
                    <span class="text-[10px] font-bold text-purple-700">Titik Mulai</span>
                </div>
            </div>

            <!-- Tahap 2: Literasi Digital -->
            <a href="{{ route('digiself.literasi') }}" wire:navigate class="p-4 rounded-2xl bg-white border border-gray-100 hover:border-purple-300 hover:shadow-xs transition flex flex-col justify-between">
                <div>
                    <div class="w-7 h-7 rounded-lg bg-indigo-600 text-white font-extrabold text-xs flex items-center justify-center mb-2">2</div>
                    <h4 class="font-bold text-xs text-gray-900">Literasi Digital</h4>
                    <p class="text-[11px] text-gray-500 mt-1 leading-relaxed">Mempelajari keamanan, privasi, evaluasi info, dan etika komunikasi.</p>
                </div>
                <div class="mt-3 pt-2 border-t border-gray-100">
                    <span class="text-[10px] font-bold text-indigo-600">Pelajari &rarr;</span>
                </div>
            </a>

            <!-- Tahap 3: Refleksi Kebiasaan -->
            <a href="{{ route('digiself.refleksi') }}" wire:navigate class="p-4 rounded-2xl bg-white border border-gray-100 hover:border-purple-300 hover:shadow-xs transition flex flex-col justify-between">
                <div>
                    <div class="w-7 h-7 rounded-lg bg-blue-600 text-white font-extrabold text-xs flex items-center justify-center mb-2">3</div>
                    <h4 class="font-bold text-xs text-gray-900">Refleksi Kebiasaan</h4>
                    <p class="text-[11px] text-gray-500 mt-1 leading-relaxed">Mengenali pola pemakaian gawai, pemicu emosi, dan potensi distraksi.</p>
                </div>
                <div class="mt-3 pt-2 border-t border-gray-100">
                    <span class="text-[10px] font-bold text-blue-600">Isi Form &rarr;</span>
                </div>
            </a>

            <!-- Tahap 4: Regulasi Diri -->
            <a href="{{ route('digiself.regulasi') }}" wire:navigate class="p-4 rounded-2xl bg-white border border-gray-100 hover:border-purple-300 hover:shadow-xs transition flex flex-col justify-between">
                <div>
                    <div class="w-7 h-7 rounded-lg bg-emerald-600 text-white font-extrabold text-xs flex items-center justify-center mb-2">4</div>
                    <h4 class="font-bold text-xs text-gray-900">Regulasi Diri</h4>
                    <p class="text-[11px] text-gray-500 mt-1 leading-relaxed">Memilih strategi pengelolaan waktu, fokus, dan pengendalian distraksi.</p>
                </div>
                <div class="mt-3 pt-2 border-t border-gray-100">
                    <span class="text-[10px] font-bold text-emerald-600">Pilih Trik &rarr;</span>
                </div>
            </a>

            <!-- Tahap 5: Rencana Aksi -->
            <a href="{{ route('digiself.rencana-aksi') }}" wire:navigate class="p-4 rounded-2xl bg-white border border-gray-100 hover:border-purple-300 hover:shadow-xs transition flex flex-col justify-between">
                <div>
                    <div class="w-7 h-7 rounded-lg bg-amber-600 text-white font-extrabold text-xs flex items-center justify-center mb-2">5</div>
                    <h4 class="font-bold text-xs text-gray-900">Rencana Aksi</h4>
                    <p class="text-[11px] text-gray-500 mt-1 leading-relaxed">Menentukan tujuan, langkah konkrit, jadwal, dan indikator sukses.</p>
                </div>
                <div class="mt-3 pt-2 border-t border-gray-100">
                    <span class="text-[10px] font-bold text-amber-600">Buat Target &rarr;</span>
                </div>
            </a>

            <!-- Tahap 6: Jurnal Evaluasi -->
            <a href="{{ route('digiself.jurnal') }}" wire:navigate class="p-4 rounded-2xl bg-white border border-gray-100 hover:border-purple-300 hover:shadow-xs transition flex flex-col justify-between">
                <div>
                    <div class="w-7 h-7 rounded-lg bg-rose-600 text-white font-extrabold text-xs flex items-center justify-center mb-2">6</div>
                    <h4 class="font-bold text-xs text-gray-900">Jurnal & Evaluasi</h4>
                    <p class="text-[11px] text-gray-500 mt-1 leading-relaxed">Mencatat pengalaman, meninjau hambatan, dan memantau kemajuan.</p>
                </div>
                <div class="mt-3 pt-2 border-t border-gray-100">
                    <span class="text-[10px] font-bold text-rose-600">Tulis Catatan &rarr;</span>
                </div>
            </a>
        </div>
    </div>

    <!-- Modul Fitur Menu Sesuai Tabel Proposal (Halaman 9-10 & 27-28) -->
    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-extrabold text-gray-900">Jelajahi Menu Utama DIGISELF</h3>
            <span class="text-xs text-gray-500">8 Menu Terpadu</span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
            <!-- 1. Literasi Digital -->
            <div class="bg-white/95 backdrop-blur-md border border-purple-100 rounded-3xl p-6 shadow-xs hover:shadow-md hover:border-purple-300 transition duration-200 flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 rounded-2xl bg-purple-100 text-purple-700 flex items-center justify-center mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                    <h4 class="font-extrabold text-base text-gray-900">Literasi Digital</h4>
                    <p class="text-xs text-gray-500 mt-1.5 leading-relaxed">
                        Materi psikoedukasi 4 dimensi: evaluasi informasi kredibel, keamanan & privasi akun, etika komunikasi daring, serta penggunaan teknologi yang bertanggung jawab.
                    </p>
                </div>
                <div class="mt-5 pt-4 border-t border-gray-100">
                    <a href="{{ route('digiself.literasi') }}" wire:navigate class="inline-flex items-center gap-1.5 text-xs font-bold text-purple-700 hover:text-purple-900 transition">
                        Buka Materi & Kuis &rarr;
                    </a>
                </div>
            </div>

            <!-- 2. Refleksi Kebiasaan Digital -->
            <div class="bg-white/95 backdrop-blur-md border border-purple-100 rounded-3xl p-6 shadow-xs hover:shadow-md hover:border-purple-300 transition duration-200 flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 rounded-2xl bg-indigo-100 text-indigo-700 flex items-center justify-center mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h4 class="font-extrabold text-base text-gray-900">Refleksi Kebiasaan</h4>
                    <p class="text-xs text-gray-500 mt-1.5 leading-relaxed">
                        Sarana interaktif untuk meninjau jam penggunaan layar, mengidentifikasi aplikasi pemicu distraksi, dan mengevaluasi dampak terhadap studi.
                    </p>
                </div>
                <div class="mt-5 pt-4 border-t border-gray-100">
                    <a href="{{ route('digiself.refleksi') }}" wire:navigate class="inline-flex items-center gap-1.5 text-xs font-bold text-indigo-700 hover:text-indigo-900 transition">
                        Mulai Refleksi &rarr;
                    </a>
                </div>
            </div>

            <!-- 3. Regulasi Diri -->
            <div class="bg-white/95 backdrop-blur-md border border-purple-100 rounded-3xl p-6 shadow-xs hover:shadow-md hover:border-purple-300 transition duration-200 flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 rounded-2xl bg-emerald-100 text-emerald-700 flex items-center justify-center mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <h4 class="font-extrabold text-base text-gray-900">Regulasi Diri</h4>
                    <p class="text-xs text-gray-500 mt-1.5 leading-relaxed">
                        Strategi praktis penetapan tujuan SMART, manajemen waktu belajar (Pomodoro), pengendalian notifikasi, dan pemantauan perilaku.
                    </p>
                </div>
                <div class="mt-5 pt-4 border-t border-gray-100">
                    <a href="{{ route('digiself.regulasi') }}" wire:navigate class="inline-flex items-center gap-1.5 text-xs font-bold text-emerald-700 hover:text-emerald-900 transition">
                        Pelajari Strategi &rarr;
                    </a>
                </div>
            </div>

            <!-- 4. Rencana Aksi -->
            <div class="bg-white/95 backdrop-blur-md border border-purple-100 rounded-3xl p-6 shadow-xs hover:shadow-md hover:border-purple-300 transition duration-200 flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 rounded-2xl bg-amber-100 text-amber-700 flex items-center justify-center mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                        </svg>
                    </div>
                    <h4 class="font-extrabold text-base text-gray-900">Rencana Aksi</h4>
                    <p class="text-xs text-gray-500 mt-1.5 leading-relaxed">
                        Ubah niat menjadi langkah konkret. Tentukan target harian, waktu pelaksanaan, dan indikator keberhasilan yang realistis.
                    </p>
                </div>
                <div class="mt-5 pt-4 border-t border-gray-100">
                    <a href="{{ route('digiself.rencana-aksi') }}" wire:navigate class="inline-flex items-center gap-1.5 text-xs font-bold text-amber-700 hover:text-amber-900 transition">
                        Susun Rencana Aksi &rarr;
                    </a>
                </div>
            </div>

            <!-- 5. Jurnal & Evaluasi Diri -->
            <div class="bg-white/95 backdrop-blur-md border border-purple-100 rounded-3xl p-6 shadow-xs hover:shadow-md hover:border-purple-300 transition duration-200 flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 rounded-2xl bg-rose-100 text-rose-700 flex items-center justify-center mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </div>
                    <h4 class="font-extrabold text-base text-gray-900">Jurnal & Evaluasi</h4>
                    <p class="text-xs text-gray-500 mt-1.5 leading-relaxed">
                        Catat evaluasi harian, nilai kepuasan kontrol diri, amati hambatan distraksi, dan rumuskan penyesuaian strategi berikutnya.
                    </p>
                </div>
                <div class="mt-5 pt-4 border-t border-gray-100">
                    <a href="{{ route('digiself.jurnal') }}" wire:navigate class="inline-flex items-center gap-1.5 text-xs font-bold text-rose-700 hover:text-rose-900 transition">
                        Catat Jurnal &rarr;
                    </a>
                </div>
            </div>

            <!-- 6. Bantuan BK -->
            <div class="bg-white/95 backdrop-blur-md border border-purple-100 rounded-3xl p-6 shadow-xs hover:shadow-md hover:border-purple-300 transition duration-200 flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 rounded-2xl bg-purple-100 text-purple-700 flex items-center justify-center mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <h4 class="font-extrabold text-base text-gray-900">Bantuan BK</h4>
                    <p class="text-xs text-gray-500 mt-1.5 leading-relaxed">
                        Informasi kapan perlu pendampingan profesional, kanal kontak Guru BK, jam pelayanan, dan form permohonan konsultasi langsung.
                    </p>
                </div>
                <div class="mt-5 pt-4 border-t border-gray-100">
                    <a href="{{ route('digiself.bantuan') }}" wire:navigate class="inline-flex items-center gap-1.5 text-xs font-bold text-purple-700 hover:text-purple-900 transition">
                        Akses Layanan Konselor &rarr;
                    </a>
                </div>
            </div>

            <!-- 7. Tentang & Privasi -->
            <div class="bg-white/95 backdrop-blur-md border border-purple-100 rounded-3xl p-6 shadow-xs hover:shadow-md hover:border-purple-300 transition duration-200 flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-700 flex items-center justify-center mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h4 class="font-extrabold text-base text-gray-900">Tentang & Privasi</h4>
                    <p class="text-xs text-gray-500 mt-1.5 leading-relaxed">
                        Penjelasan tujuan produk, batas otomatisasi tanpa diagnosis psikologis, prinsip kerahasiaan data, dan identitas pengembang.
                    </p>
                </div>
                <div class="mt-5 pt-4 border-t border-gray-100">
                    <a href="{{ route('digiself.tentang') }}" wire:navigate class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-700 hover:text-slate-900 transition">
                        Baca Kebijakan Privasi &rarr;
                    </a>
                </div>
            </div>

            <!-- 8. Panduan Penggunaan Mandiri -->
            <div class="bg-gradient-to-tr from-purple-50 to-indigo-50 border border-purple-100 rounded-3xl p-6 shadow-xs flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 rounded-2xl bg-purple-700 text-white flex items-center justify-center mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                        </svg>
                    </div>
                    <h4 class="font-extrabold text-base text-gray-900">Tips Mandiri</h4>
                    <p class="text-xs text-gray-600 mt-1.5 leading-relaxed">
                        Gunakan DIGISELF secara fleksibel. Anda tidak harus menyelesaikan semua aktivitas dalam satu sesi. Luangkan waktu 5-10 menit setiap malam.
                    </p>
                </div>
                <div class="mt-5 pt-4 border-t border-purple-100">
                    <span class="text-[11px] font-bold text-purple-700">Fleksibel & Mandiri</span>
                </div>
            </div>
        </div>
    </div>
</div>
