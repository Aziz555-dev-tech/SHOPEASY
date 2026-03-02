<?php

namespace App\Console\Commands;

use App\Models\Bien;

use App\Notifications\StockFaibleNotification;
use Illuminate\Console\Command;

class CheckLowStock extends Command
{
    protected $signature = 'stock:check-low';
    protected $description = 'Notifier les propriétaires si stock ≤ 5';

    public function handle()
    {
        // 1️⃣ Biens en stock faible
        $biens = Bien::with('proprietaire')
            ->where('stock', '<=', 5)
            ->where('stock', '>', 0)
            ->get()
            ->groupBy('proprietaire_id');

        // 2️⃣ Envoi par propriétaire
        foreach ($biens as $proprietaireId => $biensDuProprio) {

            $proprietaire = $biensDuProprio->first()->proprietaire;

            if (!$proprietaire) continue;

            $proprietaire->notify(
                new StockFaibleNotification($biensDuProprio)
            );
        }

        $this->info('Notifications stock faible envoyées.');
    }
}
