<?php

use App\Models\DigiselfRefleksi;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component
{
    public $durasi_layar = '';
    public $tujuan_penggunaan = '';
    public $aplikasi_distraksi = '';
    public $situasi_pemicu = '';
    public $dampak_negatif = '';
    public $catatan_refleksi = '';

    public function rules(): array
    {
        return [
            'durasi_layar' => 'required',
            'tujuan_penggunaan' => 'required',
            'aplikasi_distraksi' => 'required|string|max:255',
            'situasi_pemicu' => 'required|string|max:255',
            'dampak_negatif' => 'nullable|string|max:500',
            'catatan_refleksi' => 'nullable|string|max:1000',
        ];
    }

    public function simpanRefleksi(): void
    {
        $this->validate();

        DigiselfRefleksi::create([
            'user_id' => auth()->id(),
            'durasi_layar' => $this->durasi_layar,
            'tujuan_penggunaan' => $this->tujuan_penggunaan,
            'aplikasi_distraksi' => $this->aplikasi_distraksi,
            'situasi_pemicu' => $this->situasi_pemicu,
            'dampak_negatif' => $this->dampak_negatif,
            'catatan_refleksi' => $this->catatan_refleksi,
        ]);

        $this->reset(['durasi_layar', 'tujuan_penggunaan', 'aplikasi_distraksi', 'situasi_pemicu', 'dampak_negatif', 'catatan_refleksi']);

        session()->flash('success', 'Catatan refleksi kebiasaan digital berhasil disimpan!');
    }

    public function with(): array
    {
        return [
            'riwayatRefleksi' => DigiselfRefleksi::where('user_id', auth()->id())
                ->latest()
                ->get(),
        ];
    }
}; ?>

