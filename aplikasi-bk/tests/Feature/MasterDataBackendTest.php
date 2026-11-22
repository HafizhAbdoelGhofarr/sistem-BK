<?php

namespace Tests\Feature;

use App\Models\JenisPelanggaran;
use App\Models\Kelas;
use App\Models\Pelanggaran;
use App\Models\Siswa;
use App\Models\TataTertib;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class MasterDataBackendTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_master_data_and_guru_bk_can_read_it(): void
    {
        $admin = $this->userWithRole('admin');
        $guruBk = $this->userWithRole('guru_bk');

        $kelasId = $this->actingAs($admin)
            ->postJson('/bk/kelas', [
                'nama' => 'X IPA 1',
                'tingkat' => 'X',
                'tahun_ajaran' => '2026/2027',
            ])
            ->assertCreated()
            ->json('id');

        $this->getJson('/bk/kelas/'.$kelasId)
            ->assertOk()
            ->assertJsonPath('nama', 'X IPA 1');

        $tataTertib = $this->actingAs($admin)
            ->postJson('/bk/tata-tertib', [
                'kode' => 'TT-API',
                'nama' => 'Tata Tertib API',
            ])
            ->assertCreated()
            ->json('id');

        $this->postJson('/bk/jenis-pelanggaran', [
            'tata_tertib_id' => $tataTertib,
            'kode' => 'JP-API',
            'nama' => 'Terlambat',
            'kategori' => 'ringan',
            'poin' => 5,
        ])->assertCreated();

        $this->actingAs($guruBk)
            ->getJson('/bk/jenis-pelanggaran')
            ->assertOk()
            ->assertJsonPath('data.0.kode', 'JP-API');

        $this->postJson('/bk/jenis-pelanggaran', [])->assertForbidden();
    }

    public function test_used_violation_types_cannot_be_hard_deleted(): void
    {
        $admin = $this->userWithRole('admin');
        $jenisPelanggaran = JenisPelanggaran::create([
            'tata_tertib_id' => TataTertib::create([
                'kode' => 'TT-USED',
                'nama' => 'Tata Tertib',
            ])->id,
            'kode' => 'JP-USED',
            'nama' => 'Pelanggaran',
            'kategori' => 'ringan',
            'poin' => 5,
        ]);
        $pelanggaran = Pelanggaran::create([
            'siswa_id' => Siswa::create([
                'kelas_id' => Kelas::create([
                    'nama' => 'X IPA 1',
                    'tingkat' => 'X',
                    'tahun_ajaran' => '2026/2027',
                ])->id,
                'nis' => 'NIS-USED',
                'nama' => 'Siswa Uji',
                'jenis_kelamin' => 'L',
            ])->id,
            'jenis_pelanggaran_id' => $jenisPelanggaran->id,
            'pelapor_id' => $admin->id,
            'tanggal' => '2026-11-23',
            'poin' => 5,
        ]);
        $pelanggaran->delete();

        $this->actingAs($admin)
            ->deleteJson('/bk/jenis-pelanggaran/'.$jenisPelanggaran->id)
            ->assertUnprocessable()
            ->assertJsonValidationErrors('jenis_pelanggaran');
    }

    private function userWithRole(string $roleName): User
    {
        $role = Role::findOrCreate($roleName, 'web');
        $user = User::factory()->create();
        $user->assignRole($role);

        return $user;
    }
}
