<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Discussion extends Model
{
    protected $fillable = ['student_id','subject','question','replied','course_id','lecture_id','admin'];

    public function student(){
        return $this->belongsTo(Student::class);
    }

    public function course(){
        return $this->belongsTo(Course::class);
    }

    public function lecture(){
        return $this->belongsTo(Lecture::class);
    }

    public function discussionReplies(){
        return $this->hasMany(DiscussionReply::class);
    }

}
