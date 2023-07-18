<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PartnerStudent extends Model
{
    use SoftDeletes;

    const PAGE_FOLDER = 'PartnerStudent';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ptrn_students';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'last_name',
        'email',
        '2_Country',
        '3_Designation',
        '4_Organization	',
        'mobile_number',
        'partner_id',
        'status',
	    'course_id'
    ];
}
