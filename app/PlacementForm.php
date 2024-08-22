<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DateTimeInterface;

class PlacementForm extends Model
{
    use SoftDeletes;

    protected $table = 'tblLTP_Placement_Forms';
    protected $fillable = [
        'CodeIndexID', 'Code', 'Te_Code', 'schedule_id', 'Term', 'INDEXID', 'mgr_email', 'mgr_fname', 'mgr_lname', 'L', 'profile', 'continue_bool', 'approval', 'approval_hr', 'DEPT', 'country_mission', 'ngo_name', 'attachment_id', 'attachment_pay', 'is_self_pay_form', 'form_counter', 'eform_submit_count', 'cancelled_by_student', 'agreementBtn', 'consentBtn', 'placement_schedule_id', 'placement_time', 'flexibleBtn', 'flexibleDay', 'flexibleTime', 'flexibleFormat', 'contractDate', 'dayInput', 'timeInput', 'deliveryMode', 'selfpay_approval', 'Comments', 'modified_by', 'updated_by_admin', 'overall_approval', 'teacher_comments', 'admin_eform_comment', 'admin_plform_comment', 'created_at', 'admin_eform_cancel_comment', 'admin_plform_cancel_comment', 'cancelled_by_admin', 'std_comments', 'course_preference_comment',
    ];

    public function history()
    {
        return $this->morphMany(PashHistory::class, 'historical', 'reference_table', 'reference_id', 'indexno');
    }

    /**
     * The name of the "updated at" column.
     *
     * @var string
     */
    const UPDATED_AT = 'UpdatedOn';

    public function placementSchedule()
    {
        return $this->belongsTo('App\PlacementSchedule');
    }

    public function placementTime()
    {
        return $this->belongsTo('App\Time');
    }

    // public function pashqtcur() {
    // return $this->belongsTo('App\Repo'); }

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
    public function adminCommentPlacement()
    {
        return $this->hasMany('App\AdminCommentPlacement',  'placement_id', 'id');
    }
    public function waitlist()
    {
        return $this->hasMany('App\Waitlist',  'INDEXID', 'INDEXID');
    }
    public function cancelledBy()
    {
        return $this->belongsTo('App\User', 'cancelled_by_admin', 'id');
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
