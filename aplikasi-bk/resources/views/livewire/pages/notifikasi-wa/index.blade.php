<?php

use App\Models\NotifikasiWa;
use App\Services\WhatsAppService;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use Livewire\WithPagination;

new #[Layout('layouts.app')] class extends Component
{
    use WithPagination;

    public string $statusFilter = '';

    public function mount(): void
    {
        if (! auth()->user()->hasRole('admin')) {
            abort(403, 'Akses ditolak: Hanya Administrator yang berwenang mengakses dan memantau log WhatsApp Sidobe.');
        }
    }

    public function kirimUlang(int $id, WhatsAppService $service): void
    {
        $notif = NotifikasiWa::with('siswa')->findOrFail($id);

        try {
            $pesan = "Pemberitahuan Tata Tertib Sekolah: Siswa ybs atas nama " . ($notif->siswa?->nama ?? 'Siswa') . " telah tercatat melakukan pelanggaran tata tertib sekolah.";
            $service->kirimPesan($notif->siswa->no_hp_ortu, $pesan);
            
            $notif->update([
                'status' => 'terkirim',
                'percobaan' => $notif->percobaan + 1,
            ]);

            session()->flash('success', 'Pesan WhatsApp berhasil dikirim ulang ke nomor orang tua/wali!');
        } catch (\Exception $e) {
            $notif->update([
                'status' => 'gagal',
                'percobaan' => $notif->percobaan + 1,
                'respon' => $e->getMessage(),
            ]);

            session()->flash('error', 'Pengiriman gagal: ' . $e->getMessage());
        }
    }

    public function with(): array
    {
        $query = NotifikasiWa::with(['siswa.kelas', 'pelanggaran.jenisPelanggaran'])->latest();

        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        return [
            'notifikasiList' => $query->paginate(15),
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
                <span class="text-purple-700 font-semibold">Gateway WhatsApp</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight">Log Notifikasi WhatsApp Sidobe</h1>
            <p class="text-xs text-gray-500 mt-0.5">Pemantauan status pengiriman pesan otomatis ke nomor WhatsApp orang tua/wali murid</p>
        </div>

        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-emerald-50 border border-emerald-200 text-xs font-bold text-emerald-800">
            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
            Status Gateway: Terhubung ke Sidobe API
        </div>
    </div>

    <!-- Info Box Status Integrasi Sidobe -->
    <div class="p-5 bg-gradient-to-r from-purple-200 via-purple-300 to-indigo-200 text-gray-900 rounded-3xl shadow-lg shadow-purple-950/10 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="space-y-1">
            <div class="flex items-center gap-2 text-xs text-purple-900 font-bold uppercase tracking-wider">
                <svg class="w-4 h-4 text-emerald-700" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                Otomasi Pengiriman Pesan Aktif
            </div>
            <h3 class="text-base font-bold text-gray-900">Layanan Notifikasi Terjadwal (Queue Database)</h3>
            <p class="text-xs text-gray-700 max-w-xl">
                Setiap kali pelanggaran dicatat, sistem memasukkan job ke antrean background agar aplikasi tetap cepat. Pesan berisi nama siswa, kelas, jenis pelanggaran, dan akumulasi poin terkini.
            </p>
        </div>

        <!-- Filter Status Tab -->
        <div class="inline-flex p-1 bg-white/70 rounded-2xl border border-purple-200 text-xs self-start md:self-center">
            <button wire:click="$set('statusFilter', '')" class="px-3 py-1.5 rounded-xl font-bold transition {{ $statusFilter === '' ? 'bg-purple-700 text-white' : 'text-gray-700 hover:text-purple-900' }}">Semua</button>
            <button wire:click="$set('statusFilter', 'terkirim')" class="px-3 py-1.5 rounded-xl font-bold transition {{ $statusFilter === 'terkirim' ? 'bg-emerald-700 text-white' : 'text-gray-700 hover:text-purple-900' }}">Terkirim</button>
            <button wire:click="$set('statusFilter', 'pending')" class="px-3 py-1.5 rounded-xl font-bold transition {{ $statusFilter === 'pending' ? 'bg-amber-600 text-white' : 'text-gray-700 hover:text-purple-900' }}">Antrean</button>
            <button wire:click="$set('statusFilter', 'gagal')" class="px-3 py-1.5 rounded-xl font-bold transition {{ $statusFilter === 'gagal' ? 'bg-rose-600 text-white' : 'text-gray-700 hover:text-purple-900' }}">Gagal</button>
        </div>
    </div>

    <!-- Tabel Log WhatsApp -->
    <div class="bg-white/95 backdrop-blur-md border border-purple-100/90 rounded-3xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-purple-50/60 border-b border-purple-100/80 text-[11px] font-bold uppercase tracking-wider text-purple-900">
                        <th class="py-3.5 px-4">Waktu</th>
                        <th class="py-3.5 px-4">Siswa & Orang Tua</th>
                        <th class="py-3.5 px-4">Nomor WhatsApp</th>
                        <th class="py-3.5 px-4">Kasus Terkait</th>
                        <th class="py-3.5 px-4 text-center">Percobaan</th>
                        <th class="py-3.5 px-4 text-center">Status</th>
                        <th class="py-3.5 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-gray-700">
                    @forelse ($notifikasiList as $notif)
                        <tr class="hover:bg-purple-50/30 transition">
                            <td class="py-3.5 px-4 whitespace-nowrap text-gray-500">
                                {{ $notif->created_at->translatedFormat('d M Y, H:i') }}
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <span class="font-bold text-gray-900 block">{{ $notif->siswa?->nama ?? 'Siswa' }}</span>
                                <span class="text-[11px] text-gray-500">Ortu: {{ $notif->siswa?->nama_ortu ?? 'Wali Murid' }}</span>
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap font-mono text-purple-700 font-semibold">
                                {{ $notif->siswa?->no_hp_ortu ?? '-' }}
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="font-medium text-gray-800">{{ $notif->pelanggaran?->jenisPelanggaran?->nama ?? 'Pelanggaran Tata Tertib' }}</span>
                                <span class="text-[11px] text-rose-600 block">(+{{ $notif->pelanggaran?->poin ?? 0 }} Poin)</span>
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap text-center font-bold">
                                {{ $notif->percobaan ?? 1 }}x
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap text-center">
                                @if ($notif->status === 'terkirim')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-800">
                                        ✓ Sukses Terkirim
                                    </span>
                                @elseif ($notif->status === 'gagal')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-rose-100 text-rose-800" title="{{ $notif->respon }}">
                                        ✕ Pengiriman Gagal
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-amber-100 text-amber-800 animate-pulse">
                                        &bull; Dalam Antrean
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap text-right">
                                @if ($notif->status !== 'terkirim')
                                    <button wire:click="kirimUlang({{ $notif->id }})" class="px-2.5 py-1 bg-purple-700 hover:bg-purple-800 text-white rounded-lg text-[11px] font-bold shadow-xs transition">
                                        Kirim Ulang
                                    </button>
                                @else
                                    <span class="text-emerald-600 text-xs font-semibold">Tersampaikan</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-gray-400">
                                <div class="w-12 h-12 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center mx-auto mb-3">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                    </svg>
                                </div>
                                <span class="font-bold text-sm text-gray-700 block">Belum Ada Riwayat Notifikasi WhatsApp</span>
                                <span class="text-xs text-gray-400">Log pesan akan muncul saat pelanggaran siswa dicatat.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($notifikasiList->hasPages())
            <div class="p-4 border-t border-gray-100 bg-white">
                {{ $notifikasiList->links() }}
            </div>
        @endif
    </div>
</div>
