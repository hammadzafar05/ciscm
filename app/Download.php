<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Download extends Model
{
    protected $fillable = ['name','enabled','description','admin_id'];

    public function admin(){
        return $this->belongsTo(Admin::class);
    }

    public function downloadFiles(){
        return $this->hasMany(DownloadFile::class);
    }

    public function courses(){
        return $this->belongsToMany(Course::class);
    }

}
