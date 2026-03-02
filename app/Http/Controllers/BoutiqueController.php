<?php

namespace App\Http\Controllers;

use App\Models\Boutique;
use App\Models\Category;
use Illuminate\Http\Request;

class BoutiqueController extends Controller
{
    public function index(Request $request)
    {
        $boutiques = Boutique::where('active', true)
            ->withCount('biens')
            ->paginate(24);

        return view('boutiques.index', compact('boutiques'));
    }

    public function show($slug, Request $request)
    {
        $boutique = Boutique::where('slug', $slug)
            ->where('active', true)
            ->firstOrFail();

        $query = $boutique->biens()
            ->with(['categorie', 'sousCategorie', 'subType', 'medias'])
            ->where('stock', '>', 0);

        // Réutiliser EXACTEMENT la logique de /catalogue
        if ($request->categorie) {
            $query->whereHas('categorie', fn($q) =>
                $q->where('slug', $request->categorie)
            );
        }

        if ($request->search) {
            $query->where('titre', 'like', '%'.$request->search.'%');
        }

        $biens = $query->paginate(12)->withQueryString();

        $categories = Category::with('sousCategories.subTypes')->get();

        return view('boutiques.show', compact('boutique', 'biens', 'categories'));
    }

    public function editLocation()
    {
        $boutique = auth()->user()->boutique;

        return view('proprio.boutique.localisation', compact('boutique'));
    }
    
}
