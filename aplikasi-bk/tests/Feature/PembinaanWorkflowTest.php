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

class PembinaanWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_guru_bk_can_record_a_session_follow_up_and_complete_the_violation(): void
    {
        $guruBk = $this->userWithRole('guru_bk');
        $student = Siswa::create([
            'kelas_id' => Kelas::create([
                'nama' => 'X IPA 1',
                'tingkat' => 'X',
                'tahun_ajaran' => '2026/2027',
            ])->id,
            'nis' => 'NIS-500',
            'nama' => 'Siswa Uji',
            'jenis_kelamin' => 'L',
        ]);
        $violation = Pelanggaran::create([
            'siswa_id' => $student->id,
            'jenis_pelanggaran_id' => JenisPelanggaran::create([
                'tata_tertib_id' => TataTertib::create([
                    'kode' => 'TT-500',
                    'nama' => 'Tata Tertib Uji',
                ])->id,
                'kode' => 'JP-500',
                'nama' => 'Pelanggaran Uji',
                'kategori' => 'sedang',
                'poin' => 25,
                'is_active' => true,
            ])->id,
            'pelapor_id' => $guruBk->id,
            'tanggal' => '2026-11-23',
            'poin' => 25,
        ]);

        $response = $this->actingAs($guruBk)
            ->postJson('/bk/pembinaan', [
                'siswa_id' => $student->id,
                'pelanggaran_id' => $violation->id,
                'tanggal' => '2026-11-23',
                'jenis' => 'Konseling',
                'catatan' => 'Sesi awal',
            ])
            ->assertCreated()
            ->assertJsonPath('status', 'dijadwalkan');

        $this->assertSame('dibina', $violation->refresh()->status);
        $pembinaanId = (int) $response->json('id');

        $this->patchJson('/bk/pembinaan/'.$pembinaanId, ['status' => 'selesai'])->assertOk();
        $this->assertSame('selesai', $violation->refresh()->status);

        $tindakLanjutResponse = $this->postJson('/bk/pembinaan/'.$pembinaanId.'/tindak-lanjut', [
            'tanggal' => '2026-11-24',
            'deskripsi' => 'Evaluasi perilaku',
            'hasil' => 'Ada perbaikan',
            'status' => 'selesai',
        ])->assertCreated()
            ->assertJsonPath('status', 'selesai');

        $this->assertDatabaseHas('tindak_lanjut', [
            'pembinaan_id' => $pembinaanId,
            'dibuat_oleh' => $guruBk->id,
            'status' => 'selesai',
        ]);

        $this->patchJson('/bk/tindak-lanjut/'.$tindakLanjutResponse->json('id'), [
            'hasil' => 'Perbaikan berlanjut',
        ])->assertOk()->assertJsonPath('hasil', 'Perbaikan berlanjut');
    }

    public function test_admin_can_view_but_cannot_create_a_pembinaan_session(): void
    {
        $admin = $this->userWithRole('admin');

        $this->actingAs($admin)
            ->postJson('/bk/pembinaan', [])
            ->assertForbidden();
    }

    private function userWithRole(string $roleName): User
    {
        $role = Role::findOrCreate($roleName, 'web');
        $user = User::factory()->create();
        $user->assignRole($role);

        return $user;
    }
}
