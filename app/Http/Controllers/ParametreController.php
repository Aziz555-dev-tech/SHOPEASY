<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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
        } elseif ($user->role === 'livreur') {
            $layout = 'layouts.livreur';
        } 
        else {
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

    public function updateInfo(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        $user->email = $request->email;
        $user->save();

        return back()->with('success', 'Email mis à jour avec succès.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Mot de passe actuel incorrect']);
        }

        $user->password = Hash::make($request->password);
        $user->must_change_password = false;
        $user->save();

        return back()->with('success', 'Mot de passe changé avec succès.');
    }

}



