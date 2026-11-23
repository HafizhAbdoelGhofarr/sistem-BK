<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DigiselfJurnalEvaluasi extends Model
{
    use HasFactory;

    protected $table = 'digiself_jurnal_evaluasi';

    protected $fillable = [
        'user_id',
        'rencana_aksi_id',
        'tanggal',
        'skor_regulasi',
        'pengalaman_harian',
        'hambatan',
        'langkah_perbaikan',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function rencanaAksi(): BelongsTo
    {
        return $this->belongsTo(DigiselfRencanaAksi::class, 'rencana_aksi_id');
    }
}
