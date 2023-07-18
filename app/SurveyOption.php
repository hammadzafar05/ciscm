<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SurveyOption extends Model
{
    protected $fillable= ['survey_question_id','option'];

    public function surveyQuestion(){
        return $this->belongsTo(SurveyQuestion::class);
    }

}
