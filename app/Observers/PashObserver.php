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

    /**
     * Handle the repo "created" event.
     *
     * @param  \App\Repo  $repo
     * @return void
     */
    public function created(Repo $repo)
    {
        //
    }

    /**
     * Handle the repo "deleted" event.
     *
     * @param  \App\Repo  $repo
     * @return void
     */
    public function deleted(Repo $repo)
    {
        //
    }

    /**
     * Handle the repo "restored" event.
     *
     * @param  \App\Repo  $repo
     * @return void
     */
    public function restored(Repo $repo)
    {
        //
    }

    /**
     * Handle the repo "force deleted" event.
     *
     * @param  \App\Repo  $repo
     * @return void
     */
    public function forceDeleted(Repo $repo)
    {
        //
    }
}
