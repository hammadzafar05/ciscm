<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SmsGateway extends Model
{
    protected $fillable = ['gateway_name','directory','enabled','settings','default'];
}
