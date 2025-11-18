<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjekAkhir extends Model
{
    use HasFactory;

    protected $table = 'projek_akhir';

    // migration uses id_proyek_akhir as primary key
    protected $primaryKey = 'id_proyek_akhir';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'nim',
        'nidn1',
        'nidn2',
        'judul',
        'file_proposal',
        'file_naskah_publikasi',
        'link_jurnal',
        'file_pitch_deck',
        'file_story_bible',
        'status',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relation to mahasiswa by nim
     */
    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'nim', 'nim');
    }
}
