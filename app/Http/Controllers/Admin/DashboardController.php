<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Bien;
use App\Models\Attribution;
use App\Models\Paiement;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // === Statistiques générales ===
        $nombreProprio = User::where('role', 'proprietaire')->count();
        $nombreClients = User::where('role', 'client')->count();
        $nombreBiens = Bien::count();
        $nombreTransactions = Paiement::whereIn('status_paiement', ['paye', 'reussi'])->count();
    
        // === Transactions récentes ===
        $transactions = Paiement::with('attribution.bien', 'attribution.client')
            ->whereIn('status_paiement', ['paye', 'reussi'])
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();
    
        // === Attributions récentes ===
        $attributions = Attribution::with('client', 'bien')
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();
    
        // === Graphique 1 : Paiements encaissés ===
        $labels = [];
        $paiementsMensuels = [];
    
        // === Graphique 2 : Nouveaux utilisateurs ===
        $nouveauxUsersMensuels = [];
    
        for ($i = 1; $i <= 12; $i++) {
    
            // Mois en français
            $labels[] = Carbon::create()->month($i)->locale('fr')->translatedFormat('F');
    
            // Paiements encaissés (paye ou reussi)
            $paiementsMensuels[] = Paiement::whereMonth('created_at', $i)
                ->whereIn('status_paiement', ['paye', 'reussi'])
                ->sum('montant');
    
            // Nouveaux utilisateurs
            $nouveauxUsersMensuels[] = User::whereMonth('created_at', $i)->count();
        }
    
        return view('admin.dashboard', compact(
            'nombreProprio',
            'nombreClients',
            'nombreBiens',
            'nombreTransactions',
            'transactions',
            'attributions',
            'labels',
            'paiementsMensuels',
            'nouveauxUsersMensuels'
        ));
    }
    
}
