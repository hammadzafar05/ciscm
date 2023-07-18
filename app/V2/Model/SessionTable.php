<?php
/**
 * Created by PhpStorm.
 * User: USER PC
 * Date: 8/7/2016
 * Time: 3:33 PM
 */

namespace App\V2\Model;


use App\Lib\BaseTable;
use Illuminate\Support\Carbon;
use Laminas\Db\Sql\Select;
use Laminas\Paginator\Adapter\DbSelect;
use Laminas\Paginator\Paginator;

class SessionTable extends BaseTable {

    protected $tableName = 'courses';
    //protected $primary = 'course_id';
    protected $accountId = true;

    public function getLimitedRecords($limit)
    {
        $select = new Select($this->tableName);
        $select->order('name')
                ->limit($limit);

        if(!GLOBAL_ACCESS){
            $select->where(['admin_id'=>ADMIN_ID]);
        }

        $resultSet = $this->tableGateway->selectWith($select);
        return $resultSet;
    }


    public function getPaginatedRecords($paginated=false,$id=null,$activeOnly=false,$filter=null,$group=null,$order=null,$type=null,$futureOnly=false,$payment=null,$request_form=null)
    {


        if(empty($group)){
            $select = new Select($this->tableName);
        }
        else{
            $select = new Select('course_course_category');
	        $select->join($this->getPrefix().'courses','course_course_category.course_id='.$this->getPrefix().'courses.id',['id','name','description','venue','start_date','enabled','end_date','type','admin_id','short_description','enrollment_closes','payment_required','fee','enable_chat','enable_discussion','enforce_order','enable_forum']);/*--MARUF--*/
            $select->join($this->getPrefix().'course_categories',$this->getPrefix().'course_course_category.course_category_id='.$this->getPrefix().'course_categories.id',['category_name'=>'name']);
            $select->where([$this->getPrefix().'course_categories.id'=>$group]);
        }

        if(!GLOBAL_ACCESS){
            $select->where([$this->tableName.'.admin_id'=>ADMIN_ID]);
        }
	
	    if ($request_form == 'website') {
		    $select->where([$this->tableName . '.approved_by_super_admin' => 'Accepted']);
	    }

        if(!empty($type)){
            if(is_array($type)){
                $sql= '(';
                $count = 0;
                foreach($type as $value){
                   if(!empty($count)){
                       $sql.= ' OR ';
                   }
                    $sql .= 'type=\''.$value.'\'';
                    $count++;
                }
                $sql .= ')';

                $select->where($sql);
            }else{
                $select->where(['type'=>$type]);
            }

        }




        switch($order){
            case 'asc':
                $select->order('name asc');
                break;
            case 'desc':
                $select->order('name desc');
                break;
            case 'recent':
                $select->order($this->getPrefix().'courses.id desc');
                break;
            case 'date':
                $select->order('start_date desc');
                break;
            case 'priceAsc':
                $select->order('fee asc');
                break;
            case 'priceDesc':
                $select->order('fee desc');
                break;
            default:
                $select->order($this->getPrefix().'courses.id desc');
                break;

        }

        if(isset($filter))
        {
            $filter = addslashes($filter);
            //$select->where("MATCH (".$this->getPrefix()."courses.name,".$this->getPrefix()."courses.description,venue) AGAINST ('$filter' IN NATURAL LANGUAGE MODE)");
            //$select->where("MATCH (name,description,short_description,introduction,venue) AGAINST ('$filter' IN NATURAL LANGUAGE MODE)");
            $select->where("`name` LIKE '%".$filter."%'");

        }

        if($activeOnly){
            $select->where(array($this->getPrefix().'courses.enabled'=>1));
        }

        if(is_numeric($payment)){
            $select->where(array('payment_required'=>$payment));
        }

        if($futureOnly){
          //  $select->where(array('end_date > '.time()));
            $time = Carbon::now()->toDateTimeString();
        //    $select->where("(end_date > {$time} OR end_date=0 )");
            $select->where("( (end_date IS NULL OR end_date=0 OR end_date > '$time') AND (enrollment_closes IS NULL OR enrollment_closes=0 OR enrollment_closes > '$time')  )");

        }

        if($paginated)
        {
            $paginatorAdapter = new DbSelect($select,$this->tableGateway->getAdapter());
            $paginator = new Paginator($paginatorAdapter);
            return $paginator;
        }

        $resultSet = $this->tableGateway->selectWith($select);
	    $resultSet->buffer();
        return $resultSet;
    }
	
