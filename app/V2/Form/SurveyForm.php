<?php
namespace App\V2\Form;

use App\Lib\BaseForm;

class SurveyForm extends BaseForm
{

    public function __construct($name = null,$serviceLocator)
    {
        $this->setServiceLocator($serviceLocator);
        // we want to ignore the name passed
        parent::__construct('user');
        $this->setAttribute('method', 'post');

        $this->createText('name','Survey Name',true);
        $this->createTextArea('description','Instructions');
        $this->createSelect('enabled','Status',[1=>__lang('Enabled'),0=>__lang('Disabled')],true,false);
        $this->get('description')->setAttribute('id','description');
        $this->createSelect('private','Private',[0=>__lang('no'),1=>__lang('yes')]);


    }


}
