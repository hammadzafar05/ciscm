<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    protected $fillable = ['name','enabled','sort_order','directory','supported_currencies','label','is_global','settings'];

    public function invoices(){
        return $this->hasMany(Invoice::class);
    }

    public function currencies(){
        return $this->belongsToMany(Currency::class);
    }

}
