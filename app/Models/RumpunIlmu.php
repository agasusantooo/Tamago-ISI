<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RumpunIlmu extends Model
{
    use HasFactory;

    protected $table = 'rumpun_ilmus';

    protected $fillable = ['nama'];

    public function dosens()
    {
        return $this->belongsToMany(Dosen::class, 'dosen_rumpun_ilmu', 'rumpun_ilmu_id', 'dosen_nidn');
    }
}
