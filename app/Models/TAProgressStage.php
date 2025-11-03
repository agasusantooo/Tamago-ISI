<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TAProgressStage extends Model
{
    use HasFactory;

    protected $table = 'ta_progress_stages';

    protected $fillable = [
        'stage_code',
        'stage_name',
        'description',
        'weight',
        'sequence',
        'is_active',
    ];

    protected $casts = [
        'weight' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Relasi ke student progress
     */
    public function studentProgress()
    {
        return $this->hasMany(StudentProgress::class, 'stage_id');
    }

    /**
     * Get active stages ordered by sequence
     */
    public static function getActiveStages()
    {
        return self::where('is_active', true)
            ->orderBy('sequence')
            ->get();
    }
}