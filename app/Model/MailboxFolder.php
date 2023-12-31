<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class MailboxFolder extends Model
{
    protected $table = "mailbox_folder";

    protected $fillable = ["title", "icon"];
}
