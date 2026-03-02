<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Attribution;
use App\Models\User;

class LivraisonController extends Controller
{
    public function assignerLivreur(Attribution $attribution)
    {
        $boutique = $attribution->bien->boutique;

        if (!$boutique || !$boutique->latitude || !$boutique->longitude) {
            return back()->with('error', 'Localisation de la boutique manquante');
        }

        $livreur = User::where('role', 'livreur')
            ->where('is_available', true)
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->selectRaw("
                users.*,
                (6371 * acos(
                    cos(radians(?)) * cos(radians(latitude)) *
                    cos(radians(longitude) - radians(?)) +
                    sin(radians(?)) * sin(radians(latitude))
                )) AS distance
            ", [
                $boutique->latitude,
                $boutique->longitude,
                $boutique->latitude
            ])
            ->orderBy('distance')
            ->first();

        if (!$livreur) {
            return back()->with('error', 'Aucun livreur disponible');
        }

        $attribution->update([
            'livreur_id' => $livreur->id,
            'statut_livraison' => 'en_livraison',
        ]);

        $livreur->update(['is_available' => false]);

        return back()->with('success', 'Livreur assigné avec succès');
    }
}
