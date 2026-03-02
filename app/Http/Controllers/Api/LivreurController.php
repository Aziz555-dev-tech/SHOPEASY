<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Livraison;
use Illuminate\Http\Request;

class LivreurController extends Controller
{
    public function updateLocation(Request $request)
    {
        $request->validate([
            'latitude'  => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $user = $request->user();

        if ($user->role !== 'livreur') {
            return response()->json(['error' => 'Non autorisé'], 403);
        }

        $user->update([
            'latitude'  => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Position mise à jour',
        ]);
    }

    public function position(Livraison $livraison)
    {
        if (!$livraison->livreur) {
            return response()->json(['error' => 'Livreur non assigné'], 404);
        }

        return response()->json([
            'latitude'  => $livraison->livreur->latitude,
            'longitude' => $livraison->livreur->longitude,
        ]);
    }
}

