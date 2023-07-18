<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PendingStudentFile extends Model
{
        protected $fillable = ['pending_student_id','file_name','file_path','field_id'];

        public function pendingStudent(){
            return $this->belongsTo(PendingStudent::class);
        }

}
