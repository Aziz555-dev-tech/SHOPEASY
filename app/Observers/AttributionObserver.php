<?php

namespace App\Observers;

use App\Models\Attribution;

class AttributionObserver
{
    /**
     * Handle the Attribution "created" event.
     */


    public function created(Attribution $attribution)
    {
        if ($attribution->statut_paiement === 'paye') {
            app(\App\Http\Controllers\LivraisonController::class)->assignerLivreur($attribution);
        }
    }


    /**
     * Handle the Attribution "updated" event.
     */
    public function updated(Attribution $attribution): void
    {
        //
    }

    /**
     * Handle the Attribution "deleted" event.
     */
    public function deleted(Attribution $attribution): void
    {
        //
    }

    /**
     * Handle the Attribution "restored" event.
     */
    public function restored(Attribution $attribution): void
    {
        //
    }

    /**
     * Handle the Attribution "force deleted" event.
     */
    public function forceDeleted(Attribution $attribution): void
    {
        //
    }
}
