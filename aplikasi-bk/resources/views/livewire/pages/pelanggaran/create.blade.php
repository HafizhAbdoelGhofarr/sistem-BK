<?php

use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\JenisPelanggaran;
use App\Services\PelanggaranService;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component
{
    public ?int $kelas_id = null;
    public ?int $siswa_id = null;
    public ?int $jenis_pelanggaran_id = null;
    public string $tanggal = '';
    public string $keterangan = '';

    public function mount(): void
    {
        if (auth()->user()->hasRole('siswa')) {
            abort(403, 'Akses ditolak: Siswa tidak memiliki izin untuk mencatat pelanggaran.');
        }

        $this->tanggal = date('Y-m-d');
        $firstKelas = Kelas::first();
        if ($firstKelas) {
            $this->kelas_id = $firstKelas->id;
        }
    }

    public function simpan(PelanggaranService $service): void
    {
        $this->validate([
            'siswa_id' => ['required', 'exists:siswa,id'],
            'jenis_pelanggaran_id' => ['required', 'exists:jenis_pelanggaran,id'],
            'tanggal' => ['required', 'date'],
            'keterangan' => ['nullable', 'string', 'max:500'],
        ]);

        $pelanggaran = $service->buat([
            'siswa_id' => $this->siswa_id,
            'jenis_pelanggaran_id' => $this->jenis_pelanggaran_id,
            'pelapor_id' => auth()->id(),
            'tanggal' => $this->tanggal,
            'keterangan' => $this->keterangan,
        ]);

        session()->flash('success', 'Pelanggaran siswa berhasil dicatat! Notifikasi WhatsApp otomatis telah dijadwalkan ke nomor orang tua/wali.');

        $this->redirect(route('pelanggaran.index'), navigate: true);
    }
}; ?>

