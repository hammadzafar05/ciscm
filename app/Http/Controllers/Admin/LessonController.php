<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Lesson;
use App\Lib\BaseForm;
use App\Lib\HelperTrait;
use App\V2\Form\LessonFilter;
use App\V2\Form\LessonForm;
use App\V2\Model\LectureTable;
use App\V2\Model\LessonFileTable;
use App\V2\Model\LessonGroupTable;
use App\V2\Model\LessonTable;
use App\V2\Model\LessonToLessonGroupTable;
use App\V2\Model\SessionLessonTable;
use Illuminate\Http\Request;
use Laminas\Form\Element\Select;
use Laminas\Form\Element\Text;
use Laminas\InputFilter\InputFilter;

class LessonController extends Controller
{
    use HelperTrait;

    public function index(Request $request) {
        // TODO Auto-generated NewssController::index(Request $request) default action
        $table = new LessonTable();

        $filter = request()->get('filter');


        if (empty($filter)) {
            $filter=null;
        }

        $group = request()->get('group', null);
        if (empty($group)) {
            $group=null;
        }

        $sort = request()->get('sort', null);
        if (empty($sort)) {
            $sort=null;
        }


        $text = new Text('filter');
        $text->setAttribute('class','form-control');
        $text->setAttribute('placeholder',__lang('Filter by class name'));
        $text->setValue($filter);

        $select = new Select('group');
        $select->setAttribute('class','form-control select2');
        $select->setEmptyOption('--'.__lang('Select Class Group').'--');

        $sortSelect = new Select('sort');
        $sortSelect->setAttribute('class','form-control');
        $sortSelect->setValueOptions([
            'recent'=>__lang('Recently Added'),
            'asc'=>__lang('Alphabetical (Ascending)'),
            'desc'=>__lang('Alphabetical (Descending)'),
            'sortOrder'=>__lang('Sort Order'),
            'session'=>__lang('Physical Location'),
            'online'=>__lang('Online')
        ]);
        $sortSelect->setEmptyOption('--'.__lang('Sort').'--');
        $sortSelect->setValue($sort);

        $groupTable = new LessonGroupTable();
        $groupRowset = $groupTable->getLimitedRecords(1000);
        $options =[];

        foreach($groupRowset as $row){
            $options[$row->id] = $row->name;
        }
        $select->setValueOptions($options);
        $select->setValue($group);



        $paginator = $table->getLessons(true,$filter,$group,$sort);


       // $paginator->setCurrentPageNumber((int)request()->get('page', 1));
      //  $paginator->setItemCountPerPage(30);
        return viewModel('admin',__CLASS__,__FUNCTION__,array(
            'paginator'=>$paginator,
            'pageTitle'=>__lang('Classes'),
            'lectureTable'=> new LectureTable(),
            'filter'=>$filter,
            'group'=>$group,
            'text'=>$text,
            'select'=>$select,
            'sortSelect'=>$sortSelect,
            'sort'=>$sort
        ));


    }

    public function add(Request $request)
    {
        $output = array();
        $lessonTable = new LessonTable();
        $lessonGroupTable = new LessonToLessonGroupTable();
        $form = new LessonForm(null,$this->getServiceLocator());
        $filter = new LessonFilter();


        if (request()->isMethod('post')) {

            $form->setInputFilter($filter);
            $data = request()->all();

            $form->setData($data);
            if ($form->isValid()) {

                $array = $form->getData();
                unset($array['lesson_group_id[]']);
                $array[$lessonTable->getPrimary()]=0;
                $id = $lessonTable->saveRecord($array);
                $lesson = Lesson::find($id);
                //now put the records in
                if(isset($data['lesson_group_id'])){
                    $lesson->lessonGroups()->attach($data['lesson_group_id']);

                }


                $output['flash_message'] = __lang('Record Added!');
                $form = new LessonForm(null,$this->getServiceLocator());
                session()->flash('flash_message',__lang('Class Added'));

                //now check if session id is present
                if(isset($_GET['sessionId'])){
                    $sessionId = $_GET['sessionId'];
                    $sessionLessonTable = new SessionLessonTable();

                     //$last = SessionLesson::where('session_id',$sessionId)->orderBy('sort_order','desc')->first();
                    $last = $sessionLessonTable->getLastSortOrder($sessionId);
                    if($last){
                        $sortOrder = $last->sort_order + 1;
                    }
                    else{
                        $sortOrder = 1;
                    }

                    $sessionLessonTable->addRecord([
                        'course_id'=>$sessionId,
                        'lesson_id'=>$id,
                        'sort_order'=>$sortOrder
                    ]);
                    return back();
                }



                if($array['type']=='c'){
                    return adminRedirect(array('controller'=>'lecture','action'=>'index','id'=>$id));

                }
                else{
                    return adminRedirect(array('controller'=>'lesson','action'=>'index'));

                }

            }
            else{

                if(isset($data['lesson_group_id'])){
                    foreach($data['lesson_group_id'] as $value){
                        $array['lesson_group_id[]'][] = $value[0];
                    }
                }

                $form->setData($data);

                $output['flash_message'] = $this->getFormErrors($form);
                if ($data['picture']) {
                    $output['display_image']= resizeImage($data['picture'], 100, 100,$this->getBaseUrl());
                }
                else{
                    $output['display_image']= resizeImage('img/no_image.jpg', 100, 100,$this->getBaseUrl());

                }
            }

        }
        else{
            $output['display_image']= resizeImage('img/no_image.jpg', 100, 100,$this->getBaseUrl());
        }
        $output['no_image']= resizeImage('img/no_image.jpg', 100, 100,$this->getBaseUrl());

        $output['form'] = $form;
        $output['pageTitle']= __lang('Add Class');
        $output['action']='add';
        $output['id']=null;
        return viewModel('admin',__CLASS__,__FUNCTION__,$output);
    }

