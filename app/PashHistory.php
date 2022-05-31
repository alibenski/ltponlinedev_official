<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PashHistory extends Model
{
    protected $table = 'pash_history';
    protected $fillable = [
        'reference_table', 'reference_id', 'actor_id', 'body'
    ];

    /**
     * Get all of the owning commentable models.
     */
    public function pash_historical()
    {
        return $this->morphTo();
    }
}
