<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DigiselfRefleksi extends Model
{
    use HasFactory;

    protected $table = 'digiself_refleksi';

    protected $fillable = [
        'user_id',
        'durasi_layar',
        'tujuan_penggunaan',
        'aplikasi_distraksi',
        'situasi_pemicu',
        'dampak_negatif',
        'catatan_refleksi',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
