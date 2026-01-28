<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Conversation;
use App\Models\User;

class ConversationController extends Controller
{
    /**
     * Afficher toutes les conversations de l’utilisateur connecté
     */
    public function index()
    {
        $user = Auth::user();
    
        // Utilisateurs autorisés selon le rôle
        $allowedUsers = match($user->role) {
            'admin' => User::where('id', '!=', $user->id)->get(),
            'client' => User::whereIn('role', ['admin'])->get(),
            'proprietaire' => User::whereIn('role', ['admin'])->get(),
            default => collect(),
        };
    
        // Récupérer toutes les conversations existantes où l'utilisateur est participant
        $conversations = Conversation::with(['participantOne', 'participantTwo', 'messages'])
            ->where('participant1_id', $user->id)
            ->orWhere('participant2_id', $user->id)
            ->latest('updated_at')
            ->get();
    
        $folder = $this->getViewFolder($user);
    
        return view("$folder.conversations.index", [
            'conversations' => $conversations,
            'user' => $user,
            'users' => $allowedUsers, // pour démarrer de nouvelles conversations
        ]);
    }
    

    /**
     * Créer ou récupérer une conversation entre deux utilisateurs
     */
    public function start($interlocuteurId)
    {
        $user = Auth::user();

        // Vérifie si l'interlocuteur est autorisé
        $allowedUsers = $this->getAllowedUsers($user);
        if (!$allowedUsers->pluck('id')->contains($interlocuteurId)) {
            abort(403, 'Vous ne pouvez pas démarrer une conversation avec cet utilisateur.');
        }

        // Vérifie si une conversation existe déjà
        $conversation = Conversation::where(function($q) use ($user, $interlocuteurId) {
                $q->where('participant1_id', $user->id)
                  ->where('participant2_id', $interlocuteurId);
            })
            ->orWhere(function($q) use ($user, $interlocuteurId) {
                $q->where('participant1_id', $interlocuteurId)
                  ->where('participant2_id', $user->id);
            })
            ->first();

        if (!$conversation) {
            $conversation = Conversation::create([
                'participant1_id' => $user->id,
                'participant2_id' => $interlocuteurId,
                'titre' => null,
            ]);
        }

        return redirect()->route('conversations.show', $conversation->id);
    }

    /**
     * Afficher une conversation précise
     */

    public function show($id)
    {
        $user = Auth::user();

        $conversation = Conversation::with(['messages.expediteur', 'participantOne', 'participantTwo'])
            ->findOrFail($id);

        if (!in_array($user->id, [$conversation->participant1_id, $conversation->participant2_id])) {
            abort(403, 'Accès refusé à cette conversation');
        }

        // Marquer les messages reçus comme lus
        $conversation->messages()
            ->where('expediteur_id', '!=', $user->id)
            ->where('lu', false)
            ->update(['lu' => true]);

        $interlocuteur = $this->getInterlocuteur($conversation, $user);
        $folder = $this->getViewFolder($user);

        return view("$folder.conversations.show", compact('conversation', 'user', 'interlocuteur'));
    }


    /**
     * Détermine et retourne l’interlocuteur de la conversation
     */
    private function getInterlocuteur(Conversation $conversation, $user)
    {
        return $conversation->participant1_id === $user->id
            ? $conversation->participantTwo
            : $conversation->participantOne;
    }

    /**
     * Détermine le dossier de vue selon le rôle
     */
    private function getViewFolder($user)
    {
        return match($user->role) {
            'admin' => 'admin',
            'proprietaire' => 'proprio',
            default => 'client',
        };
    }

    /**
     * Récupère les utilisateurs avec lesquels l'utilisateur connecté peut communiquer
     */
    private function getAllowedUsers($user)
    {
        return match($user->role) {
            'admin' => User::where('id', '!=', $user->id)->get(),
            'client' => User::whereIn('role', ['proprietaire', 'admin'])->get(),
            'proprietaire' => User::whereIn('role', ['client', 'admin'])->get(),
            default => collect(),
        };
    }
}
