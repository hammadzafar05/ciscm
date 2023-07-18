<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExternalCertificate extends Model
{
    protected $tableName = 'external_certificates';

    protected $fillable = ['tracking_number','title','course','grade','cgpa','passing_year','country','issue_date','type','website','enabled'];
}
