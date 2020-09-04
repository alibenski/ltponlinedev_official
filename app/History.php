<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    protected $fillable = [
        'reference_table', 'reference_id', 'actor_id', 'body'
    ];

    /**
     * Get all of the owning commentable models.
     */
    public function historical()
    {
        return $this->morphTo();
    }
}
