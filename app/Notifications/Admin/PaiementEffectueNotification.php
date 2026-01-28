<?php

namespace App\Notifications\Admin;

use App\Notifications\BaseNotification;

class PaiementEffectueNotification extends BaseNotification
{
    public $paiement;

    public function __construct($paiement)
    {
        $this->paiement = $paiement;
    }

    public function toDatabase($notifiable)
    {
        return [
            'titre'        => 'Nouveau paiement',
            'message'      => "Le client {$this->paiement->client->name} a payé {$this->paiement->montant} FCFA pour l'attribution #{$this->paiement->attribution_id}.",
            'type'         => 'paiement',
            'client_id'    => $this->paiement->client->id,
            'montant'      => $this->paiement->montant,
            'attribution_id'=> $this->paiement->attribution_id,
            'mode'         => $this->paiement->mode,
            'status'       => $this->paiement->status_paiement,
            'date'         => $this->paiement->date_paiement->format('d/m/Y H:i'),
        ];
    }
    
}
