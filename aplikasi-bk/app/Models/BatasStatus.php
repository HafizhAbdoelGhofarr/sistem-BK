<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BatasStatus extends Model
{
    protected $table = 'batas_status';

    protected $fillable = ['status', 'min_poin'];

    protected function casts(): array
    {
        return ['min_poin' => 'integer'];
    }
}
