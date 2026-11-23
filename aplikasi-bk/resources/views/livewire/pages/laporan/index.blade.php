<?php

use App\Models\Kelas;
use App\Models\Pelanggaran;
use App\Services\PelanggaranService;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component
{
    public string $tanggal_mulai = '';
    public string $tanggal_selesai = '';
    public string $kelas_id = '';
    public string $kategori = '';

    public function mount(): void
    {
        if (! auth()->user()->hasRole('admin')) {
            abort(403, 'Akses ditolak: Hanya Administrator yang berwenang mengakses laporan rekapitulasi kedisiplinan dan ekspor dokumen.');
        }

        $this->tanggal_mulai = date('Y-m-01'); // Awal bulan ini
        $this->tanggal_selesai = date('Y-m-d'); // Hari ini
    }

    public function hapusDariLaporan(int $id, PelanggaranService $service): void
    {
        $pelanggaran = Pelanggaran::findOrFail($id);
        $service->hapus($pelanggaran);

        session()->flash('success', 'Data pelanggaran berhasil dihapus dari rekapitulasi laporan dan poin siswa telah dikurangi!');
    }

    public function with(): array
    {
        $query = Pelanggaran::with(['siswa.kelas', 'jenisPelanggaran', 'pelapor'])
            ->whereBetween('tanggal', [$this->tanggal_mulai, $this->tanggal_selesai]);

        if ($this->kelas_id) {
            $query->whereHas('siswa', fn ($q) => $q->where('kelas_id', $this->kelas_id));
        }

        if ($this->kategori) {
            $query->whereHas('jenisPelanggaran', fn ($q) => $q->where('kategori', $this->kategori));
        }

        $records = $query->latest('tanggal')->get();

        return [
            'records' => $records,
            'totalKasus' => $records->count(),
            'totalPoin' => $records->sum('poin'),
            'totalSiswa' => $records->pluck('siswa_id')->unique()->count(),
            'kelasList' => Kelas::orderBy('nama')->get(),
        ];
    }
}; ?>

