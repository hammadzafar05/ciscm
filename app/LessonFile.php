<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LessonFile extends Model
{
    protected $fillable = ['lesson_id','path','enabled'];

    public function lesson(){
        return $this->belongsTo(Lesson::class);
    }
    
}
