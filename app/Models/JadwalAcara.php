<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JadwalAcara extends Model
{
    protected $fillable = [
        'title', 'start', 'end', 'type'
    ];
}
