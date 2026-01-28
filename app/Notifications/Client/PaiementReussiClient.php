<?php

namespace App\Notifications\Client;

use App\Notifications\BaseNotification;

class PaiementReussiClient extends BaseNotification
{
    public $paiement;

    public function __construct($paiement)
    {
        $this->paiement = $paiement;
    }

    public function toDatabase($notifiable)
    {
        return [
            'titre'   => 'Paiement réussi',
            'message' => "Votre paiement de {$this->paiement->montant} FCFA a été validé.",
            'type'    => 'paiement_client',
            'client_id'    => $this->paiement->client_id,
            'montant'      => $this->paiement->montant,
            'mode'         => $this->paiement->mode,
            'status'       => $this->paiement->status_paiement,
            'date'         => $this->paiement->date_paiement?->format('d/m/Y H:i'),
            'biens'        => $this->paiement->attribution?->biens?->pluck('titre', 'id')->toArray() ?? [],
        ];
    }
}
