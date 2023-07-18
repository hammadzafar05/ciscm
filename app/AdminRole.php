<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdminRole extends Model
{
    protected $fillable = ['name'];

    public function admins(){
        return $this->hasMany(Admin::class);
    }

    public function permissions(){
        return $this->belongsToMany(Permission::class);
    }
}
