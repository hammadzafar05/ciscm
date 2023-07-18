<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ForumTopic extends Model
{
    protected $fillable = ['title','user_id','course_id','lecture_id'];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function course(){
        return $this->belongsTo(Course::class);
    }

    public function lecture(){
        return $this->belongsTo(Lecture::class);
    }

    public function forumPosts(){
        return $this->hasMany(ForumPost::class);
    }

    public function forumParticipants(){
        return $this->hasMany(ForumParticipant::class);
    }
}
