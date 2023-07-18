<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuestionBank extends Model
{
    protected $fillable = ['question','sort_order','course_id','class_id','questions_type'];

    public function survey(){
        return $this->belongsTo(Survey::class);
    }

    public function questionBankOptions(){
        return $this->hasMany(QuestionBankOption::class);
    }
}
