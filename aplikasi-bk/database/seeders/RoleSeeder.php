<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        foreach (['admin', 'guru_bk', 'siswa'] as $role) {
            Role::findOrCreate($role, 'web');
        }

        // 1. Akun Admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@sekolah.local'],
            [
                'name' => 'Administrator BK',
                'password' => Hash::make('password'),
                'is_active' => true,
            ]
        );
        if (! $admin->hasVerifiedEmail()) {
            $admin->forceFill(['email_verified_at' => now()])->save();
        }
        $admin->assignRole('admin');

        // 2. Akun Guru BK
        $guruBk = User::firstOrCreate(
            ['email' => 'gurubk@sekolah.local'],
            [
                'name' => 'Ibu Rahma, S.Pd (Guru BK)',
                'password' => Hash::make('password'),
                'is_active' => true,
            ]
        );
        if (! $guruBk->hasVerifiedEmail()) {
            $guruBk->forceFill(['email_verified_at' => now()])->save();
        }
        $guruBk->assignRole('guru_bk');

        // 3. Akun Siswa
        $siswa = User::firstOrCreate(
            ['email' => 'siswa@sekolah.local'],
            [
                'name' => 'Farrel Siswa',
                'password' => Hash::make('password'),
                'is_active' => true,
            ]
        );
        if (! $siswa->hasVerifiedEmail()) {
            $siswa->forceFill(['email_verified_at' => now()])->save();
        }
        $siswa->assignRole('siswa');
    }
}
