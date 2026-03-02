@extends('layouts.' . Auth::user()->role)

@section('title', 'Conversation avec ' . ($interlocuteur->name ?? 'Utilisateur'))

@section('content')
<div class="container py-4">
    <div class="card shadow-lg">
        <div class="card-header text-white d-flex justify-content-between align-items-center" style="background-color: #005078;">
            <h5 class="mb-0">Discussion avec {{ $interlocuteur->name ?? 'Utilisateur' }}</h5>
            <a href="{{ route('conversations.index') }}" class="btn btn-sm"><h2 class="text-light"><i class="bi bi-arrow-left-circle"></i></h2></a>
        </div>

        <div class="card-body" id="messagesBox" style="height: 450px; overflow-y: auto;">
            @foreach($conversation->messages as $message)
                <div class="mb-3 d-flex {{ $message->expediteur_id === Auth::id() ? 'justify-content-end' : 'justify-content-start' }}">
                    <div class="p-3 rounded-3 {{ $message->expediteur_id === Auth::id() ? 'bg-primary text-white' : 'bg-light text-dark' }}" style="max-width: 70%;">
                        <p class="mb-1">{{ $message->contenu }}</p>
                        <small class="d-block text-end text-muted" style="font-size: 0.75rem;">
                            <i>{{ $message->created_at->format('d/m/Y H:i') }}</i>
                        </small>  
                    </div>
                </div>
            @endforeach
        </div>

        <div class="card-footer">
            <form id="sendMessageForm" data-conversation-id="{{ $conversation->id }}">
                @csrf
                <div class="input-group">
                    <input type="text" id="messageInput" class="form-control" placeholder="Écrire un message...">
                    <button class=" rounded rounded-2 px-3" style="background-color: #005078; border: none;" type="submit"><h2 class="text-light"><i class="bi bi-send-check-fill"></i></h2></button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('sendMessageForm');
    const input = document.getElementById('messageInput');
    const box = document.getElementById('messagesBox');
    const conversationId = form.getAttribute('data-conversation-id');

    form.addEventListener('submit', function (e) {
        e.preventDefault();
        const message = input.value.trim();
        if (!message) return;

        fetch(`/conversations/${conversationId}/messages`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ contenu: message })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                const msg = data.message;
                const html = `
                    <div class="mb-3 d-flex justify-content-end">
                        <div class="p-3 rounded-3 bg-primary text-white" style="max-width: 70%;">
                            <p class="mb-1">${msg.contenu}</p>
                            <small class="d-block text-end text-muted" style="font-size: 0.75rem;">
                                ${new Date(msg.created_at).toLocaleTimeString([], {hour: '2-digit', minute: '2-digit'})}
                            </small>
                        </div>
                    </div>`;
                box.insertAdjacentHTML('beforeend', html);
                input.value = '';
                box.scrollTop = box.scrollHeight;
            }
        })
        .catch(console.error);
    });
});
</script>
@endsection
