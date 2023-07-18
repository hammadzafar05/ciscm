<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = ['student_id','payment_method_id','amount','status'];

    public function student(){
        return $this->belongsTo(Student::class);
    }

    public function paymentMethod(){
        return $this->belongsTo(PaymentMethod::class);
    }


}