	public function getPartnerWiseSessions($paginated=false,$partner_ids=null,$activeOnly=false,$filter=null,$group=null,$order=null,$type=null,$futureOnly=false,$payment=null,$request_form=null)
	{
		if(empty($group)){
			$select = new Select($this->tableName);
		}
		else{
			$select = new Select('course_course_category');
			$select->join($this->getPrefix().'courses','course_course_category.course_id='.$this->getPrefix().'courses.id',['id','name','description','venue','start_date','enabled','end_date','type','admin_id','short_description','enrollment_closes','payment_required','fee','enable_chat','enable_discussion','enforce_order','enable_forum']);/*--MARUF--*/
			$select->join($this->getPrefix().'course_categories',$this->getPrefix().'course_course_category.course_category_id='.$this->getPrefix().'course_categories.id',['category_name'=>'name']);
			$select->where([$this->getPrefix().'course_categories.id'=>$group]);
		}
		
		$select->where([$this->tableName.'.admin_id in ('.$partner_ids.')']);
		
		if ($request_form == 'website') {
			$select->where([$this->tableName . '.approved_by_super_admin' => 'Accepted']);
		}
		
		if(!empty($type)){
			if(is_array($type)){
				$sql= '(';
				$count = 0;
				foreach($type as $value){
					if(!empty($count)){
						$sql.= ' OR ';
					}
					$sql .= 'type=\''.$value.'\'';
					$count++;
				}
				$sql .= ')';
				
				$select->where($sql);
			}else{
				$select->where(['type'=>$type]);
			}
			
		}
		
		
		
		
		switch($order){
			case 'asc':
				$select->order('name asc');
				break;
			case 'desc':
				$select->order('name desc');
				break;
			case 'recent':
				$select->order($this->getPrefix().'courses.id desc');
				break;
			case 'date':
				$select->order('start_date desc');
				break;
			case 'priceAsc':
				$select->order('fee asc');
				break;
			case 'priceDesc':
				$select->order('fee desc');
				break;
			default:
				$select->order($this->getPrefix().'courses.id desc');
				break;
			
		}
		
		if(isset($filter))
		{
			$filter = addslashes($filter);
			//$select->where("MATCH (".$this->getPrefix()."courses.name,".$this->getPrefix()."courses.description,venue) AGAINST ('$filter' IN NATURAL LANGUAGE MODE)");
			//$select->where("MATCH (name,description,short_description,introduction,venue) AGAINST ('$filter' IN NATURAL LANGUAGE MODE)");
			$select->where("`name` LIKE '%".$filter."%'");
			
		}
		
		if($activeOnly){
			$select->where(array($this->getPrefix().'courses.enabled'=>1));
		}
		
		if(is_numeric($payment)){
			$select->where(array('payment_required'=>$payment));
		}
		
		if($futureOnly){
			$time = Carbon::now()->toDateTimeString();
			$select->where("( (end_date IS NULL OR end_date=0 OR end_date > '$time') AND (enrollment_closes IS NULL OR enrollment_closes=0 OR enrollment_closes > '$time')  )");
		}
		
		if($paginated)
		{
			$paginatorAdapter = new DbSelect($select,$this->tableGateway->getAdapter());
			$paginator = new Paginator($paginatorAdapter);
			return $paginator;
		}
		$resultSet = $this->tableGateway->selectWith($select);
		$resultSet->buffer();
		return $resultSet;
	}

