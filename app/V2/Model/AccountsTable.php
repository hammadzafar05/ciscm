<?php

namespace App\V2\Model;

use App\Admin;
use App\Lib\BaseTable;
use App\User;
use Laminas\Db\Sql\Select;
use Laminas\Paginator\Adapter\DbSelect;
use Laminas\Paginator\Paginator;

class AccountsTable extends BaseTable {

	protected $tableName = 'users';
	//protected $primary = 'admin_id';

	public function getAccountWithEmail($email){
        return $this->tableGateway->select(['email'=>$email])->current();
    }

    public function getAccountsForNotification(){
	    return Admin::where('notify',1)->limit(1000)->get();
    }

    public function getRecordsSorted()
    {
        $select = new Select($this->getPrefix().'admins');
        $select->order($this->getPrefix().'users.name asc');
        $select->join($this->getPrefix().'users',$this->getPrefix().'admins.user_id='.$this->getPrefix().'users.id',['user_name'=>'name','email']);
        $select->join($this->getPrefix().'admin_roles',$this->getPrefix().'admins'.'.admin_role_id='.$this->getPrefix().'admin_roles.id',['admin_role_name'=>'name']);
        return $this->tableGateway->selectWith($select);
    }

    public function getPaginatedRecords($paginated=false,$id=null)
    {
        $select = new Select($this->tableName);
        $select->order($this->primary.' desc');
        $select->join($this->getPrefix().'admin_roles',$this->tableName.'.admin_role_id='.$this->getPrefix().'admin_roles.id',['role']);
        if($paginated)
        {
            $paginatorAdapter = new DbSelect($select,$this->tableGateway->getAdapter());
            $paginator = new Paginator($paginatorAdapter);
            return $paginator;
        }

        $resultSet = $this->tableGateway->selectWith($select);
        return $resultSet;
    }

    public function emailExists($email)
    {
        $rowset = $this->tableGateway->select(array('email'=>$email));
        $total = $rowset->count();
        if (empty($total)) {
            return false;
        }
        else {
            return true;
        }
    }


    public function hasPermission($id,$permission){
	    return User::find($id)->can('access',$permission);

   /*     $user = $this->getRecord($id);
        $rolePermissiontable = new RolePermissionTable($this->getServiceLocator());
        $permissionTable = new PermissionTable($this->getServiceLocator());
        $permissionRow = $permissionTable->getRecordForPermission($permission);
        if($rolePermissiontable->roleHasPermission($user->role_id,$permissionRow->id)){
        return true;
        }
        else{
            return false;
        }*/
    }


}

?>
