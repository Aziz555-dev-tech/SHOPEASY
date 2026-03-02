<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Livraison;

class LivraisonController extends Controller
{
    /**
     * Tracking temps réel (admin)
     */
    public function tracking(Livraison $livraison)
    {
        if (!$livraison->livreur) {
            return response()->json(['error' => 'Aucun livreur']);
        }

        return response()->json([
            'livreur' => [
                'id'        => $livraison->livreur->id,
                'name'      => $livraison->livreur->name,
                'latitude'  => $livraison->livreur->latitude,
                'longitude' => $livraison->livreur->longitude,
            ],
        ]);
    }
}
