<?php
/**
 * Created by PhpStorm.
 * User: USER PC
 * Date: 5/5/2018
 * Time: 2:36 PM
 */

namespace App\V2\Model;


use App\ForumParticipant;
use App\Lib\BaseTable;

class ForumParticipantTable extends BaseTable {

    protected $tableName = 'forum_participants';
    //protected $primary = 'forum_participant_id';

    public function updateParticipant($topic,$user,$notify=1){

        $participant = ForumParticipant::where('forum_topic_id',$topic)->where('user_id',$user)->first();

        if(!$participant){
            ForumParticipant::create([
                'forum_topic_id'=>$topic,
                'user_id'=>$user,
                'notify'=>$notify
            ]);
        }else{
            $participant->notify = $notify;
            $participant->save();
        }
    }

}
