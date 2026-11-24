<?php

use App\Models\DigiselfJurnalEvaluasi;
use App\Models\DigiselfRencanaAksi;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component
{
    public $rencana_aksi_id = '';
    public $tanggal = '';
    public $skor_regulasi = 4;
    public $pengalaman_harian = '';
    public $hambatan = '';
    public $langkah_perbaikan = '';

    public function mount(): void
    {
        $this->tanggal = now()->format('Y-m-d');
    }

    public function rules(): array
    {
        return [
            'tanggal' => 'required|date',
            'skor_regulasi' => 'required|integer|min:1|max:5',
            'pengalaman_harian' => 'required|string|max:1000',
            'hambatan' => 'nullable|string|max:500',
            'langkah_perbaikan' => 'nullable|string|max:500',
            'rencana_aksi_id' => 'nullable|exists:digiself_rencana_aksi,id',
        ];
    }

    public function simpanJurnal(): void
    {
        $this->validate();

        DigiselfJurnalEvaluasi::create([
            'user_id' => auth()->id(),
            'rencana_aksi_id' => $this->rencana_aksi_id ?: null,
            'tanggal' => $this->tanggal,
            'skor_regulasi' => $this->skor_regulasi,
            'pengalaman_harian' => $this->pengalaman_harian,
            'hambatan' => $this->hambatan,
            'langkah_perbaikan' => $this->langkah_perbaikan,
        ]);

        $this->reset(['pengalaman_harian', 'hambatan', 'langkah_perbaikan']);
        $this->tanggal = now()->format('Y-m-d');
        $this->skor_regulasi = 4;

        session()->flash('success', 'Catatan jurnal evaluasi diri berhasil disimpan!');
    }

    public function hapusJurnal(int $id): void
    {
        $jurnal = DigiselfJurnalEvaluasi::where('user_id', auth()->id())->findOrFail($id);
        $jurnal->delete();

        session()->flash('success', 'Catatan jurnal berhasil dihapus.');
    }

    public function with(): array
    {
        $userId = auth()->id();
        return [
            'daftarRencana' => DigiselfRencanaAksi::where('user_id', $userId)->get(),
            'daftarJurnal' => DigiselfJurnalEvaluasi::with('rencanaAksi')
                ->where('user_id', $userId)
                ->orderBy('tanggal', 'desc')
                ->latest()
                ->get(),
        ];
    }
}; ?>

