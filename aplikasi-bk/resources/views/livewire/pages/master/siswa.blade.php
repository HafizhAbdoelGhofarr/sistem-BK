<?php

use App\Models\Kelas;
use App\Models\Siswa;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use Livewire\WithPagination;

new #[Layout('layouts.app')] class extends Component
{
    use WithPagination;

    public string $search = '';
    public string $kelas_id = '';
    public bool $showTambahModal = false;

    // Form Tambah Siswa Baru
    public string $nis = '';
    public string $nama = '';
    public string $jenis_kelamin = 'L';
    public ?int $new_kelas_id = null;
    public string $nama_ortu = '';
    public string $no_hp_ortu = '';

    // Form Edit Siswa
    public bool $showEditModal = false;
    public ?int $editSiswaId = null;
    public string $edit_nis = '';
    public string $edit_nama = '';
    public ?int $edit_kelas_id = null;
    public string $edit_nama_ortu = '';
    public string $edit_no_hp_ortu = '';

    public function mount(): void
    {
        $firstKelas = Kelas::first();
        if ($firstKelas) {
            $this->new_kelas_id = $firstKelas->id;
            $this->edit_kelas_id = $firstKelas->id;
        }
    }

    public function simpanSiswa(): void
    {
        $this->validate([
            'nis' => ['required', 'string', 'unique:siswa,nis'],
            'nama' => ['required', 'string', 'max:100'],
            'new_kelas_id' => ['required', 'exists:kelas,id'],
            'no_hp_ortu' => ['required', 'string', 'max:20'],
        ]);

        Siswa::create([
            'nis' => $this->nis,
            'nama' => $this->nama,
            'jenis_kelamin' => $this->jenis_kelamin,
            'kelas_id' => $this->new_kelas_id,
            'nama_ortu' => $this->nama_ortu,
            'no_hp_ortu' => $this->no_hp_ortu,
            'total_poin' => 0,
            'status_disiplin' => 'aman',
        ]);

        $this->showTambahModal = false;
        $this->reset(['nis', 'nama', 'nama_ortu', 'no_hp_ortu']);

        session()->flash('success', 'Data siswa baru berhasil ditambahkan ke dalam database sekolah!');
    }

    public function editSiswa(int $id): void
    {
        $siswa = Siswa::findOrFail($id);
        $this->editSiswaId = $siswa->id;
        $this->edit_nis = $siswa->nis;
        $this->edit_nama = $siswa->nama;
        $this->edit_kelas_id = $siswa->kelas_id;
        $this->edit_nama_ortu = $siswa->nama_ortu ?? '';
        $this->edit_no_hp_ortu = $siswa->no_hp_ortu ?? '';
        $this->showEditModal = true;
    }

    public function simpanEditSiswa(): void
    {
        $this->validate([
            'edit_nis' => ['required', 'string', 'unique:siswa,nis,' . $this->editSiswaId],
            'edit_nama' => ['required', 'string', 'max:100'],
            'edit_kelas_id' => ['required', 'exists:kelas,id'],
            'edit_no_hp_ortu' => ['required', 'string', 'max:20'],
        ]);

        $siswa = Siswa::findOrFail($this->editSiswaId);
        $siswa->update([
            'nis' => $this->edit_nis,
            'nama' => $this->edit_nama,
            'kelas_id' => $this->edit_kelas_id,
            'nama_ortu' => $this->edit_nama_ortu,
            'no_hp_ortu' => $this->edit_no_hp_ortu,
        ]);

        $this->showEditModal = false;
        $this->reset(['editSiswaId', 'edit_nis', 'edit_nama', 'edit_nama_ortu', 'edit_no_hp_ortu']);

        session()->flash('success', 'Data siswa berhasil diperbarui!');
    }

    public function hapusSiswa(int $id): void
    {
        $siswa = Siswa::findOrFail($id);
        $siswa->delete();

        session()->flash('success', 'Data siswa berhasil dihapus dari sistem!');
    }

    public function with(): array
    {
        $query = Siswa::with('kelas')->latest();

        if ($this->search) {
            $query->where('nama', 'like', '%' . $this->search . '%')
                  ->orWhere('nis', 'like', '%' . $this->search . '%');
        }

        if ($this->kelas_id) {
            $query->where('kelas_id', $this->kelas_id);
        }

        return [
            'siswaList' => $query->paginate(15),
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
                <span class="text-purple-700 font-semibold">Master Data</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight">Manajemen Data Siswa</h1>
            <p class="text-xs text-gray-500 mt-0.5">Kelola data induk siswa, penempatan rombongan belajar, dan kontak WhatsApp wali murid</p>
        </div>

        @role('admin')
            <button wire:click="$set('showTambahModal', true)" class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-purple-700 to-purple-600 hover:from-purple-800 hover:to-purple-700 text-white text-xs font-bold rounded-xl shadow-md shadow-purple-600/20 transition transform hover:-translate-y-0.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                </svg>
                Tambah Siswa Baru
            </button>
        @endrole
    </div>

    <!-- Filter Bar -->
    <div class="bg-white/95 backdrop-blur-md border border-purple-100/90 rounded-2xl p-4 sm:p-5 shadow-xs">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3.5">
            <div class="relative sm:col-span-2">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari berdasarkan nama siswa atau NIS..."
                       class="block w-full pl-10 py-2 border-gray-200 focus:border-purple-600 focus:ring-purple-500 rounded-xl text-xs text-gray-800 transition shadow-2xs">
            </div>

            <div>
                <select wire:model.live="kelas_id" class="block w-full py-2 border-gray-200 focus:border-purple-600 focus:ring-purple-500 rounded-xl text-xs text-gray-800 transition shadow-2xs">
                    <option value="">Semua Rombel / Kelas</option>
                    @foreach ($kelasList as $k)
                        <option value="{{ $k->id }}">{{ $k->nama }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <!-- Tabel Data Siswa -->
    <div class="bg-white/95 backdrop-blur-md border border-purple-100/90 rounded-3xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-purple-50/60 border-b border-purple-100/80 text-[11px] font-bold uppercase tracking-wider text-purple-900">
                        <th class="py-3.5 px-4">NIS</th>
                        <th class="py-3.5 px-4">Nama Lengkap</th>
                        <th class="py-3.5 px-4">Kelas</th>
                        <th class="py-3.5 px-4">Wali Murid</th>
                        <th class="py-3.5 px-4">No. WhatsApp Ortu</th>
                        <th class="py-3.5 px-4 text-center">Akumulasi Poin</th>
                        <th class="py-3.5 px-4 text-center">Status Disiplin</th>
                        @role('admin')
                            <th class="py-3.5 px-4 text-center">Aksi</th>
                        @endrole
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-gray-700">
                    @forelse ($siswaList as $s)
                        <tr class="hover:bg-purple-50/30 transition">
                            <td class="py-3.5 px-4 font-mono font-semibold text-purple-800">{{ $s->nis }}</td>
                            <td class="py-3.5 px-4 font-bold text-gray-900">{{ $s->nama }}</td>
                            <td class="py-3.5 px-4">{{ $s->kelas?->nama ?? '-' }}</td>
                            <td class="py-3.5 px-4 text-gray-600">{{ $s->nama_ortu ?? 'Orang Tua' }}</td>
                            <td class="py-3.5 px-4 font-mono text-purple-700 font-semibold">{{ $s->no_hp_ortu ?? '-' }}</td>
                            <td class="py-3.5 px-4 text-center font-extrabold text-sm">{{ $s->total_poin }}</td>
                            <td class="py-3.5 px-4 text-center">
                                <x-status-badge :status="$s->status_disiplin" />
                            </td>
                            @role('admin')
                                <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                    <div class="inline-flex items-center gap-1.5">
                                        <!-- Edit Siswa -->
                                        <button wire:click="editSiswa({{ $s->id }})" title="Edit Data Siswa"
                                                class="p-1.5 text-gray-500 hover:text-purple-700 hover:bg-purple-50 rounded-lg transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>

                                        <!-- Hapus Siswa -->
                                        <button wire:click="hapusSiswa({{ $s->id }})"
                                                wire:confirm="Yakin ingin menghapus data siswa ini secara permanen dari sistem?"
                                                title="Hapus Siswa"
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
                            <td colspan="{{ auth()->user()->hasRole('admin') ? 8 : 7 }}" class="py-12 text-center text-gray-400">
                                Tidak ada data siswa ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($siswaList->hasPages())
            <div class="p-4 border-t border-gray-100 bg-white">
                {{ $siswaList->links() }}
            </div>
        @endif
    </div>

    <!-- MODAL TAMBAH SISWA -->
    @if ($showTambahModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-xs animate-fade-in">
            <div class="bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl border border-purple-100 space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                    <h3 class="font-extrabold text-base text-gray-900">Tambah Data Siswa Baru</h3>
                    <button wire:click="$set('showTambahModal', false)" class="text-gray-400 hover:text-gray-700 text-lg font-bold">&times;</button>
                </div>

                <form wire:submit="simpanSiswa" class="space-y-4">
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <x-input-label for="nis" :value="__('Nomor Induk Siswa (NIS)')" class="font-bold text-xs uppercase text-gray-700 mb-1" />
                            <x-text-input wire:model="nis" id="nis" type="text" placeholder="Contoh: 20261005" class="block w-full py-2" required />
                            <x-input-error :messages="$errors->get('nis')" class="mt-1" />
                        </div>
                        <div>
                            <x-input-label for="new_kelas_id" :value="__('Rombel / Kelas')" class="font-bold text-xs uppercase text-gray-700 mb-1" />
                            <select wire:model="new_kelas_id" id="new_kelas_id" class="block w-full border-gray-200 focus:border-purple-600 focus:ring-purple-500 rounded-xl text-xs text-gray-800 py-2">
                                @foreach ($kelasList as $k)
                                    <option value="{{ $k->id }}">{{ $k->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div>
                        <x-input-label for="nama" :value="__('Nama Lengkap Siswa')" class="font-bold text-xs uppercase text-gray-700 mb-1" />
                        <x-text-input wire:model="nama" id="nama" type="text" placeholder="Masukkan nama siswa" class="block w-full py-2" required />
                        <x-input-error :messages="$errors->get('nama')" class="mt-1" />
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <x-input-label for="nama_ortu" :value="__('Nama Orang Tua / Wali')" class="font-bold text-xs uppercase text-gray-700 mb-1" />
                            <x-text-input wire:model="nama_ortu" id="nama_ortu" type="text" placeholder="Bpk / Ibu..." class="block w-full py-2" />
                        </div>
                        <div>
                            <x-input-label for="no_hp_ortu" :value="__('No. WhatsApp Ortu')" class="font-bold text-xs uppercase text-gray-700 mb-1" />
                            <x-text-input wire:model="no_hp_ortu" id="no_hp_ortu" type="text" placeholder="08123456789..." class="block w-full py-2" required />
                            <x-input-error :messages="$errors->get('no_hp_ortu')" class="mt-1" />
                        </div>
                    </div>

                    <div class="pt-3 border-t border-gray-100 flex justify-end gap-2.5">
                        <button type="button" wire:click="$set('showTambahModal', false)" class="px-4 py-2 border border-gray-200 text-gray-600 rounded-xl text-xs font-semibold hover:bg-gray-50">
                            Batal
                        </button>
                        <x-primary-button class="py-2 px-5 text-xs font-bold">
                            Simpan Data Siswa
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- MODAL EDIT SISWA -->
    @if ($showEditModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-xs animate-fade-in">
            <div class="bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl border border-purple-100 space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                    <div>
                        <span class="text-[11px] font-bold text-purple-700 uppercase tracking-wider">Perbarui Data</span>
                        <h3 class="font-extrabold text-base text-gray-900">Edit Data Siswa</h3>
                    </div>
                    <button wire:click="$set('showEditModal', false)" class="text-gray-400 hover:text-gray-700 text-lg font-bold">&times;</button>
                </div>

                <form wire:submit="simpanEditSiswa" class="space-y-4">
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <x-input-label for="edit_nis" :value="__('Nomor Induk Siswa (NIS)')" class="font-bold text-xs uppercase text-gray-700 mb-1" />
                            <x-text-input wire:model="edit_nis" id="edit_nis" type="text" class="block w-full py-2" required />
                            <x-input-error :messages="$errors->get('edit_nis')" class="mt-1" />
                        </div>
                        <div>
                            <x-input-label for="edit_kelas_id" :value="__('Rombel / Kelas')" class="font-bold text-xs uppercase text-gray-700 mb-1" />
                            <select wire:model="edit_kelas_id" id="edit_kelas_id" class="block w-full border-gray-200 focus:border-purple-600 focus:ring-purple-500 rounded-xl text-xs text-gray-800 py-2">
                                @foreach ($kelasList as $k)
                                    <option value="{{ $k->id }}">{{ $k->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div>
                        <x-input-label for="edit_nama" :value="__('Nama Lengkap Siswa')" class="font-bold text-xs uppercase text-gray-700 mb-1" />
                        <x-text-input wire:model="edit_nama" id="edit_nama" type="text" class="block w-full py-2" required />
                        <x-input-error :messages="$errors->get('edit_nama')" class="mt-1" />
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <x-input-label for="edit_nama_ortu" :value="__('Nama Orang Tua / Wali')" class="font-bold text-xs uppercase text-gray-700 mb-1" />
                            <x-text-input wire:model="edit_nama_ortu" id="edit_nama_ortu" type="text" class="block w-full py-2" />
                        </div>
                        <div>
                            <x-input-label for="edit_no_hp_ortu" :value="__('No. WhatsApp Ortu')" class="font-bold text-xs uppercase text-gray-700 mb-1" />
                            <x-text-input wire:model="edit_no_hp_ortu" id="edit_no_hp_ortu" type="text" class="block w-full py-2" required />
                            <x-input-error :messages="$errors->get('edit_no_hp_ortu')" class="mt-1" />
                        </div>
                    </div>

                    <div class="pt-3 border-t border-gray-100 flex justify-end gap-2.5">
                        <button type="button" wire:click="$set('showEditModal', false)" class="px-4 py-2 border border-gray-200 text-gray-600 rounded-xl text-xs font-semibold hover:bg-gray-50">
                            Batal
                        </button>
                        <x-primary-button class="py-2 px-5 text-xs font-bold">
                            Simpan Perubahan
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
