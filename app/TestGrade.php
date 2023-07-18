<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TestGrade extends Model
{
    protected $fillable = ['grade','min','max'];
}
