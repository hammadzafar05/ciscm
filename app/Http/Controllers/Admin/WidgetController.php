<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Lib\HelperTrait;
use App\V2\Model\SessionTable;
use App\V2\Model\WidgetTable;
use App\V2\Model\WidgetValueTable;
use Illuminate\Http\Request;
use Laminas\Form\Element\Number;
use Laminas\Form\Element\Select;
require_once '../app/Lib/phpQuery/phpQuery.php';
class WidgetController extends Controller
{
    use HelperTrait;
    private $data = [];

    /**
     * The default action - show the home page
     */
    public function index(Request $request) {

        $this->data['pageTitle'] = __lang('Homepage Widgets');
        // TODO Auto-generated WidgetController::index(Request $request) default action
        $widgetsTable = new WidgetTable();
        $widgetValueTable = new WidgetValueTable();

        //get all all avialable widgets
        $widgets = $widgetsTable->getRecords();
        $this->data['widgets'] = $widgets;
        $editors = [];
        $html = [];

        //create the elements for creating a new widget
        $createSelect = new Select('widget_id');
        $options = array(''=>'');
        foreach ($widgets as $row){
            $options[$row->id]=__lang($row->name);
        }
        $createSelect->setAttribute('class', 'form-control');
        $createSelect->setEmptyOption('');
        $createSelect->setAttribute('required', 'required');
        $createSelect->setValueOptions($options);
        $this->data['createSelect']=$createSelect;
        //get the merchant's widgets
        $merchantWidgets = $widgetValueTable->getWidgets();
        $this->data['merchantWidgets'] = $merchantWidgets;
        $sessionTable = new SessionTable();
        $sessions = $sessionTable->getLimitedRecords(1000);
        $sessions->buffer();
        //create category list select
        $sessionSelect = '<select name="session[num]" class="form-control select2"  ><option></option>';


        foreach ($sessions as $row){
            $type = sessionType($row->type);
            $sessionSelect.= "
<option value=\"$row->id\">$row->name ($type)</option>";
        }

        $sessionSelect.= '
					</select>';


        foreach ($merchantWidgets as $row)
        {
            $form = view('admin.widget.forms.'.$row->code)->toHtml();

            $newForm = str_replace('[sessionselect]', $sessionSelect, $form);



            $repeat = $row->repeat;

            if (!empty($repeat)) {
                $form='';
                for ($i=1;$i<=$repeat;$i++)
                {
                    $append = str_replace('[num]', $i, $newForm);
                    $form .= $append;
                }


            }

            $noImage = $this->getBaseUrl().'/img/no_image.jpg';
            $form = str_replace('[base]', $this->getBaseUrl(), $form);
            $form = str_replace('[no_image]', $noImage, $form);


            $select = new Select('enabled_'.$row->id);
            $select->setValueOptions(array(
                '1'=>__lang('Enabled'),
                '0'=>__lang('Disabled')
            ));
            $select->setAttribute('class', 'form-control');
            $select->setAttribute('name', 'enabled');

            $sortOrder = new Number('sortOrder_'.$row->id);
            $sortOrder->setAttribute('class', 'form-control');
            $sortOrder->setAttribute('name', 'sort_order');
            $sortOrder->setAttribute('placeholder', 'Sort Order');

            $select->setValue($row->enabled);
            $sortOrder->setValue($row->sort_order);

            $visibilitySelect = new Select('visibility');
            $visibilitySelect->setAttribute('class','form-control');
            $visibilitySelect->setAttribute('name','visibility');
            $visibilitySelect->setAttribute('placeholder','Visibility');
            $visibilitySelect->setValueOptions([
                'w'=>__lang('Website'),
                'm'=>__lang('Mobile App'),
                'b'=>__lang('website-app')
            ]);
            $visibilitySelect->setValue($row->visibility);

            if (!empty($row->value)) {

                $valueArray = unserialize($row->value);

                $object = \phpQuery::newDocumentHTML($form);

                foreach ($valueArray as $key=>$value)
                {
                    $object->find("[name='$key']")->val($value);

                    if ($object->find("[data-name='$key']")) {
                        if (!empty($value) && !is_array($value)) {

                            $object->find("[data-name='$key']")->attr('src',resizeImage($value, 100, 100,$this->getBaseUrl()));
                        }
                        else
                        {
                            $object->find("[data-name='$key']")->attr('src',$noImage);
                        }

                    }



                }

                $form = $object->html();





            }

            //modify textareas with editor classes
            if (preg_match('#rte#', $form)) {

                $object = \phpQuery::newDocumentHTML($form);
                $count = 1;
                foreach ($object->find('textarea.rte') as $ob)
                {
                    $editor = pq($ob);

                    $editor->attr('id',$row->code.'_editor'.$count);
                    $editors[] = $row->code.'_editor'.$count;
                    $count++;
                }


                $form = $object->html();
            }



            $html[$row->id] = array(
                'form'=>$form,
                'enabled'=>$select,
                'sortOrder'=>$sortOrder,
                'description'=>__lang($row->name.'-desc'),
                'code'=>$row->code,
                'name'=>$row->name,
                'visibility'=>$visibilitySelect
            );



        }

        $this->data['editors'] = $editors;

        $this->data['form'] = $html;

        return viewModel('admin',__CLASS__,__FUNCTION__,$this->data);
    }


    public function create(Request $request)
    {
        if(request()->isMethod('post'))
        {
            $data = $request->all();
            $merchantMobileWidgetTable = new WidgetValueTable();
            $data['enabled']=1;
            unset($data['_token']);
            $merchantMobileWidgetTable->addRecord($data);

            session()->flash('flash_message',__lang('Widget created!'));
        }
        return adminRedirect(array('controller'=>'widget','action'=>'index'));
    }

    public function process(Request $request,$id)
    {

        $merchantTemplateOptionsTable = new WidgetValueTable();
        $data = $_POST;


        $optionId = $id;
        $status = false;
        $message = __lang('Submission Failed');
        if($merchantTemplateOptionsTable->saveOptions($optionId, $data))
        {
            $status = true;
            $message = __lang('Changes Saved!');
        }

        exit(json_encode(array('status'=>$status,'message'=>$message)));


    }

    public function delete(Request $request,$id){
        $merchantTemplateOptionsTable = new WidgetValueTable();
        $optionId = $id;
        $merchantTemplateOptionsTable->deleteRecord($optionId);
        return adminRedirect(array('controller'=>'widget','action'=>'index'));

    }
}
