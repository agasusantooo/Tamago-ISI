<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dosen extends Model
{
    use HasFactory;

    protected $table = 'dosen';
    protected $primaryKey = 'nidn';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'nidn',
        'nama',
        'jabatan',
        'rumpun_ilmu',
        'status',
    ];

        // Relasi ke mahasiswa yang dibimbing
        public function mahasiswaBimbingan()
        {
            return $this->hasMany(Mahasiswa::class, 'dosen_pembimbing_id', 'nidn');
        }
}
