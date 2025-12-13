<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Semester extends Model
{
    // Allow mass assignment for fields used in controller create/update
    protected $fillable = [
        'nama',
        'tanggal_mulai',
        'tanggal_akhir',
        'is_active',
    ];

    // If you prefer boolean casting for is_active
    protected $casts = [
        'is_active' => 'boolean',
    ];
}
