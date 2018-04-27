<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PlacementForm extends Model
{
        use SoftDeletes;

    protected $table = 'tblLTP_Placement_Forms';
    protected $fillable = [
        'CodeIndexID', 'Code', 'Te_Code', 'schedule_id', 'Term', 'INDEXID', 'mgr_email', 'mgr_fname', 'mgr_lname', 'L', 'continue_bool', 'approval','approval_hr','DEPT','attachment_id','attachment_pay', 'is_self_pay_form', 'form_counter', 'eform_submit_count', 'cancelled_by_student', 'agreementBtn', 'consentBtn', 'placement_schedule_id',
    ];

     /**
     * The name of the "updated at" column.
     *
     * @var string
     */
    const UPDATED_AT = 'UpdatedOn';

    public function placementSchedule() {
    return $this->belongsTo('App\PlacementSchedule'); }

    public function languages() {
    return $this->belongsTo('App\Language', 'L', 'code'); }

    public function users() {
    return $this->belongsTo('App\User', 'INDEXID', 'indexno'); 
    }

}