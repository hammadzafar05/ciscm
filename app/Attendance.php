<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = ['lesson_id','student_id','course_id','attendance_date'];

    public function lesson(){
        return $this->belongsTo(Lesson::class);
    }

    public function student(){
        return $this->belongsTo(Student::class);
    }

    public function course(){
        return $this->belongsTo(Course::class);
    }

}
