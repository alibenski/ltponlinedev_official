<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PlacementForm extends Model
{
    protected $table = 'tblLTP_Placement_Forms';

     /**
     * The name of the "updated at" column.
     *
     * @var string
     */
    const UPDATED_AT = 'UpdatedOn';

    public function placementSchedule() {
    return $this->belongsTo('App\PlacementSchedule'); 
    }
}