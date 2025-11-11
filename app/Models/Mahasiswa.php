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
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
