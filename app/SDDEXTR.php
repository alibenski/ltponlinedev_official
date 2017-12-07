<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SDDEXTR extends Model
{
    protected $table = 'SDDEXTR';
    
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

    public function users() {
    return $this->belongsTo('App\User'); 
    }
	public function torgan() {
    return $this->hasOne('App\Torgan', 'Org name', 'DEPT'); 
    }
}
