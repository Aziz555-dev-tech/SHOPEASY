@extends('layouts.' . Auth::user()->role)

@section('title', 'Mes Conversations')

@section('content')
<div class="container py-4">

    {{-- ==================== SECTION : NOUVELLE CONVERSATION ==================== --}}

    <div class="user-carousel d-flex align-items-center gap-3 overflow-auto pb-3">
        @forelse($users as $u)
            <div class="text-center user-item flex-shrink-0" style="width: 90px;">
                <a href="javascript:void(0)" 
                   class="text-decoration-none text-dark user-preview"
                   data-name="{{ $u->name }}"
                   data-surname="{{ $u->surname }}"
                   data-role="{{ ucfirst($u->role) }}"
                   data-avatar="{{ $u->profil ? asset('storage/' . $u->profil) : asset('assets/images/PROFIL.png') }}"
                   data-id="{{ $u->id }}">

                   <img src="{{ $u->profil ? asset('storage/' . $u->profil) : asset('assets/images/PROFIL.png') }}"
                   alt="Photo de profil"
                   class="rounded-circle"
                   width="50"
                   height="50">  

                <div class="small fw-semibold">{{ Str::limit($u->name, 10) }}</div>
                <div class="text-muted small">{{ ucfirst($u->role) }}</div>
                </a>
            </div>
        @empty
            <div class="text-muted fst-italic">Aucun utilisateur disponible</div>
        @endforelse
    </div>

    <hr class="my-4">

{{-- ==================== SECTION : CONVERSATIONS EXISTANTES ==================== --}}
@if($conversations->isEmpty())
    <div class="alert alert-info">Aucune conversation pour le moment.</div>
@else
    <ul class="list-group shadow-sm">
        @foreach($conversations as $conv)
            @php
                $interlocuteur = $conv->otherParticipant($user);
                $unreadCount = $conv->messages()
                    ->where('expediteur_id', '!=', $user->id)
                    ->where('lu', false)
                    ->count();
            @endphp

            <a href="{{ route('conversations.show', $conv->id) }}" 
               class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">

                    {{-- Avatar de l’interlocuteur --}}
                    <img src="{{ $interlocuteur && $interlocuteur->profil 
                        ? asset('storage/' . $interlocuteur->profil) 
                        : asset('assets/images/PROFIL.png') }}"
                        alt="Photo de profil"
                        class="rounded-circle me-3"
                        width="50"
                        height="50">

                    {{-- Nom et dernier message --}}
                    <div>
                        <h6 class="mb-0 fw-semibold">
                            {{ $interlocuteur->name ?? 'Utilisateur supprimé' }}
                        </h6>
                        <small class="text-muted d-block">
                            {{ $conv->messages->last()?->contenu 
                                ? Str::limit($conv->messages->last()->contenu, 40) 
                                : 'Aucun message encore' }}
                        </small>
                        @if($conv->messages->last())
                            <small class="text-muted">
                                {{ $conv->messages->last()->created_at->format('d/m H:i') }}
                            </small>
                        @endif
                    </div>
                </div>

                {{-- Badge pour les messages non lus --}}
                @if($unreadCount > 0)
                    <span class="badge bg-danger">{{ $unreadCount }}</span>
                @endif
            </a>
        @endforeach
    </ul>
@endif

</div>


<style>
.user-carousel::-webkit-scrollbar {
    height: 8px;
}
.user-carousel::-webkit-scrollbar-thumb {
    background: #ccc;
    border-radius: 10px;
}
.user-carousel {
    scrollbar-color: #ccc transparent;
}
.user-item:hover img {
    transform: scale(1.08);
    transition: transform 0.3s ease;
}
</style>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const modal = new bootstrap.Modal(document.getElementById('userModal'));
        const avatar = document.getElementById('modalAvatar');
        const name = document.getElementById('modalName');
        const role = document.getElementById('modalRole');
        const button = document.getElementById('startConversationBtn');
    
        // Pour chaque utilisateur du carrousel
        document.querySelectorAll('.user-preview').forEach(el => {
            el.addEventListener('click', () => {
                // Récupération des données de l’utilisateur
                const userAvatar = el.dataset.avatar || '{{ asset('assets/images/PROFIL.png') }}';
                const userName = el.dataset.name + ' ' + el.dataset.surname || 'Utilisateur';
                const userRole = el.dataset.role || 'Inconnu';
                const userId = el.dataset.id;
    
                // Injection dans la modale
                avatar.src = userAvatar;
                name.textContent = userName;
                role.textContent = userRole;
                button.href = `/conversations/start/${userId}`;
    
                // Affichage du modal
                modal.show();
            });
        });
    });
</script>
@endsection

{{-- ==================== MODAL ZOOM PROFIL UTILISATEUR ==================== --}}
<div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content border-0 shadow-lg rounded-4">
        <div class="modal-header bg-primary text-white rounded-top-4">
          <h5 class="modal-title" id="userModalLabel">Informations de l'utilisateur</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
        </div>
        <div class="modal-body text-center py-4">
          <img id="modalAvatar" src="" class="rounded-circle mb-3 border border-3 border-primary shadow" width="120" height="120" style="object-fit: cover;">
          <h5 id="modalName" class="fw-bold mb-1"></h5>
          <p id="modalRole" class="text-muted mb-3"></p>
          <a id="startConversationBtn" href="#" class="btn btn-primary px-4">
              <i class="bi bi-chat-dots"></i> Messages
          </a>
        </div>
      </div>
    </div>
  </div>
