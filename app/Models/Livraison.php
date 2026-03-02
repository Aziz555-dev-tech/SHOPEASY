<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Livraison extends Model
{
    use HasFactory;

    protected $fillable = [
        'paiement_id',
        'boutique_id',
        'client_id',
        'livreur_id',
        'adresse',
        'latitude',
        'longitude',
        'statut',
        'assignee_at',
        'livree_at',
    ];

    public function paiement()
    {
        return $this->belongsTo(Paiement::class);
    }

    public function boutique()
    {
        return $this->belongsTo(Boutique::class);
    }

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function livreur()
    {
        return $this->belongsTo(User::class, 'livreur_id');
    }


    
}
