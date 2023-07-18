<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ManageStudentLessonAccess extends Model
{
    use SoftDeletes;

    const PAGE_FOLDER = '';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'lesson_access';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'lesson_id',
        'student_id',
        'course_id',
    ];
}
