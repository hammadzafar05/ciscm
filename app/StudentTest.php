<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StudentTest extends Model
{
	/*--MARUF START--*/
    protected $fillable = ['student_id','test_id','score','status'];
	/*--MARUF END--*/
	
    public function student(){
        return $this->belongsTo(Student::class);
    }

    public function test(){
        return $this->belongsTo(Test::class);
    }

    public function testOptions(){
        return $this->belongsToMany(TestOption::class);
    }

}
