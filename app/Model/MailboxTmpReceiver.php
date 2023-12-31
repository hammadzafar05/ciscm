<?php

namespace App\Model;

use App\User;
use Illuminate\Database\Eloquent\Model;

class MailboxTmpReceiver extends Model
{
    protected $table = "mailbox_tmp_receiver";

    protected $fillable = ["mailbox_id", "receiver_id"];


    public function mailbox()
    {
        return $this->belongsTo(Mailbox::class, "mailbox_id");
    }

    public function user()
    {
        return $this->belongsTo(User::class, "receiver_id");
    }
}
