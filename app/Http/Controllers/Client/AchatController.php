<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class AchatController extends Controller
{
    /**
     * Affiche tous les achats du client connecté.
     */
    public function index()
    {
        $user = Auth::user();
    
        // Récupérer uniquement les attributions dont le paiement est "paye" ou "reussi"
        $achats = $user->attributions()
            ->with([
                'bien.proprietaire', // charger propriétaire du bien
                'bien.medias',       // charger médias du bien
                'paiements' => function ($q) {
                    $q->whereIn('status_paiement', ['paye', 'reussi']);
                }
            ])
            ->whereHas('paiements', function ($q) {
                $q->whereIn('status_paiement', ['paye', 'reussi']);
            })
            ->orderBy('date_attribution', 'desc')
            ->get();
    
        return view('client.achats', compact('achats'));
    }
    
}
