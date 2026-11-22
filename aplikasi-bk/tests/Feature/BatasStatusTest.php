<?php

namespace Tests\Feature;

use App\Models\BatasStatus;
use App\Models\JenisPelanggaran;
use App\Models\Kelas;
use App\Models\Pelanggaran;
use App\Models\Siswa;
use App\Models\TataTertib;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class BatasStatusTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_change_thresholds_and_existing_student_statuses_are_recalculated(): void
    {
        $admin = $this->userWithRole('admin');
        $student = Siswa::create([
            'kelas_id' => Kelas::create([
                'nama' => 'X IPA 1',
                'tingkat' => 'X',
                'tahun_ajaran' => '2026/2027',
            ])->id,
            'nis' => 'NIS-STATUS',
            'nama' => 'Siswa Uji',
            'jenis_kelamin' => 'L',
        ]);
        $type = JenisPelanggaran::create([
            'tata_tertib_id' => TataTertib::create([
                'kode' => 'TT-STATUS',
                'nama' => 'Tata Tertib',
            ])->id,
            'kode' => 'JP-STATUS',
            'nama' => 'Pelanggaran',
            'kategori' => 'sedang',
            'poin' => 30,
            'is_active' => true,
        ]);
        Pelanggaran::create([
            'siswa_id' => $student->id,
            'jenis_pelanggaran_id' => $type->id,
            'pelapor_id' => $admin->id,
            'tanggal' => '2026-11-23',
            'poin' => 30,
        ]);

        $batas = BatasStatus::query()->get()->keyBy('status');
        $this->actingAs($admin)
            ->putJson('/bk/batas-status', [
                'batas' => [
                    ['id' => $batas['aman']->id, 'min_poin' => 0],
                    ['id' => $batas['perhatian']->id, 'min_poin' => 35],
                    ['id' => $batas['pembinaan']->id, 'min_poin' => 45],
                    ['id' => $batas['kritis']->id, 'min_poin' => 60],
                ],
            ])
            ->assertOk();

        $this->assertSame(30, $student->refresh()->total_poin);
        $this->assertSame('aman', $student->status_disiplin);
    }

    private function userWithRole(string $roleName): User
    {
        $role = Role::findOrCreate($roleName, 'web');
        $user = User::factory()->create();
        $user->assignRole($role);

        return $user;
    }
}
