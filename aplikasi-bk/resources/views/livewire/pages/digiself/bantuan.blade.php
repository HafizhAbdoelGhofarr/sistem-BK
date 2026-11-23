<?php

use App\Models\DigiselfBantuanBk;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component
{
    public $topik = '';
    public $urgensi = 'sedang';
    public $cerita_kendala = '';

    public function rules(): array
    {
        return [
            'topik' => 'required|string|max:255',
            'urgensi' => 'required|in:rendah,sedang,tinggi',
            'cerita_kendala' => 'required|string|max:1500',
        ];
    }

    public function kirimPermohonan(): void
    {
        $this->validate();

        DigiselfBantuanBk::create([
            'user_id' => auth()->id(),
            'topik' => $this->topik,
            'urgensi' => $this->urgensi,
            'cerita_kendala' => $this->cerita_kendala,
            'status' => 'menunggu',
        ]);

        $this->reset(['topik', 'cerita_kendala']);
        $this->urgensi = 'sedang';

        session()->flash('success', 'Permohonan bantuan konseling telah terkirim kepada Guru/Konselor BK!');
    }

    public function with(): array
    {
        return [
            'riwayatBantuan' => DigiselfBantuanBk::where('user_id', auth()->id())
                ->latest()
                ->get(),
        ];
    }
}; ?>

