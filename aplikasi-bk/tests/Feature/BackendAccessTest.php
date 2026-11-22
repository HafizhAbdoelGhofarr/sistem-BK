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

class BackendAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_can_only_list_and_view_their_own_records(): void
    {
        $student = $this->userWithRole('siswa');
        $otherStudent = $this->userWithRole('siswa');
        $ownProfile = $this->makeStudent($student, 'NIS-100');
        $otherProfile = $this->makeStudent($otherStudent, 'NIS-200');

        $this->actingAs($student)
            ->getJson('/bk/siswa')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $ownProfile->id);

        $this->getJson('/bk/siswa/'.$otherProfile->id)->assertForbidden();
    }

    public function test_student_cannot_create_or_view_another_students_violation(): void
    {
        $student = $this->userWithRole('siswa');
        $otherStudent = $this->userWithRole('siswa');
        $profile = $this->makeStudent($otherStudent, 'NIS-300');
        $violation = Pelanggaran::create([
            'siswa_id' => $profile->id,
            'jenis_pelanggaran_id' => $this->jenisPelanggaran(),
            'pelapor_id' => $student->id,
            'tanggal' => '2026-11-23',
            'poin' => 5,
        ]);

        $this->actingAs($student)
            ->getJson('/bk/pelanggaran/'.$violation->id)
            ->assertForbidden();

        $this->postJson('/bk/pelanggaran', [])->assertForbidden();
    }

    public function test_guru_bk_can_record_a_violation_using_the_configured_snapshot_points(): void
    {
        $guruBk = $this->userWithRole('guru_bk');
        $student = $this->makeStudent(null, 'NIS-400');
        $jenisPelanggaran = $this->jenisPelanggaran(25);

        $this->actingAs($guruBk)
            ->postJson('/bk/pelanggaran', [
                'siswa_id' => $student->id,
                'jenis_pelanggaran_id' => $jenisPelanggaran,
                'tanggal' => '2026-11-23',
            ])
            ->assertCreated()
            ->assertJsonPath('poin', 25);

        $this->assertSame(25, $student->refresh()->total_poin);
        $this->assertSame('perhatian', $student->status_disiplin);
    }

    private function userWithRole(string $roleName): User
    {
        $role = Role::findOrCreate($roleName, 'web');
        $user = User::factory()->create();
        $user->assignRole($role);

        return $user;
    }

    private function makeStudent(?User $user, string $nis): Siswa
    {
        $kelas = Kelas::firstOrCreate(
            ['nama' => 'X IPA 1', 'tahun_ajaran' => '2026/2027'],
            ['tingkat' => 'X']
        );

        return Siswa::create([
            'user_id' => $user?->id,
            'kelas_id' => $kelas->id,
            'nis' => $nis,
            'nama' => 'Siswa '.$nis,
            'jenis_kelamin' => 'L',
        ]);
    }

    private function jenisPelanggaran(int $poin = 5): int
    {
        $tataTertibId = TataTertib::create([
            'kode' => 'TT-'.uniqid(),
            'nama' => 'Tata Tertib Uji',
        ])->id;

        return JenisPelanggaran::create([
            'tata_tertib_id' => $tataTertibId,
            'kode' => 'JP-'.uniqid(),
            'nama' => 'Pelanggaran Uji',
            'kategori' => 'ringan',
            'poin' => $poin,
            'is_active' => true,
        ])->id;
    }
}
