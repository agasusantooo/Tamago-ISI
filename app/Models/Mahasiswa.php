<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    use HasFactory;

    protected $table = 'mahasiswa'; // 👈 penting: biar gak dicari 'mahasiswas'

    protected $fillable = [
        'user_id',
        'nim',
        'nama',
        'prodi',
        'angkatan',
            'dosen_pembimbing_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function projekAkhir()
    {
        return $this->hasOne(ProjekAkhir::class, 'mahasiswa_id', 'id');
    }

        // Relasi ke dosen pembimbing
        public function dosenPembimbing()
        {
            return $this->belongsTo(Dosen::class, 'dosen_pembimbing_id', 'nidn');
        }
}