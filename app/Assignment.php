<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    protected $fillable = ['title','course_id','lesson_id','admin_id','due_date','type','capston','instruction','passmark','notify','allow_late','opening_date','schedule_type'];

    public function course(){
        return $this->belongsTo(Course::class);
    }

    public function lesson(){
        return $this->belongsTo(Lesson::class);
    }

    public function admin(){
        return $this->belongsTo(Admin::class);
    }

    public function assignmentSubmissions(){
        return $this->hasMany(AssignmentSubmission::class);
    }




}