    public function edit(Request $request,$id){
        $output = array();
        $lessonTable = new LessonTable();
        $lessonGroupTable = new LessonToLessonGroupTable();
        $form = new LessonForm(null,$this->getServiceLocator());
        $filter = new LessonFilter();
        $lesson = Lesson::findOrFail($id);

        $row = $lessonTable->getRecord($id);
        if (request()->isMethod('post')) {

            $form->setInputFilter($filter);
            $data = request()->all();

            $form->setData($data);
            if ($form->isValid()) {

                //add groups
                $array = $form->getData();
                if(isset($data['lesson_group_id'])){
                    $lesson->lessonGroups()->sync($data['lesson_group_id']);

                }

                /*             $lessonGroupTable->clearLessonRecords($id);

                             //now put the records in
                             foreach($data['lesson_group_id'] as $value){
                                 $groupId = $value[0];
                                 $lessonGroupTable->addRecord([
                                     'lesson_id'=>$id,
                                     'lesson_group_id'=>$groupId
                                 ]);
                             }*/


                $array[$lessonTable->getPrimary()]=$id;
                unset($array['lesson_group_id[]']);
                $lessonTable->saveRecord($array);
                //    flashMessage(__lang('Changes Saved!'));
                $output['flash_message'] = __lang('Changes Saved!');
                $row = $lessonTable->getRecord($id);
                session()->flash('flash_message',__lang('Changes Saved!'));
                return adminRedirect(array('controller'=>'lesson','action'=>'index'));

            }
            else{
                foreach($data['lesson_group_id'] as $value){
                    $array['lesson_group_id[]'][] = $value[0];
                }
                $form->setData($array);
                $output['flash_message'] = __lang('save-failed-msg');
            }

        }
        else {

            $data = getObjectProperties($row);

            //get group records
            $groups = [];
            $rowset = $lessonGroupTable->getLessonRecords($id);
            foreach($rowset as $groupRow){
                $data['lesson_group_id[]'][] = $groupRow->lesson_group_id;
            }


            $form->setData($data);



        }

        if ($row->picture && file_exists(DIR_MER_IMAGE . $row->picture) && is_file(DIR_MER_IMAGE . $row->picture)) {
            $output['display_image'] = resizeImage($row->picture, 100, 100,$this->getBaseUrl());
        } else {
            $output['display_image'] = resizeImage('img/no_image.jpg', 100, 100,$this->getBaseUrl());
        }


        $output['no_image']= $this->getBaseUrl().'/'.resizeImage('img/no_image.jpg', 100, 100,$this->getBaseUrl());
        $output['form'] = $form;
        $output['id'] = $id;
        $output['pageTitle']= __lang('Edit Class');
        $output['row']= $row;
        $output['action']='edit';

        return view('admin.lesson.add',$output);

    }



    public function delete(Request $request,$id)
    {
        $table = new LessonTable();
        try{
            $table->deleteRecord($id);
            flashMessage(__lang('Record deleted'));
        }
        catch(\Exception $ex){
            $this->deleteError();
        }
        return back();

        // return adminRedirect(array('controller'=>'lesson','action'=>'index'));
    }

    /**
     * Show groups
     */
    public function groups(Request $request){

        $table = new LessonGroupTable();

        $paginator = $table->getPaginatedRecords(true);

        $paginator->setCurrentPageNumber((int)request()->get('page', 1));
        $paginator->setItemCountPerPage(30);
        return viewModel('admin',__CLASS__,__FUNCTION__,array(
            'paginator'=>$paginator,
            'pageTitle'=>__lang('Class Groups'),
        ));


    }

    private function getGroupForm(){
        $form = new BaseForm();
        $form->createText('name','Group Name',true);
        $form->createTextArea('description','Description');
        $form->createText('sort_order','Sort Order',false,'form-control number',null,__lang('Digits Only'));
        return $form;
    }

    private function getGroupFilter(){
        $filter = new InputFilter();
        $filter->add(array(
            'name'=>'name',
            'required'=>true,
            'validators'=>array(
                array(
                    'name'=>'NotEmpty'
                )
            )
        ));

        $filter->add(array(
            'name'=>'description',
            'required'=>false,

        ));


        $filter->add(array(
            'name'=>'sort_order',
            'required'=>false,
            'validators'=>array(
                array(
                    'name'=>'Digits'
                )
            )
        ));


        return $filter;
    }

