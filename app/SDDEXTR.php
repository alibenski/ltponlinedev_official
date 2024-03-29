<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DateTimeInterface;

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
        'INDEXNO', 'INDEXNO_old', 'TITLE', 'FIRSTNAME', 'LASTNAME', 'CAT', 'CATEGORY', 'SEX', 'LEVEL', 'DEPT', 'country_mission', 'ngo_name', 'PHONE', 'BIRTH', 'EMAIL', 'created_at',
    ];

    /**
     * primaryKey 
     * 
     * @var integer
     * @access protected
     */
    protected $primaryKey = 'INDEXNO';
    protected $keyType = 'string';

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
    public function countryMission()
    {
        return $this->belongsTo('App\Country', 'country_mission');
    }
    /**
     * Prepare a date for array / JSON serialization.
     *
     * @param  \DateTimeInterface  $date
     * @return string
     */
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
