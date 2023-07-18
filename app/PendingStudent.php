<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PendingStudent extends Model
{
    protected $fillable = ['data','hash'];

    public function pendingStudentFiles(){
        return $this->hasMany(PendingStudentFile::class);
    }

}
