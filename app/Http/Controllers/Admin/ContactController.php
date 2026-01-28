<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    // Afficher les messages dans le dashboard admin
    public function index()
    {
        $contacts = Contact::latest()->paginate(10);
        return view('admin.contact.index', compact('contacts'));
    }

    // Stocker un nouveau message (depuis la page publique)
    public function store(Request $request)
    {
        $data = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email',
            'tel' => 'nullable|string|max:50',
            'prestation' => 'nullable|string|max:255',
            'message' => 'required|string',
        ]);

        Contact::create($data);

        return redirect()->back()->with('success', 'Votre message a bien été envoyé !');
    }
}