<div class="space-y-6">
    <!-- Header Navigasi Submodul -->
    <x-digiself-header 
        active="jurnal" 
        title="Jurnal dan Evaluasi Diri" 
        description="Mendukung pemantauan perkembangan secara mandiri, mencatat pengalaman harian, dan mengevaluasi pelaksanaan rencana aksi." 
    />

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Kolom Kiri: Form Catatan Jurnal Harian -->
        <div class="bg-white/95 backdrop-blur-md border border-purple-100 rounded-3xl p-6 sm:p-8 shadow-sm space-y-6">
            <div class="border-b border-gray-100 pb-4">
                <span class="text-xs font-bold uppercase tracking-wider text-rose-700">Self-Evaluation Log</span>
                <h3 class="text-xl font-extrabold text-gray-900 mt-0.5">Tulis Catatan Hari Ini</h3>
                <p class="text-xs text-gray-500 mt-1">Refleksikan bagaimana Anda mengelola perhatian dan gawai hari ini.</p>
            </div>

            <form wire:submit.prevent="simpanJurnal" class="space-y-4">
                <!-- Tanggal Evaluasi -->
                <div>
                    <label class="block text-xs font-bold text-gray-800 mb-1">
                        Tanggal Evaluasi
                    </label>
                    <input type="date" wire:model="tanggal" class="w-full text-xs rounded-xl border-gray-200 focus:border-purple-500 focus:ring-purple-500">
                    @error('tanggal') <span class="text-rose-600 text-xs font-semibold">{{ $message }}</span> @enderror
                </div>

                <!-- Hubungkan dengan Rencana Aksi (Opsional) -->
                <div>
                    <label class="block text-xs font-bold text-gray-800 mb-1">
                        Kaitkan dengan Rencana Aksi (Opsional)
                    </label>
                    <select wire:model="rencana_aksi_id" class="w-full text-xs rounded-xl border-gray-200 focus:border-purple-500 focus:ring-purple-500">
                        <option value="">-- Evaluasi Umum / Mandiri --</option>
                        @foreach ($daftarRencana as $rencana)
                            <option value="{{ $rencana->id }}">{{ $rencana->tujuan_perubahan }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Skor Regulasi Diri (1 - 5 Bintang) -->
                <div>
                    <label class="block text-xs font-bold text-gray-800 mb-2">
                        Skor Kontrol Diri Digital Hari Ini (1 - 5)
                    </label>
                    <div class="flex items-center gap-2">
                        @for ($i = 1; $i <= 5; $i++)
                            <label class="flex-1 text-center p-2.5 rounded-xl border cursor-pointer text-xs transition {{ (int)$skor_regulasi === $i ? 'border-purple-600 bg-purple-100 text-purple-900 font-bold' : 'border-gray-200 hover:border-purple-300' }}">
                                <input type="radio" wire:model="skor_regulasi" value="{{ $i }}" class="sr-only">
                                <div class="flex items-center justify-center gap-1"><svg class="w-3.5 h-3.5 text-amber-500 fill-amber-400" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg><span>{{ $i }}</span></div>
                            </label>
                        @endfor
                    </div>
                    <span class="text-[10px] text-gray-400 mt-1 block">1 = Sangat Sulit Dikontrol &bull; 5 = Sangat Terkendali & Fokus</span>
                    @error('skor_regulasi') <span class="text-rose-600 text-xs font-semibold">{{ $message }}</span> @enderror
                </div>

                <!-- Pengalaman & Tindakan yang Dilakukan -->
                <div>
                    <label class="block text-xs font-bold text-gray-800 mb-1">
                        Pengalaman & Keberhasilan Hari Ini
                    </label>
                    <textarea wire:model="pengalaman_harian" rows="3" placeholder="Contoh: Berhasil fokus belajar 1 jam tanpa menyentuh ponsel. Screen time hari ini turun 40 menit dibanding kemarin..." class="w-full text-xs rounded-xl border-gray-200 focus:border-purple-500 focus:ring-purple-500"></textarea>
                    @error('pengalaman_harian') <span class="text-rose-600 text-xs font-semibold">{{ $message }}</span> @enderror
                </div>

                <!-- Hambatan Distraksi yang Dihadapi -->
                <div>
                    <label class="block text-xs font-bold text-gray-800 mb-1">
                        Hambatan / Distraksi yang Muncul (Opsional)
                    </label>
                    <input type="text" wire:model="hambatan" placeholder="Contoh: Notifikasi chat grup kampus sempat memecah konsentrasi" class="w-full text-xs rounded-xl border-gray-200 focus:border-purple-500 focus:ring-purple-500">
                </div>

                <!-- Langkah Penyesuaian Besok -->
                <div>
                    <label class="block text-xs font-bold text-gray-800 mb-1">
                        Langkah Penyesuaian untuk Esok Hari (Opsional)
                    </label>
                    <input type="text" wire:model="langkah_perbaikan" placeholder="Contoh: Besok aktifkan mode bisu saat belajar di perpustakaan" class="w-full text-xs rounded-xl border-gray-200 focus:border-purple-500 focus:ring-purple-500">
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full py-2.5 bg-gradient-to-r from-purple-700 to-purple-600 hover:from-purple-800 hover:to-purple-700 text-white font-bold text-xs rounded-xl shadow-md transition">
                        Simpan Evaluasi Harian
                    </button>
                </div>
            </form>
        </div>

        <!-- Kolom Kanan: Linimasa Catatan Jurnal -->
        <div class="lg:col-span-2 space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-extrabold text-gray-900">Riwayat Jurnal Evaluasi</h3>
                    <p class="text-xs text-gray-500">Rekapitulasi catatan refleksi dan kemajuan kontrol diri Anda</p>
                </div>
                <span class="text-xs font-bold text-rose-700 bg-rose-50 px-3 py-1 rounded-full border border-rose-100">
                    {{ $daftarJurnal->count() }} Entri Jurnal
                </span>
            </div>

            @if($daftarJurnal->isEmpty())
                <div class="bg-white/95 backdrop-blur-md border border-purple-100 rounded-3xl p-8 text-center space-y-3">
                    <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center mx-auto">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </div>
                    <h4 class="font-bold text-sm text-gray-800">Belum Ada Catatan Jurnal</h4>
                    <p class="text-xs text-gray-500 max-w-sm mx-auto">
                        Mulai tuliskan evaluasi harian pertama Anda untuk memantau sejauh mana strategi regulasi diri berjalan dengan baik.
                    </p>
                </div>
            @else
                <div class="space-y-4">
                    @foreach ($daftarJurnal as $jurnal)
                        <div class="bg-white/95 backdrop-blur-md border border-purple-100 rounded-3xl p-5 shadow-xs space-y-3">
                            <div class="flex items-center justify-between border-b border-gray-100 pb-2.5">
                                <div class="flex items-center gap-2">
                                    <span class="font-extrabold text-xs text-gray-900">
                                        {{ \Carbon\Carbon::parse($jurnal->tanggal)->translatedFormat('l, d F Y') }}
                                    </span>
                                    @if($jurnal->rencanaAksi)
                                        <span class="text-[10px] font-semibold text-purple-700 bg-purple-50 px-2 py-0.5 rounded-md truncate max-w-xs">
                                            Target: {{ $jurnal->rencanaAksi->tujuan_perubahan }}
                                        </span>
                                    @endif
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="inline-flex items-center gap-1 text-xs font-bold px-2.5 py-0.5 rounded-full bg-amber-50 text-amber-700 border border-amber-200">
<svg class="w-3.5 h-3.5 text-amber-500 fill-amber-400" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg> <span>Skor: {{ $jurnal->skor_regulasi }}/5</span>
                                    </span>
                                    <button wire:click="hapusJurnal({{ $jurnal->id }})" wire:confirm="Hapus catatan jurnal ini?" class="p-1 text-gray-400 hover:text-rose-600 rounded-md transition" title="Hapus">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <p class="text-xs text-gray-700 leading-relaxed">
                                {{ $jurnal->pengalaman_harian }}
                            </p>

                            @if($jurnal->hambatan || $jurnal->langkah_perbaikan)
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2 text-xs">
                                    @if($jurnal->hambatan)
                                        <div class="p-3 rounded-2xl bg-rose-50/60 border border-rose-100 text-rose-900">
                                            <span class="font-bold block text-[10px] uppercase tracking-wider text-rose-700 mb-0.5">Hambatan / Distraksi:</span>
                                            {{ $jurnal->hambatan }}
                                        </div>
                                    @endif
                                    @if($jurnal->langkah_perbaikan)
                                        <div class="p-3 rounded-2xl bg-emerald-50/60 border border-emerald-100 text-emerald-900">
                                            <span class="font-bold block text-[10px] uppercase tracking-wider text-emerald-700 mb-0.5">Langkah Besok:</span>
                                            {{ $jurnal->langkah_perbaikan }}
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
