<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ModifiedForms extends Model
{
        use SoftDeletes;

    protected $table = 'tblLTP_modified_forms';
    protected $fillable = [
        'CodeIndexID', 'Code', 'Te_Code', 'schedule_id', 'Term', 'INDEXID', 'mgr_email', 'mgr_fname', 'mgr_lname', 'L', 'profile', 'continue_bool', 'approval','approval_hr','DEPT','attachment_id','attachment_pay', 'is_self_pay_form', 'form_counter', 'eform_submit_count', 'cancelled_by_student', 'agreementBtn', 'consentBtn', 'placement_schedule_id', 'palcement_time', 'flexibleBtn', 'contractDate', 'dayInput', 'timeInput', 'selfpay_approval', 'Comments', 'created_at', 'UpdatedOn', 'id', 'modified_by', 'updated_by_admin', 'overall_approval', 'teacher_comments', 'admin_eform_comment', 'admin_plform_comment', 'admin_eform_cancel_comment', 'admin_plform_cancel_comment', 'cancelled_by_admin',
    ];

    public $timestamps = false;

    public function courses() {
    return $this->belongsTo('App\Course', 'Te_Code', 'Te_Code_New'); 
    }
    public function schedule() {
    return $this->belongsTo('App\Schedule', 'schedule_id'); 
    }
    public function languages() {
    return $this->belongsTo('App\Language', 'L', 'code'); 
    }
    public function users() {
    return $this->belongsTo('App\User', 'INDEXID', 'indexno'); 
    }
    
    public function modifyUser() {
    return $this->belongsTo('App\User', 'modified_by', 'id'); 
    }

    public function terms() {
    return $this->belongsTo('App\Term', 'Term', 'Term_Code'); 
    }
    public function filesId() {
    return $this->belongsTo('App\File', 'attachment_id'); 
    }   
    public function filesPay() {
    return $this->belongsTo('App\File', 'attachment_pay'); 
    }
    public function adminComment() {
        return $this->hasMany('App\AdminComment', 'CodeIndexID', 'CodeIndexID'); 
    }

     
}
