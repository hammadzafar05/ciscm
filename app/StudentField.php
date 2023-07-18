<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StudentField extends Model
{
    protected $fillable = ['name','sort_order','type','options','required','placeholder','enabled'];

}
