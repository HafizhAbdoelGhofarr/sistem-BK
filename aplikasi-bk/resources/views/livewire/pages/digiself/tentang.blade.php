<?php

use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component
{
    // Static presentation component for proposal specifications
}; ?>

<div class="space-y-6">
    <!-- Header Navigasi Submodul -->
    <x-digiself-header 
        active="tentang" 
        title="Tentang Produk & Kebijakan Privasi" 
        description="Informasi landasan pengembangan DIGISELF, batasan sistem, batas otomatisasi, dan prinsip perlindungan data pengguna." 
    />

    <!-- 1. Identitas & Profil Produk DIGISELF Sesuai Bab II Proposal -->
    <div class="bg-white/95 backdrop-blur-md border border-purple-100 rounded-3xl p-6 sm:p-8 shadow-sm space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-gray-100 pb-5">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-purple-700">Profil Produk Resmi</span>
                <h3 class="text-xl sm:text-2xl font-extrabold text-gray-900 mt-0.5">DIGISELF (Digital Self-Development for Students)</h3>
                <p class="text-xs text-gray-500 mt-1">Media Digital Bimbingan dan Konseling untuk Penguatan Literasi Digital dan Regulasi Mahasiswa</p>
            </div>
            <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-bold bg-purple-100 text-purple-800 border border-purple-200">
                Tahun Pengembangan 2026
            </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 text-xs text-gray-700">
            <div class="p-4 rounded-2xl bg-purple-50/50 border border-purple-100 space-y-1.5">
                <span class="font-bold text-purple-900 block uppercase tracking-wider text-[10px]">Jenis Produk</span>
                <p class="leading-relaxed">Media digital bimbingan dan konseling berbasis edukasi, refleksi diri, dan pengembangan keterampilan regulasi mahasiswa.</p>
            </div>
            <div class="p-4 rounded-2xl bg-purple-50/50 border border-purple-100 space-y-1.5">
                <span class="font-bold text-purple-900 block uppercase tracking-wider text-[10px]">Sasaran Pengguna</span>
                <p class="leading-relaxed">Mahasiswa dan siswa perguruan tinggi / sekolah yang membutuhkan dukungan untuk meningkatkan literasi digital dan mengelola kebiasaan berteknologi.</p>
            </div>
            <div class="p-4 rounded-2xl bg-purple-50/50 border border-purple-100 space-y-1.5">
                <span class="font-bold text-purple-900 block uppercase tracking-wider text-[10px]">Pendekatan Teoretis</span>
                <p class="leading-relaxed">Memadukan psikoedukasi literasi digital, model ADDIE, self-regulated learning (Zimmerman & Pintrich), serta cognitive-behavioral self-reflection.</p>
            </div>
            <div class="p-4 rounded-2xl bg-purple-50/50 border border-purple-100 space-y-1.5">
                <span class="font-bold text-purple-900 block uppercase tracking-wider text-[10px]">Lembaga Pengembangan</span>
                <p class="leading-relaxed">Program Studi Bimbingan dan Konseling Pendidikan Islam, Fakultas Tarbiyah dan Keguruan, Universitas Islam Negeri Raden Intan Lampung.</p>
            </div>
        </div>
    </div>

    <!-- 2. Batas Produk Sesuai Halaman 10-11 & 20-21 Proposal -->
    <div class="bg-white/95 backdrop-blur-md border border-purple-100 rounded-3xl p-6 sm:p-8 shadow-sm space-y-5">
        <div>
            <span class="text-xs font-bold uppercase tracking-wider text-rose-700">Batasan Etika & Batas Sistem (Proposal Bab I.F.3)</span>
            <h3 class="text-xl font-extrabold text-gray-900 mt-0.5">Batasan Produk DIGISELF</h3>
            <p class="text-xs text-gray-500 mt-1">Menjamin keamanan pengguna dan menjaga batas kewenangan layanan Bimbingan dan Konseling.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 pt-2">
            <!-- A. Batas Fungsi -->
            <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200 space-y-2">
                <h4 class="font-bold text-xs text-gray-900 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-purple-600"></span>
                    a. Batas Fungsi
                </h4>
                <p class="text-xs text-gray-600 leading-relaxed">
                    DIGISELF adalah media edukasi dan refleksi diri mandiri, <strong>bukan aplikasi yang menggantikan</strong> layanan konseling langsung, asesmen psikologis klinis, atau pendampingan konselor profesional.
                </p>
            </div>

            <!-- B. Batas Materi -->
            <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200 space-y-2">
                <h4 class="font-bold text-xs text-gray-900 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-purple-600"></span>
                    b. Batas Materi
                </h4>
                <p class="text-xs text-gray-600 leading-relaxed">
                    Materi difokuskan secara spesifik pada literasi digital dan regulasi diri dalam pemakaian teknologi, tidak dirancang untuk menangani gangguan klinis berat atau patologis.
                </p>
            </div>

            <!-- C. Batas Otomatisasi -->
            <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200 space-y-2">
                <h4 class="font-bold text-xs text-gray-900 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-purple-600"></span>
                    c. Batas Otomatisasi
                </h4>
                <p class="text-xs text-gray-600 leading-relaxed">
                    Fitur refleksi dan evaluasi diri <strong>tidak digunakan untuk menetapkan diagnosis otomatis</strong>, memberi label psikologis, atau mengambil vonis sepihak terhadap pengguna.
                </p>
            </div>

            <!-- D. Batas Penggunaan -->
            <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200 space-y-2">
                <h4 class="font-bold text-xs text-gray-900 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-purple-600"></span>
                    d. Pintu Masuk Layanan BK
                </h4>
                <p class="text-xs text-gray-600 leading-relaxed">
                    DIGISELF diarahkan sebagai gerbang penghubung dan media pendukung awal. Mahasiswa yang butuh bimbingan mendalam diarahkan untuk menghubungi konselor BK.
                </p>
            </div>

            <!-- E. Batas Perlindungan Data -->
            <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200 space-y-2">
                <h4 class="font-bold text-xs text-gray-900 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-purple-600"></span>
                    e. Perlindungan Data Pribadi
                </h4>
                <p class="text-xs text-gray-600 leading-relaxed">
                    Pengumpulan data dibatasi seminimal mungkin. Catatan refleksi dan jurnal evaluasi diri pengguna dilindungi secara privat dan tidak dipublikasikan ke pihak luar.
                </p>
            </div>

            <!-- F. Batas Klaim Keberhasilan -->
            <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200 space-y-2">
                <h4 class="font-bold text-xs text-gray-900 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-purple-600"></span>
                    f. Batas Klaim Produk
                </h4>
                <p class="text-xs text-gray-600 leading-relaxed">
                    Produk merupakan prototipe awal yang diuji fungsi dan kelayakannya, sehingga perbaikan dan evaluasi terus dilakukan secara berkelanjutan.
                </p>
            </div>
        </div>
    </div>

    <!-- 3. Tim Penyusun Proposal -->
    <div class="bg-gradient-to-r from-purple-900 to-indigo-900 text-white rounded-3xl p-6 sm:p-8 shadow-md">
        <div class="max-w-3xl space-y-3">
            <span class="text-xs font-bold uppercase tracking-wider text-purple-300">Tim Pengembang Proposal</span>
            <h4 class="text-xl font-extrabold leading-snug">Karya Ilmiah Bimbingan & Konseling Pendidikan Islam</h4>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2 text-xs text-purple-100">
                <div class="p-3.5 rounded-2xl bg-white/10 border border-white/15">
                    <span class="block text-[11px] text-purple-300">Disusun Oleh:</span>
                    <strong class="text-white text-sm block mt-0.5">Pata Ursida</strong>
                    <span class="text-purple-200">NIM: 2411080043 &bull; Kelas E</span>
                </div>
                <div class="p-3.5 rounded-2xl bg-white/10 border border-white/15">
                    <span class="block text-[11px] text-purple-300">Dosen Pengampu:</span>
                    <strong class="text-white text-sm block mt-0.5">Mega Aria Monica, M.Pd</strong>
                    <span class="text-purple-200">Mata Kuliah Pengembangan Teknologi BK</span>
                </div>
            </div>
            <p class="text-xs text-purple-200/90 pt-1">
                Fakultas Tarbiyah dan Keguruan, Jurusan Bimbingan dan Konseling Pendidikan Islam, Universitas Islam Negeri Raden Intan Lampung, Tahun 2026.
            </p>
        </div>
    </div>
</div>
