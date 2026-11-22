<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JenisPelanggaran extends Model
{
    protected $table = 'jenis_pelanggaran';

    protected $fillable = [
        'tata_tertib_id',
        'kode',
        'nama',
        'kategori',
        'poin',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'poin' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function tataTertib(): BelongsTo
    {
        return $this->belongsTo(TataTertib::class);
    }

    public function pelanggaran(): HasMany
    {
        return $this->hasMany(Pelanggaran::class);
    }
}
