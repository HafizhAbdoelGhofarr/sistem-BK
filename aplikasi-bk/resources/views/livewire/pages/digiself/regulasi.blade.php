<?php

use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component
{
    public $selectedTab = 'dimensi'; // 'dimensi', 'pomodoro', 'checklist'
}; ?>

<div class="space-y-6">
    <!-- Header Navigasi Submodul -->
    <x-digiself-header 
        active="regulasi" 
        title="Regulasi Diri Digital" 
        description="Pelajari 5 dimensi strategi pengelolaan perilaku digital, pengendalian distraksi, dan simulasi manajemen fokus." 
    />

    <!-- Tab Navigasi Regulasi Diri -->
    <div class="flex overflow-x-auto pb-1 gap-2 border-b border-purple-100">
        <button wire:click="$set('selectedTab', 'dimensi')" 
                class="px-4 py-2.5 rounded-2xl text-xs font-bold whitespace-nowrap transition flex items-center gap-2 {{ $selectedTab === 'dimensi' ? 'bg-purple-700 text-white shadow-md shadow-purple-700/20' : 'bg-white text-gray-600 hover:text-purple-700 hover:bg-purple-50' }}">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
            </svg>
            5 Dimensi Regulasi Diri
        </button>
        <button wire:click="$set('selectedTab', 'pomodoro')" 
                class="px-4 py-2.5 rounded-2xl text-xs font-bold whitespace-nowrap transition flex items-center gap-2 {{ $selectedTab === 'pomodoro' ? 'bg-emerald-600 text-white shadow-md shadow-emerald-600/20' : 'bg-white text-gray-600 hover:text-emerald-700 hover:bg-emerald-50' }}">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Simulasi Timer Pomodoro Fokus
        </button>
        <button wire:click="$set('selectedTab', 'checklist')" 
                class="px-4 py-2.5 rounded-2xl text-xs font-bold whitespace-nowrap transition flex items-center gap-2 {{ $selectedTab === 'checklist' ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/20' : 'bg-white text-gray-600 hover:text-indigo-700 hover:bg-indigo-50' }}">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
            </svg>
            Checklist Strategi Harian
        </button>
    </div>

    <!-- 1. Konten 5 Dimensi Regulasi Diri Sesuai Bab II Proposal -->
    @if ($selectedTab === 'dimensi')
        <div class="space-y-6">
            <div class="bg-white/95 backdrop-blur-md border border-purple-100 rounded-3xl p-6 sm:p-8 shadow-sm">
                <div class="max-w-2xl">
                    <span class="text-xs font-bold uppercase tracking-wider text-purple-700">Landasan Konseptual Bab II</span>
                    <h3 class="text-xl font-extrabold text-gray-900 mt-0.5">5 Dimensi Regulasi Diri dalam Dunia Digital</h3>
                    <p class="text-xs text-gray-500 mt-1 leading-relaxed">
                        Regulasi diri bukan sekadar menahan diri dari hiburan, tetapi proses sadar dalam mengenali tujuan, merencanakan tindakan, memantau kemajuan, dan mengevaluasi hasil.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 mt-6">
                    <!-- Dimensi 1 -->
                    <div class="p-5 rounded-2xl bg-purple-50/60 border border-purple-100 space-y-2">
                        <div class="w-8 h-8 rounded-xl bg-purple-700 text-white flex items-center justify-center font-bold text-xs">1</div>
                        <h4 class="font-bold text-sm text-gray-900">Penetapan Tujuan (Goal Setting)</h4>
                        <p class="text-xs text-gray-600 leading-relaxed">
                            Menetapkan tujuan penggunaan teknologi yang spesifik dan realistis. Contoh: Memutuskan hanya memakai gawai untuk mencari jurnal ilmiah saat jam 19.00 - 20.30.
                        </p>
                    </div>

                    <!-- Dimensi 2 -->
                    <div class="p-5 rounded-2xl bg-indigo-50/60 border border-indigo-100 space-y-2">
                        <div class="w-8 h-8 rounded-xl bg-indigo-700 text-white flex items-center justify-center font-bold text-xs">2</div>
                        <h4 class="font-bold text-sm text-gray-900">Pengelolaan Waktu (Time Management)</h4>
                        <p class="text-xs text-gray-600 leading-relaxed">
                            Menyusun jadwal berimbang antara tugas akademik, istirahat, komunikasi, dan hiburan. Memahami prioritas sehingga tugas tidak tertumpuk di akhir batas waktu.
                        </p>
                    </div>

                    <!-- Dimensi 3 -->
                    <div class="p-5 rounded-2xl bg-blue-50/60 border border-blue-100 space-y-2">
                        <div class="w-8 h-8 rounded-xl bg-blue-700 text-white flex items-center justify-center font-bold text-xs">3</div>
                        <h4 class="font-bold text-sm text-gray-900">Pengendalian Perhatian & Distraksi</h4>
                        <p class="text-xs text-gray-600 leading-relaxed">
                            Mengenali stimulus pengganggu (notifikasi, dorongan scrolling), mengaktifkan mode hening (Do Not Disturb), dan menempatkan ponsel di luar jangkauan saat belajar.
                        </p>
                    </div>

                    <!-- Dimensi 4 -->
                    <div class="p-5 rounded-2xl bg-emerald-50/60 border border-emerald-100 space-y-2">
                        <div class="w-8 h-8 rounded-xl bg-emerald-700 text-white flex items-center justify-center font-bold text-xs">4</div>
                        <h4 class="font-bold text-sm text-gray-900">Pemantauan Perilaku (Self-Monitoring)</h4>
                        <p class="text-xs text-gray-600 leading-relaxed">
                            Meninjau keselarasan antara rencana dan perilaku nyata. Mencatat durasi pemakaian layar harian dan mengamati hambatan apa saja yang dihadapi.
                        </p>
                    </div>

                    <!-- Dimensi 5 -->
                    <div class="p-5 rounded-2xl bg-amber-50/60 border border-amber-100 space-y-2">
                        <div class="w-8 h-8 rounded-xl bg-amber-700 text-white flex items-center justify-center font-bold text-xs">5</div>
                        <h4 class="font-bold text-sm text-gray-900">Evaluasi & Penyesuaian (Adaptation)</h4>
                        <p class="text-xs text-gray-600 leading-relaxed">
                            Menilai keberhasilan tanpa menghakimi diri sendiri saat target belum tercapai. Mengidentifikasi penyebab kendala dan merumuskan langkah penyesuaian baru.
                        </p>
                    </div>

                    <!-- Card Ajakan Aksi -->
                    <div class="p-5 rounded-2xl bg-gradient-to-tr from-purple-800 to-indigo-800 text-white flex flex-col justify-between">
                        <div>
                            <span class="text-[10px] font-bold uppercase tracking-wider text-purple-200">Langkah Nyata</span>
                            <h4 class="font-extrabold text-sm mt-1">Siap Menyusun Rencana Aksi?</h4>
                            <p class="text-xs text-purple-100 mt-1 leading-relaxed">
                                Terapkan dimensi regulasi diri ini ke dalam formulir Rencana Aksi pribadi Anda.
                            </p>
                        </div>
                        <div class="pt-3">
                            <a href="{{ route('digiself.rencana-aksi') }}" wire:navigate class="inline-flex items-center gap-1.5 px-4 py-2 bg-white text-purple-900 font-bold text-xs rounded-xl shadow-sm hover:bg-purple-50 transition">
                                Buat Rencana Aksi Sekarang &rarr;
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- 2. Konten Simulasi Timer Pomodoro Fokus -->
    @if ($selectedTab === 'pomodoro')
        <div class="bg-white/95 backdrop-blur-md border border-purple-100 rounded-3xl p-6 sm:p-8 shadow-sm space-y-6"
             x-data="{
                mode: 'belajar', // 'belajar' (25m), 'istirahat' (5m)
                totalSeconds: 25 * 60,
                isRunning: false,
                interval: null,
                startTimer() {
                    if (this.isRunning) return;
                    this.isRunning = true;
                    this.interval = setInterval(() => {
                        if (this.totalSeconds > 0) {
                            this.totalSeconds--;
                        } else {
                            this.pauseTimer();
                            alert(this.mode === 'belajar' ? 'Sesi belajar 25 menit selesai! Saatnya istirahat 5 menit.' : 'Waktu istirahat selesai! Siap untuk sesi fokus berikutnya.');
                            this.setMode(this.mode === 'belajar' ? 'istirahat' : 'belajar');
                        }
                    }, 1000);
                },
                pauseTimer() {
                    this.isRunning = false;
                    clearInterval(this.interval);
                },
                resetTimer() {
                    this.pauseTimer();
                    this.totalSeconds = (this.mode === 'belajar' ? 25 : 5) * 60;
                },
                setMode(newMode) {
                    this.pauseTimer();
                    this.mode = newMode;
                    this.totalSeconds = (newMode === 'belajar' ? 25 : 5) * 60;
                },
                formatTime() {
                    const m = Math.floor(this.totalSeconds / 60).toString().padStart(2, '0');
                    const s = (this.totalSeconds % 60).toString().padStart(2, '0');
                    return `${m}:${s}`;
                }
             }">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-gray-100 pb-4">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-emerald-700">Metode Regulasi Waktu Populer</span>
                    <h3 class="text-xl font-extrabold text-gray-900">Teknik Pomodoro Fokus 25:5</h3>
                    <p class="text-xs text-gray-500 mt-0.5">25 menit fokus penuh tanpa menyentuh media sosial, diselingi 5 menit istirahat sehat.</p>
                </div>

                <div class="inline-flex p-1 bg-gray-100 rounded-2xl">
                    <button @click="setMode('belajar')" :class="mode === 'belajar' ? 'bg-purple-700 text-white font-bold' : 'text-gray-600'" class="px-3.5 py-1.5 rounded-xl text-xs transition">
                        Fokus Belajar (25 Menit)
                    </button>
                    <button @click="setMode('istirahat')" :class="mode === 'istirahat' ? 'bg-emerald-600 text-white font-bold' : 'text-gray-600'" class="px-3.5 py-1.5 rounded-xl text-xs transition">
                        Istirahat (5 Menit)
                    </button>
                </div>
            </div>

            <!-- Jam Tampilan Besar -->
            <div class="flex flex-col items-center justify-center py-8">
                <div class="w-48 h-48 sm:w-56 sm:h-56 rounded-full border-4 flex flex-col items-center justify-center shadow-lg transition"
                     :class="mode === 'belajar' ? 'border-purple-600 bg-purple-50/50 text-purple-900 shadow-purple-500/10' : 'border-emerald-600 bg-emerald-50/50 text-emerald-900 shadow-emerald-500/10'">
                    <span class="text-4xl sm:text-5xl font-black tracking-tight" x-text="formatTime()"></span>
                    <span class="text-xs uppercase tracking-widest font-bold mt-2 text-gray-500" x-text="mode === 'belajar' ? 'Sesi Fokus' : 'Sesi Santai'"></span>
                </div>

                <!-- Tombol Kendali Timer -->
                <div class="mt-8 flex items-center gap-3">
                    <button x-show="!isRunning" @click="startTimer()" class="px-6 py-2.5 bg-purple-700 hover:bg-purple-800 text-white font-bold text-xs rounded-xl shadow-md transition flex items-center gap-2">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd" />
                        </svg>
                        Mulai Sesi
                    </button>
                    <button x-show="isRunning" @click="pauseTimer()" class="px-6 py-2.5 bg-amber-600 hover:bg-amber-700 text-white font-bold text-xs rounded-xl shadow-md transition flex items-center gap-2">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zM7 8a1 1 0 012 0v4a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v4a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                        Jeda (Pause)
                    </button>
                    <button @click="resetTimer()" class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold text-xs rounded-xl transition">
                        Reset
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- 3. Konten Checklist Strategi Regulasi Diri Harian -->
    @if ($selectedTab === 'checklist')
        <div class="bg-white/95 backdrop-blur-md border border-purple-100 rounded-3xl p-6 sm:p-8 shadow-sm space-y-6"
             x-data="{
                items: [
                    { id: 1, text: 'Nyalakan Do Not Disturb / Heningkan notifikasi medsos saat jam belajar.', done: false },
                    { id: 2, text: 'Letakkan ponsel minimal 2 meter dari meja belajar agar tidak mudah diraih.', done: false },
                    { id: 3, text: 'Tentukan 1 target tugas spesifik yang harus beres sebelum boleh membuka media sosial.', done: false },
                    { id: 4, text: 'Terapkan aturan 20-20-20 untuk relaksasi mata dari layar gawai.', done: false },
                    { id: 5, text: 'Hentikan pemakaian gawai minimal 30 menit sebelum tidur di malam hari.', done: false },
                    { id: 6, text: 'Hapus aplikasi game/media sosial yang paling banyak menyita waktu selama masa ujian.', done: false }
                ]
             }">
            <div class="border-b border-gray-100 pb-4">
                <span class="text-xs font-bold uppercase tracking-wider text-indigo-700">Checklist Praktis</span>
                <h3 class="text-xl font-extrabold text-gray-900 mt-0.5">Trik Cepat Pengendalian Distraksi Digital</h3>
                <p class="text-xs text-gray-500 mt-1">Centang strategi yang ingin atau sudah Anda laksanakan hari ini.</p>
            </div>

            <div class="space-y-3">
                <template x-for="item in items" :key="item.id">
                    <label class="flex items-center gap-3 p-4 rounded-2xl border transition cursor-pointer"
                           :class="item.done ? 'bg-purple-50/60 border-purple-300 text-purple-900 line-through' : 'bg-white border-gray-200 hover:border-purple-200 text-gray-800'">
                        <input type="checkbox" x-model="item.done" class="rounded text-purple-600 focus:ring-purple-500 w-4 h-4">
                        <span class="text-xs font-medium" x-text="item.text"></span>
                    </label>
                </template>
            </div>
        </div>
    @endif
</div>