<div class="max-w-4xl mx-auto space-y-6">
    <!-- Breadcrumb / Header Form -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <div class="flex items-center gap-2 text-xs text-gray-500 mb-1">
                <a href="{{ route('dashboard') }}" wire:navigate class="hover:text-purple-700">Dashboard</a>
                <span>&rsaquo;</span>
                <a href="{{ route('pelanggaran.index') }}" wire:navigate class="hover:text-purple-700">Pelanggaran</a>
                <span>&rsaquo;</span>
                <span class="text-purple-700 font-semibold">Catat Pelanggaran</span>
            </div>
            <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Formulir Pencatatan Pelanggaran Siswa</h1>
            <p class="text-xs text-gray-500 mt-0.5">Input data pelanggaran tata tertib dan picu pengiriman notifikasi WhatsApp Sidobe ke orang tua</p>
        </div>

        <a href="{{ route('pelanggaran.index') }}" wire:navigate class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-white border border-gray-200 text-xs font-semibold text-gray-700 hover:text-purple-700 hover:border-purple-200 shadow-xs transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali ke Daftar
        </a>
    </div>

    <!-- Container Form Putih & Ungu -->
    <div class="bg-white/95 backdrop-blur-md border border-purple-100 shadow-xl shadow-purple-900/5 rounded-3xl p-6 sm:p-8">
        <form wire:submit="simpan" class="space-y-6">
            
            <!-- Grid 2 Kolom: Kelas & Siswa -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <!-- Pilihan Kelas -->
                <div>
                    <x-input-label for="kelas_id" :value="__('1. Pilih Kelas Siswa')" class="font-bold text-gray-800 text-xs uppercase tracking-wider mb-1" />
                    <select wire:model.live="kelas_id" id="kelas_id" class="block w-full border-gray-200 focus:border-purple-600 focus:ring-purple-500 rounded-xl shadow-xs text-sm text-gray-800 transition">
                        <option value="">-- Pilih Rombel / Tingkat --</option>
                        @foreach (\App\Models\Kelas::orderBy('nama')->get() as $k)
                            <option value="{{ $k->id }}">{{ $k->nama }} ({{ $k->jurusan }})</option>
                        @endforeach
                    </select>
                </div>

                <!-- Pilihan Siswa -->
                <div>
                    <x-input-label for="siswa_id" :value="__('2. Pilih Nama Siswa')" class="font-bold text-gray-800 text-xs uppercase tracking-wider mb-1" />
                    <select wire:model.live="siswa_id" id="siswa_id" class="block w-full border-gray-200 focus:border-purple-600 focus:ring-purple-500 rounded-xl shadow-xs text-sm text-gray-800 transition">
                        <option value="">-- Pilih Siswa yang Melanggar --</option>
                        @if ($kelas_id)
                            @foreach (\App\Models\Siswa::where('kelas_id', $kelas_id)->orderBy('nama')->get() as $s)
                                <option value="{{ $s->id }}">{{ $s->nama }} (NIS: {{ $s->nis }}) - Poin: {{ $s->total_poin }}</option>
                            @endforeach
                        @else
                            @foreach (\App\Models\Siswa::orderBy('nama')->limit(30)->get() as $s)
                                <option value="{{ $s->id }}">{{ $s->nama }} (Kelas: {{ $s->kelas?->nama }})</option>
                            @endforeach
                        @endif
                    </select>
                    <x-input-error :messages="$errors->get('siswa_id')" class="mt-1" />
                </div>
            </div>

            <!-- Kartu Preview Siswa Terpilih (Jika Siswa Dipilih) -->
            @if ($siswa_id)
                @php $selectedSiswa = \App\Models\Siswa::find($siswa_id); @endphp
                @if ($selectedSiswa)
                    <div class="p-4 rounded-2xl bg-purple-50/70 border border-purple-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3 animate-fade-in">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-purple-700 text-white flex items-center justify-center font-bold text-sm">
                                {{ substr($selectedSiswa->nama, 0, 1) }}
                            </div>
                            <div>
                                <span class="font-bold text-sm text-gray-900 block">{{ $selectedSiswa->nama }}</span>
                                <span class="text-xs text-gray-500">
                                    NIS: {{ $selectedSiswa->nis }} &bull; Kelas: {{ $selectedSiswa->kelas?->nama }} &bull; Wali: {{ $selectedSiswa->nama_ortu ?? 'Orang Tua' }}
                                </span>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <div class="text-right">
                                <span class="text-[11px] text-gray-500 block">Poin Saat Ini:</span>
                                <span class="text-sm font-extrabold text-purple-900">{{ $selectedSiswa->total_poin }} Poin</span>
                            </div>
                            <x-status-badge :status="$selectedSiswa->status_disiplin" />
                        </div>
                    </div>
                @endif
            @endif

            <!-- Pilihan Jenis Pelanggaran -->
            <div>
                <x-input-label for="jenis_pelanggaran_id" :value="__('3. Jenis Pelanggaran Tata Tertib')" class="font-bold text-gray-800 text-xs uppercase tracking-wider mb-1" />
                <select wire:model.live="jenis_pelanggaran_id" id="jenis_pelanggaran_id" class="block w-full border-gray-200 focus:border-purple-600 focus:ring-purple-500 rounded-xl shadow-xs text-sm text-gray-800 transition">
                    <option value="">-- Pilih Pelanggaran yang Dilakukan --</option>
                    @foreach (\App\Models\JenisPelanggaran::where('is_active', true)->get() as $jp)
                        <option value="{{ $jp->id }}">
                            [{{ strtoupper($jp->kategori) }}] {{ $jp->nama }} &bull; (+{{ $jp->poin }} Poin)
                        </option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('jenis_pelanggaran_id')" class="mt-1" />
            </div>

            <!-- Preview Poin Pelanggaran Terpilih -->
            @if ($jenis_pelanggaran_id)
                @php $selectedJp = \App\Models\JenisPelanggaran::find($jenis_pelanggaran_id); @endphp
                @if ($selectedJp)
                    <div class="p-4 rounded-2xl bg-amber-50/70 border border-amber-200/80 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <span class="w-8 h-8 rounded-lg bg-amber-500 text-white flex items-center justify-center font-bold text-xs">!</span>
                            <div>
                                <span class="text-xs font-bold text-amber-900 block">{{ $selectedJp->nama }}</span>
                                <span class="text-[11px] text-amber-700">Kategori: {{ ucfirst($selectedJp->kategori) }} &bull; Otomatis diakumulasikan ke profil siswa</span>
                            </div>
                        </div>
                        <span class="text-base font-extrabold text-amber-900">+{{ $selectedJp->poin }} Poin</span>
                    </div>
                @endif
            @endif

            <!-- Grid: Tanggal & Keterangan -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <x-input-label for="tanggal" :value="__('4. Tanggal Kejadian')" class="font-bold text-gray-800 text-xs uppercase tracking-wider mb-1" />
                    <x-text-input wire:model="tanggal" id="tanggal" type="date" class="block w-full py-2.5" required />
                    <x-input-error :messages="$errors->get('tanggal')" class="mt-1" />
                </div>

                <div>
                    <x-input-label for="pelapor" :value="__('Pelapor / Guru Pencatat')" class="font-bold text-gray-800 text-xs uppercase tracking-wider mb-1" />
                    <input type="text" disabled value="{{ auth()->user()->name }} ({{ auth()->user()->roles->first()->name ?? 'Staff' }})"
                           class="block w-full bg-gray-50 border-gray-200 rounded-xl text-sm text-gray-500 py-2.5 cursor-not-allowed">
                </div>
            </div>

            <!-- Catatan Keterangan Tambahan -->
            <div>
                <x-input-label for="keterangan" :value="__('5. Kronologi / Catatan Kejadian (Opsional)')" class="font-bold text-gray-800 text-xs uppercase tracking-wider mb-1" />
                <textarea wire:model="keterangan" id="keterangan" rows="3"
                          placeholder="Tuliskan keterangan detail kejadian, lokasi, atau barang bukti yang diamankan..."
                          class="block w-full border-gray-200 focus:border-purple-600 focus:ring-purple-500 rounded-xl shadow-xs text-sm text-gray-800 transition"></textarea>
                <x-input-error :messages="$errors->get('keterangan')" class="mt-1" />
            </div>

            <!-- Banner Notifikasi Otomatis WhatsApp Sidobe -->
            <div class="p-4 rounded-2xl bg-emerald-50/80 border border-emerald-200 flex items-start gap-3">
                <div class="w-8 h-8 rounded-xl bg-emerald-600 text-white flex items-center justify-center flex-shrink-0 shadow-xs">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                </div>
                <div class="text-xs text-emerald-900 leading-relaxed">
                    <span class="font-bold block">Pemberitahuan WhatsApp Otomatis:</span>
                    Setelah data disimpan, sistem antrean akan otomatis mengirimkan pesan konfirmasi pelanggaran dan rincian poin langsung ke nomor WhatsApp orang tua/wali murid yang terdaftar melalui <strong>Sidobe Gateway</strong>.
                </div>
            </div>

            <!-- Tombol Submit -->
            <div class="pt-4 flex items-center justify-end gap-3 border-t border-gray-100">
                <a href="{{ route('pelanggaran.index') }}" wire:navigate class="px-5 py-2.5 rounded-xl border border-gray-200 text-xs font-semibold text-gray-600 hover:bg-gray-50 transition">
                    Batal
                </a>
                <x-primary-button class="py-3 px-6 shadow-md shadow-purple-600/25 text-xs font-bold uppercase tracking-wider">
                    Simpan Pelanggaran & Kirim WA
                </x-primary-button>
            </div>
        </form>
    </div>
</div>
