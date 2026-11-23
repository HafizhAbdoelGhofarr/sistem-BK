<?php

use App\Models\Kelas;
use App\Models\Pelanggaran;
use App\Models\Pembinaan;
use App\Models\Siswa;
use App\Services\PembinaanService;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component
{
    public ?int $kelas_id = null;
    public ?int $siswa_id = null;
    public ?int $pelanggaran_id = null;
    public string $tanggal = '';
    public string $jenis = 'Konseling Pribadi';
    public string $catatan = '';

    public function mount(): void
    {
        $this->tanggal = date('Y-m-d');
        if (request()->has('siswa_id')) {
            $this->siswa_id = (int) request('siswa_id');
            $s = Siswa::find($this->siswa_id);
            if ($s) {
                $this->kelas_id = $s->kelas_id;
            }
        } else {
            $firstKelas = Kelas::first();
            if ($firstKelas) {
                $this->kelas_id = $firstKelas->id;
            }
        }
    }

    public function simpan(PembinaanService $service): void
    {
        $this->validate([
            'siswa_id' => ['required', 'exists:siswa,id'],
            'tanggal' => ['required', 'date'],
            'jenis' => ['required', 'string', 'max:100'],
            'catatan' => ['nullable', 'string', 'max:1000'],
        ]);

        $service->buat([
            'siswa_id' => $this->siswa_id,
            'pelanggaran_id' => $this->pelanggaran_id ?: null,
            'tanggal' => $this->tanggal,
            'jenis' => $this->jenis,
            'catatan' => $this->catatan,
            'status' => 'dijadwalkan',
        ], auth()->user());

        session()->flash('success', 'Jadwal sesi pembinaan konseling berhasil dibuat dan dicatat dalam sistem!');

        $this->redirect(route('pembinaan.index'), navigate: true);
    }
}; ?>

