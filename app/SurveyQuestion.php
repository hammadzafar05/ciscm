<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SurveyQuestion extends Model
{
    protected $fillable = ['survey_id','question','sort_order'];

    public function survey(){
        return $this->belongsTo(Survey::class);
    }

    public function surveyOptions(){
        return $this->hasMany(SurveyOption::class);
    }
}
