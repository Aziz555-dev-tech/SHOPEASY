<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if(Auth::attempt(array_merge($credentials, ['role' => 'admin'])))
        {
            $request->session()->regenerate();

            //Vérifier si c'est le mot de passe par défaut
            if($request->password === 'password') {
                return redirect()->route('admin.password.change');
            }

            return redirect()->route('admin.dashboard.index');
        }

        return back()->withErrors([
            'email' => 'Identifiants incorrects ou non autorisé.'
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/admin.login');
    }
}
