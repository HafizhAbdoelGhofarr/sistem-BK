<?php

namespace App\Services;

use App\Jobs\KirimNotifikasiWaJob;
use App\Models\JenisPelanggaran;
use App\Models\NotifikasiWa;
use App\Models\Pelanggaran;
use App\Models\Siswa;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class PelanggaranService
{
    public function buat(array $data): Pelanggaran
    {
        $pelanggaran = DB::transaction(function () use ($data) {
            $siswa = Siswa::query()->lockForUpdate()->findOrFail($data['siswa_id']);
            $jenis = JenisPelanggaran::query()
                ->lockForUpdate()
                ->whereKey($data['jenis_pelanggaran_id'])
                ->where('is_active', true)
                ->first();

            if (! $jenis) {
                throw (new ModelNotFoundException)->setModel(JenisPelanggaran::class);
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

        $this->buatNotifikasiWa($pelanggaran);

        return $pelanggaran;
    }

    public function perbarui(Pelanggaran $pelanggaran, array $data): Pelanggaran
    {
        return DB::transaction(function () use ($pelanggaran, $data) {
            $siswa = Siswa::query()->lockForUpdate()->findOrFail($pelanggaran->siswa_id);
            $pelanggaran = Pelanggaran::query()->lockForUpdate()->findOrFail($pelanggaran->id);
            $pelanggaran->fill($data)->save();
            $this->hitungUlangStatus($siswa);

            return $pelanggaran->refresh();
        });
    }

    public function hapus(Pelanggaran $pelanggaran): void
    {
        DB::transaction(function () use ($pelanggaran) {
            $siswa = Siswa::query()->lockForUpdate()->findOrFail($pelanggaran->siswa_id);
            $pelanggaran = Pelanggaran::query()->lockForUpdate()->findOrFail($pelanggaran->id);
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

    public function hitungUlangSemuaStatus(): void
    {
        Siswa::query()->chunkById(200, function ($siswaList) {
            foreach ($siswaList as $siswa) {
                $this->hitungUlangStatus($siswa);
            }
        });
    }

    private function buatNotifikasiWa(Pelanggaran $pelanggaran): void
    {
        $pelanggaran->loadMissing(['siswa.kelas', 'jenisPelanggaran']);
        $siswa = $pelanggaran->siswa;

        if (! $siswa || ! filled($siswa->no_hp_ortu)) {
            return;
        }

        $pesan = implode("\n", [
            'Pemberitahuan pelanggaran tata tertib siswa',
            'Nama: '.$siswa->nama,
            'Kelas: '.($siswa->kelas?->nama ?? '-'),
            'Pelanggaran: '.$pelanggaran->jenisPelanggaran->nama,
            'Poin pelanggaran: '.$pelanggaran->poin,
            'Total poin: '.$siswa->total_poin,
            'Tanggal: '.$pelanggaran->tanggal->format('d-m-Y'),
        ]);

        try {
            $nomorTujuan = WhatsAppService::normalisasiNomor($siswa->no_hp_ortu);
        } catch (InvalidArgumentException $exception) {
            NotifikasiWa::create([
                'siswa_id' => $siswa->id,
                'pelanggaran_id' => $pelanggaran->id,
                'nomor_tujuan' => $siswa->no_hp_ortu,
                'pesan' => $pesan,
                'status' => 'gagal',
                'respon' => $exception->getMessage(),
            ]);

            return;
        }

        $notifikasi = NotifikasiWa::create([
            'siswa_id' => $siswa->id,
            'pelanggaran_id' => $pelanggaran->id,
            'nomor_tujuan' => $nomorTujuan,
            'pesan' => $pesan,
            'status' => 'pending',
        ]);

        KirimNotifikasiWaJob::dispatch($notifikasi->id)->afterCommit();
    }
}
