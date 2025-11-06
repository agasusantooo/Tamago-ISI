<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjekAkhir extends Model
{
    use HasFactory;

    protected $table = 'projek_akhir';
    protected $primaryKey = 'id_proyek_akhir';

    protected $fillable = [
        'nim',
        'nidn1',
        'nidn2',
        'judul',
        'file_proposal',
        'file_pitch_deck',
        'file_story_bible',
        'status',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'nim', 'nim');
    }

    public function pembimbing1()
    {
        return $this->belongsTo(Dosen::class, 'nidn1', 'nidn');
    }

    public function pembimbing2()
    {
        return $this->belongsTo(Dosen::class, 'nidn2', 'nidn');
    }

    public function bimbingan()
    {
        return $this->hasMany(Bimbingan::class, 'id_proyek_akhir', 'id_proyek_akhir');
    }

    public function storyConference()
    {
        return $this->hasOne(StoryConference::class, 'id_proyek_akhir', 'id_proyek_akhir');
    }

    public function tefaFair()
    {
        return $this->hasOne(TefaFair::class, 'id_proyek_akhir', 'id_proyek_akhir');
    }

    public function pembimbing()
    {
        $pembimbing1 = $this->pembimbing1;
        $pembimbing2 = $this->pembimbing2;

        $pembimbings = collect();

        if ($pembimbing1) {
            $pembimbings->push($pembimbing1);
        }

        if ($pembimbing2) {
            $pembimbings->push($pembimbing2);
        }

        return $pembimbings;
    }
}
