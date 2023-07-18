<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    protected $fillable = ['admin_role_id','notify','about','public','social_facebook','social_twitter','social_linkedin','social_instagram'];

    public function adminRole(){
        return $this->belongsTo(AdminRole::class);
    }

    public function assignments(){
        return $this->hasMany(Assignment::class);
    }

    public function certificates(){
        return $this->hasMany(Certificate::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
