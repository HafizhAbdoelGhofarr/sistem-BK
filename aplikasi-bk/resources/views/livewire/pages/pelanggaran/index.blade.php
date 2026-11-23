<?php

use App\Models\Kelas;
use App\Models\Pelanggaran;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use Livewire\WithPagination;

new #[Layout('layouts.app')] class extends Component
{
    use WithPagination;

    public string $search = '';
    public string $kelas_id = '';
    public string $status_filter = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function with(): array
    {
        $query = Pelanggaran::query()
            ->with(['siswa.kelas', 'jenisPelanggaran', 'pelapor', 'notifikasiWa'])
            ->latest('tanggal');

        // Jika login sebagai siswa, hanya tampilkan data diri sendiri
        if (auth()->user()->hasRole('siswa')) {
            $query->whereHas('siswa', fn ($q) => $q->where('user_id', auth()->id()));
        }

        if ($this->search) {
            $query->whereHas('siswa', fn ($q) => $q->where('nama', 'like', '%' . $this->search . '%')->orWhere('nis', 'like', '%' . $this->search . '%'));
        }

        if ($this->kelas_id) {
            $query->whereHas('siswa', fn ($q) => $q->where('kelas_id', $this->kelas_id));
        }

        if ($this->status_filter) {
            $query->where('status', $this->status_filter);
        }

        return [
            'pelanggaranList' => $query->paginate(15),
            'kelasList' => Kelas::orderBy('nama')->get(),
        ];
    }
}; ?>

