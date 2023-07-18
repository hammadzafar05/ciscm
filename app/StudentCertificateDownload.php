<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StudentCertificateDownload extends Model
{
    protected $fillable = ['student_id','certificate_id'];

    public function student(){
        return $this->belongsTo(Student::class);
    }

    public function certificate(){
        return $this->belongsTo(Certificate::class);
    }


}
