<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'reference',
        'montant_original',
        'montant',
        'mode_paiement',
        'statut',
        'cart_json',
        'details',
        'proof_path',
    ];

    protected $casts = [
        'details'   => 'array',
        'cart_json' => 'array',
    ];

    /*
     * Relations
     */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Retourne les biens contenus dans la transaction.
     * Note : ce ne sont pas des relations automatiques,
     * on doit charger via les IDs stockés dans cart_json.
     */
    public function biens()
    {
        // cart_json contient un tableau d'objets avec id, titre, prix
        if (!$this->cart_json) {
            return collect();
        }

        $ids = collect($this->cart_json)->pluck('id')->toArray();

        return Bien::whereIn('id', $ids)->get();
    }
}
