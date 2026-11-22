<?php

namespace App\Services;

use App\Models\Pelanggaran;
use App\Models\Siswa;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class PelanggaranService
{
    public function buat(array $data): Pelanggaran
    {
        return DB::transaction(function () use ($data) {
            $siswa = Siswa::findOrFail($data['siswa_id']);
            $jenis = $siswa->getConnection()->table('jenis_pelanggaran')
                ->where('id', $data['jenis_pelanggaran_id'])
                ->where('is_active', true)
                ->first();

            if (! $jenis) {
                throw new InvalidArgumentException('Jenis pelanggaran tidak aktif atau tidak ditemukan.');
            }

            $pelanggaran = Pelanggaran::create([
                'siswa_id' => $siswa->id,
                'jenis_pelanggaran_id' => $jenis->id,
                'pelapor_id' => $data['pelapor_id'],
                'tanggal' => $data['tanggal'],
                'poin' => $jenis->poin,
                'keterangan' => $data['keterangan'] ?? null,
                'bukti_foto' => $data['bukti_foto'] ?? null,
                'status' => 'baru',
            ]);

            $this->hitungUlangStatus($siswa);

            return $pelanggaran;
        });
    }

    public function ubahPoin(Pelanggaran $pelanggaran, int $poinBaru): Pelanggaran
    {
        return DB::transaction(function () use ($pelanggaran, $poinBaru) {
            $pelanggaran->update(['poin' => $poinBaru]);
            $this->hitungUlangStatus($pelanggaran->siswa);

            return $pelanggaran->refresh();
        });
    }

    public function hapus(Pelanggaran $pelanggaran): void
    {
        DB::transaction(function () use ($pelanggaran) {
            $siswa = $pelanggaran->siswa;
            $pelanggaran->delete();
            $this->hitungUlangStatus($siswa);
        });
    }

    public function hitungUlangStatus(Siswa $siswa): void
    {
        $totalPoin = Pelanggaran::where('siswa_id', $siswa->id)->sum('poin');

        $status = DB::table('batas_status')
            ->where('min_poin', '<=', $totalPoin)
            ->orderByDesc('min_poin')
            ->value('status') ?? 'aman';

        $siswa->forceFill([
            'total_poin' => $totalPoin,
            'status_disiplin' => $status,
        ])->save();
    }
}
