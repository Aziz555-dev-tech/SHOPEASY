<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Message;
use App\Models\Conversation;

class MessageController extends Controller
{
    /**
     * Récupérer les messages d’une conversation
     */
    public function index($conversationId)
    {
        $conversation = Conversation::findOrFail($conversationId);
        $user = Auth::user();

        if (!in_array($user->id, [$conversation->participant1_id, $conversation->participant2_id])) {
            abort(403, 'Accès refusé');
        }

        $messages = $conversation->messages()->with('expediteur')->get();

        return response()->json($messages);
    }

    /**
     * Envoyer un message
     */
    
    public function store(Request $request, $conversationId)
    {
        $conversation = Conversation::findOrFail($conversationId);
        $user = Auth::user();

        if (!in_array($user->id, [$conversation->participant1_id, $conversation->participant2_id])) {
            return response()->json(['success' => false, 'error' => 'Accès refusé'], 403);
        }

        $contenu = $request->input('contenu') ?? $request->json('contenu');

        if (!$contenu) {
            return response()->json(['success' => false, 'error' => 'Message vide'], 422);
        }       

        $message = Message::create([
            'conversation_id' => $conversation->id,
            'expediteur_id' => $user->id,
            'contenu' => $contenu,
            'lu' => false,
        ]);

        $conversation->touch();

        return response()->json([
            'success' => true,
            'message' => $message->load('expediteur')
        ]);
    }

}
