<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ModifiedForms extends Model
{
        use SoftDeletes;

    protected $table = 'tblLTP_modified_forms';
    protected $fillable = [
        'CodeIndexID', 'Code', 'Te_Code', 'schedule_id', 'Term', 'INDEXID', 'mgr_email', 'mgr_fname', 'mgr_lname', 'L', 'profile', 'continue_bool', 'approval','approval_hr','DEPT','attachment_id','attachment_pay', 'is_self_pay_form', 'form_counter', 'eform_submit_count', 'cancelled_by_student', 'agreementBtn', 'consentBtn', 'placement_schedule_id', 'palcement_time', 'flexibleBtn', 'contractDate', 'dayInput', 'timeInput', 'selfpay_approval', 'Comments', 'created_at', 'UpdatedOn',
    ];

    public $timestamps = false;

     /**
     * The name of the "updated at" column.
     *
     * @var string
     */
    // const UPDATED_AT = 'UpdatedOn';
}
