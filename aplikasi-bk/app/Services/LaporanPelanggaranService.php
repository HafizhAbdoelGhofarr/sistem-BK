<?php

namespace App\Services;

use App\Models\Pelanggaran;
use Illuminate\Database\Eloquent\Builder;

class LaporanPelanggaranService
{
    public function query(array $filters): Builder
    {
        return Pelanggaran::query()
            ->with([
                'siswa.kelas',
                'jenisPelanggaran',
                'pelapor:id,name',
            ])
            ->when($filters['tanggal_mulai'] ?? null, fn (Builder $query, string $tanggal) => $query->whereDate('tanggal', '>=', $tanggal))
            ->when($filters['tanggal_selesai'] ?? null, fn (Builder $query, string $tanggal) => $query->whereDate('tanggal', '<=', $tanggal))
            ->when($filters['kelas_id'] ?? null, fn (Builder $query, int $kelasId) => $query->whereHas('siswa', fn (Builder $siswa) => $siswa->where('kelas_id', $kelasId)))
            ->when($filters['status'] ?? null, fn (Builder $query, string $status) => $query->where('status', $status))
            ->orderBy('tanggal')
            ->orderBy('id');
    }
}
