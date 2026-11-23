<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class RoleAccessSeparationTest extends TestCase
{
    use RefreshDatabase;

    private function userWithRole(string $role): User
    {
        Role::findOrCreate($role, 'web');
        $user = User::factory()->create([
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
        $user->assignRole($role);

        return $user;
    }

    public function test_siswa_cannot_access_staff_and_admin_pages(): void
    {
        $siswa = $this->userWithRole('siswa');

        $this->actingAs($siswa)->get('/pelanggaran/create')->assertForbidden();
        $this->actingAs($siswa)->get('/pembinaan/create')->assertForbidden();
        $this->actingAs($siswa)->get('/master/siswa')->assertForbidden();
        $this->actingAs($siswa)->get('/notifikasi-wa')->assertForbidden();
        $this->actingAs($siswa)->get('/laporan')->assertForbidden();
    }

    public function test_guru_bk_cannot_access_admin_system_pages(): void
    {
        $guruBk = $this->userWithRole('guru_bk');

        $this->actingAs($guruBk)->get('/master/siswa')->assertForbidden();
        $this->actingAs($guruBk)->get('/notifikasi-wa')->assertForbidden();
        $this->actingAs($guruBk)->get('/laporan')->assertForbidden();
    }

    public function test_admin_cannot_access_counseling_creation(): void
    {
        $admin = $this->userWithRole('admin');

        $this->actingAs($admin)->get('/pembinaan/create')->assertForbidden();
    }

    public function test_authorized_routes_for_each_role(): void
    {
        $siswa = $this->userWithRole('siswa');
        $this->actingAs($siswa)->get('/master/tata-tertib')->assertOk();
        $this->actingAs($siswa)->get('/dashboard')->assertOk();
        $this->actingAs($siswa)->get('/profile')->assertOk();

        $guruBk = $this->userWithRole('guru_bk');
        $this->actingAs($guruBk)->get('/pelanggaran/create')->assertOk();
        $this->actingAs($guruBk)->get('/pembinaan/create')->assertOk();
        $this->actingAs($guruBk)->get('/dashboard')->assertOk();

        $admin = $this->userWithRole('admin');
        $this->actingAs($admin)->get('/master/siswa')->assertOk();
        $this->actingAs($admin)->get('/notifikasi-wa')->assertOk();
        $this->actingAs($admin)->get('/laporan')->assertOk();
        $this->actingAs($admin)->get('/dashboard')->assertOk();
    }
}
