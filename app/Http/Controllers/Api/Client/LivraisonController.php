<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Models\Livraison;

class LivraisonController extends Controller
{
    /**
     * Tracking temps réel du livreur (client)
     */
    public function tracking(Livraison $livraison)
    {
        if ($livraison->client_id !== auth()->id()) {
            abort(403);
        }

        if (!$livraison->livreur) {
            return response()->json(['error' => 'Livreur non assigné']);
        }

        return response()->json([
            'livreur' => [
                'name'      => $livraison->livreur->name,
                'latitude'  => $livraison->livreur->latitude,
                'longitude' => $livraison->livreur->longitude,
            ],
        ]);
    }
}
