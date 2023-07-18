<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    protected $fillable = ['course_id','admin_id','name','image','orientation','description','html','any_session','max_downloads'];

    public function course(){
        return $this->belongsTo(Course::class);
    }

    public function admin(){
        return $this->belongsTo(Admin::class);
    }

    public function studentCertificates(){
        return $this->hasMany(StudentCertificate::class);
    }

    public function assignments(){
        return $this->belongsToMany(Assignment::class);
    }

    public function lessons()
    {
        return $this->belongsToMany(Lesson::class);
    }

}
