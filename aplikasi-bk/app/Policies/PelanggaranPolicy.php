<?php

namespace App\Policies;

use App\Models\Pelanggaran;
use App\Models\User;

class PelanggaranPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'guru_bk', 'siswa']);
    }

    public function view(User $user, Pelanggaran $pelanggaran): bool
    {
        return $user->hasAnyRole(['admin', 'guru_bk'])
            || ($user->hasRole('siswa') && $pelanggaran->siswa?->user_id === $user->id);
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'guru_bk']);
    }

    public function update(User $user, Pelanggaran $pelanggaran): bool
    {
        return $user->hasAnyRole(['admin', 'guru_bk']);
    }

    public function delete(User $user, Pelanggaran $pelanggaran): bool
    {
        return $user->hasAnyRole(['admin', 'guru_bk']);
    }

    public function exportReport(User $user): bool
    {
        return $user->hasRole('admin');
    }
}
