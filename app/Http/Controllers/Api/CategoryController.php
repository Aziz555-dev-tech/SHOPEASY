<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\SousCategory;
use App\Models\SubType;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    // 🔹 Récupérer toutes les catégories
    public function index()
    {
        return response()->json(Category::all());
    }

    // 🔹 Récupérer les sous-catégories d'une catégorie
    public function sousCategories($id)
    {
        $category = Category::find($id);
        if (!$category) {
            return response()->json(['error' => 'Catégorie non trouvée'], 404);
        }

        return response()->json($category->sousCategories);
    }

    // 🔹 Récupérer les subtypes d'une sous-catégorie
    public function subTypes($id)
    {
        $sousCategory = SousCategory::find($id);
        if (!$sousCategory) {
            return response()->json(['error' => 'Sous-catégorie non trouvée'], 404);
        }

        return response()->json($sousCategory->subTypes);
    }
}
