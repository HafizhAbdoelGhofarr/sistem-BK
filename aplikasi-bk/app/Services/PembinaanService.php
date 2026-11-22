<?php

namespace App\Services;

use App\Models\Pelanggaran;
use App\Models\Pembinaan;
use App\Models\Siswa;
use App\Models\TindakLanjut;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

class PembinaanService
{
    public function buat(array $data, User $guruBk): Pembinaan
    {
        return DB::transaction(function () use ($data, $guruBk) {
            $siswa = Siswa::query()->lockForUpdate()->findOrFail($data['siswa_id']);
            $pelanggaran = null;

            if (! empty($data['pelanggaran_id'])) {
                $pelanggaran = Pelanggaran::query()
                    ->lockForUpdate()
                    ->whereKey($data['pelanggaran_id'])
                    ->where('siswa_id', $siswa->id)
                    ->first();

                if (! $pelanggaran) {
                    throw (new ModelNotFoundException)->setModel(Pelanggaran::class);
                }

                $pelanggaran->update(['status' => 'dibina']);
            }

            return Pembinaan::create([
                'siswa_id' => $siswa->id,
                'pelanggaran_id' => $pelanggaran?->id,
                'guru_bk_id' => $guruBk->id,
                'tanggal' => $data['tanggal'],
                'jenis' => $data['jenis'],
                'catatan' => $data['catatan'] ?? null,
                'status' => $data['status'] ?? 'dijadwalkan',
            ]);
        });
    }

    public function perbarui(Pembinaan $pembinaan, array $data): Pembinaan
    {
        return DB::transaction(function () use ($pembinaan, $data) {
            $pembinaan = Pembinaan::query()->lockForUpdate()->findOrFail($pembinaan->id);
            $pembinaan->fill($data)->save();

            if ($pembinaan->pelanggaran_id) {
                $statusPelanggaran = Pembinaan::query()
                    ->where('pelanggaran_id', $pembinaan->pelanggaran_id)
                    ->where('status', '!=', 'selesai')
                    ->exists()
                    ? 'dibina'
                    : 'selesai';

                Pelanggaran::query()
                    ->whereKey($pembinaan->pelanggaran_id)
                    ->update(['status' => $statusPelanggaran]);
            }

            return $pembinaan->refresh();
        });
    }

    public function tambahTindakLanjut(Pembinaan $pembinaan, User $pembuat, array $data): TindakLanjut
    {
        return DB::transaction(function () use ($pembinaan, $pembuat, $data) {
            $pembinaan = Pembinaan::query()->lockForUpdate()->findOrFail($pembinaan->id);

            return $pembinaan->tindakLanjut()->create([
                'dibuat_oleh' => $pembuat->id,
                'tanggal' => $data['tanggal'],
                'deskripsi' => $data['deskripsi'],
                'hasil' => $data['hasil'] ?? null,
                'status' => $data['status'] ?? 'proses',
            ]);
        });
    }

    public function perbaruiTindakLanjut(TindakLanjut $tindakLanjut, array $data): TindakLanjut
    {
        return DB::transaction(function () use ($tindakLanjut, $data) {
            $tindakLanjut = TindakLanjut::query()->lockForUpdate()->findOrFail($tindakLanjut->id);
            $tindakLanjut->fill($data)->save();

            return $tindakLanjut->refresh();
        });
    }
}
