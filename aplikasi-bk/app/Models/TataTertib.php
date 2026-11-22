<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TataTertib extends Model
{
    protected $table = 'tata_tertib';

    protected $fillable = ['kode', 'nama', 'deskripsi'];

    public function jenisPelanggaran(): HasMany
    {
        return $this->hasMany(JenisPelanggaran::class);
    }
}
