<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    protected $fillable = ['country_id','exchange_rate'];

    public function country(){
        return $this->belongsTo(Country::class);
    }

}
