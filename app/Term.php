<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Term extends Model
{
    public function repos() {
    return $this->hasMany('App\Repo'); 
    }

    protected $table = 'LTP_Terms';
    

	/**
	 * primaryKey 
	 * 
	 * @var integer
	 * @access protected
	 */
	protected $primaryKey = 'Term_Code';

	/**
	 * Indicates if the IDs are auto-incrementing.
	 *
	 * @var bool
	 */
	public $incrementing = false;    

}