<div class="space-y-6">
    <!-- Header Halaman -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs text-gray-500 mb-1">
                <a href="{{ route('dashboard') }}" wire:navigate class="hover:text-purple-700">Dashboard</a>
                <span>&rsaquo;</span>
                <span class="text-purple-700 font-semibold">Riwayat Pelanggaran</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight">Daftar Pelanggaran Tata Tertib</h1>
            <p class="text-xs text-gray-500 mt-0.5">Rekapitulasi catatan ketertiban siswa, poin akumulasi, dan status notifikasi WhatsApp</p>
        </div>

        @role('admin|guru_bk')
            <div class="flex items-center gap-2.5">
                <a href="{{ route('pelanggaran.create') }}" wire:navigate class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-purple-700 to-purple-600 hover:from-purple-800 hover:to-purple-700 text-white text-xs font-bold rounded-xl shadow-md shadow-purple-600/20 transition transform hover:-translate-y-0.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Catat Pelanggaran
                </a>

                <a href="{{ route('bk.laporan.pelanggaran.pdf') }}" target="_blank" class="inline-flex items-center gap-1.5 px-3.5 py-2.5 bg-white border border-gray-200 text-gray-700 hover:text-purple-700 hover:border-purple-200 text-xs font-bold rounded-xl shadow-xs transition">
                    <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                    Unduh PDF
                </a>
            </div>
        @endrole
    </div>

    <!-- Filter & Pencarian Bar -->
    <div class="bg-white/95 backdrop-blur-md border border-purple-100/90 rounded-2xl p-4 sm:p-5 shadow-xs">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3.5">
            <!-- Search Nama Siswa / NIS -->
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari nama siswa atau NIS..."
                       class="block w-full pl-10 py-2 border-gray-200 focus:border-purple-600 focus:ring-purple-500 rounded-xl text-xs text-gray-800 transition shadow-2xs">
            </div>

            <!-- Filter Kelas -->
            <div>
                <select wire:model.live="kelas_id" class="block w-full py-2 border-gray-200 focus:border-purple-600 focus:ring-purple-500 rounded-xl text-xs text-gray-800 transition shadow-2xs">
                    <option value="">Semua Rombel / Kelas</option>
                    @foreach ($kelasList as $k)
                        <option value="{{ $k->id }}">{{ $k->nama }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Filter Status Pelanggaran -->
            <div>
                <select wire:model.live="status_filter" class="block w-full py-2 border-gray-200 focus:border-purple-600 focus:ring-purple-500 rounded-xl text-xs text-gray-800 transition shadow-2xs">
                    <option value="">Semua Status Kasus</option>
                    <option value="baru">Baru Dicatat</option>
                    <option value="dibina">Dalam Pembinaan BK</option>
                    <option value="selesai">Selesai / Ditutup</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Tabel Data Pelanggaran -->
    <div class="bg-white/95 backdrop-blur-md border border-purple-100/90 rounded-3xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-purple-50/60 border-b border-purple-100/80 text-[11px] font-bold uppercase tracking-wider text-purple-900">
                        <th class="py-3.5 px-4">Tanggal</th>
                        <th class="py-3.5 px-4">Nama Siswa & Kelas</th>
                        <th class="py-3.5 px-4">Jenis Pelanggaran</th>
                        <th class="py-3.5 px-4 text-center">Bobot</th>
                        <th class="py-3.5 px-4">Status Kasus</th>
                        <th class="py-3.5 px-4">WhatsApp Ortu</th>
                        <th class="py-3.5 px-4">Pelapor</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-gray-700">
                    @forelse ($pelanggaranList as $item)
                        <tr class="hover:bg-purple-50/30 transition">
                            <!-- Tanggal -->
                            <td class="py-3.5 px-4 whitespace-nowrap font-medium text-gray-600">
                                {{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d M Y') }}
                            </td>

                            <!-- Siswa & Kelas -->
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <div class="font-bold text-gray-900">{{ $item->siswa?->nama ?? 'Siswa Tidak Ditemukan' }}</div>
                                <div class="text-[11px] text-gray-500">
                                    NIS: {{ $item->siswa?->nis }} &bull; {{ $item->siswa?->kelas?->nama }}
                                </div>
                            </td>

                            <!-- Jenis Pelanggaran -->
                            <td class="py-3.5 px-4">
                                <div class="font-semibold text-gray-800">{{ $item->jenisPelanggaran?->nama ?? '-' }}</div>
                                @if ($item->keterangan)
                                    <div class="text-[11px] text-gray-400 mt-0.5 truncate max-w-xs">{{ $item->keterangan }}</div>
                                @endif
                            </td>

                            <!-- Poin -->
                            <td class="py-3.5 px-4 whitespace-nowrap text-center">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-extrabold bg-rose-50 text-rose-700 border border-rose-200">
                                    +{{ $item->poin }} Poin
                                </span>
                            </td>

                            <!-- Status Kasus -->
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                @if ($item->status === 'baru')
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                        Baru
                                    </span>
                                @elseif ($item->status === 'dibina')
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-semibold bg-purple-50 text-purple-700 border border-purple-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-purple-500"></span>
                                        Dalam Pembinaan
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Selesai
                                    </span>
                                @endif
                            </td>

                            <!-- WhatsApp Ortu -->
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                @php $notif = $item->notifikasiWa->first(); @endphp
                                @if ($notif)
                                    @if ($notif->status === 'terkirim')
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-bold bg-emerald-100 text-emerald-800">
                                            ✓ Terkirim
                                        </span>
                                    @elseif ($notif->status === 'gagal')
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-bold bg-rose-100 text-rose-800">
                                            ✕ Gagal
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-bold bg-amber-100 text-amber-800 animate-pulse">
                                            &bull; Menunggu Antrean
                                        </span>
                                    @endif
                                @else
                                    <span class="text-gray-400 text-[11px]">-</span>
                                @endif
                            </td>

                            <!-- Pelapor -->
                            <td class="py-3.5 px-4 whitespace-nowrap text-gray-500">
                                {{ $item->pelapor?->name ?? 'Sistem' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-gray-400">
                                <div class="w-12 h-12 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center mx-auto mb-3">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <span class="font-bold text-sm text-gray-700 block">Belum Ada Catatan Pelanggaran</span>
                                <span class="text-xs text-gray-400">Siswa menjaga ketertiban sekolah dengan baik!</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if ($pelanggaranList->hasPages())
            <div class="p-4 border-t border-gray-100 bg-white">
                {{ $pelanggaranList->links() }}
            </div>
        @endif
    </div>
</div>
