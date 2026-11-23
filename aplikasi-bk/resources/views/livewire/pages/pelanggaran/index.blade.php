<?php

use App\Models\Kelas;
use App\Models\Pelanggaran;
use App\Services\PelanggaranService;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use Livewire\WithPagination;

new #[Layout('layouts.app')] class extends Component
{
    use WithPagination;

    public string $search = '';
    public string $kelas_id = '';
    public string $status_filter = '';

    // State Modal Edit Pelanggaran
    public bool $showEditModal = false;
    public ?int $editId = null;
    public string $edit_nama_siswa = '';
    public string $edit_tanggal = '';
    public string $edit_keterangan = '';
    public string $edit_status = 'baru';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function editPelanggaran(int $id): void
    {
        $pelanggaran = Pelanggaran::with('siswa')->findOrFail($id);
        $this->editId = $pelanggaran->id;
        $this->edit_nama_siswa = $pelanggaran->siswa?->nama ?? '-';
        $this->edit_tanggal = $pelanggaran->tanggal ? date('Y-m-d', strtotime($pelanggaran->tanggal)) : date('Y-m-d');
        $this->edit_keterangan = $pelanggaran->keterangan ?? '';
        $this->edit_status = $pelanggaran->status ?? 'baru';
        $this->showEditModal = true;
    }

    public function simpanPerubahan(PelanggaranService $service): void
    {
        $this->validate([
            'edit_tanggal' => ['required', 'date'],
            'edit_status' => ['required', 'in:baru,dibina,selesai'],
            'edit_keterangan' => ['nullable', 'string', 'max:500'],
        ]);

        $pelanggaran = Pelanggaran::findOrFail($this->editId);

        $service->perbarui($pelanggaran, [
            'tanggal' => $this->edit_tanggal,
            'status' => $this->edit_status,
            'keterangan' => $this->edit_keterangan,
        ]);

        $this->showEditModal = false;
        $this->reset(['editId', 'edit_nama_siswa', 'edit_tanggal', 'edit_keterangan', 'edit_status']);

        session()->flash('success', 'Catatan pelanggaran berhasil diperbarui!');
    }

    public function hapusPelanggaran(int $id, PelanggaranService $service): void
    {
        $pelanggaran = Pelanggaran::findOrFail($id);
        $service->hapus($pelanggaran);

        session()->flash('success', 'Catatan pelanggaran berhasil dihapus dan akumulasi poin siswa otomatis diperbarui!');
    }

    public function with(): array
    {
        $query = Pelanggaran::query()
            ->with(['siswa.kelas', 'jenisPelanggaran', 'pelapor', 'notifikasiWa'])
            ->latest('tanggal');

        $isSiswa = auth()->user()->hasRole('siswa');
        $siswaLogin = null;

        // Jika login sebagai siswa, HANYA tampilkan data diri sendiri
        if ($isSiswa) {
            $siswaLogin = auth()->user()->siswa;
            if ($siswaLogin) {
                $query->where('siswa_id', $siswaLogin->id);
            } else {
                $query->whereHas('siswa', fn ($q) => $q->where('user_id', auth()->id()));
            }
        } else {
            // Filter & Pencarian HANYA aktif untuk Guru BK / Admin
            if ($this->search) {
                $query->whereHas('siswa', fn ($q) => $q->where('nama', 'like', '%' . $this->search . '%')->orWhere('nis', 'like', '%' . $this->search . '%'));
            }

            if ($this->kelas_id) {
                $query->whereHas('siswa', fn ($q) => $q->where('kelas_id', $this->kelas_id));
            }

            if ($this->status_filter) {
                $query->where('status', $this->status_filter);
            }
        }

        return [
            'pelanggaranList' => $query->paginate(15),
            'kelasList' => Kelas::orderBy('nama')->get(),
            'siswaLogin' => $siswaLogin ?? auth()->user()->siswa,
            'isSiswa' => $isSiswa,
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
                <span class="text-purple-700 font-semibold">{{ $isSiswa ? 'Pelanggaran Saya' : 'Riwayat Pelanggaran' }}</span>
            </div>
            @if ($isSiswa)
                <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight">Catatan Pelanggaran Saya</h1>
                <p class="text-xs text-gray-500 mt-0.5">Monitoring ketertiban diri sendiri dan riwayat poin kedisiplinan yang tercatat di sekolah</p>
            @else
                <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight">Daftar Pelanggaran Tata Tertib</h1>
                <p class="text-xs text-gray-500 mt-0.5">Rekapitulasi catatan ketertiban siswa, poin akumulasi, dan status notifikasi WhatsApp</p>
            @endif
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

    <!-- Khusus Siswa: Card Status Kedisiplinan Pribadi -->
    @if ($isSiswa)
        <div class="p-5 rounded-3xl bg-gradient-to-r from-purple-900 via-purple-800 to-indigo-900 text-white shadow-xl shadow-purple-950/15 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-white/15 backdrop-blur-md flex items-center justify-center text-white font-extrabold text-lg flex-shrink-0">
                    {{ strtoupper(substr($siswaLogin?->nama ?? auth()->user()->name, 0, 1)) }}
                </div>
                <div>
                    <span class="text-[11px] font-bold text-purple-200 uppercase tracking-wider block">Akun Siswa Terdaftar</span>
                    <h2 class="text-lg font-bold">{{ $siswaLogin?->nama ?? auth()->user()->name }}</h2>
                    <div class="text-xs text-purple-200 flex flex-wrap items-center gap-2 mt-0.5">
                        <span>NIS: <strong class="text-white">{{ $siswaLogin?->nis ?? '-' }}</strong></span>
                        <span>&bull;</span>
                        <span>Kelas: <strong class="text-white">{{ $siswaLogin?->kelas?->nama ?? '-' }}</strong></span>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-5 sm:border-l sm:border-white/15 sm:pl-6">
                <div>
                    <span class="text-[10px] text-purple-200 uppercase block font-semibold">Akumulasi Poin:</span>
                    <span class="text-2xl font-black text-rose-300">{{ $siswaLogin?->total_poin ?? 0 }} Poin</span>
                </div>
                <div>
                    <span class="text-[10px] text-purple-200 uppercase block font-semibold mb-1">Status Disiplin:</span>
                    <x-status-badge :status="$siswaLogin?->status_disiplin ?? 'aman'" />
                </div>
            </div>
        </div>
    @endif

    <!-- Filter & Pencarian Bar (HANYA MUNCUL UNTUK GURU BK & ADMIN) -->
    @role('admin|guru_bk')
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
    @endrole

    <!-- Tabel Data Pelanggaran -->
    <div class="bg-white/95 backdrop-blur-md border border-purple-100/90 rounded-3xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-purple-50/60 border-b border-purple-100/80 text-[11px] font-bold uppercase tracking-wider text-purple-900">
                        <th class="py-3.5 px-4">Tanggal</th>
                        @role('admin|guru_bk')
                            <th class="py-3.5 px-4">Nama Siswa & Kelas</th>
                        @endrole
                        <th class="py-3.5 px-4">Jenis Pelanggaran</th>
                        <th class="py-3.5 px-4 text-center">Bobot</th>
                        <th class="py-3.5 px-4">Status Kasus</th>
                        <th class="py-3.5 px-4">WhatsApp Ortu</th>
                        @role('admin|guru_bk')
                            <th class="py-3.5 px-4">Pelapor</th>
                            <th class="py-3.5 px-4 text-center">Aksi</th>
                        @endrole
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-gray-700">
                    @forelse ($pelanggaranList as $item)
                        <tr class="hover:bg-purple-50/30 transition">
                            <!-- Tanggal -->
                            <td class="py-3.5 px-4 whitespace-nowrap font-medium text-gray-600">
                                {{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d M Y') }}
                            </td>

                            <!-- Siswa & Kelas (Hanya untuk Admin/Guru BK) -->
                            @role('admin|guru_bk')
                                <td class="py-3.5 px-4 whitespace-nowrap">
                                    <div class="font-bold text-gray-900">{{ $item->siswa?->nama ?? 'Siswa Tidak Ditemukan' }}</div>
                                    <div class="text-[11px] text-gray-500">
                                        NIS: {{ $item->siswa?->nis }} &bull; {{ $item->siswa?->kelas?->nama }}
                                    </div>
                                </td>
                            @endrole

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

                            <!-- Pelapor (Khusus Guru BK & Admin) -->
                            @role('admin|guru_bk')
                                <td class="py-3.5 px-4 whitespace-nowrap text-gray-500">
                                    {{ $item->pelapor?->name ?? 'Sistem' }}
                                </td>
                            @endrole

                            <!-- Aksi Edit & Hapus (Admin & Guru BK) -->
                            @role('admin|guru_bk')
                                <td class="py-3.5 px-4 whitespace-nowrap text-center">
                                    <div class="inline-flex items-center gap-1.5">
                                        <!-- Edit -->
                                        <button wire:click="editPelanggaran({{ $item->id }})" title="Edit Catatan Kasus"
                                                class="p-1.5 text-gray-500 hover:text-purple-700 hover:bg-purple-50 rounded-lg transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>

                                        <!-- Hapus -->
                                        <button wire:click="hapusPelanggaran({{ $item->id }})"
                                                wire:confirm="Yakin ingin menghapus catatan pelanggaran ini? Akumulasi poin siswa akan otomatis dikurangi kembali."
                                                title="Hapus Pelanggaran"
                                                class="p-1.5 text-gray-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            @endrole
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ auth()->user()->hasRole('siswa') ? 5 : 8 }}" class="py-12 text-center text-gray-400">
                                <div class="w-12 h-12 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center mx-auto mb-3">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <span class="font-bold text-sm text-gray-700 block">
                                    {{ auth()->user()->hasRole('siswa') ? 'Catatan Kedisiplinan Anda Bersih!' : 'Belum Ada Catatan Pelanggaran' }}
                                </span>
                                <span class="text-xs text-gray-400">
                                    {{ auth()->user()->hasRole('siswa') ? 'Pertahankan sikap tertib dan budi pekerti luhur di sekolah.' : 'Siswa menjaga ketertiban sekolah dengan baik!' }}
                                </span>
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

    <!-- Modal Edit Pelanggaran (Admin & Guru BK) -->
    @if ($showEditModal)
        <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
            <div class="bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl border border-purple-100 text-left relative animate-in fade-in zoom-in-95 duration-150">
                <div class="flex items-center justify-between pb-4 border-b border-gray-100 mb-5">
                    <div>
                        <span class="text-[11px] font-bold text-purple-700 uppercase tracking-wider">Perbarui Data</span>
                        <h3 class="text-base font-extrabold text-gray-900">Koreksi Catatan Pelanggaran</h3>
                        <p class="text-xs text-gray-500 mt-0.5">Siswa: <span class="font-bold text-purple-900">{{ $edit_nama_siswa }}</span></p>
                    </div>
                    <button wire:click="$set('showEditModal', false)" class="text-gray-400 hover:text-gray-600 font-bold text-xl leading-none">&times;</button>
                </div>

                <form wire:submit="simpanPerubahan" class="space-y-4">
                    <!-- Tanggal Kejadian -->
                    <div>
                        <x-input-label for="edit_tanggal" :value="__('Tanggal Kejadian')" class="font-bold text-xs text-gray-700" />
                        <x-text-input wire:model="edit_tanggal" id="edit_tanggal" type="date" class="mt-1 block w-full py-2" required />
                        <x-input-error :messages="$errors->get('edit_tanggal')" class="mt-1" />
                    </div>

                    <!-- Status Kasus -->
                    <div>
                        <x-input-label for="edit_status" :value="__('Status Kasus Kedisiplinan')" class="font-bold text-xs text-gray-700" />
                        <select wire:model="edit_status" id="edit_status" class="mt-1 block w-full border-gray-200 focus:border-purple-600 focus:ring-purple-500 rounded-xl text-xs text-gray-800 py-2">
                            <option value="baru">Baru Dicatat</option>
                            <option value="dibina">Dalam Pembinaan BK</option>
                            <option value="selesai">Selesai / Ditutup</option>
                        </select>
                        <x-input-error :messages="$errors->get('edit_status')" class="mt-1" />
                    </div>

                    <!-- Keterangan Tambahan / Kronologi -->
                    <div>
                        <x-input-label for="edit_keterangan" :value="__('Keterangan / Kronologi Kasus')" class="font-bold text-xs text-gray-700" />
                        <textarea wire:model="edit_keterangan" id="edit_keterangan" rows="3"
                                  class="mt-1 block w-full border-gray-200 focus:border-purple-600 focus:ring-purple-500 rounded-xl text-xs text-gray-800 shadow-2xs placeholder-gray-400"
                                  placeholder="Catatan tambahan hasil klarifikasi atau koreksi..."></textarea>
                        <x-input-error :messages="$errors->get('edit_keterangan')" class="mt-1" />
                    </div>

                    <!-- Action Buttons -->
                    <div class="pt-3 flex items-center justify-end gap-2.5 border-t border-gray-100">
                        <button type="button" wire:click="$set('showEditModal', false)"
                                class="px-4 py-2 border border-gray-200 text-gray-600 hover:bg-gray-50 rounded-xl text-xs font-bold transition">
                            Batal
                        </button>
                        <button type="submit"
                                class="px-5 py-2 bg-gradient-to-r from-purple-700 to-purple-600 hover:from-purple-800 hover:to-purple-700 text-white rounded-xl text-xs font-bold shadow-md shadow-purple-600/20 transition">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
