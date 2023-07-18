<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Lib\HelperTrait;
use App\V2\Model\AccountsTable;
use App\V2\Model\DiscussionAccountTable;
use App\V2\Model\DiscussionReplyTable;
use App\V2\Model\DiscussionTable;
use App\V2\Model\StudentTable;
use Illuminate\Http\Request;

class DiscussController extends Controller
{

    use HelperTrait;
    public function index(Request $request) {
        // TODO Auto-generated ArticlesController::index(Request $request) default action
        $table = new DiscussionTable();
        $discussionReplyTable = new DiscussionReplyTable();
        $discussionAccountTable = new DiscussionAccountTable();

        //$replied = $request->get('replied');
        $replied= @$_GET['replied'];
        $total = $table->getTotalDiscussions($replied);

        $paginator = $table->getDiscussRecords(true,$replied);

        $paginator->setCurrentPageNumber((int)request()->get('page', 1));
        $paginator->setItemCountPerPage(30);
        return viewModel('admin',__CLASS__,__FUNCTION__,array(
            'paginator'=>$paginator,
            'pageTitle'=>__lang('Instructor Chat'),
            'replyTable'=>$discussionReplyTable,
            'total'=>$total,
            'accountTable'=>$discussionAccountTable,
        ));

    }


    public function addreply(Request $request,$id){
        $table = new DiscussionReplyTable();
        $discussionTable = new DiscussionTable();
        $studentTable = new StudentTable();

        $accountTable = new AccountsTable();
        $this->validateDiscussion($id);
        $discussionRow = $discussionTable->getRecord($id);

        if(request()->isMethod('post'))
        {

            $reply = $request->post('reply');
            $user= $this->getAdmin();

            if(!empty($reply)){
                $data = [
                    'discussion_id'=>$id,
                    'reply'=> $reply,
                    'user_id'=> $user->id,
                ];
                $rid= $table->addRecord($data);

                if(!empty($rid))
                {
                    //update discussion
                    $discussionTable->update(['replied'=>1],$id);
                }

                $name = $user->name.' '.$user->last_name;
                //send notification to admins
                $subject = __lang('New reply for').' "'.$discussionRow->subject.'"';
                $message = __lang('discussion-reply-mail',['subject'=>$discussionRow->subject,'name'=>$name,'reply'=>$reply]);
                $loginLink= $this->getBaseUrl().'/login';
                $loginLink = "<a href=\"$loginLink\" >$loginLink</a>";
                $adminLink = $this->getBaseUrl().'/admin';
                $adminLink = "<a href=\"$adminLink\" >$adminLink</a>";
                //notify student
                $student = $studentTable->getRecord($discussionRow->student_id);
                $this->sendEmail($student->email,$subject,$message.$loginLink);

                $rowset = $table->getRepliedAdmins($id);
                foreach($rowset as $row){
                    try{
                        if(!empty($row->email) && $row->email != $user->email){
                            $this->sendEmail($row->email,$subject,$message.$loginLink);
                        }
                    }
                    catch(\Exception $ex)
                    {

                    }

                }

                session()->flash('flash_message',__lang('reply-added-msg'));
            }
            else{
                session()->flash('flash_message',__lang('submission-failed-msg'));
            }

        }

        return adminRedirect(['controller'=>'discuss','action'=>'index']);
    }

    public function viewdiscussion(Request $request,$id)
    {
        $table = new DiscussionReplyTable();
        $discussionTable = new DiscussionTable();
        $discussionAccountTable =  new DiscussionAccountTable();
        $this->validateDiscussion($id);
        $row= $discussionTable->getRecord($id);


        $paginator = $table->getPaginatedRecordsForDiscussion(true,$id);

        $paginator->setCurrentPageNumber((int)request()->get('page', 1));
        $paginator->setItemCountPerPage(30);
        $accounts = $discussionAccountTable->getDiscussionAccounts($id);

        return viewModel('admin',__CLASS__,__FUNCTION__,array(
            'paginator'=>$paginator,
            'pageTitle'=>__lang('View Chat'),
            'row'=>$row,
            'studentTable'=>new StudentTable(),
            'accountTable'=> new AccountsTable(),
            'total'=>$table->getTotalReplies($id),
            'accounts'=>$accounts
        ));
    }

    public function delete(Request $request,$id)
    {
        $table = new DiscussionTable();
        $discussionAccountTable = new DiscussionAccountTable();
        $this->validateDiscussion($id);

        if(GLOBAL_ACCESS){
            $table->deleteRecord($id);
        }
        else{
            $discussionAccountTable->deleteAccountRecord($id,ADMIN_ID);
        }


        flashMessage(__lang('Record deleted'));
        return adminRedirect(array('controller'=>'discuss','action'=>'index'));
    }


    private function validateDiscussion($id){

        $discussionAccountTable = new DiscussionAccountTable();
        if($discussionAccountTable->hasDiscussion(ADMIN_ID,$id) || GLOBAL_ACCESS){
            return true;
        }
        else{
            return back();
        }
    }

}
