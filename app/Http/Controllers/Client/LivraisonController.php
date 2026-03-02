<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Livraison;
use Illuminate\Http\Request;

class LivraisonController extends Controller
{
    public function tracking(Livraison $livraison)
    {
        // Sécurité : la livraison appartient bien au client
        if ($livraison->client_id !== auth()->id()) {
            abort(403);
        }

        return view('client.livraisons.tracking', compact('livraison'));
    }
}

