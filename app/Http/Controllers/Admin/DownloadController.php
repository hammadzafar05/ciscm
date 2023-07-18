<?php

namespace App\Http\Controllers\Admin;

use App\Download;
use App\Http\Controllers\Controller;
use App\Lib\HelperTrait;
use App\V2\Form\DownloadFilter;
use App\V2\Form\DownloadForm;
use App\V2\Model\DownloadFileTable;
use App\V2\Model\DownloadSessionTable;
use App\V2\Model\DownloadTable;
use App\V2\Model\SessionInstructorTable;
use Illuminate\Http\Request;

class DownloadController extends Controller
{
    use HelperTrait;
    public function index(Request $request){
        $table = new DownloadTable();
        $downloadFileTable = new DownloadFileTable();

        $paginator = $table->getPaginatedRecords(true);

        $paginator->setCurrentPageNumber((int)request()->get('page', 1));
        $paginator->setItemCountPerPage(30);
        return viewModel('admin',__CLASS__,__FUNCTION__,array(
            'paginator'=>$paginator,
            'pageTitle'=>__lang('Downloads'),
            'downloadTable'=>$table,
            'fileTable'=>$downloadFileTable
        ));
    }

    public function add(Request $request)
    {
        $output = array();
        $downloadTable = new DownloadTable();
        $form = new DownloadForm(null,$this->getServiceLocator());
        $filter = new DownloadFilter();

        if (request()->isMethod('post')) {

            $form->setInputFilter($filter);
            $data = request()->all();
            $form->setData($data);
            if ($form->isValid()) {

                $array = $form->getData();
                $array[$downloadTable->getPrimary()]=0;
                $id= $downloadTable->saveRecord($array);
                flashMessage(__lang('download-created!'));

                return adminRedirect(['controller'=>'download','action'=>'edit','id'=>$id]);


            }
            else{
                $output['flash_message'] = __lang('save-failed-msg');

            }

        }

        $output['form'] = $form;
        $output['pageTitle']= __lang('Add Download');
        $output['action']='add';
        $output['id']=null;
        return viewModel('admin',__CLASS__,__FUNCTION__,$output);
    }


    public function edit(Request $request,$id){
        $output = array();
        $table = new DownloadTable();

        $filter = new DownloadFilter();
        $form = new DownloadForm(null,$this->getServiceLocator());

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
                $output['flash_message'] = __lang('Changes Saved!');
                flashMessage($output['flash_message']);
                return redirect()->route('admin.download.index');

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
        $output['pageTitle']= __lang('Edit Download');
        $output['row']= $row;
        $output['action']='edit';



     /*   $filesViewModel = $this->forward()->dispatch('Admin\Controller\Download',['action'=>'files','id'=>$id]);
        $viewRender = $this->getServiceLocator()->get('ViewRenderer');*/
        $html = app(DownloadController::class)->files($request,$id)->toHtml();
        $output['files'] = $html;

       /* $sessionsViewModel = $this->forward()->dispatch('Admin\Controller\Download',['action'=>'sessions','id'=>$id]);
        $viewRender = $this->getServiceLocator()->get('ViewRenderer');*/
        $html = app(DownloadController::class)->sessions($request,$id)->toHtml();
        $output['sessions'] = $html;


        $viewModel = viewModel('admin',__CLASS__,__FUNCTION__,$output);



        return $viewModel ;

    }



    public function files(Request $request,$id){

        $table = new DownloadFileTable();
        $rowset = $table->getDownloadRecords($id);
        $viewModel = viewModel('admin',__CLASS__,__FUNCTION__,['rowset'=>$rowset]);

        return $viewModel;
    }

    public function sessions(Request $request,$id){

        $table = new DownloadSessionTable();
        $rowset = $table->getDownloadRecords($id);
        $viewModel = viewModel('admin',__CLASS__,__FUNCTION__,['rowset'=>$rowset]);

        return $viewModel;
    }

