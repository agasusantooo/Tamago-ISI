<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TefaFair extends Model
{
    use HasFactory;

    protected $table = 'tefa_fair';
    protected $primaryKey = 'id_tefa';

    protected $fillable = [
        'id_proyek_akhir',
        'semester',
        'file_presentasi',
        'daftar_kebutuhan',
    ];

    public function projekAkhir()
    {
        return $this->belongsTo(ProjekAkhir::class, 'id_proyek_akhir', 'id_proyek_akhir');
    }
}
