<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Repo extends Model
{
    use SoftDeletes;

    protected $table = 'LTP_PASHQTcur';
    protected $fillable = [
        'CodeIndexIDClass','CodeClass', 'schedule_id','CodeIndexID', 'Code', 'Te_Code', 'Term', 'INDEXID', 'EMAIL', 'L', 'DEPT', 'PS', 'Comments', 'created_at', 'UpdatedOn', 'is_self_pay_form', 'flexibleBtn', 'form_counter', 'eform_submit_count', 'convocation_email_sent', 'Written', 'Oral', 'Result', 'Overall_Grade', 'cancelled_by', 'teacher_comments', 'admin_eform_comment', 'admin_plform_comment', 'admin_eform_cancel_comment', 'admin_plform_cancel_comment', 'std_comments', 'hr_comments', 'course_preference_comment', 'pash_remark', 'last_remark_by', 'exclude_from_billing', 'excluded_by'
    ];
    
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
    //in this case, field Te_Code inside table PASH is associated to foreign key Te_Code
    //which is a field in table LTP_Terms (Model: Term) 
    public function courses() {
    return $this->belongsTo('App\Course', 'Te_Code', 'Te_Code_New'); 
	}

    public function coursesOld() {
    return $this->belongsTo('App\Course', 'Te_Code_old', 'Te_Code'); 
    }

    public function languages() {
    return $this->belongsTo('App\Language', 'L', 'code'); 
    }

    public function users() {
    return $this->belongsTo('App\User', 'INDEXID','indexno'); 
    }

    public function cancelledBy() {
    return $this->belongsTo('App\User', 'cancelled_by','id'); 
    }

	public function terms() {
    return $this->belongsTo('App\Term', 'Term', 'Term_Code'); 
    }

    public function courseschedules() {
    return $this->belongsTo('App\CourseSchedule', 'Code', 'cs_unique'); 
    }

    public function schedules() {
    return $this->belongsTo('App\Schedule', 'schedule_id'); 
    }

    public function classrooms() {
    return $this->belongsTo('App\Classroom', 'CodeClass', 'Code'); 
    }

    public function previewTempSort() {
    return $this->belongsTo('App\PreviewTempSort', 'CodeIndexID', 'CodeIndexID'); 
    }

    public function comments() {
        return $this->hasMany('App\Comment', 'pash_id', 'id'); 
    }

    public function attendances() {
        return $this->hasOne('App\Attendance', 'pash_id', 'id'); 
    }

    public function lastRemarkBy() {
    return $this->belongsTo('App\User', 'last_remark_by', 'id'); 
    }

    public function enrolments() {
    return $this->hasMany('App\Preenrolment', 'INDEXID', 'INDEXID'); 
    }

    public function placements() {
    return $this->hasMany('App\PlacementForm', 'INDEXID', 'INDEXID'); 
    }
}
