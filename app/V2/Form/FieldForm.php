<?php
/**
 * Created by PhpStorm.
 * User: USER PC
 * Date: 1/12/2017
 * Time: 4:41 PM
 */

namespace App\V2\Form;


use App\Lib\BaseForm;

class FieldForm extends BaseForm {

    public function __construct($name = null)
    {
        // we want to ignore the name passed
        parent::__construct('field');
        $this->setAttribute('method', 'post');


        $this->createText('name','Label',true,null,null,__lang('e.g. Home Address'));
        $this->createText('sort_order','sort-order-(optional)',false,'form-control number',null,__lang('Digits only'));
        $types = [
            'text'=>__lang('Text Field'),
            'textarea'=>__lang('Text Area'),
            'select'=>__lang('Dropdown'),
            'radio'=>__lang('Radio Button'),
            'checkbox'=>__lang('Checkbox'),
            'file'=>__lang('File/Image')
        ];
        $this->createSelect('type','Input Type',$types,true,true);

        $this->createTextArea('options','Options',false,null,__lang('enter-new-line'));
        $this->createSelect('required','Mandatory?',[1=>__lang('Yes'),0=>__lang('No')],true);
        $this->createText('placeholder','Field Hint');
        $this->createSelect('enabled','student-editable?',['1'=>__lang('Yes'),'0'=>__lang('No')],true,false);


    }

}
