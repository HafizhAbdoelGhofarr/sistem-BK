<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NotifikasiWa extends Model
{
    protected $table = 'notifikasi_wa';

    protected $fillable = [
        'siswa_id',
        'pelanggaran_id',
        'nomor_tujuan',
        'pesan',
        'status',
        'percobaan',
        'respon',
        'dikirim_pada',
    ];

    protected function casts(): array
    {
        return [
            'percobaan' => 'integer',
            'dikirim_pada' => 'datetime',
        ];
    }

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class);
    }

    public function pelanggaran(): BelongsTo
    {
        return $this->belongsTo(Pelanggaran::class);
    }
}
