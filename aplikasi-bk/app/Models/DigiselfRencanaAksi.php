<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DigiselfRencanaAksi extends Model
{
    use HasFactory;

    protected $table = 'digiself_rencana_aksi';

    protected $fillable = [
        'user_id',
        'tujuan_perubahan',
        'langkah_tindakan',
        'waktu_pelaksanaan',
        'indikator_keberhasilan',
        'status',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function jurnalEvaluasi(): HasMany
    {
        return $this->hasMany(DigiselfJurnalEvaluasi::class, 'rencana_aksi_id');
    }
}
