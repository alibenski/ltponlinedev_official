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
}