<div class="space-y-6">
    <!-- Flash Notification -->
    @if (session()->has('success'))
        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold flex items-center justify-between shadow-xs">
            <div class="flex items-center gap-2">
                <span class="w-5 h-5 rounded-full bg-emerald-600 text-white flex items-center justify-center text-[10px]">✓</span>
                <span>{{ session('success') }}</span>
            </div>
            <button @click="$el.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700 font-bold">&times;</button>
        </div>
    @endif

    <!-- Header Halaman -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs text-gray-500 mb-1">
                <a href="{{ route('dashboard') }}" wire:navigate class="hover:text-purple-700">Dashboard</a>
                <span>&rsaquo;</span>
                <span class="text-purple-700 font-semibold">Laporan & Rekapitulasi</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight">Rekapitulasi Pelanggaran Siswa</h1>
            <p class="text-xs text-gray-500 mt-0.5">Filter data berdasarkan rentang tanggal dan cetak dokumen resmi untuk evaluasi sekolah</p>
        </div>

        @role('admin')
            <!-- Tombol Ekspor PDF & Excel -->
            <div class="flex items-center gap-2.5">
                <a href="{{ route('bk.laporan.pelanggaran.pdf', ['tanggal_mulai' => $tanggal_mulai, 'tanggal_selesai' => $tanggal_selesai, 'kelas_id' => $kelas_id]) }}" target="_blank"
                   class="inline-flex items-center gap-2 px-4 py-2.5 bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold rounded-xl shadow-md shadow-rose-600/20 transition transform hover:-translate-y-0.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                    Cetak Laporan PDF
                </a>

                <a href="{{ route('bk.laporan.pelanggaran.excel', ['tanggal_mulai' => $tanggal_mulai, 'tanggal_selesai' => $tanggal_selesai, 'kelas_id' => $kelas_id]) }}" target="_blank"
                   class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-md shadow-emerald-600/20 transition transform hover:-translate-y-0.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Ekspor Excel (.xlsx)
                </a>
            </div>
        @endrole
    </div>

    <!-- Filter Form Bar -->
    <div class="bg-white/95 backdrop-blur-md border border-purple-100/90 rounded-2xl p-5 shadow-xs">
        <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
            <div>
                <x-input-label for="tanggal_mulai" :value="__('Dari Tanggal')" class="font-bold text-xs uppercase text-gray-700 mb-1" />
                <x-text-input wire:model.live="tanggal_mulai" id="tanggal_mulai" type="date" class="block w-full py-2" />
            </div>

            <div>
                <x-input-label for="tanggal_selesai" :value="__('Sampai Tanggal')" class="font-bold text-xs uppercase text-gray-700 mb-1" />
                <x-text-input wire:model.live="tanggal_selesai" id="tanggal_selesai" type="date" class="block w-full py-2" />
            </div>

            <div>
                <x-input-label for="kelas_id" :value="__('Filter Kelas')" class="font-bold text-xs uppercase text-gray-700 mb-1" />
                <select wire:model.live="kelas_id" id="kelas_id" class="block w-full border-gray-200 focus:border-purple-600 focus:ring-purple-500 rounded-xl text-xs text-gray-800 py-2">
                    <option value="">Semua Kelas</option>
                    @foreach ($kelasList as $k)
                        <option value="{{ $k->id }}">{{ $k->nama }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <x-input-label for="kategori" :value="__('Kategori Pelanggaran')" class="font-bold text-xs uppercase text-gray-700 mb-1" />
                <select wire:model.live="kategori" id="kategori" class="block w-full border-gray-200 focus:border-purple-600 focus:ring-purple-500 rounded-xl text-xs text-gray-800 py-2">
                    <option value="">Semua Kategori</option>
                    <option value="ringan">Ringan (5 - 10 poin)</option>
                    <option value="sedang">Sedang (15 - 25 poin)</option>
                    <option value="berat">Berat (30 - 55+ poin)</option>
                </select>
            </div>
        </div>
    </div>

    <!-- 3 Kartu Ringkasan Metrik Laporan -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
        <div class="p-5 rounded-2xl bg-white/95 border border-purple-100 shadow-xs">
            <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Total Kasus Tercatat</span>
            <div class="text-3xl font-extrabold text-purple-900 mt-2">{{ $totalKasus }} Kasus</div>
            <p class="text-[11px] text-gray-400 mt-1">Dalam rentang tanggal terpilih</p>
        </div>

        <div class="p-5 rounded-2xl bg-white/95 border border-purple-100 shadow-xs">
            <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Total Akumulasi Poin</span>
            <div class="text-3xl font-extrabold text-rose-600 mt-2">+{{ $totalPoin }} Poin</div>
            <p class="text-[11px] text-gray-400 mt-1">Bobot sanksi terakumulasi</p>
        </div>

        <div class="p-5 rounded-2xl bg-white/95 border border-purple-100 shadow-xs">
            <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Siswa Terlibat</span>
            <div class="text-3xl font-extrabold text-amber-600 mt-2">{{ $totalSiswa }} Siswa</div>
            <p class="text-[11px] text-gray-400 mt-1">Individu yang tercatat melanggar</p>
        </div>
    </div>

    <!-- Preview Tabel Data -->
    <div class="bg-white/95 backdrop-blur-md border border-purple-100/90 rounded-3xl shadow-sm overflow-hidden">
        <div class="p-4 border-b border-gray-100 flex items-center justify-between">
            <span class="text-xs font-bold text-gray-800">Preview Data Rekapitulasi</span>
            <span class="text-xs font-semibold text-purple-700 bg-purple-50 px-2.5 py-1 rounded-lg">
                Menampilkan {{ $totalKasus }} baris data
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-purple-50/60 border-b border-purple-100/80 text-[11px] font-bold uppercase tracking-wider text-purple-900">
                        <th class="py-3 px-4">No</th>
                        <th class="py-3 px-4">Tanggal</th>
                        <th class="py-3 px-4">NIS & Siswa</th>
                        <th class="py-3 px-4">Kelas</th>
                        <th class="py-3 px-4">Jenis Pelanggaran</th>
                        <th class="py-3 px-4 text-center">Kategori</th>
                        <th class="py-3 px-4 text-center">Poin</th>
                        <th class="py-3 px-4">Guru Pelapor</th>
                        @role('admin')
                            <th class="py-3 px-4 text-center">Aksi</th>
                        @endrole
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-gray-700">
                    @forelse ($records as $index => $r)
                        <tr class="hover:bg-purple-50/30 transition">
                            <td class="py-3 px-4 font-mono text-gray-400">{{ $index + 1 }}</td>
                            <td class="py-3 px-4 whitespace-nowrap">{{ \Carbon\Carbon::parse($r->tanggal)->translatedFormat('d/m/Y') }}</td>
                            <td class="py-3 px-4 font-bold text-gray-900 whitespace-nowrap">
                                {{ $r->siswa?->nama }}
                                <span class="text-gray-400 font-normal block text-[11px] font-mono">NIS: {{ $r->siswa?->nis }}</span>
                            </td>
                            <td class="py-3 px-4 whitespace-nowrap">{{ $r->siswa?->kelas?->nama }}</td>
                            <td class="py-3 px-4">
                                <span class="font-medium text-gray-800">{{ $r->jenisPelanggaran?->nama }}</span>
                            </td>
                            <td class="py-3 px-4 text-center">
                                <span class="inline-flex px-2 py-0.5 rounded text-[10px] font-extrabold uppercase {{ $r->jenisPelanggaran?->kategori === 'berat' ? 'bg-rose-100 text-rose-800' : ($r->jenisPelanggaran?->kategori === 'sedang' ? 'bg-amber-100 text-amber-800' : 'bg-emerald-100 text-emerald-800') }}">
                                    {{ $r->jenisPelanggaran?->kategori }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-center font-bold text-rose-600">+{{ $r->poin }}</td>
                            <td class="py-3 px-4 text-gray-500 whitespace-nowrap">{{ $r->pelapor?->name ?? 'Staff BK' }}</td>
                            @role('admin')
                                <td class="py-3 px-4 text-center whitespace-nowrap">
                                    <button wire:click="hapusDariLaporan({{ $r->id }})"
                                            wire:confirm="Hapus catatan pelanggaran ini dari laporan? Poin siswa terkait akan otomatis dihitung ulang."
                                            title="Hapus Data Pelanggaran"
                                            class="p-1.5 text-gray-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </td>
                            @endrole
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ auth()->user()->hasRole('admin') ? 9 : 8 }}" class="py-10 text-center text-gray-400">
                                Tidak ada data pelanggaran pada rentang tanggal dan filter ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
