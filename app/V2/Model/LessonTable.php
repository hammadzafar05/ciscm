<?php
/**
 * Created by PhpStorm.
 * User: USER PC
 * Date: 8/7/2016
 * Time: 3:23 PM
 */

namespace App\V2\Model;



use App\Lesson;
use App\Lib\BaseTable;

class LessonTable extends BaseTable {

    protected $tableName = 'lessons';
    //protected $primary = 'lesson_id';
    protected $accountId = true;

    public function getRecords(){


        $rowset = Lesson::orderBy('name');

        if(!GLOBAL_ACCESS){
            $rowset->where('admin_id',ADMIN_ID);
        }

        $rowset = $rowset->get();
        return $rowset;
    }

    public function getLimitedLessonRecords($type=null,$limit=null){
        $select = Lesson::orderBy('name');

        if($type){
            $select->where(['type'=>$type]);
        }
        if($limit){
            $select->limit($limit);
        }

        if(!GLOBAL_ACCESS){
            $select->where(['admin_id'=>ADMIN_ID]);
        }

        $rowset = $select->get();
        return $rowset;
    }

    public function getRecordsOrdered(){
        $select = Lesson::orderBy('sort_order');

        if(!GLOBAL_ACCESS){
            $select->where(['admin_id'=>ADMIN_ID]);
        }

        $rowset = $select->get();
        return $rowset;
    }

    public function getLessons($paginated=false,$filter=null,$group=null,$order=null,$perPage=30)
    {
        if(empty($group)){
            $select = Lesson::where('id','>',0);
        }
        else{
            $select = Lesson::whereHas('lessonGroups',function ($query) use ($group){
                $query->where('id',$group);
            });

        }

        if(!GLOBAL_ACCESS){
            $select->where(['admin_id'=>ADMIN_ID]);
        }

        switch($order){
            case 'asc':
                $select->orderBy('name');
                break;
            case 'desc':
                $select->orderBy('name','desc');
                break;
            case 'recent':
                $select->orderBy('id','desc');
                break;
            case 'sortOrder':
                $select->orderBy('sort_order');
                break;
            case 'courses':
                $select->where('type','s');
                break;
            case 'online':
                $select->where('type','c');
                break;
            default:
                $select->orderBy('id','desc');
                break;

        }


        if(isset($filter))
        {
            $filter = addslashes($filter);
            $select->whereRaw("match(name,description,introduction) against (? IN NATURAL LANGUAGE MODE)", [$filter]);

        }



        if($paginated)
        {
            return $select->paginate($perPage);
        }

        return $select->get();
    }



}
