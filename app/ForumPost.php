<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ForumPost extends Model
{
    protected $fillable = ['forum_topic_id','message','post_reply_id','user_id'];

    public function forumTopic(){
        return $this->belongsTo(ForumTopic::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function postReply(){
        return $this->belongsTo(ForumPost::class);
    }
}