    /**
     * Add a new group
     */
    public function addgroup(Request $request){

        $output = array();
        $table = new LessonGroupTable();
        $form = $this->getGroupForm();
        $filter = $this->getGroupFilter();


        if (request()->isMethod('post')) {

            $form->setInputFilter($filter);
            $data = request()->all();
            $form->setData($data);
            if ($form->isValid()) {

                $array = $form->getData();


                $array[$table->getPrimary()]=0;
                $table->saveRecord($array);
                //    flashMessage(__lang('Changes Saved!'));
                $output['flash_message'] = __lang('Record Added!');
                session()->flash('flash_message',__lang('group-created'));
                return adminRedirect(array('controller'=>'lesson','action'=>'groups'));

            }
            else{
                $output['flash_message'] = __lang('save-failed-msg');
            }

        }


        $output['form'] = $form;
        $output['pageTitle']= __lang('Add Class Group');
        $output['action']='addgroup';
        $output['id']=null;
        return viewModel('admin',__CLASS__,__FUNCTION__,$output);


    }

    public function editgroup(Request $request,$id){
        $output = array();
        $table = new LessonGroupTable();

        $filter = $this->getGroupFilter();
        $form = $this->getGroupForm();

        $row = $table->getRecord($id);
        if (request()->isMethod('post')) {

            $form->setInputFilter($filter);
            $data = request()->all();
            $form->setData($data);
            if ($form->isValid()) {



                $array = $form->getData();

                $array[$table->getPrimary()]=$id;
                $table->saveRecord($array);
                //    flashMessage(__lang('Changes Saved!'));
                session()->flash('flash_message',__lang('Changes Saved!'));

                return adminRedirect(array('controller'=>'lesson','action'=>'groups'));

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
        $output['pageTitle']= __lang('Edit Class Group');
        $output['row']= $row;
        $output['action']='editgroup';

        $viewModel = viewModel('admin',__CLASS__,'addgroup',$output);
        return $viewModel ;
    }

    public function deletegroup(Request $request,$id){
        $table = new LessonGroupTable();

        try{
            $table->deleteRecord($id);
            flashMessage(__lang('Record deleted'));
        }
        catch(\Exception $ex){
            $this->deleteError();
        }

        return adminRedirect(array('controller'=>'lesson','action'=>'groups'));

    }

    public function files(Request $request,$id){

        $table = new LessonFileTable();
        $lessonTable = new LessonTable();
        $lessonRow = $lessonTable->getRecord($id);
        $rowset = $table->getDownloadRecords($id);

        $viewModel = viewModel('admin',__CLASS__,__FUNCTION__,['rowset'=>$rowset,
            'pageTitle'=>__lang('Class Downloads').': '.$lessonRow->name,
            'id'=>$id
        ]);

        return $viewModel;
    }

    public function addfile(Request $request,$id){
        $path = $request->get('path');

        $downloadFileTable = new LessonFileTable();
        $path = str_ireplace('usermedia/','',$path);
        if(!$downloadFileTable->fileExists($id,$path)){
            $downloadFileTable->addRecord([
                'lesson_id'=>$id,
                'path'=>$path,
                'enabled'=>1
            ]);
        }
        $filesViewModel = app(LessonController::class)->files($request,$id);
        return $filesViewModel;

    }

    public function removefile(Request $request,$id){

        $downloadFileTable = new LessonFileTable();
        $row = $downloadFileTable->getRecord($id);
        $lessonId = $row->lesson_id;

        $downloadFileTable->deleteRecord($id);

        $filesViewModel = app(LessonController::class)->files($request,$lessonId);
        return $filesViewModel;
    }


    public function download(Request $request,$id){
        set_time_limit(86400);
        $table = new LessonFileTable();
        $row = $table->getRecord($id);
        $path = 'usermedia/'.$row->path;



        header('Content-type: '.getFileMimeType($path));

// It will be called downloaded.pdf
        header('Content-Disposition: attachment; filename="'.basename($path).'"');

// The PDF source is in original.pdf
        readfile($path);
        exit();
    }


    public function duplicate(Request $request,$id){

        $oldLesson= Lesson::find($id);

        $lesson= $oldLesson->replicate();
        $lesson->save();

        //get all lectures
        foreach($oldLesson->lectures as $oldLecture){
            $data = $oldLecture->toArray();
            unset($data['id']);
            $lecture= $lesson->lectures()->create($data);
            //now save lecture pages
            foreach($oldLecture->lecturePages as $oldLecturePage){
                $data = $oldLecturePage->toArray();
                unset($data['id']);
                $lecture->lecturePages()->create($data);
            }

            foreach($oldLecture->lectureFiles as $oldLectureFile){
                $data = $oldLectureFile->toArray();
                unset($data['id']);
                $lecture->lectureFiles()->create($data);
            }

        }

        foreach ($oldLesson->lessonGroups as $group){
            $lesson->lessonGroups->attach($group->id);
        }


        session()->flash('flash_message',__lang('record-duplicated'));
        return back();


    }


}
