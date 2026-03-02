<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

use App\Models\Category;
use App\Models\SousCategory;
use App\Models\SubType;

use App\Models\Bien;

class ViewController extends Controller
{
    public function accueil()
    {
        return view('index');
    }

    public function apropos()
    {
        return view('apropos');
    }


    public function boutiques()
    {
        return view('boutiques');
    }

    public function partenaire()
    {
        return view('nospartenaire');
    }

    public function contact()
    {
        return view('contact');
    }

    public function faq() 
    {
        return view('faq');
    }


    

    public function catalogue(Request $request)
    {
        // Récupérer les filtres depuis la requête GET
        $categorieSlug    = $request->get('categorie');  // slug de Category
        $sousCategorieSlug= $request->get('sous');      // slug de SousCategory (optionnel)
        $subTypeSlug      = $request->get('etat');      // slug de SubType
        $type             = $request->get('type');      // type de vente ou location 

        // Base query avec relations
        $query = Bien::with(['categorie', 'sousCategorie', 'subType', 'medias'])
        ->where('stock', '>', 0); // Uniquement les biens dont le stock est plus que 0

        // Filtrer par catégorie
        if ($categorieSlug) {
            $query->whereHas('categorie', function ($q) use ($categorieSlug) {
                $q->where('slug', $categorieSlug);
            });
        }

        // Filtrer par sous-catégorie si fourni
        if ($sousCategorieSlug) {
            $query->whereHas('sousCategorie', function ($q) use ($sousCategorieSlug) {
                $q->where('slug', $sousCategorieSlug);
            });
        }

        // Filtrer par subType si fourni
        if ($subTypeSlug) {
            $query->whereHas('subType', function ($q) use ($subTypeSlug) {
                $q->where('slug', $subTypeSlug);
            });
        }

        // Filtrer par type si défini
        if ($type) {
            $query->where('type', $type);
        }

        // Pagination
        $biens = $query->orderBy('created_at', 'desc')->paginate(12)->withQueryString();


        // Tous les filtres pour la vue (si nécessaire pour accordéon)
        $categories = Category::with('sousCategories.subTypes')->get();

        return view('catalogue', compact('biens', 'categories'));
    }

    
    

    public function actualite()
    {
        
        $posts = Post::where('publie', true)->latest()->paginate(6);
        $recentPosts = Post::where('publie', true)->latest()->take(5)->get();

        return view('actualite', compact('posts', 'recentPosts'));
    }

}
