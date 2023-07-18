<?php
/**
 * Created by PhpStorm.
 * User: USER PC
 * Date: 2/1/2017
 * Time: 1:37 PM
 */

namespace App\V2\Model;


use App\Lib\BaseTable;
use App\Student;
use Laminas\Db\Sql\Expression;
use Laminas\Db\Sql\Select;
use Laminas\Paginator\Adapter\DbSelect;
use Laminas\Paginator\Paginator;

class StudentVideoTable extends BaseTable
{

    protected $tableName = 'student_video';
    //protected $primary = 'student_video_id';

    public function addVideoForStudent($studentId,$videoId){

        $student = Student::find($studentId);
        if (!$student){
            return  false;
        }
        $record= $student->videos()->where('id',$videoId)->first();
        if(!$record){
            $student->videos()->attach($videoId);

        }

        return $record;

    }


    public function hasVideo($studentId,$videoId){
        $student = Student::find($studentId);
        $record= $student->videos()->where('id',$videoId)->count();
        return !empty($record);
    }


}
