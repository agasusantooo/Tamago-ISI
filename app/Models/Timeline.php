<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Timeline extends Model
{
    protected $fillable = ['semester_id', 'ta_progress_stage_id', 'due_date'];
    
    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }
    
    public function taProgressStage()
    {
        return $this->belongsTo(TAProgressStage::class);
    }
}
