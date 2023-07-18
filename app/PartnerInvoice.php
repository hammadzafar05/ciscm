<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PartnerInvoice extends Model
{
    protected $fillable = ['admin_admin_id', 'admin_user_id', 'course_id', 'currency_id', 'amount', 'paid_amount', 'due_amount', 'payment_completed', 'receipt_file', 'partner_admin_id', 'partner_user_id', 'sent_date', 'students_ids', 'status'];
	
	protected static function boot()
	{
		parent::boot();
		
		static::created(function ($model) {
			$model->invoice_number = 'WARD-PRTN-' . str_pad($model->id, 5, '0', STR_PAD_LEFT);;
			$model->save();
		});
	}

    public function user(){
        return $this->belongsTo(User::class, 'partner_user_id', 'id');
    }
    public function course(){
        return $this->belongsTo(Course::class, 'course_id', 'id');
    }

    public function currency(){
        return $this->belongsTo(Currency::class);
    }

    public function paymentMethod(){
        return $this->belongsTo(PaymentMethod::class);
    }

    public function invoiceTransactions(){
        return $this->hasMany(InvoiceTransaction::class);
    }
}
