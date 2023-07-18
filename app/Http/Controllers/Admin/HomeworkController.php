<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Lib\HelperTrait;
use App\V2\Form\HomeworkFilter;
use App\V2\Form\HomeworkForm;
use App\V2\Model\HomeworkTable;
use Illuminate\Http\Request;

class HomeworkController extends Controller
{
    use HelperTrait;
    public function index(Request $request) {
        // TODO Auto-generated ArticlesController::index(Request $request) default action
        $table = new HomeworkTable();

        $paginator = $table->getPaginatedRecords(true);

        $paginator->setCurrentPageNumber((int)request()->get('page', 1));
        $paginator->setItemCountPerPage(30);
        return viewModel('admin',__CLASS__,__FUNCTION__,array(
            'paginator'=>$paginator,
            'pageTitle'=>__lang('Revision Notes'),
        ));


    }

    public function add(Request $request)
    {

        $output = array();
        $articlesTable = new HomeworkTable();
        $form = new HomeworkForm(null,$this->getServiceLocator());

        $filter = new HomeworkFilter();

        if (request()->isMethod('post')) {

            $form->setInputFilter($filter);
            $data = request()->all();
            $data['lesson_id'] = str_ireplace('string:','',$data['lesson_id']);


            $form->setData($data);
            if ($form->isValid()) {

                $array = $form->getData();
                $array['lesson_id'] = intval($data['lesson_id']);

                $array[$articlesTable->getPrimary()]=0;
                $articlesTable->saveRecord($array);
                //    flashMessage(__lang('Changes Saved!'));
                $output['flash_message'] = __lang('Record Added!');
                $form = new HomeworkForm(null,$this->getServiceLocator());

                if(!empty($data['notify'])){
                    $subject = __lang('New revision note');
                    $message = __lang('revision-note-mail',['title'=>$data['title'],'description'=>$data['description']]);
                    $sms = __lang('revision-note-sms',['title',$data['title']]);
                    $this->notifySessionStudents($data['course_id'],$subject,$message,true,$sms);
                }

                return redirect()->route('admin.homework.index')->with('flash_message',$output['flash_message']);
            }
            else{
                $output['flash_message'] = __lang('save-failed-msg');
            }

        }



        $output['form'] = $form;
        $output['pageTitle']= __lang('Add Note');
        $output['action']='add';
        $output['id']=null;
        return viewModel('admin',__CLASS__,__FUNCTION__,$output);
    }

    public function edit(Request $request,$id){
        $output = array();
        $articleTable = new HomeworkTable();
        $form = new HomeworkForm(null,$this->getServiceLocator());
        $filter = new HomeworkFilter();

        $row = $articleTable->getRecord($id);
        $oldName = $row->title;
        if (request()->isMethod('post')) {

            $form->setInputFilter($filter);
            $data = request()->all();
            $data['lesson_id'] = str_ireplace('string:','',$data['lesson_id']);


            $form->setData($data);
            if ($form->isValid()) {



                $array = $form->getData();
                $array['lesson_id']=$data['lesson_id'];
                $array[$articleTable->getPrimary()]=$id;
                $articleTable->saveRecord($array);
                //    flashMessage(__lang('Changes Saved!'));
                $output['flash_message'] = __lang('Changes Saved!');

                if(!empty($data['notify'])){
                    $subject = __lang('Updated revision note');
                    $message= __lang('revision-note-updated-mail',['name'=>$oldName]);
                    $this->notifySessionStudents($data['course_id'],$subject,$message);
                }
                return redirect()->route('admin.homework.index')->with('flash_message',$output['flash_message']);
            }
            else{
                $output['flash_message'] = __lang('save-failed-msg');
            }

        }
        else {

            $data = getObjectProperties($row);
            $form->setData($data);

        }

        $output['form'] = $form;
        $output['id'] = $id;
        $output['pageTitle']= __lang('Edit Note');
        $output['row']= $row;
        $output['action']='edit';

        $viewModel = viewModel('admin',__CLASS__,'add',$output);
        return $viewModel ;

    }



    public function delete(Request $request,$id)
    {
        $table = new HomeworkTable();
        $table->deleteRecord($id);
        flashMessage(__lang('Record deleted'));
        return adminRedirect(array('controller'=>'homework','action'=>'index'));
    }
}
