@php
    $layout = match(Auth::user()->role) {
        'proprietaire' => 'layouts.proprietaire',
        'client' => 'layouts.client',
        'admin' => 'layouts.admin',
        default => 'layouts.app',
    };

    $user = Auth::user();
    $interlocuteur = null;

    // Cas où l'utilisateur connecté est ADMIN
    if ($user->role === 'admin') {
        if ($conversation->client_id && $conversation->client) {
            // Discussion avec un client
            $interlocuteur = $conversation->client;
        } elseif ($conversation->proprietaire_id && $conversation->proprietaire) {
            // Discussion avec un propriétaire
            $interlocuteur = $conversation->proprietaire;
        }
    } 
    // Cas où l'utilisateur connecté est PROPRIÉTAIRE
    elseif ($user->role === 'proprietaire') {
        if ($conversation->client_id && $conversation->client) {
            $interlocuteur = $conversation->client;
        } else {
            $interlocuteur = \App\Models\User::where('role', 'admin')->first();
        }
    } 
    // Cas où l'utilisateur connecté est CLIENT
    elseif ($user->role === 'client') {
        if ($conversation->proprietaire_id && $conversation->proprietaire) {
            $interlocuteur = $conversation->proprietaire;
        } else {
            $interlocuteur = \App\Models\User::where('role', 'admin')->first();
        }
    }
@endphp

@extends($layout)

@section('title', $conversation->titre ?? 'Conversation')

@section('content')
<div class="container mt-4">
    <h4 class="mb-3">
        {{ $conversation->titre ?? 'Conversation' }}
        @if($interlocuteur)
            — <strong>{{ $interlocuteur->name ?? '' }} {{ $interlocuteur->surname ?? '' }}</strong>
            <span class="text-muted">({{ ucfirst($interlocuteur->role ?? '') }})</span>
        @endif
    </h4>

    <div class="border rounded p-3 mb-3" style="height:400px; overflow-y:auto;">
        @foreach($conversation->messages as $msg)
            <div class="mb-2 d-flex {{ $msg->expediteur_id == auth()->id() ? 'justify-content-end' : 'justify-content-start' }}">
                <div class="p-2 rounded {{ $msg->expediteur_id == auth()->id() ? 'bg-primary text-white' : 'bg-light' }}">
                    {{ $msg->contenu }}
                    <br>
                    <small class="text-muted">{{ $msg->created_at->format('d/m/Y H:i') }}</small>
                </div>
            </div>
        @endforeach
    </div>

    <form action="{{ route(Auth::user()->role . '.messagerie.send', $conversation) }}" method="POST">
        @csrf
        <div class="input-group">
            <input type="text" name="contenu" class="form-control" placeholder="Écrire un message..." required>
            <button class="btn btn-primary"><i class="bi bi-send-fill"></i></button>
        </div>
    </form>    
</div>
@endsection
