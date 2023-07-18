<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = ['user_id','mobile_number','api_token','token_expires'];

    public function assignmentSubmissions(){
        return $this->hasMany(AssignmentSubmission::class);
    }

    public function attendances(){
        return $this->hasMany(Attendance::class);
    }

    public function bookmarks(){
        return $this->hasMany(Bookmark::class);
    }

    public function certificates(){
        return $this->hasMany(Certificate::class);
    }


    public function invoices(){
        return $this->hasMany(Invoice::class);
    }

    public function discussions(){
        return $this->hasMany(Discussion::class);
    }

    public function lectureNotes(){
        return $this->hasMany(LectureNote::class);
    }

    public function studentCertificates()
    {
        return $this->hasMany(StudentCertificate::class);
    }

    public function studentCourses(){
        return $this->hasMany(StudentCourse::class);
    }

    public function videos(){
        return $this->belongsToMany(Video::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function studentCertificateDownloads(){
        return $this->hasMany(StudentCertificateDownload::class);
    }

    public function studentTests(){
        return $this->hasMany(StudentTest::class);
    }

    public function studentFields(){
        return $this->belongsToMany(StudentField::class);
    }

}
