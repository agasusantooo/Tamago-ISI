<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    use HasFactory;

    protected $table = 'mahasiswa'; // 👈 penting: biar gak dicari 'mahasiswas'

    // The `mahasiswa` table uses `nim` as the primary key (string), not an auto-incrementing `id`.
    protected $primaryKey = 'nim';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'user_id',
        'nim',
        'nama',
        // 'prodi' and 'angkatan' removed per request
            'dosen_pembimbing_id',
        'email',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function projekAkhir()
    {
        // projek_akhir.mahasiswa_id menyimpan `users.id`, sedangkan model Mahasiswa
        // menyimpan kolom `user_id` yang mengacu ke users.id. Gunakan user_id
        // sebagai local key agar relasi bekerja dengan benar.
        return $this->hasOne(ProjekAkhir::class, 'mahasiswa_id', 'user_id');
    }

        // Relasi ke dosen pembimbing
        public function dosenPembimbing()
        {
            return $this->belongsTo(Dosen::class, 'dosen_pembimbing_id', 'nidn');
        }
}