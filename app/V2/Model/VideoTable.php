<?php
/**
 * Created by PhpStorm.
 * User: USER PC
 * Date: 9/15/2018
 * Time: 10:55 PM
 */

namespace App\V2\Model;


use Application\Entity\Video;
use App\Lib\BaseTable;
use Laminas\Db\Sql\Select;
use Laminas\Paginator\Adapter\DbSelect;
use Laminas\Paginator\Paginator;

class VideoTable extends BaseTable
{

    protected $tableName = 'videos';
    //protected $primary = 'video_id';



    public function getVideos($paginated=false,$filter=null,$order=null)
    {

            $select = new Select($this->tableName);


        if(!GLOBAL_ACCESS){
            $select->where(['admin_id'=>ADMIN_ID]);
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
            default:
                $select->order('id desc');
                break;

        }


        if(isset($filter))
        {
            $filter = addslashes($filter);

            $select->where("MATCH (name,description) AGAINST ('$filter' IN NATURAL LANGUAGE MODE)");

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


}
