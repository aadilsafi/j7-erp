<?php

namespace App\Observers;

use App\Models\Stakeholder;
use DB;

class StakeholderObserver
{
    /**
     * Handle events after all transactions are committed.
     *
     * @var bool
     */
    public $afterCommit = true;

    /**
     * Handle the Stakeholder "created" event.
     *
     * @param  \App\Models\Stakeholder  $stakeholder
     * @return void
     */
    public function created(Stakeholder $stakeholder)
    {
        DB::transaction(function () use ($stakeholder) {
            $changes = collect($stakeholder)->except(['id', 'created_at', 'updated_at'])->toArray();
            storeMultiValue($stakeholder, $changes);
        });
    }

    /**
     * Handle the Stakeholder "updated" event.
     *
     * @param  \App\Models\Stakeholder  $stakeholder
     * @return void
     */
    public function updated(Stakeholder $stakeholder)
    {

        DB::transaction(function () use ($stakeholder) {
            $changes = $stakeholder->getChanges();
            unset($changes['updated_at']);
            storeMultiValue($stakeholder, $changes);
        });
    }

    /**
     * Handle the Stakeholder "deleted" event.
     *
     * @param  \App\Models\Stakeholder  $stakeholder
     * @return void
     */
    public function deleted(Stakeholder $stakeholder)
    {
        //
    }

    /**
     * Handle the Stakeholder "restored" event.
     *
     * @param  \App\Models\Stakeholder  $stakeholder
     * @return void
     */
    public function restored(Stakeholder $stakeholder)
    {
        //
    }

    /**
     * Handle the Stakeholder "force deleted" event.
     *
     * @param  \App\Models\Stakeholder  $stakeholder
     * @return void
     */
    public function forceDeleted(Stakeholder $stakeholder)
    {
        //
    }
}
