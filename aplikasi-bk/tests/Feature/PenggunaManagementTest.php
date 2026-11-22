<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class PenggunaManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_a_user_with_only_a_supported_role(): void
    {
        $admin = $this->userWithRole('admin');

        $this->actingAs($admin)
            ->postJson('/bk/pengguna', [
                'name' => 'Akun Siswa',
                'email' => 'siswa@example.test',
                'password' => 'password-aman',
                'role' => 'siswa',
            ])
            ->assertCreated()
            ->assertJsonPath('roles.0.name', 'siswa');

        $this->assertDatabaseHas('users', [
            'email' => 'siswa@example.test',
            'is_active' => true,
        ]);
    }

    public function test_only_admin_can_manage_users(): void
    {
        $guruBk = $this->userWithRole('guru_bk');

        $this->actingAs($guruBk)->getJson('/bk/pengguna')->assertForbidden();
    }

    public function test_the_last_active_admin_cannot_be_demoted_or_disabled(): void
    {
        $admin = $this->userWithRole('admin');

        $this->actingAs($admin)
            ->putJson('/bk/pengguna/'.$admin->id, ['is_active' => false])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('role');
    }

    private function userWithRole(string $roleName): User
    {
        $role = Role::findOrCreate($roleName, 'web');
        $user = User::factory()->create();
        $user->assignRole($role);

        return $user;
    }
}
