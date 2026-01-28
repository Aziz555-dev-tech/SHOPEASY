<?php

namespace App\Notifications\Proprietaire;

use App\Notifications\BaseNotification;

class PaiementRecuProprietaire extends BaseNotification
{
    public $paiement;

    public function __construct($paiement)
    {
        $this->paiement = $paiement;
    }

    public function toDatabase($notifiable)
    {
        return [
            'titre'   => 'Paiement reçu',
            'message' => "Le client {$this->paiement->client->name} a payé {$this->paiement->montant} FCFA pour votre/vos produit(s).",
            'type'    => 'paiement_proprietaire',
            'client_id'    => $this->paiement->client_id,
            'montant'      => $this->paiement->montant,
            'mode'         => $this->paiement->mode,
            'status'       => $this->paiement->status_paiement,
            'date'         => $this->paiement->date_paiement?->format('d/m/Y H:i'),
            'biens'        => $this->paiement->attribution?->biens?->pluck('titre', 'id')->toArray() ?? [],
        ];
    }
}

