<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Torgan extends Model
{
	protected $table = 'TORGAN';

	/**
	 * primaryKey 
	 * 
	 * @var integer
	 * @access protected
	 */
	protected $primaryKey = 'OrgCode';

	/**
	 * Indicates if the IDs are auto-incrementing.
	 *
	 * @var bool
	 */
	public $incrementing = true;

	public function sddextr()
	{
		return $this->belongsTo('App\SDDEXTR');
	}

	public function focalpoints()
	{
		return $this->hasMany('App\FocalPoints', 'org_id', 'OrgCode');
	}
}
