<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = ['title','content','slug','enabled','meta_title','meta_description','mobile'];


}