    public function getTotalRecords($paginated=false,$id=null,$activeOnly=false,$filter=null,$group=null,$order=null,$type=null,$futureOnly=false,$payment=null)
    {


        if(empty($group)){
            $select = new Select($this->tableName);
        }
        else{
            $select = new Select('course_course_category');
            $select->join($this->getPrefix().'courses','course_course_category.course_id='.$this->getPrefix().'courses.id',['name','description','venue','start_date','enabled','end_date','type']);
            $select->join($this->getPrefix().'course_categories','course_course_category.course_category_id='.$this->getPrefix().'course_categories.id',['category_name'=>'name']);
            $select->where(['course_categories.id'=>$group]);
        }

        if(!GLOBAL_ACCESS){
            $select->where([$this->tableName.'.admin_id'=>ADMIN_ID]);
        }
	
	    /*$select->where([$this->tableName.'.approved_by_super_admin'=>'Accepted']);*/

        if(!empty($type)){
            if(is_array($type)){
                $sql= '(';
                $count = 0;
                foreach($type as $value){
                    if(!empty($count)){
                        $sql.= ' OR ';
                    }
                    $sql .= 'type=\''.$value.'\'';
                    $count++;
                }
                $sql .= ')';

                $select->where($sql);
            }else{
                $select->where(['type'=>$type]);
            }

        }




        switch($order){
            case 'asc':
                $select->order($this->getPrefix().'courses.name asc');
                break;
            case 'desc':
                $select->order($this->getPrefix().'courses.name desc');
                break;
            case 'recent':
                $select->order($this->getPrefix().'courses.id desc');
                break;
            case 'date':
                $select->order('start_date desc');
                break;
            case 'priceAsc':
                $select->order('fee asc');
                break;
            case 'priceDesc':
                $select->order('fee desc');
                break;
            default:
                $select->order($this->getPrefix().'courses.id desc');
                break;

        }

        if(isset($filter))
        {
            $filter = addslashes($filter);
           $select->where("MATCH (name,description,short_description,introduction,venue) AGAINST ('$filter' IN NATURAL LANGUAGE MODE)");
        }

        if($activeOnly){
            $select->where(array($this->getPrefix().'courses.enabled'=>1));
        }

        if(is_numeric($payment)){
            $select->where(array('payment_required'=>$payment));
        }

        if($futureOnly){
        //    $select->where(array('end_date > '.time()));
            $time = Carbon::now()->toDateTimeString();
//            $select->where("(end_date > {$time} OR end_date=0 )");
            $select->where("( (end_date IS NULL OR end_date=0 OR end_date > '$time') AND (enrollment_closes IS NULL OR enrollment_closes=0 OR enrollment_closes > '$time')  )");

        }


        $rowset = $this->tableGateway->selectWith($select);
            return $rowset->count();

    }




