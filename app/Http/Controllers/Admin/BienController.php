<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bien;
use App\Models\BienMedia;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BienController extends Controller
{
    public function index()
    {
        $biens = Bien::with(['proprietaire','medias'])->latest()->get();
        $proprietaires = User::where('role','proprietaire')->get();
        $categories = \App\Models\Category::all();
    
        return view('admin.biens.index', compact('biens','proprietaires','categories'));
    }    

    public function create()
    {
        $biens = Bien::with(['proprietaire','medias'])->latest()->get();
        $proprietaires = User::where('role','proprietaire')->orderBy('name')->get();
        $categories = \App\Models\Category::all();

        return view('admin.biens.create', compact('biens','proprietaires','categories'));
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

        return redirect()->route('admin.biens.index')
                        ->with('success', 'Bien ajouté avec succès.');
    }


    public function edit(Bien $bien)
    {
        $proprietaires = User::where('role','proprietaire')->get();
        $categories = \App\Models\Category::all();

        return view('admin.biens.edit', compact('bien','proprietaires', 'categories'));
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
            'stock'               => 'required|numeric|min:1',
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

    public function louer(Request $request, Bien $bien)
    {
        if ($bien->statut === 'disponible') {

            $bien->update([
                'statut'   => 'attribue',
                'date_fin' => now()->addMonths(6),
            ]);

            return redirect()->back()->with('success', 'Le bien a été loué avec succès');
        }

        return redirect()->back()->with('error', 'Ce bien est déjà loué');
    }
}
