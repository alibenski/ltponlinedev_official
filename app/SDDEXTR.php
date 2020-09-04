<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SDDEXTR extends Model
{
    use SoftDeletes;

    protected $table = 'SDDEXTR';

    public function history()
    {
        return $this->morphMany(History::class, 'historical', 'reference_table', 'reference_id');
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'INDEXNO', 'INDEXNO_old', 'TITLE', 'FIRSTNAME', 'LASTNAME', 'CAT', 'CATEGORY', 'SEX', 'LEVEL', 'DEPT', 'PHONE', 'BIRTH', 'EMAIL', 'created_at',
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

    public function users()
    {
        return $this->belongsTo('App\User', 'INDEXNO', 'indexno');
    }
    public function torgan()
    {
        return $this->hasOne('App\Torgan', 'Org name', 'DEPT');
    }
}
