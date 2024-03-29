<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CourseSchedule extends Model
{
    use SoftDeletes;

    protected $table = 'tblLTP_CourseSchedule';
    protected $fillable = [
        'Code', 'cs_unique', 'Te_Code_New', 'schedule_id', 'Te_Term', 'L', 'room_id', 'Tch_ID', 'Te_Hours', 'Te_Description', 'Te_Price', 'deleted_by', 'created_by', 'specialized', 'course_new_format_categories', 'course_new_format_modes'

    ];

    /**
     * The name of the "updated at" column.
     *
     * @var string
     */
    const UPDATED_AT = 'Updated';


    //declare the foreign key on the 3rd parameter of the function
    //in this case, field Te_Code inside table PASH is associated to foreign key Te_Code
    //which is a field in table LTP_Terms (Model: Term) 
    public function course()
    {
        return $this->belongsTo('App\Course', 'Te_Code_New', 'Te_Code_New');
    }

    public function scheduler()
    {
        return $this->belongsTo('App\Schedule', 'schedule_id');
    }

    public function terms()
    {
        return $this->belongsTo('App\Term', 'Te_Term', 'Term_Code');
    }

    public function rooms()
    {
        return $this->belongsTo('App\Room', 'room_id');
    }

    public function teachers()
    {
        return $this->belongsTo('App\Teachers', 'Tch_ID', 'Tch_ID');
    }

    public function prices()
    {
        return $this->belongsTo('App\Price', 'Te_Price', 'id');
    }

    public function courseduration()
    {
        return $this->belongsTo('App\CourseDuration', 'Te_Hours', 'id');
    }

    public function courseformat()
    {
        return $this->belongsTo('App\CourseFormat', 'Te_Description', 'id');
    }

    public function classroom()
    {
        return $this->hasMany('App\Classroom', 'cs_unique', 'cs_unique');
    }
}
