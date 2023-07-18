<?php

namespace App\V2\Form;

use App\V2\Model\LessonTable;
use App\V2\Model\PermissionTable;
use App\Lib\BaseForm;
use Laminas\Form\Form;

class RoleForm extends BaseForm {
    public function __construct($name = null,$serviceLocator)
    {

        // we want to ignore the name passed
        parent::__construct('user');
        $this->setAttribute('method', 'post');

        $this->createText('role','Role',true);

        $permissionTable = new PermissionTable($serviceLocator);

        $rowset = $permissionTable->getRecords();
        foreach($rowset as $row){
            $this->createCheckbox('permission_'.strtolower(str_replace(' ','_',$row->group)).'_'.$row->permission_id,ucwords(str_replace('_',' ',trim($row->permission))),$row->permission_id);
            $this->get('permission_'.strtolower(str_replace(' ','_',$row->group)).'_'.$row->permission_id)->setAttribute('class','cbox');
        }

    }

}

?>
