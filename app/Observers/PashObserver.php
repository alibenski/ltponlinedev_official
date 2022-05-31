<?php

namespace App\Observers;

use App\Traits\TracksPashHistoryTrait;

class PashObserver
{
    use TracksPashHistoryTrait;

    /**
     * Handle the user "updated" event.
     *
     * @param  \App\Repo  $user
     * @return void
     */
    public function updated($model)
    {
        $this->track($model);
    }
}
