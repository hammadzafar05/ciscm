<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ApplicationForm extends Model
{
    use SoftDeletes;

    const PAGE_FOLDER = 'ApplicationForm';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'applications';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'last_name',
        'email',
        'country',
        'designation',
        'organization',
        'mobile_number',
        'course_name',
    ];
}
