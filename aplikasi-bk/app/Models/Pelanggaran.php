<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pelanggaran extends Model
{
    use SoftDeletes;

    protected $table = 'pelanggaran';

    protected $fillable = [
        'siswa_id',
        'jenis_pelanggaran_id',
        'pelapor_id',
        'tanggal',
        'poin',
        'keterangan',
        'bukti_foto',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
            'poin' => 'integer',
        ];
    }

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class);
    }

    public function jenisPelanggaran(): BelongsTo
    {
        return $this->belongsTo(JenisPelanggaran::class);
    }

    public function pelapor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pelapor_id');
    }

    public function pembinaan(): HasOne
    {
        return $this->hasOne(Pembinaan::class);
    }

    public function notifikasiWa(): HasMany
    {
        return $this->hasMany(NotifikasiWa::class);
    }
}
