<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ParametreController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Détermine le layout selon le rôle
        if ($user->role === 'admin') {
            $layout = 'layouts.admin';
        } elseif ($user->role === 'proprietaire') {
            $layout = 'layouts.proprio';
        } else {
            $layout = 'layouts.client';
        }

        return view('parametres.index', compact('user', 'layout'));
    }


    public function updatePhoto(Request $request)
    {
        $request->validate([
            'profil' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $user = Auth::user();

        // Supprimer l’ancienne photo si elle existe
        if ($user->profil && Storage::exists('public/' . $user->profil)) {
            Storage::delete('public/' . $user->profil);
        }

        // Stocker la nouvelle photo
        $path = $request->file('profil')->store('photos_profil', 'public');

        // Mettre à jour le chemin dans la BDD
        $user->profil = $path;
        $user->save();

        return back()->with('success', 'Photo de profil mise à jour avec succès.');
    }
}
