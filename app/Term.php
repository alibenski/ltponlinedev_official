<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Term extends Model
{
    protected $table = 'LTP_Terms';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'Term_Code', 'Term_Name', 'Term_Begin', 'Term_End', 'Term_Prev', 'Term_Next', 'Comments', 'Enrol_Date_Begin', 'Enrol_Date_End', 'Cancel_Date_Limit', 'Approval_Date_Limit', 'Remind_Mgr_After', 'Remind_HR_After',
    ];
	/**
	 * primaryKey 
	 * 
	 * @var integer
	 * @access protected
	 */
	protected $primaryKey = 'Term_Code';

	 /**
     * The name of the "updated at" column.
     *
     * @var string
     */
    const UPDATED_AT = 'Updated';

	/**
	 * Indicates if the IDs are auto-incrementing.
	 *
	 * @var bool
	 */
	public $incrementing = false;


    public function repos() {
    return $this->hasMany('App\Repo'); 
    }
}
