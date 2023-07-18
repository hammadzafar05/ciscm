<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AssignmentSubmission extends Model
{
    protected $fillable= ['assignment_id','student_id','content','file_path','grade','editable','admin_comment','submitted','student_comment'];

    public function assignment(){
        return $this->belongsTo(Assignment::class);
    }

    public function student(){
        return $this->belongsTo(Student::class);
    }


}
