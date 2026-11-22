<?php

namespace App\Policies;

use App\Models\TataTertib;
use App\Models\User;

class TataTertibPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'guru_bk']);
    }

    public function view(User $user, TataTertib $tataTertib): bool
    {
        return $user->hasAnyRole(['admin', 'guru_bk']);
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin');
    }

    public function update(User $user, TataTertib $tataTertib): bool
    {
        return $user->hasRole('admin');
    }

    public function delete(User $user, TataTertib $tataTertib): bool
    {
        return $user->hasRole('admin');
    }
}
