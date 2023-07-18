<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LessonGroup extends Model
{
    protected $fillable = ['name','description','sort_order'];

    public function lessons(){
        return $this->belongsToMany(Lesson::class);
    }

}
