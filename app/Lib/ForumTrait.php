<?php
namespace App\Lib;



use App\ForumPost;
use Laminas\InputFilter\InputFilter;

trait ForumTrait {


    private function forumTopicForm(){
        $form = new BaseForm();
        $form->createText('topic_title','Topic',true,null,null,'Enter the topic for this thread');
        $form->createTextArea('message','Post',true,null,'Enter the first post');
        $form->get('message')->setAttribute('class','form-control summernote');
        $form->setInputFilter($this->forumTopicFilter());
        return $form;
    }

    private function forumTopicFilter(){
        $filter = new InputFilter();
        $filter->add([
            'name'=>'topic_title',
            'required'=>true,
            'validators'=>[
                [
                    'name'=>'NotEmpty'
                ]
            ]
        ]);

        $filter->add([
            'name'=>'message',
            'required'=>true,
            'validators'=>[
                [
                    'name'=>'NotEmpty'
                ]
            ]
        ]);

        return $filter;
    }

    public function notifyParticipants($forumPostId,$api=false,$basepath=false){
        $forumPost = ForumPost::find($forumPostId);
        $forumTopic = $forumPost->forumTopic;

        foreach($forumTopic->forumParticipants as $participant){
            if(empty($participant->notify)){
                continue;
            }

            $data=[
                'name'=>$participant->user->name,
                'post'=>$forumPost,
               ];

            if($participant->user->role_id==2){
                if($api){
                    $data['url'] = $basepath.'/student/forum/topic/'.$forumPost->forum_topic_id;
                }
                else{
                    //TODO : CHANGE URL TO STUDENT LINK
                    //$data['url']= $this->url()->fromRoute('application/default',['controller'=>'forum','action'=>'topic','id'=>$forumPost->forum_topic_id],['force_canonical'=>true]);
                    $data['url'] =  url('/student/forum/topic/'.$forumPost->forum_topic_id);
                }

            }
            else{
                if($api){
                    $data['url'] = $basepath.'/admin/forum/topic/'.$forumPost->forum_topic_id;
                }
                else{
                    $data['url']= adminUrl(['controller'=>'forum','action'=>'topic','id'=>$forumPost->forum_topic_id]);
                }
            }

            //get blade
/*            if(!$api){
                $message = $this->bladeHtml('mails.forum_reply',$data);
            }
            else{
                extract($data);
                $name = $data['name'];
                $replyier = $forumPost->user->name;
                $title = $forumPost->forumTopic->title;
                $fmessage = $forumPost->message;
                $url = $data['url'];

                $message = __lang('forum-notification-msg',[
                   'name'=>$name,
                   'replier'=>$replyier,
                   'br'=>'<br/>',
                   'title'=>$title,
                   'message'=>$fmessage,
                   'link' => '<a href="'.$url.'">'.$url.'</a>'
                ]);


            }*/

            extract($data);
            $name = $data['name'];
            $replyier = $forumPost->user->name;
            $title = $forumPost->forumTopic->title;
            $fmessage = $forumPost->message;
            $url = $data['url'];

            $message = __lang('forum-notification-msg',[
                'name'=>$name,
                'replier'=>$replyier,
                'br'=>'<br/>',
                'title'=>$title,
                'message'=>$fmessage,
                'link' => '<a href="'.$url.'">'.$url.'</a>'
            ]);


            try{
                $this->sendEmail($participant->user->email,__lang('new-forum-reply').$forumTopic->title,$message);
            }
            catch(\Exception $ex){

            }

        }


    }

}
