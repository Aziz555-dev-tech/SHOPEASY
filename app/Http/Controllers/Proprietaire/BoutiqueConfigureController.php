<?php

namespace App\Http\Controllers\Proprietaire;

use App\Http\Controllers\Controller;
use App\Models\Boutique;
use Illuminate\Http\Request;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class BoutiqueConfigureController extends Controller
{
    public function index()
    {
        $boutique = Boutique::where('proprietaire_id', auth()->id())->firstOrFail();

        return view('proprio.boutique.config', compact('boutique'));
    }

    public function update(Request $request, Boutique $boutique)
    {
        // Sécurité : empêcher un proprio de modifier une autre boutique
        if ($boutique->proprietaire_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'nom' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:boutiques,slug,' . $boutique->id,
            'description' => 'required|string',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Upload logo
        if ($request->hasFile('logo')) {
            if ($boutique->logo) {
                Storage::delete($boutique->logo);
            }
            $boutique->logo = $request->file('logo')->store('boutiques', 'public');
        }

        $boutique->update([
            'nom' => $request->nom,
            'slug' => Str::slug($request->slug),
            'description' => $request->description,
            'statut' => 'active',
        ]);

        return redirect()
            ->route('proprietaire.dashboard')
            ->with('success', 'Boutique configurée avec succès 🎉');
    }

    public function updateLocation(Request $request)
    {
        $request->validate([
            'adresse'   => 'required|string|max:255',
            'latitude'  => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        if (!$request->latitude || !$request->longitude) {
            return back()->with('error', 'Veuillez sélectionner la position sur la carte.');
        }        

        $boutique = Boutique::where('proprietaire_id', auth()->id())->firstOrFail();

        $boutique->update([
            'adresse'   => $request->adresse,
            'latitude'  => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        return back()->with('success', 'Localisation enregistrée avec succès ');
    }

}


