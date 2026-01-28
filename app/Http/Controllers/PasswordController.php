<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PasswordController extends Controller
{
    public function showChangeForm() {
        return view('auth.change-password');
    }

    public function update(Request $request) {
        $request->validate([
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        

        $user = auth()->user();
        $user->password = Hash::make($request->password);
        $user->must_change_password = false;
        $user->save();

        return redirect()->route('proprietaire.dashboard')->with('success', 'Mot de passe modifié avec succès');
    }
}

