<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attribution;
use App\Models\Bien;
use App\Models\User;
use App\Notifications\AttributionCreee;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttributionController extends Controller
{

    public function index()
    {
        $this->synchroniserBiens();

        $attributions = Attribution::with(['bien', 'client'])->get();
        return view('admin.attributions.index', compact('attributions'));
    }

    public function create()
    {
        $biens = Bien::where('statut', 'disponible')->get();
        $clients = User::where('role', 'client')->get();
        return view('admin.attributions.create', compact('biens', 'clients'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'bien_id' => 'required|exists:biens,id',
            'client_id' => 'required|exists:users,id',
            'prix' => 'required|numeric|min:0',
            'proprietaire_id' => 'nullable|exists:users,id' ]);

        return DB::transaction(function () use ($validated) {

            $bien = Bien::findOrFail($validated['bien_id']);
        
            if ($bien->statut === 'attribue') {
                return back()->with('error', 'Ce bien est déjà attribué.');
            }
        
            // Update
            $bien->update(['statut' => 'attribue']);
        
            // Ajout infos
            $validated['date_attribution'] = now()->toDateString();
            $validated['statut_paiement']  = 'en_attente';
        
            $attribution = Attribution::create($validated);
        
            // Notifications
            if ($attribution->client) {
                $attribution->client->notify(new AttributionCreee($attribution));
            }
            if ($bien->proprietaire) {
                $bien->proprietaire->notify(new AttributionCreee($attribution));
            }

            $attributions = Attribution::with(['bien', 'client'])->get();
        
            return redirect()->route('admin.attributions.index')
                             ->with('success', 'Attribution créée avec succès.');
        });
        
    }
    

    private function synchroniserBiens()
    {
        $biens = Bien::with('attributions')->get();
    
        foreach ($biens as $bien) {
    
            // Si le bien a une attribution mais est marqué disponible → on corrige
            if ($bien->attributions->count() > 0 && $bien->statut === 'disponible') {
                $bien->update(['statut' => 'attribue']);
            }
    
            // Si le bien n’a plus aucune attribution mais est marqué attribué → on corrige
            if ($bien->attributions->count() === 0 && $bien->statut === 'attribue') {
                $bien->update(['statut' => 'disponible']);
            }
        }
    }
    


    public function annuler(Attribution $attribution)
    {
        // Vérifier que c’est bien une attribution à venir
        if ($attribution->date_debut <= now()) {
            return redirect()->route('admin.attributions.index')
                ->with('error', 'Seules les attributions à venir peuvent être annulées.');
        }

        // Remettre le bien en disponible
        if ($attribution->bien) {
            $attribution->bien->update(['statut' => 'disponible']);
        }

        // Supprimer l’attribution
        $attribution->delete();

        return redirect()->route('admin.attributions.index')
            ->with('success', 'Attribution annulée avec succès.');
    }


}
