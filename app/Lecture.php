<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lecture extends Model
{
    protected $fillable = ['lesson_id','title','sort_order'];

    public function lesson(){
        return $this->belongsTo(Lesson::class);
    }

    public function lectureFiles()
    {
        return $this->hasMany(LectureFile::class);
    }

    public function lectureNotes(){
        return $this->hasMany(LectureNote::class);
    }

    public  function lecturePages(){
        return $this->hasMany(LecturePage::class);
    }

}
