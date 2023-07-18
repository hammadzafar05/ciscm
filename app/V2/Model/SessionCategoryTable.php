<?php
/**
 * Created by PhpStorm.
 * User: USER PC
 * Date: 9/7/2017
 * Time: 1:32 PM
 */

namespace App\V2\Model;

use App\CourseCategory;
use App\Lib\BaseTable;

class SessionCategoryTable extends BaseTable {

    private $categoryList = [];
    private $parentList = [];
    private $childList = [];

    protected $tableName='course_categories';
    //protected $primary='course_category_id';

    public function getAllCategories(){
        $this->getCategoriesForParent(0);
        return $this->categoryList;
    }

    public function getCategoryParent($id){
        $category = CourseCategory::find($id);
        if (!empty($category->parent_id)){
            $this->parentList[] = $category->parent_id;
            $this->getCategoryParent($category->parent_id);
        }
    }


    public function getAllParents($id){
        $this->getCategoryParent($id);
        return $this->parentList;
    }

    public function getChildren($id){
        $categories = CourseCategory::where('parent_id',$id)->orderBy('sort_order')->where('enabled',1)->get();
        foreach ($categories as $category){
            $this->childList[$category->id] = $category->id;
            $this->getChildren($category->id);
        }
    }

    public function getAllChildren($id){
        $this->getChildren($id);
        return $this->childList;
    }

    public function getCategoriesForParent($parentId,$level=0,$exId=null){

        if(empty($parentId)){
            $categories = CourseCategory::whereNull('parent_id')->orderBy('sort_order')->get();
        }
        else{
            $categories = CourseCategory::where('parent_id',$parentId)->orderBy('sort_order')->get();
        }


        $repeater = '  |__  ';

        foreach($categories as $row)
        {

            if (isset($exId) && $exId==$row->id) {
                continue;
            }

            $this->categoryList[$row->id] = str_repeat($repeater,$level).$row->name;
            $this->getCategoriesForParent($row->id, $level+1,$exId);
        }

    }


    /*    public function getLimitedRecords($limit)
        {
            $select = new Select($this->tableName);

            $select->limit($limit);
            $select->order('sort_order');


            $resultSet = $this->tableGateway->selectWith($select);
            return $resultSet;
        }


        public function getActiveRecords($limit){
            $select = new Select($this->tableName);

            $select->limit($limit);
            $select->order('sort_order');
            $select->where(['status'=>1]);


            $resultSet = $this->tableGateway->selectWith($select);
            return $resultSet;
        }*/


}
