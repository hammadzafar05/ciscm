<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InvoiceTransaction extends Model
{
    protected $fillable = ['invoice_id','status','amount'];

    public function invoice(){
        return $this->belongsTo(Invoice::class);
    }
}
