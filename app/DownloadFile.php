<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DownloadFile extends Model
{
    protected $fillable = ['download_id','path','enabled'];

    public function download(){
        return $this->belongsTo(Download::class);
    }

}
