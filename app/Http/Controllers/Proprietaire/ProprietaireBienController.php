<?php

namespace App\Http\Controllers\Proprietaire;

use App\Http\Controllers\Controller;
use App\Models\Bien;
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
    public function index()
    {
        $userId = auth()->id(); // ID du propriétaire connecté
        
        $biens = Bien::with(['proprietaire', 'medias'])
                    ->where('proprietaire_id', $userId) // Filtre sur le propriétaire connecté
                    ->latest()
                    ->get();

        $proprietaires = User::where('role', 'proprietaire')->get();
        $categories = Category::all();

        return view('proprio.biens.index', compact('biens', 'proprietaires', 'categories'));
    }


    public function create()
    {
        $categories = Category::all();
        return view('proprio.biens.create', compact('categories'));
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
            'type'               => 'nullable|in:vente,location',
            'proprietaire_id'    => 'required|exists:users,id',

            'medias.*'           => 'nullable|mimes:jpg,jpeg,png,gif,mp4,mov,avi|max:20000',
        ]);

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
        $categories = Category::all();
        return view('proprio.biens.edit', compact('categories', 'bien'));
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
            'type'               => 'nullable|in:vente,location',
    
            'proprietaire_id'    => 'required|exists:users,id',
    
            'medias.*'           => 'nullable|mimes:jpg,jpeg,png,gif,mp4,mov,avi|max:20000',
        ]);
    
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
