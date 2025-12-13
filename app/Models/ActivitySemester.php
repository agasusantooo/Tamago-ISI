<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivitySemester extends Model
{
    // Allow mass assignment for fields used when configuring activity semesters
    protected $fillable = [
        'type',
        'semester_id',
    ];

    // Cast semester_id to integer if needed
    protected $casts = [
        'semester_id' => 'integer',
    ];
}