    public function getPaginatedCourseRecords($paginated=false,$id=null,$activeOnly=false,$filter=null,$group=null,$order=null,$type=null,$request_form=null)
    {


        if(empty($group)){
            $select = new Select($this->tableName);
        }
        else{

            $select = new Select('course_course_category');
            $select->join($this->getPrefix().'courses',$this->getPrefix().'course_course_category.course_id='.$this->getPrefix().'courses.id',['id','name','description','venue','start_date','enabled','end_date','type','short_description','picture','enrollment_closes','fee','payment_required']);
            $select->join($this->getPrefix().'course_categories',$this->getPrefix().'course_course_category.course_category_id='.$this->getPrefix().'course_categories.id',['category_name'=>'name']);
            $select->where([$this->getPrefix().'course_categories.id'=>$group]);
        }

        if(!GLOBAL_ACCESS){
            $select->where([$this->tableName.'.admin_id'=>ADMIN_ID]);
        }
	
		if ($request_form == 'website') {
			$select->where([$this->tableName . '.approved_by_super_admin' => 'Accepted']);
		}

        if(!empty($type)){
            if(is_array($type)){
                $sql= '(';
                $count = 0;
                foreach($type as $value){
                    if(!empty($count)){
                        $sql.= ' OR ';
                    }
                    $sql .= 'type=\''.$value.'\'';
                    $count++;
                }
                $sql .= ')';

                $select->where($sql);
            }else{
                $select->where(['type'=>$type]);
            }

        }




        switch($order){
            case 'asc':
                $select->order('name asc');
                break;
            case 'desc':
                $select->order('name desc');
                break;
            case 'recent':
                $select->order($this->getPrefix().'courses.id desc');
                break;
            case 'date':
                $select->order('start_date desc');
                break;
            case 'priceAsc':
                $select->order('fee asc');
                break;
            case 'priceDesc':
                $select->order('fee desc');
                break;
            default:
                $select->order($this->getPrefix().'courses.id desc');
                break;

        }

        if(isset($filter))
        {
            $filter = addslashes($filter);
           // $select->where("MATCH (name,description,venue) AGAINST ('$filter' IN NATURAL LANGUAGE MODE)");
            $select->where("MATCH (name,description,short_description,introduction,venue) AGAINST ('$filter' IN NATURAL LANGUAGE MODE)");

        }

        if($activeOnly){
            $select->where(array($this->getPrefix().'courses.enabled'=>1));
        }

       $time = Carbon::now()->toDateString();
        $select->where("( (end_date IS NULL OR end_date =0 OR end_date > '$time') AND (enrollment_closes IS NULL OR enrollment_closes =0 OR enrollment_closes > '$time')  )");


        if($paginated)
        {
            $paginatorAdapter = new DbSelect($select,$this->tableGateway->getAdapter());
            $paginator = new Paginator($paginatorAdapter);
            return $paginator;
        }

        $resultSet = $this->tableGateway->selectWith($select);
        return $resultSet;
    }

    public function getTotalCourseRecords($paginated=false,$id=null,$activeOnly=false,$filter=null,$group=null,$order=null,$type=null)
    {


        if(empty($group)){
            $select = new Select($this->tableName);
        }
        else{
            $select = new Select('course_course_category');
            $select->join($this->getPrefix().'courses','course_course_category.course_id='.$this->getPrefix().'courses.id',['name','description','venue','start_date','enabled','end_date','type','short_description','picture','enrollment_closes']);
            $select->join($this->getPrefix().'course_categories','course_course_category.course_category_id=course_category.course_category_id',['category_name']);
            $select->where(['course_categories.id'=>$group]);
        }

        if(!GLOBAL_ACCESS){
            $select->where([$this->tableName.'.admin_id'=>ADMIN_ID]);
        }
	
	    /*$select->where([$this->tableName.'.approved_by_super_admin'=>'Accepted']);*/

        if(!empty($type)){
            if(is_array($type)){
                $sql= '(';
                $count = 0;
                foreach($type as $value){
                    if(!empty($count)){
                        $sql.= ' OR ';
                    }
                    $sql .= 'type=\''.$value.'\'';
                    $count++;
                }
                $sql .= ')';

                $select->where($sql);
            }else{
                $select->where(['type'=>$type]);
            }

        }




        switch($order){
            case 'asc':
                $select->order('name asc');
                break;
            case 'desc':
                $select->order('name desc');
                break;
            case 'recent':
                $select->order('id desc');
                break;
            case 'date':
                $select->order('start_date desc');
                break;
            case 'priceAsc':
                $select->order('fee asc');
                break;
            case 'priceDesc':
                $select->order('fee desc');
                break;
            default:
                $select->order('id desc');
                break;

        }

        if(isset($filter))
        {
            $filter = addslashes($filter);
         //   $select->where("MATCH (name,description,venue) AGAINST ('$filter' IN NATURAL LANGUAGE MODE)");
            $select->where("MATCH (name,description,short_description,introduction,venue) AGAINST ('$filter' IN NATURAL LANGUAGE MODE)");

        }

        if($activeOnly){
            $select->where(array('enabled'=>1));
        }

        $time = time();
        $select->where("( (end_date IS NULL OR end_date =0 OR end_date > '$time') AND (enrollment_closes IS NULL OR enrollment_closes =0 OR enrollment_closes > '$time')  )");


        $rowset = $this->tableGateway->selectWith($select);
        return $rowset->count();
    }


