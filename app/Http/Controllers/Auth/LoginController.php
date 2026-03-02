<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function showClientLoginForm()
    {
        return view('auth.clientLogin');
    }

    public function showLoginFormLivreur()
    {
        return view('auth.livreurLogin');
    }

    public function loginLivreur(Request $request)
    {
        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');
        if(Auth::attempt($credentials, $request->filled('remember'))) {
            // On vérifie si l'user doit changer son mot de passe
            if(Auth::user()->must_change_password)
            {
                return redirect()->route('password.change.form');
            }

            return redirect()->intended('livreur/dashboard');
        }

        return back()->with('error', 'Email ou mot de passe incorrecte.');
    }



    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');
        if(Auth::attempt($credentials, $request->filled('remember'))) {
            // On vérifie si l'user doit changer son mot de passe
            if(Auth::user()->must_change_password)
            {
                return redirect()->route('password.change.form');
            }

            return redirect()->intended('proprietaire/dashboard');
        }

        return back()->with('error', 'Email ou mot de passe incorrecte.');
    }

    public function clientLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');
        if(Auth::attempt($credentials, $request->filled('remember')))
        {
            return redirect()->route('client.dashboard');
        }

        return back()->with('error', 'Téléphone ou mot de passe incorrect');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    public function clientLogout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('client.login');
    }


}