    public function addfile(Request $request,$id){
        $path = $request->get('path');

        $downloadFileTable = new DownloadFileTable();
        $path = str_ireplace('usermedia/','',$path);
        if(!$downloadFileTable->fileExists($id,$path)){
            $downloadFileTable->addRecord([
                'download_id'=>$id,
                'path'=>$path,
                'enabled'=>1
            ]);
        }


        $filesViewModel = app(DownloadController::class)->files($request,$id);
        return $filesViewModel;
    }

    public function removefile(Request $request,$id ){

        $downloadFileTable = new DownloadFileTable();
        $row = $downloadFileTable->getRecord($id);
        $downloadId = $row->download_id;

        $downloadFileTable->deleteRecord($id);
        $filesViewModel = app(DownloadController::class)->files($request,$downloadId);
        return $filesViewModel;
    }

    public function addsession(Request $request,$id){

        $downloadSessionTable = new DownloadSessionTable();
        $count = 0;
        if(request()->isMethod('post')){
            $data = request()->all();

            foreach($data as $key=>$value){
                if(preg_match('#session_#',$key) && !$downloadSessionTable->sessionExists($id,$value)){
                    $downloadSessionTable->addRecord([
                        'download_id'=>$id,
                        'course_id'=>$value
                    ]);
                    $count++;

                }
            }
            session()->flash('flash_message',$count.' '.__lang('added-to-download-msg'));
        }

        return adminRedirect(['controller'=>'download','action'=>'edit','id'=>$id]);
    }

    public function removesession(Request $request,$id){

        $downloadSessionTable = new DownloadSessionTable();
        $row = $downloadSessionTable->getRecord($id);
        $downloadId = $row->download_id;

        $downloadSessionTable->deleteRecord($id);
        $filesViewModel = app(DownloadController::class)->sessions($request,$downloadId);
        return $filesViewModel;
    }


    public function delete(Request $request,$id){
        $table = new DownloadTable();
        $table->deleteRecord($id);
        session()->flash('flash_message',__lang('Record deleted'));
        return adminRedirect(['controller'=>'download','action'=>'index']);

    }


    public function duplicate(Request $request,$id){



        //get tables
        $downloadTable = new DownloadTable();
        $downloadFileTable = new DownloadFileTable();
        $downloadSessionTable = new DownloadSessionTable();

        //now get session records
        $downloadRow = $downloadTable->getRecord($id);
        $downloadFileRowset = $downloadFileTable->getDownloadRecords($id);
        $downloadSessionRowset = $downloadSessionTable->getDownloadRecords($id);

        //create row
        $downloadArray= getObjectProperties($downloadRow);
        unset($downloadArray['id']);
        $newId = $downloadTable->addRecord($downloadArray);

        //now get lessons
        foreach($downloadFileRowset as $row){
            $data = getObjectProperties($row);
            unset($data['id']);
            $data['download_id']=$newId;
            $downloadFileTable->addRecord($data);
        }

        //get instructors
        foreach($downloadSessionRowset as $row){
            $data = getObjectProperties($row);
            unset($data['id']);
            $data['download_id']=$newId;
            $courseName = $data['course_name'];
            unset($data['course_name']);
           // $data['name']
            $downloadSessionTable->addRecord($data);
        }

        session()->flash('flash_message',__lang('Download duplicated successfully'));
        return adminRedirect(array('controller'=>'download','action'=>'index'));


    }

    public function browsesessions(Request $request,$id){
        $data = app(StudentController::class)->sessions($request)->getData();

        $data['id'] =$id;

        $sessionInstructorTable = new SessionInstructorTable();
        $assigned = $sessionInstructorTable->getAccountRecords(ADMIN_ID);
        $data['assigned'] = $assigned;

        return view('admin.download.browsesessions',$data);
    }

    public function download(Request $request,$id){
        set_time_limit(86400);
        $table = new DownloadFileTable();
        $row = $table->getRecord($id);
        $path = 'usermedia/'.$row->path;



        header('Content-type: '.getFileMimeType($path));

// It will be called downloaded.pdf
        header('Content-Disposition: attachment; filename="'.basename($path).'"');

// The PDF source is in original.pdf
        readfile($path);
        exit();
    }






}
