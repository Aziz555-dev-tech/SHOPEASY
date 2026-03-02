<?php

namespace App\Http\Controllers\Proprietaire;

use App\Http\Controllers\Controller;
use App\Models\Paiement;
use Illuminate\Support\Facades\Auth;

class PaiementController extends Controller
{
    public function index()
    {
        $proprietaireId = Auth::id();

        $paiements = Paiement::whereHas('attribution.bien', function ($query) use ($proprietaireId) {
            $query->where('proprietaire_id', $proprietaireId);
        })
        ->with([
            'attribution.bien',
            'attribution.client',
            'client'
        ])
        ->latest()
        ->get();

        return view('proprio.paiements', compact('paiements'));
    }
}