<div class="space-y-6">
    <!-- Header Navigasi Submodul -->
    <x-digiself-header 
        active="bantuan" 
        title="Bantuan Konseling BK" 
        description="Informasi akses bantuan profesional, panduan kapan memerlukan konselor, dan formulir permohonan bimbingan konseling." 
    />

    <!-- Notice Penting Sesuai Bab II & III Proposal -->
    <div class="p-5 rounded-3xl bg-amber-50/90 border border-amber-200/90 shadow-xs flex items-start gap-3.5">
        <div class="w-9 h-9 rounded-xl bg-amber-500 text-white flex items-center justify-center flex-shrink-0 shadow-xs">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        <div class="text-xs text-amber-900 leading-relaxed">
            <strong class="font-bold">Batas Penggunaan & Peran Konselor:</strong> DIGISELF adalah media digital pendukung edukasi dan refleksi diri, <em>bukan pengganti konseling langsung</em> dan <em>bukan alat diagnosis psikologis otomatis</em>. Apabila Anda merasa kebiasaan digital sudah mengganggu kesehatan mental, tidur, atau perkuliahan Anda, jangan ragu untuk berkonsultasi dengan Guru BK atau Konselor profesional.
        </div>
    </div>

    <!-- 1. Kapan Perlu Mencari Bantuan Profesional? -->
    <div class="bg-white/95 backdrop-blur-md border border-purple-100 rounded-3xl p-6 sm:p-8 shadow-sm space-y-4">
        <div>
            <span class="text-xs font-bold uppercase tracking-wider text-purple-700">Indikator Kebutuhan Pendampingan</span>
            <h3 class="text-xl font-extrabold text-gray-900 mt-0.5">Kapan Anda Perlu Menghubungi Layanan BK?</h3>
            <p class="text-xs text-gray-500">Perhatikan beberapa tanda peringatan dini (Red Flags) berikut:</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 pt-2">
            <div class="p-4 rounded-2xl bg-rose-50/60 border border-rose-100 space-y-2">
                <span class="text-xs font-bold text-rose-800 flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                    Nomophobia Akut
                </span>
                <p class="text-[11px] text-gray-600 leading-relaxed">
                    Kecemasan atau kepanikan ekstrem ketika tidak memegang gawai, baterai habis, atau berada di luar jangkauan sinyal internet.
                </p>
            </div>

            <div class="p-4 rounded-2xl bg-rose-50/60 border border-rose-100 space-y-2">
                <span class="text-xs font-bold text-rose-800 flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                    Insomnia Kronis
                </span>
                <p class="text-[11px] text-gray-600 leading-relaxed">
                    Pola tidur rusak total akibat begadang scrolling media sosial atau bermain game hingga larut malam setiap hari.
                </p>
            </div>

            <div class="p-4 rounded-2xl bg-rose-50/60 border border-rose-100 space-y-2">
                <span class="text-xs font-bold text-rose-800 flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                    Akademik Terbengkalai
                </span>
                <p class="text-[11px] text-gray-600 leading-relaxed">
                    Nilai mata pelajaran/kuliah merosot tajam, bolos berulang, atau tugas penting diabaikan karena dorongan membuka game/gawai.
                </p>
            </div>

            <div class="p-4 rounded-2xl bg-rose-50/60 border border-rose-100 space-y-2">
                <span class="text-xs font-bold text-rose-800 flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                    Tekanan Emosional
                </span>
                <p class="text-[11px] text-gray-600 leading-relaxed">
                    Mengalami pelecehan daring (cyberbullying), ancaman penyebaran privasi, penipuan digital, atau perasaan terisolasi dari lingkungan nyata.
                </p>
            </div>
        </div>
    </div>

    <!-- 2. Jalur Kontak Konselor & Ruang Bantuan -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Form Permohonan Konsultasi BK -->
        <div class="lg:col-span-2 bg-white/95 backdrop-blur-md border border-purple-100 rounded-3xl p-6 sm:p-8 shadow-sm space-y-5">
            <div class="border-b border-gray-100 pb-3">
                <span class="text-xs font-bold uppercase tracking-wider text-purple-700">Konseling Mandiri & Privat</span>
                <h3 class="text-xl font-extrabold text-gray-900 mt-0.5">Ajukan Permohonan Bimbingan Konseling</h3>
                <p class="text-xs text-gray-500 mt-1">Pesan ini hanya dapat dibaca oleh Guru BK untuk menjadwalkan sesi konsultasi.</p>
            </div>

            <form wire:submit.prevent="kirimPermohonan" class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-gray-800 mb-1">
                        Topik / Masalah yang Ingin Dikonsultasikan
                    </label>
                    <input type="text" wire:model="topik" placeholder="Contoh: Kesulitan mengendalikan waktu main game dan penurunan motivasi belajar" class="w-full text-xs rounded-xl border-gray-200 focus:border-purple-500 focus:ring-purple-500">
                    @error('topik') <span class="text-rose-600 text-xs font-semibold">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-800 mb-1">
                        Tingkat Urgensi
                    </label>
                    <div class="grid grid-cols-3 gap-3 text-xs">
                        <label class="p-2.5 rounded-xl border cursor-pointer flex items-center gap-2 transition {{ $urgensi === 'rendah' ? 'border-purple-600 bg-purple-50 text-purple-900 font-bold' : 'border-gray-200' }}">
                            <input type="radio" wire:model="urgensi" value="rendah" class="text-purple-600 focus:ring-purple-500">
                            <span>Rendah (Konsultasi Santai)</span>
                        </label>
                        <label class="p-2.5 rounded-xl border cursor-pointer flex items-center gap-2 transition {{ $urgensi === 'sedang' ? 'border-amber-600 bg-amber-50 text-amber-900 font-bold' : 'border-gray-200' }}">
                            <input type="radio" wire:model="urgensi" value="sedang" class="text-amber-600 focus:ring-amber-500">
                            <span>Sedang (Perlu Saran)</span>
                        </label>
                        <label class="p-2.5 rounded-xl border cursor-pointer flex items-center gap-2 transition {{ $urgensi === 'tinggi' ? 'border-rose-600 bg-rose-50 text-rose-900 font-bold' : 'border-gray-200' }}">
                            <input type="radio" wire:model="urgensi" value="tinggi" class="text-rose-600 focus:ring-rose-500">
                            <span>Tinggi (Butuh Segera)</span>
                        </label>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-800 mb-1">
                        Ceritakan Kendala yang Anda Alami
                    </label>
                    <textarea wire:model="cerita_kendala" rows="4" placeholder="Tuliskan secara terbuka apa yang sedang Anda rasakan atau kesulitan apa yang sedang Anda hadapi saat ini..." class="w-full text-xs rounded-xl border-gray-200 focus:border-purple-500 focus:ring-purple-500"></textarea>
                    @error('cerita_kendala') <span class="text-rose-600 text-xs font-semibold">{{ $message }}</span> @enderror
                </div>

                <div class="flex items-center justify-between pt-2">
                    <span class="text-[11px] text-gray-400">Prinsip kerahasiaan konseling terjamin</span>
                    <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-purple-700 to-purple-600 hover:from-purple-800 hover:to-purple-700 text-white font-bold text-xs rounded-xl shadow-md transition">
                        Kirim Permohonan Konseling
                    </button>
                </div>
            </form>
        </div>

        <!-- Kolom Informasi Kontak & Jam Pelayanan BK -->
        <div class="space-y-6">
            <div class="bg-gradient-to-tr from-purple-900 to-indigo-900 text-white rounded-3xl p-6 shadow-sm space-y-4">
                <span class="text-[10px] font-bold uppercase tracking-wider text-purple-200">Kontak Resmi BK</span>
                <h4 class="font-extrabold text-base leading-snug">Ruang Konseling Bimbingan & Konseling</h4>
                <div class="text-xs space-y-2 text-purple-100">
                    <div class="flex items-start gap-2">
                        <svg class="w-4 h-4 text-purple-300 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        </svg>
                        <span>Gedung Pusat BK, Lantai 2</span>
                    </div>
                    <div class="flex items-start gap-2">
                        <svg class="w-4 h-4 text-purple-300 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Senin - Jumat: 08.00 - 15.30 WIB</span>
                    </div>
                    <div class="flex items-start gap-2">
                        <svg class="w-4 h-4 text-purple-300 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        <span>konseling-bk@uinril.ac.id</span>
                    </div>
                </div>
            </div>

            <!-- Riwayat Permohonan Konseling Pengguna -->
            <div class="bg-white/95 backdrop-blur-md border border-purple-100 rounded-3xl p-5 shadow-xs space-y-3">
                <div class="flex items-center justify-between border-b border-gray-100 pb-2.5">
                    <h4 class="font-extrabold text-xs text-gray-900 uppercase tracking-wider">Riwayat Pengajuan Anda</h4>
                    <span class="text-[11px] font-bold text-purple-700">{{ $riwayatBantuan->count() }} Pengajuan</span>
                </div>

                @if($riwayatBantuan->isEmpty())
                    <p class="text-xs text-gray-400 py-3 text-center">Belum ada permohonan bimbingan konseling yang diajukan.</p>
                @else
                    <div class="space-y-3 max-h-72 overflow-y-auto pr-1">
                        @foreach ($riwayatBantuan as $item)
                            <div class="p-3 rounded-2xl bg-purple-50/50 border border-purple-100 text-xs space-y-1.5">
                                <div class="flex items-center justify-between text-[11px]">
                                    <span class="font-bold text-gray-900 truncate max-w-[130px]">{{ $item->topik }}</span>
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $item->status === 'selesai' ? 'bg-emerald-100 text-emerald-800' : ($item->status === 'dijadwalkan' ? 'bg-indigo-100 text-indigo-800' : 'bg-amber-100 text-amber-800') }}">
                                        {{ $item->status }}
                                    </span>
                                </div>
                                <p class="text-gray-600 text-[11px] line-clamp-2">{{ $item->cerita_kendala }}</p>
                                <span class="text-[10px] text-gray-400 block pt-1 border-t border-purple-100/60">{{ $item->created_at->translatedFormat('d M Y, H:i') }}</span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
