<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DigiselfBantuanBk extends Model
{
    use HasFactory;

    protected $table = 'digiself_bantuan_bk';

    protected $fillable = [
        'user_id',
        'topik',
        'urgensi',
        'cerita_kendala',
        'status',
        'tanggapan_konselor',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
