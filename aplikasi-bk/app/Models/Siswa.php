<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Siswa extends Model
{
    use SoftDeletes;

    protected $table = 'siswa';

    protected $fillable = [
        'user_id',
        'kelas_id',
        'nis',
        'nisn',
        'nama',
        'jenis_kelamin',
        'tanggal_lahir',
        'alamat',
        'nama_ortu',
        'no_hp_ortu',
        'total_poin',
        'status_disiplin',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_lahir' => 'date',
            'total_poin' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class);
    }

    public function pelanggaran(): HasMany
    {
        return $this->hasMany(Pelanggaran::class);
    }

    public function pembinaan(): HasMany
    {
        return $this->hasMany(Pembinaan::class);
    }

    public function notifikasiWa(): HasMany
    {
        return $this->hasMany(NotifikasiWa::class);
    }
}
