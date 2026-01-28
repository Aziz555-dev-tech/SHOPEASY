<?php

namespace App\Http\Controllers\Proprietaire;

use App\Http\Controllers\Controller;
use App\Models\Attribution;
use App\Models\Bien;
use App\Models\Paiement;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $proprio = Auth::user();
    
        // Nombre de clients (uniquement ceux qui ont acheté ses biens)
        $nombreclient = User::whereHas('attributions', function($q) use ($proprio) {
            $q->whereHas('bien', fn($b) => $b->where('proprietaire_id', $proprio->id));
        })->count();
    
        // Nombre de biens possédés
        $nombreBiens = Bien::where('proprietaire_id', $proprio->id)->count();
    
        // Nombre de biens vendus
        $nombreBiensVendus = Attribution::whereHas('bien', function($q) use ($proprio) {
            $q->where('proprietaire_id', $proprio->id);
        })
        ->whereHas('paiements', function($q) {
            $q->whereIn('status_paiement', ['paye', 'reussi']);
        })
        ->count();
    
        // Nombre de transactions liées aux biens du propriétaire
        $nombreTransactions = Paiement::whereIn('status_paiement', ['paye', 'reussi'])
            ->whereHas('attribution.bien', fn($q) => $q->where('proprietaire_id', $proprio->id))
            ->count();
    
        // Paiements récents (5 derniers)
        $transactionsRecentes = Paiement::with('attribution.client', 'attribution.bien')
            ->whereIn('status_paiement', ['paye', 'reussi'])
            ->whereHas('attribution.bien', fn($q) => $q->where('proprietaire_id', $proprio->id))
            ->latest()
            ->limit(5)
            ->get();
    
        // Attributions récentes (5 derniers achats)
        $attributionsRecentes = Attribution::with('client', 'bien')
            ->whereHas('bien', fn($q) => $q->where('proprietaire_id', $proprio->id))
            ->latest()
            ->limit(5)
            ->get();
    
        // === GRAPH : Montants encaissés par mois ===
        $moisLabels = [];
        $loyersMois = [];
    
        for ($i = 1; $i <= 12; $i++) {
            $moisLabels[] = Carbon::create()->month($i)->locale('fr')->translatedFormat('F');
    
            $loyersMois[] = Paiement::whereMonth('created_at', $i)
                ->whereIn('status_paiement', ['paye', 'reussi'])
                ->whereHas('attribution.bien', fn($q) => $q->where('proprietaire_id', $proprio->id))
                ->sum('montant');
        }
    
        return view('proprio.dashboard', compact(
            'proprio',
            'nombreclient',
            'nombreBiens',
            'nombreBiensVendus',
            'nombreTransactions',
            'transactionsRecentes',
            'attributionsRecentes',
            'moisLabels',
            'loyersMois'
        ));
    }
    

    public function mesClients()
    {
        $biens = Bien::with([
            'proprietaire',
            'categorie',
            'sousCategorie',
            'subType'
        ])
        ->where('proprietaire_id', Auth::id())
        ->get();
    
        $attributions = Attribution::with([
            'client',
            'bien',
            'bien.categorie',
            'bien.sousCategorie',
            'bien.subType'
        ])
        ->whereHas('bien', function($query) {
            $query->where('proprietaire_id', auth()->id());
        })
        ->orderBy('date_attribution', 'desc')
        ->get();
    
        return view('proprio.mesclients', compact('attributions', 'biens'));
    }
    


    public function contratPDF($id)
    {
        $attribution = Attribution::with([
            'bien:id,titre,proprietaire_id,prix',
            'client:id,name,surname,telephone'
        ])
        ->whereHas('bien', fn($q) => $q->where('proprietaire_id', auth()->id()))
        ->findOrFail($id);

        $pdf = Pdf::loadView('proprio.pdfs.contrat', compact('attribution'))
                ->setPaper('a4', 'portrait');

        return $pdf->download('achat_'.$attribution->id.'.pdf');
    }

}