<div class="space-y-6">
    <!-- Header Navigasi Submodul -->
    <x-digiself-header 
        active="refleksi" 
        title="Refleksi Kebiasaan Digital" 
        description="Membantu mahasiswa/siswa meninjau tujuan, frekuensi, situasi pemicu, dan potensi distraksi penggunaan teknologi." 
    />

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Kolom Kiri: Form Refleksi Interaktif -->
        <div class="lg:col-span-2 bg-white/95 backdrop-blur-md border border-purple-100 rounded-3xl p-6 sm:p-8 shadow-sm space-y-6">
            <div class="border-b border-gray-100 pb-4">
                <span class="text-xs font-bold uppercase tracking-wider text-purple-700">Instrumen Refleksi Diri</span>
                <h3 class="text-xl font-extrabold text-gray-900 mt-0.5">Kenali Pola Hubungan Anda dengan Gawai</h3>
                <p class="text-xs text-gray-500 mt-1">Jawablah dengan jujur sesuai apa yang Anda alami sehari-hari tanpa perlu merasa dihakimi.</p>
            </div>

            <form wire:submit.prevent="simpanRefleksi" class="space-y-5">
                <!-- 1. Estimasi Durasi Layar -->
                <div>
                    <label class="block text-xs font-bold text-gray-800 mb-2">
                        1. Berapa rata-rata waktu layar (Screen Time) ponsel/laptop Anda per hari?
                    </label>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5">
                        @foreach (['< 3 Jam (Rendah)', '3 - 5 Jam (Sedang)', '6 - 8 Jam (Tinggi)', '> 8 Jam (Sangat Tinggi)'] as $durasi)
                            <label class="flex items-center gap-2 p-3 rounded-2xl border cursor-pointer text-xs transition {{ $durasi_layar === $durasi ? 'border-purple-600 bg-purple-50 text-purple-900 font-bold' : 'border-gray-200 hover:border-purple-300' }}">
                                <input type="radio" wire:model="durasi_layar" value="{{ $durasi }}" class="text-purple-600 focus:ring-purple-500">
                                <span>{{ $durasi }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('durasi_layar') <span class="text-rose-600 text-xs font-semibold mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- 2. Tujuan Penggunaan Utama -->
                <div>
                    <label class="block text-xs font-bold text-gray-800 mb-2">
                        2. Untuk aktivitas apa waktu digital Anda paling banyak dihabiskan?
                    </label>
                    <select wire:model="tujuan_penggunaan" class="w-full text-xs rounded-xl border-gray-200 focus:border-purple-500 focus:ring-purple-500">
                        <option value="">-- Pilih Tujuan Terbanyak --</option>
                        <option value="Tugas Kuliah & Materi Akademik">Tugas Kuliah & Materi Akademik</option>
                        <option value="Media Sosial (TikTok, Instagram, X/Twitter)">Media Sosial (TikTok, Instagram, X/Twitter)</option>
                        <option value="Streaming Video & Film (YouTube, Netflix)">Streaming Video & Film (YouTube, Netflix)</option>
                        <option value="Bermain Game Daring (Mobile Legends, Valorant, dll)">Bermain Game Daring (Mobile Legends, Valorant, dll)</option>
                        <option value="Chatting & Komunikasi Tanpa Arah">Chatting & Komunikasi Tanpa Arah</option>
                    </select>
                    @error('tujuan_penggunaan') <span class="text-rose-600 text-xs font-semibold mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- 3. Aplikasi Paling Menyita Waktu -->
                <div>
                    <label class="block text-xs font-bold text-gray-800 mb-2">
                        3. Aplikasi atau platform apa yang paling sering membuat Anda lupa waktu / terdistraksi?
                    </label>
                    <input type="text" wire:model="aplikasi_distraksi" placeholder="Contoh: TikTok FYP, Instagram Reels, Game Mobile" class="w-full text-xs rounded-xl border-gray-200 focus:border-purple-500 focus:ring-purple-500">
                    @error('aplikasi_distraksi') <span class="text-rose-600 text-xs font-semibold mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- 4. Situasi Pemicu Kompulsif -->
                <div>
                    <label class="block text-xs font-bold text-gray-800 mb-2">
                        4. Dalam situasi apa Anda biasanya terdorong untuk membuka ponsel secara refleks?
                    </label>
                    <input type="text" wire:model="situasi_pemicu" placeholder="Contoh: Saat mulai merasa bosan mengerjakan tugas, saat sendirian, sebelum tidur" class="w-full text-xs rounded-xl border-gray-200 focus:border-purple-500 focus:ring-purple-500">
                    @error('situasi_pemicu') <span class="text-rose-600 text-xs font-semibold mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- 5. Dampak yang Dirasakan -->
                <div>
                    <label class="block text-xs font-bold text-gray-800 mb-2">
                        5. Apa dampak negatif yang Anda rasakan dari kebiasaan digital tersebut? (Opsional)
                    </label>
                    <textarea wire:model="dampak_negatif" rows="2" placeholder="Contoh: Sering begadang, tugas kuliah tertunda, mata cepat lelah, susah berkonsentrasi saat belajar" class="w-full text-xs rounded-xl border-gray-200 focus:border-purple-500 focus:ring-purple-500"></textarea>
                    @error('dampak_negatif') <span class="text-rose-600 text-xs font-semibold mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- 6. Komitmen Refleksi Bebas -->
                <div>
                    <label class="block text-xs font-bold text-gray-800 mb-2">
                        6. Catatan Refleksi Pribadi & Niat Awal (Opsional)
                    </label>
                    <textarea wire:model="catatan_refleksi" rows="2" placeholder="Tuliskan pemikiran atau perasaan Anda saat ini..." class="w-full text-xs rounded-xl border-gray-200 focus:border-purple-500 focus:ring-purple-500"></textarea>
                </div>

                <div class="flex items-center justify-between pt-2">
                    <span class="text-[11px] text-gray-400">Catatan refleksi bersifat rahasia dan aman</span>
                    <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-purple-700 to-purple-600 hover:from-purple-800 hover:to-purple-700 text-white font-bold text-xs rounded-xl shadow-md transition">
                        Simpan Catatan Refleksi
                    </button>
                </div>
            </form>
        </div>

        <!-- Kolom Kanan: Wawasan & Riwayat Refleksi -->
        <div class="space-y-6">
            <div class="bg-gradient-to-tr from-purple-900 to-indigo-900 text-white rounded-3xl p-6 shadow-md space-y-3">
                <span class="text-[10px] font-bold uppercase tracking-wider text-purple-300">Prinsip Psikoedukasi</span>
                <h4 class="font-extrabold text-base leading-snug">Refleksi adalah Langkah Pertama Regulasi Diri</h4>
                <p class="text-xs text-purple-100/90 leading-relaxed">
                    Menyadari pemicu kebiasaan (habit loop) membantu Anda menghentikan siklus otomatis. Ketika Anda tahu pemicunya (misal: rasa bosan), Anda bisa memilih alternatif tindakan sehat sebelum menyentuh gawai.
                </p>
                <div class="pt-2">
                    <a href="{{ route('digiself.regulasi') }}" wire:navigate class="inline-flex items-center gap-1.5 text-xs font-bold text-purple-200 hover:text-white underline">
                        Lanjut ke Menu Regulasi Diri &rarr;
                    </a>
                </div>
            </div>

            <!-- Daftar Riwayat Refleksi Sebelumnya -->
            <div class="bg-white/95 backdrop-blur-md border border-purple-100 rounded-3xl p-5 shadow-xs space-y-4">
                <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                    <h4 class="font-extrabold text-xs text-gray-900 uppercase tracking-wider">Riwayat Refleksi Anda</h4>
                    <span class="text-[11px] font-bold text-purple-700">{{ $riwayatRefleksi->count() }} Catatan</span>
                </div>

                @if($riwayatRefleksi->isEmpty())
                    <p class="text-xs text-gray-400 text-center py-4">Belum ada catatan refleksi yang tersimpan.</p>
                @else
                    <div class="space-y-3 max-h-96 overflow-y-auto pr-1">
                        @foreach ($riwayatRefleksi as $item)
                            <div class="p-3.5 rounded-2xl bg-purple-50/50 border border-purple-100 text-xs space-y-1.5">
                                <div class="flex items-center justify-between text-[11px] text-gray-500">
                                    <span class="font-bold text-purple-800">{{ $item->created_at->translatedFormat('d M Y, H:i') }}</span>
                                    <span class="px-2 py-0.5 rounded-md bg-purple-100 text-purple-800 font-semibold">{{ $item->durasi_layar }}</span>
                                </div>
                                <div class="text-gray-700">
                                    <strong class="text-gray-900">Distraksi:</strong> {{ $item->aplikasi_distraksi }}
                                </div>
                                <div class="text-gray-600 text-[11px]">
                                    <strong class="text-gray-800">Pemicu:</strong> {{ $item->situasi_pemicu }}
                                </div>
                                @if($item->catatan_refleksi)
                                    <div class="mt-1 pt-1 border-t border-purple-100/80 text-gray-500 italic text-[11px]">
                                        "{{ $item->catatan_refleksi }}"
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
