<?php
/**
 * Created by PhpStorm.
 * User: USER PC
 * Date: 1/27/2017
 * Time: 10:42 AM
 */

namespace App\V2\Model;


use App\Lib\BaseTable;
use Laminas\Db\Sql\Select;

class RolePermissionTable extends BaseTable {

    protected $tableName = 'admin_role_permission';
    //protected $primary = 'role_permission_id';

    public function getPermissionsForRole($id)
    {
        $select = new Select($this->tableName);
        $select->where(['admin_role_id'=>$id])
                ->join($this->getPrefix().'permissions',$this->tableName.'.permission_id=permission.permission_id')
                ->join($this->getPrefix().'permission_groups','permission.permission_group_id=permission_group.permission_group_id');


        return $this->tableGateway->selectWith($select);
    }

    public function deletePermissionsForRole($id){
        return $this->tableGateway->delete(['admin_role_id'=>$id]);
    }

    public function roleHasPermission($roleId,$permissionId){
        $total = $this->tableGateway->select(['admin_role_id'=>$roleId,'permission_id'=>$permissionId])->count();
        if(empty($total))
        {
            return false;
        }
        else{
            return true;
        }
    }

    public function roleHasPermissionName($roleId,$permission){

    }


}
