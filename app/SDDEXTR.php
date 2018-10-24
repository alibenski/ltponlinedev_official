<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SDDEXTR extends Model
{
    protected $table = 'SDDEXTR';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'INDEXNO', 'INDEXNO_old', 'TITLE','FIRSTNAME', 'LASTNAME', 'CAT', 'CATEGORY', 'SEX', 'LEVEL', 'DEPT', 'PHONE', 'BIRTH', 'EMAIL', 'created_at', 
    ];

	/**
	 * primaryKey 
	 * 
	 * @var integer
	 * @access protected
	 */
	protected $primaryKey = 'INDEXNO';

	/**
	 * Indicates if the IDs are auto-incrementing.
	 *
	 * @var bool
	 */
	public $incrementing = false;

    /**
     * The name of the "updated at" column.
     *
     * @var string
     */
    const UPDATED_AT = 'UPDATED';

    public function users() {
    return $this->belongsTo('App\User','indexno','INDEXNO'); 
    }
	public function torgan() {
    return $this->hasOne('App\Torgan', 'Org name', 'DEPT'); 
    }
}
