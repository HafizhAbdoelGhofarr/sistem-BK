<?php

namespace Tests\Feature;

use App\Models\JenisPelanggaran;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\TataTertib;
use App\Models\User;
use App\Services\PelanggaranService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PelanggaranServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_poin_dan_status_siswa_diperbarui_dengan_transaction(): void
    {
        $pelapor = User::factory()->create();

        $kelas = Kelas::create([
            'nama' => 'X IPA 1',
            'tingkat' => 'X',
            'tahun_ajaran' => '2026/2027',
        ]);

        $siswa = Siswa::create([
            'kelas_id' => $kelas->id,
            'nis' => 'NIS-001',
            'nama' => 'Siswa Uji',
            'jenis_kelamin' => 'L',
            'total_poin' => 0,
            'status_disiplin' => 'aman',
        ]);

        $tataTertib = TataTertib::create([
            'kode' => 'TT-TEST',
            'nama' => 'Tata Tertib Uji',
        ]);

        $jenis = JenisPelanggaran::create([
            'tata_tertib_id' => $tataTertib->id,
            'kode' => 'JP-TEST',
            'nama' => 'Pelanggaran Uji',
            'kategori' => 'sedang',
            'poin' => 25,
            'is_active' => true,
        ]);

        $service = app(PelanggaranService::class);
        $pelanggaran = $service->buat([
            'siswa_id' => $siswa->id,
            'jenis_pelanggaran_id' => $jenis->id,
            'pelapor_id' => $pelapor->id,
            'tanggal' => '2026-11-22',
            'keterangan' => 'Uji alur poin',
        ]);

        $this->assertDatabaseHas('pelanggaran', [
            'id' => $pelanggaran->id,
            'poin' => 25,
        ]);

        $this->assertSame(25, $siswa->refresh()->total_poin);
        $this->assertSame('perhatian', $siswa->status_disiplin);

        $jenis->update(['poin' => 40]);
        $this->assertSame(25, $pelanggaran->refresh()->poin);

        $service->perbarui($pelanggaran, ['poin' => 40]);
        $this->assertSame(40, $siswa->refresh()->total_poin);
        $this->assertSame('pembinaan', $siswa->status_disiplin);

        $service->hapus($pelanggaran);

        $this->assertSame(0, $siswa->refresh()->total_poin);
        $this->assertSame('aman', $siswa->status_disiplin);
    }
}
