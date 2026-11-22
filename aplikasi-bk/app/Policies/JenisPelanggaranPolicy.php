<?php

namespace App\Policies;

use App\Models\JenisPelanggaran;
use App\Models\User;

class JenisPelanggaranPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'guru_bk']);
    }

    public function view(User $user, JenisPelanggaran $jenisPelanggaran): bool
    {
        return $user->hasAnyRole(['admin', 'guru_bk']);
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin');
    }

    public function update(User $user, JenisPelanggaran $jenisPelanggaran): bool
    {
        return $user->hasRole('admin');
    }

    public function delete(User $user, JenisPelanggaran $jenisPelanggaran): bool
    {
        return $user->hasRole('admin');
    }
}