    public function getValidSessions($paginated=false,$type=null)
    {
        $select = new Select($this->tableName);
        $select->order('start_date desc');


            $select->where(array('enabled'=>1,'end_date > '.time()));
        if(!GLOBAL_ACCESS){
            $select->where([$this->tableName.'.admin_id'=>ADMIN_ID]);
        }
	
	    /*$select->where([$this->tableName.'.approved_by_super_admin'=>'Accepted']);*/
		
        if(!empty($type)){
            if(is_array($type)){
                $sql= '(';
                $count = 0;
                foreach($type as $value){
                    if(!empty($count)){
                        $sql.= ' OR ';
                    }
                    $sql .= 'type=\''.$value.'\'';
                    $count++;
                }
                $sql .= ')';

                $select->where($sql);
            }else{
                $select->where(['type'=>$type]);
            }

        }
        if($paginated)
        {
            $paginatorAdapter = new DbSelect($select,$this->tableGateway->getAdapter());
            $paginator = new Paginator($paginatorAdapter);
            return $paginator;
        }

        $resultSet = $this->tableGateway->selectWith($select);
        return $resultSet;
    }

    public function checkSession($id){
        $rowset = $this->tableGateway->select(array('course_id'=>$id));
        $total = $rowset->count();
        if(empty($total)){
            $data = array(
                'course_id'=>$id,
                'name'=>'Random Session '.time(),
                'start_date'=> mktime(null,null,null,null,null,2006),
                'enabled'=>0
            );
            $this->addRecord($data);
        }


    }


    public function getUpcomingCourses($days=3){
        $upperLimit = strtotime('tomorrow midnight') - 1;

        $timestamp = strtotime("+$days day");

        $timeLimit = mktime(23,59,0,date('n',$timestamp),date('j',$timestamp),date('Y',$timestamp));

        $upperLimit = Carbon::createFromTimestamp($upperLimit)->toDateTimeString();
        $timeLimit = Carbon::createFromTimestamp($timeLimit)->toDateTimeString();

        $upperLimit = "'{$upperLimit}'";
        $timeLimit = "'{$timeLimit}'";

        $select = new Select($this->tableName);
        $select->where(['end_date < '.$timeLimit,'end_date > '.$upperLimit,'type'=>'c'])
            ->order('end_date');
        $rowset = $this->tableGateway->selectWith($select);
        $rowset->buffer();
        return $rowset;

    }

    public function getClosingCourses($days=3){
        $upperLimit = strtotime('tomorrow midnight') - 1;

        $timestamp = strtotime("+$days day");

        $timeLimit = mktime(23,59,0,date('n',$timestamp),date('j',$timestamp),date('Y',$timestamp));

        $upperLimit = Carbon::createFromTimestamp($upperLimit)->toDateTimeString();
        $timeLimit = Carbon::createFromTimestamp($timeLimit)->toDateTimeString();

        $upperLimit = "'{$upperLimit}'";
        $timeLimit = "'{$timeLimit}'";

        $select = new Select($this->tableName);
        $select->where(['end_date > 0','end_date < '.$timeLimit,'end_date > '.$upperLimit,'start_date < '.time()])
            ->order('end_date');
        $rowset = $this->tableGateway->selectWith($select);
        $rowset->buffer();
        return $rowset;

    }

}
