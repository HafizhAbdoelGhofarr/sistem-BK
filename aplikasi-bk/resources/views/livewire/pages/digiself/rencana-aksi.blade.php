<?php

use App\Models\DigiselfRencanaAksi;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component
{
    public $tujuan_perubahan = '';
    public $langkah_tindakan = '';
    public $waktu_pelaksanaan = '';
    public $indikator_keberhasilan = '';

    public function rules(): array
    {
        return [
            'tujuan_perubahan' => 'required|string|max:255',
            'langkah_tindakan' => 'required|string|max:500',
            'waktu_pelaksanaan' => 'required|string|max:255',
            'indikator_keberhasilan' => 'required|string|max:255',
        ];
    }

    public function simpanRencana(): void
    {
        $this->validate();

        DigiselfRencanaAksi::create([
            'user_id' => auth()->id(),
            'tujuan_perubahan' => $this->tujuan_perubahan,
            'langkah_tindakan' => $this->langkah_tindakan,
            'waktu_pelaksanaan' => $this->waktu_pelaksanaan,
            'indikator_keberhasilan' => $this->indikator_keberhasilan,
            'status' => 'dalam_proses',
        ]);

        $this->reset(['tujuan_perubahan', 'langkah_tindakan', 'waktu_pelaksanaan', 'indikator_keberhasilan']);

        session()->flash('success', 'Rencana aksi regulasi diri berhasil disimpan!');
    }

    public function toggleStatus(int $id): void
    {
        $rencana = DigiselfRencanaAksi::where('user_id', auth()->id())->findOrFail($id);
        $rencana->status = $rencana->status === 'tercapai' ? 'dalam_proses' : 'tercapai';
        $rencana->save();

        session()->flash('success', 'Status rencana aksi berhasil diperbarui!');
    }

    public function hapusRencana(int $id): void
    {
        $rencana = DigiselfRencanaAksi::where('user_id', auth()->id())->findOrFail($id);
        $rencana->delete();

        session()->flash('success', 'Rencana aksi berhasil dihapus.');
    }

    public function with(): array
    {
        return [
            'daftarRencana' => DigiselfRencanaAksi::where('user_id', auth()->id())
                ->latest()
                ->get(),
        ];
    }
}; ?>

