<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['admin', 'siswa', 'guru', 'guru_bk', 'wali_kelas'] as $role) {
            Role::findOrCreate($role, 'web');
        }

        $admin = User::firstOrCreate(
            ['email' => 'admin@sekolah.local'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('password'),
                'is_active' => true,
            ]
        );

        $admin->assignRole('admin');
    }
}
