<?php

namespace App\Policies;

use App\Models\NotifikasiWa;
use App\Models\User;

class NotifikasiWaPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin');
    }

    public function retry(User $user, NotifikasiWa $notifikasiWa): bool
    {
        return $user->hasRole('admin') && $notifikasiWa->status === 'gagal';
    }
}
