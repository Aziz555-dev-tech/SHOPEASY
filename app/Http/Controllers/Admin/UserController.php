<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Rules\Password;
use Illuminate\Validation\Rules;

use Illuminate\Support\Facades\Mail;
use App\Mail\TemporaryPasswordMail;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Récupérer tous les utilisateurs et biens ou juste les derniers
        $users = User::latest()->take(12)->get();

        // Passer à la vue
        return view('admin.users', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'surname' => ['required', 'string', 'max:255'],
            'telephone' => ['required', 'unique:users,telephone'],
            'email' => ['required', 'string', 'lowercase', 'unique:users,email', 'max:255'],
        ]);
    
        // Générer le mot de passe temporaire
        $passwordTemp = Str::random(8);
    
        $user = User::create([
            'name' => $request->name,
            'surname' => $request->surname,
            'telephone' => $request->telephone,
            'email' => strtolower(trim($request->email)),
            'password' => Hash::make($passwordTemp),
            'role' => 'proprietaire',
            'must_change_password' => true,
        ]);
    
        // Envoi par email
        Mail::to($user->email)->send(new TemporaryPasswordMail($passwordTemp));
    
        // Flash dans la session (conservation du fonctionnement existant)
        session()->flash('password_temp', $passwordTemp);
    
        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur créé avec succès. Mot de passe envoyé par email au '.$user->email, $passwordTemp);
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
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
