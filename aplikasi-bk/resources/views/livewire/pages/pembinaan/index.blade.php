<?php

use App\Models\Pembinaan;
use App\Models\Siswa;
use App\Services\PembinaanService;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use Livewire\WithPagination;

new #[Layout('layouts.app')] class extends Component
{
    use WithPagination;

    public string $tab = 'sesi'; // 'sesi' atau 'prioritas'
    public string $statusFilter = '';

    // Modal Tindak Lanjut
    public bool $showTindakLanjutModal = false;
    public ?int $selectedPembinaanId = null;
    public string $tl_tanggal = '';
    public string $tl_deskripsi = '';
    public string $tl_hasil = '';
    public string $tl_status = 'selesai';

    public function mount(): void
    {
        $this->tl_tanggal = date('Y-m-d');
    }

    public function bukaModalTindakLanjut(int $pembinaanId): void
    {
        $this->selectedPembinaanId = $pembinaanId;
        $this->showTindakLanjutModal = true;
    }

    public function simpanTindakLanjut(PembinaanService $service): void
    {
        $this->validate([
            'tl_deskripsi' => ['required', 'string', 'max:500'],
            'tl_hasil' => ['nullable', 'string', 'max:500'],
        ]);

        $pembinaan = Pembinaan::findOrFail($this->selectedPembinaanId);

        $service->tambahTindakLanjut($pembinaan, auth()->user(), [
            'tanggal' => $this->tl_tanggal,
            'deskripsi' => $this->tl_deskripsi,
            'hasil' => $this->tl_hasil,
            'status' => $this->tl_status,
        ]);

        // Perbarui status pembinaan menjadi selesai jika dipilih selesai
        if ($this->tl_status === 'selesai') {
            $service->perbarui($pembinaan, ['status' => 'selesai']);
        }

        $this->showTindakLanjutModal = false;
        $this->reset(['tl_deskripsi', 'tl_hasil', 'selectedPembinaanId']);

        session()->flash('success', 'Catatan tindak lanjut berhasil disimpan dan status kasus diperbarui!');
    }

    public function with(): array
    {
        $querySesi = Pembinaan::with(['siswa.kelas', 'pelanggaran.jenisPelanggaran', 'guruBk', 'tindakLanjut'])->latest('tanggal');

        // Jika siswa, hanya lihat pembinaan dirinya sendiri
        if (auth()->user()->hasRole('siswa')) {
            $querySesi->whereHas('siswa', fn ($q) => $q->where('user_id', auth()->id()));
        }

        if ($this->statusFilter) {
            $querySesi->where('status', $this->statusFilter);
        }

        $siswaPrioritas = Siswa::with('kelas')
            ->whereIn('status_disiplin', ['kritis', 'pembinaan', 'perhatian'])
            ->orderByDesc('total_poin')
            ->get();

        return [
            'pembinaanList' => $querySesi->paginate(10),
            'siswaPrioritas' => $siswaPrioritas,
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
                <span class="text-purple-700 font-semibold">Bimbingan Konseling</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight">Manajemen Sesi Pembinaan Siswa</h1>
            <p class="text-xs text-gray-500 mt-0.5">Pendampingan karakter, tindak lanjut evaluasi sanksi, dan pemantauan siswa binaan BK</p>
        </div>

        @role('admin|guru_bk')
            <div class="flex items-center gap-2.5">
                <a href="{{ route('pembinaan.create') }}" wire:navigate class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-purple-700 to-purple-600 hover:from-purple-800 hover:to-purple-700 text-white text-xs font-bold rounded-xl shadow-md shadow-purple-600/20 transition transform hover:-translate-y-0.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Jadwalkan Pembinaan Baru
                </a>
            </div>
        @endrole
    </div>

    <!-- Tab Pilihan (Sesi Konseling vs Siswa Prioritas) -->
    <div class="flex items-center gap-3 border-b border-purple-100 pb-2">
        <button wire:click="$set('tab', 'sesi')"
                class="px-4 py-2 rounded-xl text-xs font-bold transition flex items-center gap-2 {{ $tab === 'sesi' ? 'bg-purple-700 text-white shadow-xs' : 'text-gray-600 hover:text-purple-700 hover:bg-purple-50' }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            Daftar Sesi Pembinaan
        </button>

        @role('admin|guru_bk')
            <button wire:click="$set('tab', 'prioritas')"
                    class="px-4 py-2 rounded-xl text-xs font-bold transition flex items-center gap-2 {{ $tab === 'prioritas' ? 'bg-purple-700 text-white shadow-xs' : 'text-gray-600 hover:text-purple-700 hover:bg-purple-50' }}">
                <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                Daftar Pantau Siswa Prioritas
                <span class="px-2 py-0.5 rounded-full text-[10px] bg-rose-100 text-rose-700 font-extrabold">
                    {{ count($siswaPrioritas) }}
                </span>
            </button>
        @endrole
    </div>

    <!-- KONTEN TAB 1: DAFTAR SESI PEMBINAAN -->
    @if ($tab === 'sesi')
        <div class="bg-white/95 backdrop-blur-md border border-purple-100/90 rounded-3xl shadow-sm overflow-hidden">
            <div class="p-4 border-b border-gray-100 flex flex-wrap items-center justify-between gap-3">
                <span class="text-xs font-bold text-gray-700">Daftar Jadwal & Catatan Konseling</span>
                
                <div class="inline-flex p-1 bg-purple-50/70 rounded-xl text-xs">
                    <button wire:click="$set('statusFilter', '')" class="px-3 py-1 rounded-lg font-bold transition {{ $statusFilter === '' ? 'bg-white text-purple-900 shadow-2xs' : 'text-gray-500' }}">Semua</button>
                    <button wire:click="$set('statusFilter', 'dijadwalkan')" class="px-3 py-1 rounded-lg font-bold transition {{ $statusFilter === 'dijadwalkan' ? 'bg-amber-500 text-white shadow-2xs' : 'text-gray-500' }}">Dijadwalkan</button>
                    <button wire:click="$set('statusFilter', 'berlangsung')" class="px-3 py-1 rounded-lg font-bold transition {{ $statusFilter === 'berlangsung' ? 'bg-purple-600 text-white shadow-2xs' : 'text-gray-500' }}">Berlangsung</button>
                    <button wire:click="$set('statusFilter', 'selesai')" class="px-3 py-1 rounded-lg font-bold transition {{ $statusFilter === 'selesai' ? 'bg-emerald-600 text-white shadow-2xs' : 'text-gray-500' }}">Selesai</button>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-purple-50/60 border-b border-purple-100/80 text-[11px] font-bold uppercase tracking-wider text-purple-900">
                            <th class="py-3.5 px-4">Tanggal</th>
                            <th class="py-3.5 px-4">Nama Siswa & Kelas</th>
                            <th class="py-3.5 px-4">Bentuk Pembinaan</th>
                            <th class="py-3.5 px-4">Fokus Masalah</th>
                            <th class="py-3.5 px-4">Konselor</th>
                            <th class="py-3.5 px-4 text-center">Status</th>
                            <th class="py-3.5 px-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-gray-700">
                        @forelse ($pembinaanList as $item)
                            <tr class="hover:bg-purple-50/30 transition">
                                <td class="py-3.5 px-4 whitespace-nowrap font-medium text-gray-600">
                                    {{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d M Y') }}
                                </td>
                                <td class="py-3.5 px-4 whitespace-nowrap">
                                    <span class="font-bold text-gray-900 block">{{ $item->siswa?->nama ?? 'Siswa' }}</span>
                                    <span class="text-[11px] text-gray-500">{{ $item->siswa?->kelas?->nama }} &bull; Poin: {{ $item->siswa?->total_poin }}</span>
                                </td>
                                <td class="py-3.5 px-4 whitespace-nowrap font-semibold text-purple-900">
                                    {{ $item->jenis }}
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="text-gray-800 line-clamp-2 max-w-xs">{{ $item->catatan ?? '-' }}</span>
                                    @if ($item->tindakLanjut->count() > 0)
                                        <span class="text-[10px] text-emerald-700 font-bold block mt-1">✓ {{ $item->tindakLanjut->count() }} Tindak Lanjut Tercatat</span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 whitespace-nowrap text-gray-600">
                                    {{ $item->guruBk?->name ?? 'Konselor BK' }}
                                </td>
                                <td class="py-3.5 px-4 whitespace-nowrap text-center">
                                    @if ($item->status === 'dijadwalkan')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-amber-100 text-amber-800">
                                            &bull; Dijadwalkan
                                        </span>
                                    @elseif ($item->status === 'berlangsung')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-purple-100 text-purple-800 animate-pulse">
                                            &bull; Berlangsung
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-800">
                                            ✓ Kasus Selesai
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 whitespace-nowrap text-right">
                                    @role('admin|guru_bk')
                                        @if ($item->status !== 'selesai')
                                            <button wire:click="bukaModalTindakLanjut({{ $item->id }})" class="px-3 py-1.5 bg-purple-700 hover:bg-purple-800 text-white rounded-xl text-xs font-bold shadow-xs transition">
                                                + Tindak Lanjut
                                            </button>
                                        @else
                                            <span class="text-xs font-semibold text-emerald-600">Selesai Dibina</span>
                                        @endif
                                    @endrole
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-12 text-center text-gray-400">
                                    <div class="w-12 h-12 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center mx-auto mb-3">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                        </svg>
                                    </div>
                                    <span class="font-bold text-sm text-gray-700 block">Belum Ada Sesi Pembinaan Aktif</span>
                                    <span class="text-xs text-gray-400">Jadwalkan konseling untuk siswa yang membutuhkan bimbingan.</span>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($pembinaanList->hasPages())
                <div class="p-4 border-t border-gray-100 bg-white">
                    {{ $pembinaanList->links() }}
                </div>
            @endif
        </div>
    @endif

    <!-- KONTEN TAB 2: DAFTAR PANTAU SISWA PRIORITAS -->
    @if ($tab === 'prioritas')
        <div class="bg-white/95 backdrop-blur-md border border-purple-100/90 rounded-3xl shadow-sm p-6 space-y-4">
            <div>
                <h3 class="text-base font-extrabold text-gray-900">Daftar Pantau Siswa Berisiko Tinggi</h3>
                <p class="text-xs text-gray-500">Siswa yang memerlukan perhatian khusus atau pemanggilan resmi orang tua berdasarkan akumulasi poin</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach ($siswaPrioritas as $sp)
                    <div class="p-4 rounded-2xl border transition duration-150 {{ $sp->status_disiplin === 'kritis' ? 'border-rose-200 bg-rose-50/40' : ($sp->status_disiplin === 'pembinaan' ? 'border-orange-200 bg-orange-50/40' : 'border-amber-200 bg-amber-50/40') }}">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <span class="font-extrabold text-sm text-gray-900 block">{{ $sp->nama }}</span>
                                <span class="text-xs text-gray-600">NIS: {{ $sp->nis }} &bull; Rombel: {{ $sp->kelas?->nama }}</span>
                                <div class="text-[11px] text-gray-500 mt-1">
                                    Wali: {{ $sp->nama_ortu ?? 'Orang Tua' }} &bull; WA: <span class="font-mono text-purple-700">{{ $sp->no_hp_ortu ?? '-' }}</span>
                                </div>
                            </div>

                            <div class="text-right">
                                <span class="text-lg font-black block {{ $sp->status_disiplin === 'kritis' ? 'text-rose-600' : 'text-orange-600' }}">
                                    {{ $sp->total_poin }} Poin
                                </span>
                                <x-status-badge :status="$sp->status_disiplin" />
                            </div>
                        </div>

                        <div class="mt-4 pt-3 border-t border-black/5 flex items-center justify-between">
                            <span class="text-[11px] text-gray-500">
                                {{ $sp->status_disiplin === 'kritis' ? 'Wajib Pemanggilan Resmi Ortu' : 'Perlu Sesi Konseling Mandiri' }}
                            </span>

                            <a href="{{ route('pembinaan.create', ['siswa_id' => $sp->id]) }}" wire:navigate
                               class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-purple-700 hover:bg-purple-800 text-white rounded-xl text-xs font-bold shadow-xs transition">
                                Bina Siswa Ini &rarr;
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- MODAL POPUP: TAMBAH TINDAK LANJUT -->
    @if ($showTindakLanjutModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-xs animate-fade-in">
            <div class="bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl border border-purple-100 space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                    <h3 class="font-extrabold text-base text-gray-900">Catat Tindak Lanjut Konseling</h3>
                    <button wire:click="$set('showTindakLanjutModal', false)" class="text-gray-400 hover:text-gray-700 text-lg font-bold">&times;</button>
                </div>

                <form wire:submit="simpanTindakLanjut" class="space-y-4">
                    <div>
                        <x-input-label for="tl_tanggal" :value="__('Tanggal Evaluasi')" class="font-bold text-xs uppercase text-gray-700 mb-1" />
                        <x-text-input wire:model="tl_tanggal" id="tl_tanggal" type="date" class="block w-full py-2" required />
                    </div>

                    <div>
                        <x-input-label for="tl_deskripsi" :value="__('Hasil Konseling & Tindakan')" class="font-bold text-xs uppercase text-gray-700 mb-1" />
                        <textarea wire:model="tl_deskripsi" id="tl_deskripsi" rows="3" required
                                  placeholder="Contoh: Siswa telah menandatangani surat kesepakatan tidak mengulangi pelanggaran dan diberikan sanksi merawat taman sekolah..."
                                  class="block w-full border-gray-200 focus:border-purple-600 focus:ring-purple-500 rounded-xl text-xs text-gray-800"></textarea>
                    </div>

                    <div>
                        <x-input-label for="tl_hasil" :value="__('Komitmen Perubahan Siswa')" class="font-bold text-xs uppercase text-gray-700 mb-1" />
                        <input wire:model="tl_hasil" id="tl_hasil" type="text" placeholder="Contoh: Berjanji hadir tepat waktu maksimal 06.45 WIB"
                               class="block w-full border-gray-200 focus:border-purple-600 focus:ring-purple-500 rounded-xl text-xs text-gray-800 py-2">
                    </div>

                    <div>
                        <x-input-label for="tl_status" :value="__('Status Kasus Setelah Ini')" class="font-bold text-xs uppercase text-gray-700 mb-1" />
                        <select wire:model="tl_status" id="tl_status" class="block w-full border-gray-200 focus:border-purple-600 focus:ring-purple-500 rounded-xl text-xs text-gray-800 py-2">
                            <option value="selesai">Kasus Selesai (Siswa Telah Dibina Penuh)</option>
                            <option value="proses">Masih Dalam Pemantauan (Butuh Sesi Lanjutan)</option>
                        </select>
                    </div>

                    <div class="pt-3 border-t border-gray-100 flex justify-end gap-2.5">
                        <button type="button" wire:click="$set('showTindakLanjutModal', false)" class="px-4 py-2 border border-gray-200 text-gray-600 rounded-xl text-xs font-semibold hover:bg-gray-50">
                            Batal
                        </button>
                        <x-primary-button class="py-2 px-5 text-xs font-bold">
                            Simpan Tindak Lanjut
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
