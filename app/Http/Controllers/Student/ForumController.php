<?php
/**
 * Created by PhpStorm.
 * User: USER PC
 * Date: 8/22/2017
 * Time: 11:11 AM
 */

namespace App\Http\Controllers\Student;
use App\Http\Controllers\Controller;
use App\Lib\HelperTrait;
use Illuminate\Http\Request;

use App\ForumParticipant;
use App\ForumPost;
use App\ForumTopic;
use App\Lecture;
use App\Course;
use App\V2\Model\ForumParticipantTable;
use App\V2\Model\SessionTable;
use App\V2\Model\StudentSessionTable;
use App\Lib\BaseForm;
use App\Lib\ForumTrait;
use Illuminate\Support\Facades\Auth;
use Laminas\EventManager\EventManagerInterface;
use Laminas\Form\Element\Checkbox;
use Laminas\InputFilter\InputFilter;

use Laminas\View\Model\ViewModel;
use Illuminate\Database\Capsule\Manager as DB;


class ForumController extends Controller {

    use HelperTrait;
    use ForumTrait;

    /**
     * Get list of all sessions student is enrolled in and for which forum is enabled
     */
    public function index(Request $request){

        $studentSessionTable = new StudentSessionTable();
        $studentId = $this->getId();
        $paginator = $studentSessionTable->getStudentForumRecords(true,$studentId);
        $paginator->setCurrentPageNumber((int)request()->get('page', 1));
        $paginator->setItemCountPerPage(30);


        return view('student.forum.index',[
            'paginator'=>$paginator,
            'pageTitle'=>__lang('Student Forum')
        ]);

    }


    public function topics(Request $request,$id){
        $sessionId = $id;
        $this->validateAccess($sessionId);
        $lectureId = $request->get('lecture_id');
        $session = Course::find($sessionId);

        if(!empty($lectureId)){
            $topics = $session->forumTopics()->where('lecture_id',$lectureId)->orderBy('id','desc')->paginate(20);

        }
        else{
            $topics = $session->forumTopics()->orderBy('id','desc')->paginate(20);
        }

        $this->data['lecture'] = Lecture::find($lectureId);

        $this->data['pageTitle'] = __lang('Forum Topics in').' '.$session->name;
        $this->data['id']=$sessionId;


        $this->data['topics'] = $topics;

        if($topics->count()==0){
            $this->data['message'] = __lang('no-topics');
        }
        $this->data['student'] = $this->getStudent();

        return view('student.forum.topics',$this->data);
        //return $this->data;
    }

    public function addtopic(Request $request,$id){

        $this->validateAccess($id);
        $form = $this->forumTopicForm();
        $course = Course::find($id);
        if(request()->isMethod('post')){
            $formData = request()->all();
            $form->setData($formData);
            if($form->isValid()){
                $data = $form->getData();
                //create topic


                    $studentId= Auth::user()->id;
                    $forumTopic = $course->forumTopics()->create([
                        'title'=>$data['topic_title'],
                        'user_id'=>$studentId
                    ]);

                    $message = $this->saveInlineImages($data['message'],$this->getBaseUrl());
                    $message = clean($message);

                    //creat post
                    $forumTopic->forumPosts()->create([
                        'message'=>$message,
                        'user_id'=>$studentId
                    ]);

                    $fpTable = new ForumParticipantTable();
                    $fpTable->updateParticipant($forumTopic->id,$studentId);
                    //now redirect to topic page
                    return redirect()->route('student.forum.topic',['id'=>$forumTopic->id]);




   }
            else{
                $this->data['message'] = $this->getFormErrors($form);
            }
        }

        $this->data['pageTitle'] = __lang('Add Topic').': '.Course::find($id)->name;
        $this->data['form'] = $form;
        $this->data['customCrumbs'] = [
            route('student.dashboard')=>__lang('dashboard'),
             route('student.forum.index')=>__lang('Student Forum'),
             route('student.forum.topics',['id'=>$id])=>__lang('Forum Topics'),
            '#'=>__lang('Add Topic')
        ];
        return view('student.forum.addtopic',$this->data);

    }


    public function topic(Request $request,$id){

        $this->data['id'] = $id;
        $forumTopic = ForumTopic::find($id);
        $sessionId = $forumTopic->course->id;
        $this->validateAccess($sessionId);

        $this->data['posts'] = $forumTopic->forumPosts()->paginate(70);
        $this->data['pageTitle'] = $forumTopic->course->name;
        $this->data['customCrumbs'] = [
            route('student.dashboard')=>__lang('dashboard'),
            route('student.forum.index')=>__lang('Student Forum'),
            route('student.forum.topics',['id'=>$sessionId])=>__lang('Forum Topics'),
            '#'=>__lang('Forum Topic')
        ];

        $checkbox = new Checkbox('notify');
        $checkbox->setAttribute('id','notify');
        $checkbox->setCheckedValue(1);
        //check for participant
        $participant = ForumParticipant::where('forum_topic_id',$id)->where('user_id',Auth::user()->id)->first();
        if($participant && $participant->notify==1){
            $this->data['checked']=true;
        }
        else{
            $this->data['checked'] = false;
        }
        $this->data['checkbox'] = $checkbox;
        $this->data['forumTopic'] = $forumTopic;


        return view('student.forum.topic',$this->data);
    }

    public function reply(Request $request,$id){

        $topic = ForumTopic::find($id);
        $this->validateAccess($topic->course_id);
        if(request()->isMethod('post')){
            $post = request()->all();
            $message = $post['message'];

            if(empty($message)){
                flashMessage(__lang('Please enter a message'));
                return back();
            }


            $message = $this->saveInlineImages($message,$this->getBaseUrl());

            $student = $this->getStudent();
            $message= clean($message);


            $reply = new ForumPost();
            $reply->forum_topic_id = $id;
            $reply->message = $message;
            $reply->user_id = Auth::user()->id;
            if(!empty($post['post_reply_id'])){
                $reply->post_reply_id = $post['post_reply_id'];
            }
            try{
                $reply->save();
            }
            catch(\Exception $ex){
                flashMessage(__lang('forum-content-error'));
                return back();
            }



            $fpTable = new ForumParticipantTable();
            $fpTable->updateParticipant($id,Auth::user()->id);
            $this->notifyParticipants($id);
            flashMessage(__lang('Reply saved!'));

        }

        return back();
    }

    public function notifications(Request $request,$id){
        $topic = ForumTopic::find($id);
        $this->validateAccess($topic->course_id);

        $notify = $request->get('notify');
        $table = new ForumParticipantTable();
        $table->updateParticipant($id,Auth::user()->id,$notify);
        exit('true');

    }

    public function deletetopic(Request $request,$id){
        //check if user is owner of topic
        $forumTopic = ForumTopic::findOrFail($id);

        $student = $this->getStudent();
        if($forumTopic->user_id==Auth::user()->id){
            $forumTopic->delete();
            flashMessage(__lang('Topic deleted!'));

        }

        return back();
    }


    private function validateAccess($sessionId){
        $studentSessionTable = new StudentSessionTable();
        $sessionTable = new SessionTable();
        $session = $sessionTable->getRecord($sessionId);
        if(!$studentSessionTable->enrolled($this->getId(),$sessionId) && $session->enable_forum==1 && $session->enabled==1){
            flashMessage(__lang('forum-unavailable'));
            return redirect()->route('student.forum.index');
        }
    }
}
