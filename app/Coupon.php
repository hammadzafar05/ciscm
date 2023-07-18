<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = ['code','discount','expires_on','enabled','name','type','total','date_start','uses_total','uses_customer'];

    public function courses(){
        return $this->belongsToMany(Course::class);
    }

    public function courseCategories(){
        return $this->belongsToMany(CourseCategory::class);
    }

    public function invoices(){
        return $this->belongsToMany(Invoice::class);
    }



}
