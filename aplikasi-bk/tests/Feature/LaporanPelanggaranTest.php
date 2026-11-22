<?php

namespace Tests\Feature;

use App\Exports\LaporanPelanggaranExport;
use App\Models\JenisPelanggaran;
use App\Models\Kelas;
use App\Models\Pelanggaran;
use App\Models\Siswa;
use App\Models\TataTertib;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class LaporanPelanggaranTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_download_pdf_and_excel_reports(): void
    {
        $admin = $this->userWithRole('admin');
        $this->buatPelanggaran($admin, 'NIS-REPORT', '2026-11-23');

        $this->actingAs($admin)
            ->get('/bk/laporan/pelanggaran/pdf?tanggal_mulai=2026-11-23&tanggal_selesai=2026-11-23')
            ->assertDownload('laporan-pelanggaran.pdf');

        $this->get('/bk/laporan/pelanggaran/excel?status=baru')
            ->assertDownload('laporan-pelanggaran.xlsx');
    }

    public function test_non_admin_cannot_download_reports(): void
    {
        $guruBk = $this->userWithRole('guru_bk');

        $this->actingAs($guruBk)
            ->get('/bk/laporan/pelanggaran/pdf')
            ->assertForbidden();

        $this->get('/bk/laporan/pelanggaran/excel')->assertForbidden();
    }

    public function test_invalid_date_ranges_are_rejected(): void
    {
        $admin = $this->userWithRole('admin');

        $this->actingAs($admin)
            ->getJson('/bk/laporan/pelanggaran/excel?tanggal_mulai=2026-11-24&tanggal_selesai=2026-11-23')
            ->assertUnprocessable()
            ->assertJsonValidationErrors('tanggal_selesai');
    }

    public function test_excel_export_treats_formula_like_user_text_as_plain_text(): void
    {
        $cell = (new Spreadsheet)->getActiveSheet()->getCell('A1');

        $this->assertTrue((new LaporanPelanggaranExport([]))->bindValue($cell, '=1+1'));
        $this->assertSame(DataType::TYPE_STRING, $cell->getDataType());
        $this->assertSame('=1+1', $cell->getValue());
    }

    private function buatPelanggaran(User $pelapor, string $nis, string $tanggal): Pelanggaran
    {
        $kelas = Kelas::create([
            'nama' => 'X IPA 1',
            'tingkat' => 'X',
            'tahun_ajaran' => '2026/2027',
        ]);
        $siswa = Siswa::create([
            'kelas_id' => $kelas->id,
            'nis' => $nis,
            'nama' => 'Siswa Laporan',
            'jenis_kelamin' => 'P',
        ]);
        $tataTertib = TataTertib::create([
            'kode' => 'TT-REPORT',
            'nama' => 'Tata Tertib Laporan',
        ]);
        $jenisPelanggaran = JenisPelanggaran::create([
            'tata_tertib_id' => $tataTertib->id,
            'kode' => 'JP-REPORT',
            'nama' => 'Terlambat',
            'kategori' => 'ringan',
            'poin' => 5,
            'is_active' => true,
        ]);

        return Pelanggaran::create([
            'siswa_id' => $siswa->id,
            'jenis_pelanggaran_id' => $jenisPelanggaran->id,
            'pelapor_id' => $pelapor->id,
            'tanggal' => $tanggal,
            'poin' => 5,
            'status' => 'baru',
        ]);
    }

    private function userWithRole(string $roleName): User
    {
        $role = Role::findOrCreate($roleName, 'web');
        $user = User::factory()->create();
        $user->assignRole($role);

        return $user;
    }
}
