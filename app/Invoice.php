<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = ['user_id','currency_id','amount','cart','paid','payment_method_id','emi_status','emi_installment'];
    
        public function user(){
        return $this->belongsTo(User::class);
    }

}
