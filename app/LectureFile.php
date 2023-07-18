<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LectureFile extends Model
{
    protected $fillable = ['lecture_id','path','enabled'];

    public function lecture(){
        return $this->belongsTo(Lecture::class);
    }

}
