<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bookmark extends Model
{
    protected $fillable = ['course_id','student_id','lecture_page_id'];

    public function course(){
        return $this->belongsTo(Course::class);
    }

    public function student(){
        return $this->belongsTo(Student::class);
    }

    public function lecturePage(){
        return $this->belongsTo(LecturePage::class);
    }

}
