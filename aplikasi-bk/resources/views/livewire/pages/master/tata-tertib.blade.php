<?php

use App\Models\BatasStatus;
use App\Models\JenisPelanggaran;
use App\Models\TataTertib;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component
{
    public function with(): array
    {
        return [
            'tataTertib' => TataTertib::first(),
            'jenisPelanggaran' => JenisPelanggaran::with('tataTertib')->orderBy('poin')->get(),
            'batasStatus' => BatasStatus::orderBy('min_poin')->get(),
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
                <span class="text-purple-700 font-semibold">Buku Saku</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight">Tata Tertib & Bobot Poin Pelanggaran</h1>
            <p class="text-xs text-gray-500 mt-0.5">Pedoman aturan kedisiplinan sekolah, klasifikasi sanksi, dan ambang batas poin bimbingan</p>
        </div>
    </div>

    <!-- 1. Ambang Batas Status Kedisiplinan -->
    <div class="bg-white/95 backdrop-blur-md border border-purple-100/90 rounded-3xl p-6 shadow-sm space-y-4">
        <div>
            <span class="text-xs font-bold text-purple-700 uppercase tracking-wider">Tingkat Penanganan</span>
            <h3 class="text-base font-extrabold text-gray-900 mt-0.5">Ambang Batas Status Kedisiplinan Siswa</h3>
            <p class="text-xs text-gray-500">Tingkat pembinaan ditentukan otomatis berdasarkan akumulasi poin pelanggaran siswa</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            @foreach ($batasStatus as $bs)
                <div class="p-4 rounded-2xl border {{ $bs->status === 'aman' ? 'border-emerald-200 bg-emerald-50/50' : ($bs->status === 'perhatian' ? 'border-amber-200 bg-amber-50/50' : ($bs->status === 'pembinaan' ? 'border-orange-200 bg-orange-50/50' : 'border-rose-200 bg-rose-50/50')) }}">
                    <div class="flex items-center justify-between mb-2">
                        <x-status-badge :status="$bs->status" />
                        <span class="text-xs font-bold {{ $bs->status === 'kritis' ? 'text-rose-700' : 'text-gray-800' }}">
                            &ge; {{ $bs->min_poin }} Poin
                        </span>
                    </div>
                    <div class="text-xs text-gray-600 mt-2 leading-relaxed">
                        @if ($bs->status === 'aman')
                            Siswa tertib. Bebas sanksi kedisiplinan dan berhak mengikuti seluruh program sekolah.
                        @elseif ($bs->status === 'perhatian')
                            Mendapat teguran lisan dan evaluasi komitmen kedisiplinan bersama Wali Kelas.
                        @elseif ($bs->status === 'pembinaan')
                            Wajib mengikuti sesi bimbingan konseling dan menandatangani surat perjanjian binaan.
                        @else
                            Status kritis. Pemanggilan resmi orang tua/wali ke sekolah untuk penanganan khusus.
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- 2. Tabel Master Jenis Pelanggaran & Bobot Poin -->
    <div class="bg-white/95 backdrop-blur-md border border-purple-100/90 rounded-3xl shadow-sm overflow-hidden">
        <div class="p-5 border-b border-gray-100 flex items-center justify-between">
            <div>
                <h3 class="text-base font-extrabold text-gray-900">Daftar Jenis Pelanggaran & Poin Sanksi</h3>
                <p class="text-xs text-gray-500 mt-0.5">Snapshot poin pelanggaran berlaku tetap saat dicatat</p>
            </div>
            <span class="text-xs font-semibold text-purple-700 bg-purple-50 px-3 py-1 rounded-full">
                {{ $jenisPelanggaran->count() }} Jenis Terdaftar
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-purple-50/60 border-b border-purple-100/80 text-[11px] font-bold uppercase tracking-wider text-purple-900">
                        <th class="py-3.5 px-4">Kode</th>
                        <th class="py-3.5 px-4">Uraian Pelanggaran Tata Tertib</th>
                        <th class="py-3.5 px-4 text-center">Kategori</th>
                        <th class="py-3.5 px-4 text-center">Bobot Poin</th>
                        <th class="py-3.5 px-4 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-gray-700">
                    @foreach ($jenisPelanggaran as $jp)
                        <tr class="hover:bg-purple-50/30 transition">
                            <td class="py-3.5 px-4 font-mono font-bold text-purple-800">{{ $jp->kode }}</td>
                            <td class="py-3.5 px-4 font-semibold text-gray-900">{{ $jp->nama }}</td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="inline-flex px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $jp->kategori === 'berat' ? 'bg-rose-100 text-rose-800' : ($jp->kategori === 'sedang' ? 'bg-amber-100 text-amber-800' : 'bg-emerald-100 text-emerald-800') }}">
                                    {{ $jp->kategori }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-center font-black text-rose-600 text-sm">
                                +{{ $jp->poin }}
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-emerald-700">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    Aktif
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
