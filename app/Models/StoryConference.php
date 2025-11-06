<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoryConference extends Model
{
    use HasFactory;

    protected $table = 'story_conference';
    protected $primaryKey = 'id_conference';

    protected $fillable = [
        'id_proyek_akhir',
        'tanggal',
        'catatan_evaluasi',
        'status',
        'waktu',
        'file',
    ];

    public function projekAkhir()
    {
        return $this->belongsTo(ProjekAkhir::class, 'id_proyek_akhir', 'id_proyek_akhir');
    }
}
