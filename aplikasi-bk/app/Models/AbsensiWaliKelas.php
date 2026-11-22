<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AbsensiWaliKelas extends Model
{
    protected $table = 'absensi_wali_kelas';

    protected $fillable = [
        'guru_id',
        'kelas_id',
        'tanggal',
        'jam_masuk',
        'jam_keluar',
        'status',
        'keterangan',
    ];

    protected function casts(): array
    {
        return ['tanggal' => 'date'];
    }

    public function guru(): BelongsTo
    {
        return $this->belongsTo(Guru::class);
    }

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class);
    }
}
