<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DateTimeInterface;

class Preenrolment extends Model
{
    use SoftDeletes;

    protected $table = 'tblLTP_Enrolment';
    protected $fillable = [
        'CodeIndexID', 'Code', 'Te_Code', 'schedule_id', 'Term', 'INDEXID', 'mgr_email', 'mgr_fname', 'mgr_lname', 'L', 'profile', 'continue_bool', 'approval', 'approval_hr', 'DEPT', 'country_mission', 'ngo_name', 'attachment_id', 'attachment_pay', 'is_self_pay_form', 'selfpay_approval', 'form_counter', 'eform_submit_count', 'cancelled_by_student', 'agreementBtn', 'consentBtn', 'flexibleBtn', 'flexibleDay', 'flexibleTime', 'flexibleFormat', 'contractDate', 'Comments', 'modified_by', 'updated_by_admin', 'std_comments', 'overall_approval', 'teacher_comments', 'admin_eform_comment', 'admin_plform_comment', 'created_at', 'admin_eform_cancel_comment', 'admin_plform_cancel_comment', 'cancelled_by_admin',
    ];

    public function history()
    {
        return $this->morphMany(PashHistory::class, 'historical', 'reference_table', 'reference_id', 'indexno');
    }

    /**
     * The storage format of the model's date columns.
     *
     * @var string
     */
    // protected $dateFormat = 'Y-m-d H:i';

    // public function getDateFormat()
    // {
    //   return 'Y-m-d H:i';
    // }

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
    /**
     * The name of the "updated at" column.
     *
     * @var string
     */
    const UPDATED_AT = 'UpdatedOn';

    //declare the foreign key on the 3rd parameter of the function
    //in this case, field Te_Code inside tblLTP_Enrolment table is associated to foreign key Te_Code
    //which is a field in table LTP_Terms (Model: Term) 
    public function courses()
    {
        return $this->belongsTo('App\Course', 'Te_Code', 'Te_Code_New');
    }
    public function schedule()
    {
        return $this->belongsTo('App\Schedule', 'schedule_id');
    }
    public function languages()
    {
        return $this->belongsTo('App\Language', 'L', 'code');
    }
    public function users()
    {
        return $this->belongsTo('App\User', 'INDEXID', 'indexno');
    }

    public function modifyUser()
    {
        return $this->belongsTo('App\User', 'modified_by', 'id');
    }

    public function terms()
    {
        return $this->belongsTo('App\Term', 'Term', 'Term_Code');
    }
    public function filesId()
    {
        return $this->belongsTo('App\File', 'attachment_id');
    }
    public function filesPay()
    {
        return $this->belongsTo('App\File', 'attachment_pay');
    }
    public function adminComment()
    {
        return $this->hasMany('App\AdminComment', 'CodeIndexID', 'CodeIndexID');
    }
    public function cancelledBy()
    {
        return $this->belongsTo('App\User', 'cancelled_by_admin', 'id');
    }
    public function pash()
    {
        return $this->belongsTo('App\Repo', 'INDEXID', 'INDEXID');
    }
    public function pashMany()
    {
        return $this->hasMany('App\Repo', 'INDEXID', 'INDEXID');
    }
    public function contracts()
    {
        return $this->hasMany('App\ContractFile', 'enrolment_id');
    }
    /**
     * Prepare a date for array / JSON serialization.
     *
     * @param  \DateTimeInterface  $date
     * @return string
     */
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
