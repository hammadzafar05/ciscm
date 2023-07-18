<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StudentCertificate extends Model
{
    protected $fillable = ['student_id','certificate_id','tracking_number'];


    public function student(){
        return $this->belongsTo(Student::class);
    }

    public function certificate(){
        return $this->belongsTo(Certificate::class);
    }



}
