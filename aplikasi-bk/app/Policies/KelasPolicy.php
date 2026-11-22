<?php

namespace App\Policies;

use App\Models\Kelas;
use App\Models\User;

class KelasPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'guru_bk']);
    }

    public function view(User $user, Kelas $kelas): bool
    {
        return $user->hasAnyRole(['admin', 'guru_bk']);
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin');
    }

    public function update(User $user, Kelas $kelas): bool
    {
        return $user->hasRole('admin');
    }

    public function delete(User $user, Kelas $kelas): bool
    {
        return $user->hasRole('admin');
    }
}
