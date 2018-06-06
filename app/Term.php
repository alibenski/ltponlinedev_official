<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Term extends Model
{
    protected $table = 'LTP_Terms';
    
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
