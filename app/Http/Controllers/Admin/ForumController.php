<?php

namespace App\Http\Controllers\Admin;

use App\Course;
use App\ForumParticipant;
use App\ForumPost;
use App\ForumTopic;
use App\Http\Controllers\Controller;
use App\Lib\BaseForm;
use App\Lib\ForumTrait;
use App\Lib\HelperTrait;
use App\V2\Model\ForumParticipantTable;
use App\V2\Model\ForumTopicTable;
use App\V2\Model\SessionInstructorTable;
use App\V2\Model\SessionTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laminas\Form\Element\Checkbox;
use Laminas\Form\Element\Select;

class ForumController extends Controller
{
    use HelperTrait;
    use ForumTrait;




    /**
     * Get list of all sessions student is enrolled in and for which forum is enabled
     */
    public function index(Request $request){

        $table = new ForumTopicTable();

        $sessionId = $request->get('course_id');

        $paginator = $table->getTopicsForAdmin($this->getAdmin()->admin->id,$sessionId);
        $paginator->setCurrentPageNumber((int)request()->get('page', 1));
        $paginator->setItemCountPerPage(30);

        $pageTitle = __lang('Student Forum').': '.$table->total.' '.__lang('topics');
        if(!empty($sessionId)){
            $pageTitle = __lang('Forum Topics for').' '.Course::find($sessionId)->name.' ('.$table->total.')';
        }

        $form = $this->adminForumForm();
        $form->get('course_id')->setValue($sessionId);
        $form->get('course_id')->setAttribute('style','min-width:150px');
        return view('admin.forum.index',[
            'topics'=>$paginator,
            'pageTitle'=>$pageTitle,
            'select'=> $form->get('course_id'),
            'form'=>$form
        ]);

    }



    public function addtopic(Request $request){

        $form = $this->adminForumForm();

        if(request()->isMethod('post')){
            $formData = request()->all();
            $form->setData($formData);
            if($form->isValid()){
                $data = $form->getData();
                //create topic




                    $forumTopic = ForumTopic::create([
                        'title'=>$data['topic_title'],
                        'user_id'=>$this->getAdminId(),
                        'course_id'=>$data['course_id']
                    ]);

                    $message = $this->saveInlineImages($data['message'],$this->getBaseUrl());
                    $message = clean($message);

                    //creat post
                    $postId = ForumPost::create([
                        'forum_topic_id'=>$forumTopic->id,
                        'message'=>$message,
                        'user_id'=>$this->getAdminId()
                    ]);

                    $fpTable = new ForumParticipantTable();
                    $fpTable->updateParticipant($forumTopic->id,$this->getAdminId());

                    //now redirect to topic page
                    return adminRedirect(['controller'=>'forum','action'=>'topic','id'=>$forumTopic->id]);




            }
            else{
                $this->data['message'] = $this->getFormErrors($form);
            }
        }

        $this->data['pageTitle'] = __lang('Add Topic');
        $this->data['form'] = $form;
        $this->data['customCrumbs'] = [
            route('admin.dashboard')=>__lang('Dashboard'),
            adminUrl(['controller'=>'forum','action'=>'index'])=>__lang('Student Forum'),
            '#'=>__lang('Add Topic')
        ];
        return view('admin.forum.addtopic',$this->data);

    }


    public function topic(Request $request,$id){
        $this->data['id'] = $id;
        $forumTopic = ForumTopic::find($id);
        $sessionId = $forumTopic->course->id;
        $this->validateAccess($sessionId);

        $this->data['posts'] = $forumTopic->forumPosts()->paginate(70);

        $this->data['customCrumbs'] = [
            route('admin.dashboard')=>__lang('Dashboard'),
            adminUrl(['controller'=>'forum','action'=>'index'])=>__lang('Student Forum'),
            '#'=>__lang('Forum Topic')
        ];

        $checkbox = new Checkbox('notify');
        $checkbox->setAttribute('id','notify');
        $checkbox->setCheckedValue(1);
        //check for participant
        $participant = ForumParticipant::where('forum_topic_id',$id)->where('user_id',$this->getAdminId())->first();
        if($participant && $participant->notify==1){
            $this->data['checked']=true;
        }
        else{
            $this->data['checked'] = false;
        }
        $this->data['checkbox'] = $checkbox;
        $this->data['forumTopic'] = $forumTopic;
        $this->data['pageTitle'] = $forumTopic->title;

        return view('admin.forum.topic',$this->data);
    }

