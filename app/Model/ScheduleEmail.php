<?php

namespace App\Model;


use Illuminate\Database\Eloquent\Model;

class ScheduleEmail extends Model
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'schedule_email';
 
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
	    'email',
	    'name',
	    'sent_time',
	    'message',
	    'sender_name',
	    'sender_email',
	    'subject',
	    'is_sent',
    ];
}
