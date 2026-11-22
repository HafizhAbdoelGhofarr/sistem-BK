<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TindakLanjut extends Model
{
    protected $table = 'tindak_lanjut';

    protected $fillable = [
        'pembinaan_id',
        'dibuat_oleh',
        'tanggal',
        'deskripsi',
        'hasil',
        'status',
    ];

    protected function casts(): array
    {
        return ['tanggal' => 'date'];
    }

    public function pembinaan(): BelongsTo
    {
        return $this->belongsTo(Pembinaan::class);
    }

    public function pembuat(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dibuat_oleh');
    }
}
