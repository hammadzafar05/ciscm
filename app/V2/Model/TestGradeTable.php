<?php
/**
 * Created by PhpStorm.
 * User: USER PC
 * Date: 5/25/2018
 * Time: 4:48 PM
 */

namespace App\V2\Model;


use App\TestGrade;
use App\Lib\BaseTable;

class TestGradeTable extends BaseTable {
    protected $tableName = 'test_grades';
    //protected $primary = 'test_grade_id';

    public function getGrade($score){
        if(!is_numeric($score)){
            return '';
        }

        $testGrade = TestGrade::where('min','<=',$score)->where('max','>=',$score)->first();
        if($testGrade){
            return $testGrade->grade;
        }
        else{
            return '';
        }
    }
}
