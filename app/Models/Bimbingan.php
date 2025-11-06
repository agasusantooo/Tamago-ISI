<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bimbingan extends Model
{
    use HasFactory;

    protected $table = 'bimbingan';
    protected $primaryKey = 'id_bimbingan';

    protected $fillable = [
        'id_proyek_akhir',
        'nidn',
        'tanggal',
        'catatan_bimbingan',
        'pencapaian',
        'status_persetujuan',
    ];

    public function projekAkhir()
    {
        return $this->belongsTo(ProjekAkhir::class, 'id_proyek_akhir', 'id_proyek_akhir');
    }

    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'nidn', 'nidn');
    }
}