<div class="max-w-4xl mx-auto space-y-6">
    <!-- Breadcrumb & Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <div class="flex items-center gap-2 text-xs text-gray-500 mb-1">
                <a href="{{ route('dashboard') }}" wire:navigate class="hover:text-purple-700">Dashboard</a>
                <span>&rsaquo;</span>
                <a href="{{ route('pembinaan.index') }}" wire:navigate class="hover:text-purple-700">Pembinaan</a>
                <span>&rsaquo;</span>
                <span class="text-purple-700 font-semibold">Jadwalkan Bimbingan</span>
            </div>
            <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Formulir Sesi Pembinaan Konseling</h1>
            <p class="text-xs text-gray-500 mt-0.5">Jadwalkan pendampingan siswa bermasalah dan catat rencana bimbingan karakter</p>
        </div>

        <a href="{{ route('pembinaan.index') }}" wire:navigate class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-white border border-gray-200 text-xs font-semibold text-gray-700 hover:text-purple-700 hover:border-purple-200 shadow-xs transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali ke Daftar
        </a>
    </div>

    <!-- Container Form -->
    <div class="bg-white/95 backdrop-blur-md border border-purple-100 shadow-xl shadow-purple-900/5 rounded-3xl p-6 sm:p-8">
        <form wire:submit="simpan" class="space-y-6">
            
            <!-- Pilih Siswa & Kelas -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <x-input-label for="kelas_id" :value="__('1. Pilih Kelas')" class="font-bold text-gray-800 text-xs uppercase tracking-wider mb-1" />
                    <select wire:model.live="kelas_id" id="kelas_id" class="block w-full border-gray-200 focus:border-purple-600 focus:ring-purple-500 rounded-xl shadow-xs text-sm text-gray-800 transition">
                        <option value="">-- Pilih Kelas --</option>
                        @foreach (\App\Models\Kelas::orderBy('nama')->get() as $k)
                            <option value="{{ $k->id }}">{{ $k->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <x-input-label for="siswa_id" :value="__('2. Nama Siswa yang Dibina')" class="font-bold text-gray-800 text-xs uppercase tracking-wider mb-1" />
                    <select wire:model.live="siswa_id" id="siswa_id" class="block w-full border-gray-200 focus:border-purple-600 focus:ring-purple-500 rounded-xl shadow-xs text-sm text-gray-800 transition">
                        <option value="">-- Pilih Siswa --</option>
                        @if ($kelas_id)
                            @foreach (\App\Models\Siswa::where('kelas_id', $kelas_id)->orderBy('nama')->get() as $s)
                                <option value="{{ $s->id }}">{{ $s->nama }} (Poin: {{ $s->total_poin }} &bull; {{ ucfirst($s->status_disiplin) }})</option>
                            @endforeach
                        @endif
                    </select>
                    <x-input-error :messages="$errors->get('siswa_id')" class="mt-1" />
                </div>
            </div>

            <!-- Preview Siswa & Kasus Pelanggaran Terkait -->
            @if ($siswa_id)
                @php 
                    $selectedSiswa = \App\Models\Siswa::find($siswa_id);
                    $pelanggaranSiswa = \App\Models\Pelanggaran::with('jenisPelanggaran')->where('siswa_id', $siswa_id)->latest('tanggal')->get();
                @endphp
                @if ($selectedSiswa)
                    <div class="p-4 rounded-2xl bg-purple-50/70 border border-purple-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-purple-700 text-white flex items-center justify-center font-bold text-sm">
                                {{ substr($selectedSiswa->nama, 0, 1) }}
                            </div>
                            <div>
                                <span class="font-bold text-sm text-gray-900 block">{{ $selectedSiswa->nama }}</span>
                                <span class="text-xs text-gray-500">
                                    NIS: {{ $selectedSiswa->nis }} &bull; Wali: {{ $selectedSiswa->nama_ortu ?? 'Orang Tua' }} ({{ $selectedSiswa->no_hp_ortu ?? '-' }})
                                </span>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <div class="text-right">
                                <span class="text-[11px] text-gray-500 block">Total Akumulasi:</span>
                                <span class="text-sm font-extrabold text-purple-900">{{ $selectedSiswa->total_poin }} Poin</span>
                            </div>
                            <x-status-badge :status="$selectedSiswa->status_disiplin" />
                        </div>
                    </div>

                    <!-- Hubungkan ke Kasus Pelanggaran (Opsional) -->
                    @if ($pelanggaranSiswa->count() > 0)
                        <div>
                            <x-input-label for="pelanggaran_id" :value="__('3. Hubungkan ke Kasus Pelanggaran (Opsional)')" class="font-bold text-gray-800 text-xs uppercase tracking-wider mb-1" />
                            <select wire:model="pelanggaran_id" id="pelanggaran_id" class="block w-full border-gray-200 focus:border-purple-600 focus:ring-purple-500 rounded-xl shadow-xs text-sm text-gray-800 transition">
                                <option value="">-- Bimbingan Rutin / Tidak Terikat 1 Kasus Spesifik --</option>
                                @foreach ($pelanggaranSiswa as $p)
                                    <option value="{{ $p->id }}">
                                        {{ \Carbon\Carbon::parse($p->tanggal)->translatedFormat('d M Y') }} &bull; {{ $p->jenisPelanggaran?->nama }} (+{{ $p->poin }} Poin) - Status: {{ ucfirst($p->status) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                @endif
            @endif

            <!-- Grid: Jenis Bimbingan & Tanggal Pelaksanaan -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <x-input-label for="jenis" :value="__('4. Bentuk / Jenis Pembinaan')" class="font-bold text-gray-800 text-xs uppercase tracking-wider mb-1" />
                    <select wire:model="jenis" id="jenis" class="block w-full border-gray-200 focus:border-purple-600 focus:ring-purple-500 rounded-xl shadow-xs text-sm text-gray-800 transition">
                        <option value="Konseling Pribadi">Konseling Pribadi (One-on-One)</option>
                        <option value="Bimbingan Karakter">Bimbingan Karakter & Sikap</option>
                        <option value="Panggilan Orang Tua">Pemanggilan Orang Tua / Wali Murid</option>
                        <option value="Mediasi Konflik">Mediasi Siswa / Penyelesaian Konflik</option>
                        <option value="Sanksi Edukatif">Penugasan Sanksi Edukatif Sekolah</option>
                    </select>
                    <x-input-error :messages="$errors->get('jenis')" class="mt-1" />
                </div>

                <div>
                    <x-input-label for="tanggal" :value="__('5. Tanggal Pelaksanaan')" class="font-bold text-gray-800 text-xs uppercase tracking-wider mb-1" />
                    <x-text-input wire:model="tanggal" id="tanggal" type="date" class="block w-full py-2.5" required />
                    <x-input-error :messages="$errors->get('tanggal')" class="mt-1" />
                </div>
            </div>

            <!-- Catatan Masalah & Target Pembinaan -->
            <div>
                <x-input-label for="catatan" :value="__('6. Catatan Masalah / Fokus Bimbingan')" class="font-bold text-gray-800 text-xs uppercase tracking-wider mb-1" />
                <textarea wire:model="catatan" id="catatan" rows="3"
                          placeholder="Tuliskan pokok permasalahan siswa, latar belakang perilaku, serta target perubahan yang diharapkan dari sesi konseling ini..."
                          class="block w-full border-gray-200 focus:border-purple-600 focus:ring-purple-500 rounded-xl shadow-xs text-sm text-gray-800 transition"></textarea>
                <x-input-error :messages="$errors->get('catatan')" class="mt-1" />
            </div>

            <!-- Konselor Pendamping -->
            <div class="p-4 rounded-2xl bg-amber-50/70 border border-amber-200/80 flex items-center justify-between text-xs">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-xl bg-amber-600 text-white flex items-center justify-center font-bold">
                        BK
                    </div>
                    <div>
                        <span class="font-bold text-amber-950 block">Konselor Penanggung Jawab:</span>
                        <span class="text-amber-800">{{ auth()->user()->name }} ({{ auth()->user()->email }})</span>
                    </div>
                </div>
                <span class="text-amber-900 font-bold bg-amber-100 px-2.5 py-1 rounded-lg">Status Awal: Dijadwalkan</span>
            </div>

            <!-- Tombol Submit -->
            <div class="pt-4 flex items-center justify-end gap-3 border-t border-gray-100">
                <a href="{{ route('pembinaan.index') }}" wire:navigate class="px-5 py-2.5 rounded-xl border border-gray-200 text-xs font-semibold text-gray-600 hover:bg-gray-50 transition">
                    Batal
                </a>
                <x-primary-button class="py-3 px-6 shadow-md shadow-purple-600/25 text-xs font-bold uppercase tracking-wider">
                    Jadwalkan Sesi Pembinaan
                </x-primary-button>
            </div>
        </form>
    </div>
</div>
