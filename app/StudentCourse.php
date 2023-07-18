<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StudentCourse extends Model
{
    protected $fillable = ['student_id','course_id','reg_code','completed'];


    public function student(){
        return $this->belongsTo(Student::class);
    }

    public function course(){
        return $this->belongsTo(Course::class);
    }

}