<div class="space-y-6">
    <!-- Header Navigasi Submodul -->
    <x-digiself-header 
        active="rencana-aksi" 
        title="Rencana Aksi Digital" 
        description="Mengarahkan hasil refleksi kebiasaan menjadi langkah perubahan perilaku digital yang spesifik, terencana, dan realistis." 
    />

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Kolom Kiri: Form Penyusunan Rencana Aksi -->
        <div class="bg-white/95 backdrop-blur-md border border-purple-100 rounded-3xl p-6 sm:p-8 shadow-sm space-y-6">
            <div class="border-b border-gray-100 pb-4">
                <span class="text-xs font-bold uppercase tracking-wider text-amber-700">Formulir Rencana Aksi</span>
                <h3 class="text-xl font-extrabold text-gray-900 mt-0.5">Buat Target Perubahan Baru</h3>
                <p class="text-xs text-gray-500 mt-1">Pilih satu kebiasaan kecil yang realistis untuk Anda mulai ubah hari ini.</p>
            </div>

            <form wire:submit.prevent="simpanRencana" class="space-y-4">
                <!-- 1. Tujuan Perubahan -->
                <div>
                    <label class="block text-xs font-bold text-gray-800 mb-1">
                        1. Tujuan / Target Perubahan (SMART)
                    </label>
                    <input type="text" wire:model="tujuan_perubahan" placeholder="Contoh: Mengurangi screen time media sosial menjadi 1 jam/hari" class="w-full text-xs rounded-xl border-gray-200 focus:border-purple-500 focus:ring-purple-500">
                    @error('tujuan_perubahan') <span class="text-rose-600 text-xs font-semibold">{{ $message }}</span> @enderror
                </div>

                <!-- 2. Langkah Tindakan Konkret -->
                <div>
                    <label class="block text-xs font-bold text-gray-800 mb-1">
                        2. Langkah Tindakan Spesifik
                    </label>
                    <textarea wire:model="langkah_tindakan" rows="3" placeholder="Contoh: Mengatur batas waktu aplikasi 30 menit, meletakkan HP di laci saat belajar, dan mematikan notifikasi non-akademik" class="w-full text-xs rounded-xl border-gray-200 focus:border-purple-500 focus:ring-purple-500"></textarea>
                    @error('langkah_tindakan') <span class="text-rose-600 text-xs font-semibold">{{ $message }}</span> @enderror
                </div>

                <!-- 3. Waktu Pelaksanaan -->
                <div>
                    <label class="block text-xs font-bold text-gray-800 mb-1">
                        3. Waktu & Frekuensi Pelaksanaan
                    </label>
                    <input type="text" wire:model="waktu_pelaksanaan" placeholder="Contoh: Setiap hari Senin - Jumat pukul 19.00 - 21.00" class="w-full text-xs rounded-xl border-gray-200 focus:border-purple-500 focus:ring-purple-500">
                    @error('waktu_pelaksanaan') <span class="text-rose-600 text-xs font-semibold">{{ $message }}</span> @enderror
                </div>

                <!-- 4. Indikator Keberhasilan -->
                <div>
                    <label class="block text-xs font-bold text-gray-800 mb-1">
                        4. Indikator Keberhasilan Sederhana
                    </label>
                    <input type="text" wire:model="indikator_keberhasilan" placeholder="Contoh: Tugas kuliah selesai sebelum jam 21.00 tanpa membuka TikTok" class="w-full text-xs rounded-xl border-gray-200 focus:border-purple-500 focus:ring-purple-500">
                    @error('indikator_keberhasilan') <span class="text-rose-600 text-xs font-semibold">{{ $message }}</span> @enderror
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full py-2.5 bg-gradient-to-r from-purple-700 to-purple-600 hover:from-purple-800 hover:to-purple-700 text-white font-bold text-xs rounded-xl shadow-md transition">
                        Simpan Target Rencana Aksi
                    </button>
                </div>
            </form>
        </div>

        <!-- Kolom Kanan: Daftar Rencana Aksi Aktif & Selesai -->
        <div class="lg:col-span-2 space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-extrabold text-gray-900">Daftar Rencana Aksi Anda</h3>
                    <p class="text-xs text-gray-500">Pantau eksekusi komitmen dan tandai jika sudah tercapai</p>
                </div>
                <span class="text-xs font-bold text-purple-700 bg-purple-50 px-3 py-1 rounded-full border border-purple-100">
                    {{ $daftarRencana->count() }} Rencana Terdaftar
                </span>
            </div>

            @if($daftarRencana->isEmpty())
                <div class="bg-white/95 backdrop-blur-md border border-purple-100 rounded-3xl p-8 text-center space-y-3">
                    <div class="w-12 h-12 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center mx-auto">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <h4 class="font-bold text-sm text-gray-800">Belum Ada Rencana Aksi</h4>
                    <p class="text-xs text-gray-500 max-w-sm mx-auto">
                        Susun rencana aksi pertama Anda menggunakan formulir di sebelah kiri untuk mulai mengatur kebiasaan digital secara terencana.
                    </p>
                </div>
            @else
                <div class="space-y-3">
                    @foreach ($daftarRencana as $rencana)
                        <div class="bg-white/95 backdrop-blur-md border rounded-3xl p-5 shadow-xs transition duration-200 {{ $rencana->status === 'tercapai' ? 'border-emerald-200 bg-emerald-50/20' : 'border-purple-100' }}">
                            <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-3">
                                <div class="space-y-2 flex-1">
                                    <div class="flex items-center gap-2">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $rencana->status === 'tercapai' ? 'bg-emerald-100 text-emerald-800' : 'bg-purple-100 text-purple-800' }}">
                                            {{ $rencana->status === 'tercapai' ? 'Tercapai âœ“' : 'Sedang Dijalankan' }}
                                        </span>
                                        <span class="text-[11px] text-gray-400">Dibuat {{ $rencana->created_at->translatedFormat('d M Y') }}</span>
                                    </div>
                                    <h4 class="text-sm font-extrabold text-gray-900 leading-snug {{ $rencana->status === 'tercapai' ? 'line-through text-gray-500' : '' }}">
                                        {{ $rencana->tujuan_perubahan }}
                                    </h4>
                                    <p class="text-xs text-gray-600 leading-relaxed">
                                        <strong class="text-gray-800">Langkah:</strong> {{ $rencana->langkah_tindakan }}
                                    </p>
                                    <div class="flex flex-wrap items-center gap-4 text-xs text-gray-500 pt-1">
                                        <span class="flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            {{ $rencana->waktu_pelaksanaan }}
                                        </span>
                                        <span class="flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            Indikator: {{ $rencana->indikator_keberhasilan }}
                                        </span>
                                    </div>
                                </div>

                                <div class="flex items-center gap-2 pt-2 sm:pt-0 flex-shrink-0">
                                    <button wire:click="toggleStatus({{ $rencana->id }})" class="px-3 py-1.5 rounded-xl text-xs font-bold transition {{ $rencana->status === 'tercapai' ? 'bg-amber-100 text-amber-800 hover:bg-amber-200' : 'bg-emerald-600 text-white hover:bg-emerald-700 shadow-sm' }}">
                                        {{ $rencana->status === 'tercapai' ? 'Buka Kembali' : 'Tandai Tercapai' }}
                                    </button>
                                    <button wire:click="hapusRencana({{ $rencana->id }})" wire:confirm="Hapus rencana aksi ini?" class="p-1.5 text-rose-500 hover:text-rose-700 hover:bg-rose-50 rounded-lg transition" title="Hapus">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
