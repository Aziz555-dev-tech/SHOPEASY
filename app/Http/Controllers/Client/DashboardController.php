<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $client = Auth::user();
    
        // Nombre de biens achetés par le client
        $clientId = Auth::id();

        $biensAchetes = \App\Models\Attribution::where('client_id', $clientId)
            ->whereHas('paiements')
            ->count();


        $clientId = Auth::id();

        $nombreVendeurs = User::where('role', 'proprietaire')
            ->whereHas('biens.attributions', function ($q) use ($clientId) {
                $q->where('client_id', $clientId);
            })
            ->distinct('id')
            ->count('id');
        
        
    
        // Paiements récents
        $paiementsRecents = $client->paiements()
            ->with('attribution.bien')
            ->latest()
            ->limit(5)
            ->get();
    
        // Achats récents (basé sur attributions)
        $achatsRecents = $client->attributions()
            ->with('bien')
            ->latest()
            ->limit(5)
            ->get();
    
        return view('client.dashboard', compact(
            'client',
            'biensAchetes',
            'nombreVendeurs',
            'paiementsRecents',
            'achatsRecents'
        ));
    }    
}
