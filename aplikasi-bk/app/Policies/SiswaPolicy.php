<?php

namespace App\Policies;

use App\Models\Siswa;
use App\Models\User;

class SiswaPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'guru_bk', 'siswa']);
    }

    public function view(User $user, Siswa $siswa): bool
    {
        return $user->hasAnyRole(['admin', 'guru_bk'])
            || ($user->hasRole('siswa') && $siswa->user_id === $user->id);
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin');
    }

    public function update(User $user, Siswa $siswa): bool
    {
        return $user->hasRole('admin');
    }

    public function delete(User $user, Siswa $siswa): bool
    {
        return $user->hasRole('admin');
    }
}
