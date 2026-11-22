<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pembinaan extends Model
{
    protected $table = 'pembinaan';

    protected $fillable = [
        'siswa_id',
        'pelanggaran_id',
        'guru_bk_id',
        'tanggal',
        'jenis',
        'catatan',
        'status',
    ];

    protected function casts(): array
    {
        return ['tanggal' => 'date'];
    }

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class);
    }

    public function pelanggaran(): BelongsTo
    {
        return $this->belongsTo(Pelanggaran::class);
    }

    public function guruBk(): BelongsTo
    {
        return $this->belongsTo(User::class, 'guru_bk_id');
    }

    public function tindakLanjut(): HasMany
    {
        return $this->hasMany(TindakLanjut::class);
    }
}
