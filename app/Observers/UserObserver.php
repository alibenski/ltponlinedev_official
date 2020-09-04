<?php

namespace App\Observers;

use App\Traits\TracksHistoryTrait;

class UserObserver
{
    use TracksHistoryTrait;

    /**
     * Handle the user "updated" event.
     *
     * @param  \App\User  $user
     * @return void
     */
    public function updated($model)
    {
        $this->track($model);
    }
}
