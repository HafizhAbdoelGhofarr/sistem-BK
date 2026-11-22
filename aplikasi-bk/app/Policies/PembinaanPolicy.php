<?php

namespace App\Policies;

use App\Models\Pembinaan;
use App\Models\User;

class PembinaanPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'guru_bk', 'siswa']);
    }

    public function view(User $user, Pembinaan $pembinaan): bool
    {
        return $user->hasAnyRole(['admin', 'guru_bk'])
            || ($user->hasRole('siswa') && $pembinaan->siswa?->user_id === $user->id);
    }

    public function create(User $user): bool
    {
        return $user->hasRole('guru_bk');
    }

    public function update(User $user, Pembinaan $pembinaan): bool
    {
        return $user->hasRole('guru_bk');
    }

    public function delete(User $user, Pembinaan $pembinaan): bool
    {
        return false;
    }
}
