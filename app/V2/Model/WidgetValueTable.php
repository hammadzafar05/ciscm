<?php
/**
 * Created by PhpStorm.
 * User: USER PC
 * Date: 1/21/2017
 * Time: 1:17 PM
 */

namespace App\V2\Model;


use App\Lib\BaseTable;
use Laminas\Db\Sql\Select;

class WidgetValueTable extends BaseTable {

    protected $tableName = 'widget_values';
    //protected $primary = 'widget_value_id';

    public function getWidgets($enabled=null,$visibility=null){
        $select = new Select($this->tableName);
        $select->order('sort_order asc');
        $select->join($this->getPrefix().'widgets', $this->tableName.'.widget_id='.$this->getPrefix().'widgets.id',['name','code','repeat']);
        if($enabled){
            $select->where(['enabled'=>$enabled]);
        }

        if($visibility){
            $select->where("visibility='{$visibility}' OR visibility='b'");
        }

        $rowset = $this->tableGateway->selectWith($select);
        return $rowset;
    }

    public function saveOptions($merchantWidgetId,$data)
    {
        $dbData = array(
            'enabled'=>$data['enabled'],
            'sort_order'=>$data['sort_order'],
            'value'=>serialize($data),
            'visibility'=>'b'
        );


        $this->tableGateway->update($dbData,array('id'=>$merchantWidgetId));

        return $merchantWidgetId;



    }

}
