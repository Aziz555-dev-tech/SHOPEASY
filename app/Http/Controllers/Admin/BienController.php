<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bien;
use App\Models\BienMedia;
use App\Models\Boutique;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BienController extends Controller
{
    public function index(Request $request)
    {
        $biens = Bien::with(['proprietaire','medias'])->latest()->get();
        $proprietaires = User::where('role','proprietaire')->get();
        $categories = \App\Models\Category::all();

        $query = Bien::with(['proprietaire', 'medias']);

                // FILTRE
                if ($request->disponibilite == 'disponible') {
                    $query->where('stock', '>', 0);
                }
        
                if ($request->disponibilite == 'faible') {
                    $query->whereBetween('stock', [1, 4]);
                }
        
                if ($request->disponibilite == 'epuise') {
                    $query->where('stock', 0);
                }
        
                $biens = $query->latest()->get();
        
                $proprietaires = User::where('role', 'proprietaire')->get();
                $categories = Category::all();
        
        return view('admin.biens.index', compact('biens','proprietaires','categories'));
    }    

    public function create()
    {
        $biens = Bien::with(['proprietaire','medias'])->latest()->get();

        $boutiques = Boutique::all();
        $proprietaires = User::where('role','proprietaire')->orderBy('name')->get();
        
        $categories = \App\Models\Category::all();

        return view('admin.biens.create', compact('biens', 'boutiques', 'proprietaires','categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'titre'              => 'required|string|max:255',
            'description'        => 'nullable|string',

            'categorie_id'       => 'required|exists:categories,id',
            'sous_categorie_id'  => 'required|exists:sous_categories,id',
            'sub_type_id'        => 'nullable|exists:sub_types,id',

            'adresse'            => 'nullable|string|max:255',
            'prix'               => 'required|numeric|min:0',
            'stock'               => 'required|numeric|min:1',
            'type'               => 'nullable|in:vente,location',
            'boutique_id'       => 'required|exists:boutiques,id',
            'medias.*'           => 'nullable|mimes:jpg,jpeg,png,gif,mp4,mov,avi|max:20000',
        ]);

        // On récupère la boutique donc l'id de son propriétaire aussi est déjà impliqué
        $boutique = Boutique::findOrFail($request->boutique_id);

        // On passe l'id du proprietaire dans la requete
        $data['proprietaire_id'] = $boutique->proprietaire_id;

        $data['type'] = $data['type'] ?? 'vente';

        $bien = Bien::create($data);

        // Upload medias
        if ($request->hasFile('medias')) {
            foreach ($request->file('medias') as $file) {
                $type = str_contains($file->getMimeType(), 'video') ? 'video' : 'image';

                $bien->medias()->create([
                    'type' => $type,
                    'path' => $file->store('biens', 'public'),
                ]);
            }
        }

        return redirect()->route('admin.biens.index')
                        ->with('success', 'Bien ajouté avec succès.');
    }


    public function edit(Bien $bien)
    {
        $proprietaires = User::where('role','proprietaire')->get();
        $categories = \App\Models\Category::all();
        $boutiques = Boutique::all();

        return view('admin.biens.edit', compact('bien','proprietaires', 'categories', 'boutiques'));
    }

    public function update(Request $request, Bien $bien)
    {
        $validated = $request->validate([
            'titre'              => 'required|string|max:255',
            'description'        => 'nullable|string',
    
            'categorie_id'       => 'required|exists:categories,id',
            'sous_categorie_id'  => 'required|exists:sous_categories,id',
            'sub_type_id'        => 'nullable|exists:sub_types,id',
    
            'adresse'            => 'nullable|string|max:255',
            'prix'               => 'required|numeric|min:0',
            'stock'               => 'required|numeric|min:0',
            'type'               => 'nullable|in:vente,location',
    
            'boutique_id'    => 'required|exists:boutiques,id',
    
            'medias.*'           => 'nullable|mimes:jpg,jpeg,png,gif,mp4,mov,avi|max:20000',
        ]);

        $boutique = Boutique::findOrFail($request->boutique_id);

        $validated['proprietaire_id'] = $boutique->proprietaire_id;
    
        $bien->update($validated);
    
        // Suppression anciens médias
        if ($request->has('delete_medias')) {
            foreach ($request->delete_medias as $mediaId) {
                $media = $bien->medias()->find($mediaId);
                if ($media) {
                    Storage::disk('public')->delete($media->path);
                    $media->delete();
                }
            }
        }
    
        // Ajout nouveaux fichiers
        if ($request->hasFile('medias')) {
            foreach ($request->file('medias') as $file) {
                $type = str_contains($file->getMimeType(), 'video') ? 'video' : 'image';
    
                $bien->medias()->create([
                    'path' => $file->store('biens', 'public'),
                    'type' => $type,
                ]);
            }
        }
    
        return redirect()->route('admin.biens.index')
                         ->with('success', 'Bien mis à jour avec succès.');
    }
    

    public function destroy(Bien $bien)
    {
        // Supprimer les fichiers médias associés
        foreach ($bien->medias as $media) {
            Storage::disk('public')->delete($media->path);
        }

        $bien->delete();

        return redirect()->route('admin.biens.index')
                         ->with('success', 'Bien supprimé.');
    }

}
