<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DiscussionReply extends Model
{
    protected $fillable = ['discussion_id','reply','user_id'];

    public function discussion(){
        return $this->belongsTo(Discussion::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

}
