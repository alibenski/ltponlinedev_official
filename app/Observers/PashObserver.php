<?php

namespace App\Observers;

use App\Repo;
use App\Traits\TracksHistoryTrait;

class PashObserver
{
    use TracksHistoryTrait;

    /**
     * Handle the repo "updated" event.
     *
     * @param  \App\Repo  $repo
     * @return void
     */
    public function updated($model)
    {
        $this->track($model);
    }
}
