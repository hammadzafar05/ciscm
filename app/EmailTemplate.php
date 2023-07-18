<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{
    protected $fillable = ['name','description','message','default','placeholders','subject','default_subject'];
}
