<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{

    protected $fillable = ['name','iso_code_2','iso_code_3','currency_name','currency_code','symbol_left','symbol_right'];




}
