<?php

namespace App\Model;


use Illuminate\Database\Eloquent\Model;

class ScheduleSms extends Model
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'schedule_sms';
 
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'gateway',
        'mobile_number',
        'sent_time',
        'message',
        'is_sent',
    ];
}
