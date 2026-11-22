<?php

namespace App\Policies;

use App\Models\BatasStatus;
use App\Models\User;

class BatasStatusPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin');
    }

    public function update(User $user, BatasStatus $batasStatus): bool
    {
        return $user->hasRole('admin');
    }
}
