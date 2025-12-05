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
        'user_id',
        'nama',
        'jabatan',
        'rumpun_ilmu',
        'status',
        'jabatan_fungsional',
        'is_dosen_seminar',
    ];

    /**
     * Get the user that owns the dosen.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

        // Relasi ke mahasiswa yang dibimbing
            public function mahasiswaBimbingan()
            {
                return $this->hasMany(Mahasiswa::class, 'dosen_pembimbing_id', 'nidn');
            }
        
            public function rumpunIlmus()
            {
                return $this->belongsToMany(RumpunIlmu::class, 'dosen_rumpun_ilmu', 'dosen_nidn', 'rumpun_ilmu_id');
            }
        }
        
