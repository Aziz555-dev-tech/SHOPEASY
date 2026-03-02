<?php

namespace App\Http\Controllers\Proprietaire;

use App\Http\Controllers\Controller;
use App\Models\Bien;
use App\Models\Boutique;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProprietaireBienController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $userId = auth()->id();

        $query = Bien::with(['proprietaire', 'medias'])
                    ->where('proprietaire_id', $userId); // Filtre sur le propriétaire connecté

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

        $stats = [
            'total' => Bien::where('proprietaire_id',$userId)->count(),
            'disponible' => Bien::where('proprietaire_id',$userId)->where('stock','>',0)->count(),
            'faible' => Bien::where('proprietaire_id',$userId)->whereBetween('stock',[1,4])->count(),
            'epuise' => Bien::where('proprietaire_id',$userId)->where('stock',0)->count(),
        ];
        

        return view('proprio.biens.index', compact('biens', 'proprietaires', 'categories', 'stats'));
    }



    public function create()
    {
        $boutiques = Boutique::all();
        $categories = Category::all();
        return view('proprio.biens.create', compact('categories', 'boutiques'));
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
            'boutique_id'   => 'required|exists:boutiques,id',
            'medias.*'           => 'nullable|mimes:jpg,jpeg,png,gif,mp4,mov,avi|max:20000',
        ]);

        $boutique = Boutique::findOrFail($request->boutique_id);

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

        return redirect()->route('proprietaire.biens.index')
                        ->with('success', 'Bien ajouté avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Bien $bien)
    {
        $boutiques = Boutique::all();
        $categories = Category::all();
        return view('proprio.biens.edit', compact('categories', 'bien', 'boutiques'));
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
    
            'boutique_id'   => 'required|exists:boutiques,id',    
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
    
        return redirect()->route('proprietaire.biens.index')->with('success', 'Bien mis à jour avec succès.');
    }
    

    public function destroy(Bien $bien)
    {
        // Supprimer les fichiers médias associés
        foreach ($bien->medias as $media) {
            Storage::disk('public')->delete($media->path);
        }

        $bien->delete();

        return redirect()->route('proprietaire.biens.index')->with('success', 'Bien supprimé.');
    }
}