    public function reply(Request $request,$id){
        ini_set('post_max_size', '5M');
        $topic = ForumTopic::find($id);
        $this->validateAccess($topic->course_id);
        if(request()->isMethod('post')){
            $post = request()->all();
            $message = $post['message'];

            if(empty($message)){
                session()->flash('flash_message',__lang('Please enter a message'));
                return back();
            }
            $message = $this->saveInlineImages($message,$this->getBaseUrl());

            $message= clean($message);

            $reply = new ForumPost();
            $reply->forum_topic_id = $id;
            $reply->message = $message;
            $reply->user_id = $this->getAdminId();
            if(!empty($post['post_reply_id'])){
                $reply->post_reply_id = $post['post_reply_id'];
            }
            try{
                $reply->save();
            }
            catch(\Exception $ex){
                session()->flash('flash_message',__lang('forum-content-error'));
                flashMessage($post);
                return back();
            }



            $fpTable = new ForumParticipantTable();
            $fpTable->updateParticipant($id,$this->getAdminId());
            $this->notifyParticipants($id);
            session()->flash('flash_message',__lang('Reply saved!'));

        }

        return back();
    }

    public function notifications(Request $request,$id){

        $topic = ForumTopic::find($id);
        $this->validateAccess($topic->course_id);

        $notify = $request->get('notify');
        $table = new ForumParticipantTable();
        $table->updateParticipant($id,$this->getAdminId(),$notify);
        exit('true');

    }

    public function deletetopic(Request $request,$id){
        $forumTopic = ForumTopic::findOrFail($id);
        $this->validateAccess($forumTopic->course_id);
        $forumTopic->delete();
        session()->flash('flash_message',__lang('Topic deleted'));
        return back();

    }


    private function validateAccess($sessionId){

        //check if user has global access
        if(GLOBAL_ACCESS){
            return true;
        }

        //check if is owner of session
        if(Course::find($sessionId)->admin_id==$this->getAdmin()->admin->id){
            return true;
        }
        $sessionInstructorTable = new SessionInstructorTable();
        //check if is instructor
        if($sessionInstructorTable->isInstructor($sessionId,$this->getAdminId())){
            return true;
        }


        flashMessage(__lang('no-forum-access'));
        return adminRedirect(['controller'=>'forum','action'=>'index']);

    }

    private function adminForumForm(){
        $form = new BaseForm();
        $form->createText('topic_title',__lang('Topic'),true,null,null,__lang('enter-thread-topic'));
        $form->createTextArea('message',__lang('Post'),true,null,__lang('enter-first-post'));
        $form->get('message')->setAttribute('class','form-control summernote');


        $sessionTable = new SessionTable();
        $rowset = $sessionTable->getLimitedRecords(5000);




        $options = [];
        $log = [];
        foreach($rowset as $row){
            // $options[$row->course_id] = $row->session_name;
            $options[] =  ['attributes'=>['data-type'=>$row->type],'value'=>$row->id,'label'=>$row->name.' ('.$row->id.')'];
            $log[$row->id]=true;
        }
        $sessionInstructorTable = new SessionInstructorTable();
        $rowset = $sessionInstructorTable->getAccountRecords($this->getAdminId());
        foreach($rowset as $row){
            if(isset($log[$row->course_id])){
                continue;
            }
            // $options[$row->course_id] = $row->session_name;
            $options[] =  ['attributes'=>['data-type'=>$row->type],'value'=>$row->id,'label'=>$row->name.' ('.$row->id.')'];

        }

        //$form->createSelect('course_id','Session/Course',$options,true);
        // $form->get('course_id')->setAttribute('class','form-control select2');

        $sessionId = new Select('course_id');
        $sessionId->setLabel(__lang('Session/Course'));
        $sessionId->setAttribute('class','form-control select2');
        $sessionId->setAttribute('id','course_id');
        $sessionId->setValueOptions($options);

        $form->add($sessionId);

        $form->setInputFilter($this->adminForumFilter());
        return $form;
    }

    private function adminForumFilter(){
        $filter = $this->forumTopicFilter();
        $filter->add([
            'name'=>'course_id',
            'required'=>true,
            'validators'=>[
                [
                    'name'=>'NotEmpty'
                ]
            ]
        ]);
        return $filter;
    }

}
