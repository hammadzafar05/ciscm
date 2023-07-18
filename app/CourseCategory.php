<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CourseCategory extends Model
{
    protected $fillable = ['name','enabled','sort_order','description','parent_id'];

    public function courses(){
        return $this->belongsToMany(Course::class);
    }

}
