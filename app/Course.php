<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = ['name','admin_id','enabled','start_date','end_date','enrollment_closes','capacity','payment_required','fee','description','venue','type','picture','enable_discussion','enable_chat','enforce_order','effort','length','short_description','introduction','enable_forum','enforce_capacity','outcomes','grading_system','module_description','module_length','regular_fee','emi_status','emi_installment','accreditation','course_objective','student_feedback','course_faq','course_details','approved_by_super_admin'];

    public function admin(){
        return $this->belongsTo(Admin::class);
    }

    public function admins(){
        return $this->belongsToMany(Admin::class);
    }

    public function studentCourses(){
        return $this->hasMany(StudentCourse::class);
    }

    public function courseCategories(){
        return $this->belongsToMany(CourseCategory::class);
    }

    public function assignments(){
        return $this->hasMany(Assignment::class);
    }

    public function attendances(){
        return $this->hasMany(Attendance::class);
    }

    public function courses(){
        return $this->hasMany(Bookmark::class);
    }

    public function certificates(){
        return $this->hasMany(Certificate::class);
    }

    public function lessons(){
        return $this->belongsToMany(Lesson::class)->withPivot('lesson_date','lesson_venue','lesson_start','lesson_end','sort_order');
    }

    public function tests(){
        return $this->belongsToMany(Test::class);
    }

    public function downloads(){
        return $this->belongsToMany(Download::class);
    }

    public function relatedCourses(){
        return $this->hasMany(RelatedCourse::class);
    }

    public function courseLessonAdmins(){
        return $this->hasMany(CourseLessonAdmin::class);
    }

    public function revisionNotes(){
        return $this->hasMany(RevisionNote::class);
    }

    public function forumTopics()
    {
        return $this->hasMany(ForumTopic::class);
    }
}
