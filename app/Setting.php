<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['key','placeholder','value','type','options','class','sort_order'];




}
