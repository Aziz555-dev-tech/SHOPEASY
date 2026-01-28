<div class="dropdown">
    <button class="btn btn-light position-relative" data-bs-toggle="dropdown">
        🔔
        @php $unread = auth()->user()->unreadNotifications->count(); @endphp
        @if($unread)
            <span class="badge bg-danger position-absolute top-0 start-100 translate-middle">
                {{ $unread }}
            </span>
        @endif
    </button>

    <ul class="dropdown-menu dropdown-menu-end p-2" style="width: 350px; max-height: 400px; overflow-y:auto;">
        @php $notifs = auth()->user()->notifications()->latest()->limit(20)->get(); @endphp

        @forelse($notifs as $notif)
            @php
                // $data peut être déjà un array; sinon essayer de le décoder
                $data = $notif->data;
                if (!is_array($data)) {
                    try {
                        $data = json_decode($notif->data, true) ?: [];
                    } catch (\Throwable $e) {
                        $data = [];
                    }
                }

                // valeurs de secours
                $titre = $data['titre'] ?? ($data['title'] ?? class_basename($notif->type));
                $message = $data['message'] ?? ($data['text'] ?? ($data['body'] ?? 'Nouvelle notification'));
                $lien = $data['lien'] ?? ($data['url'] ?? null);
                $created = $notif->created_at ? $notif->created_at->diffForHumans() : null;
            @endphp

            <li class="mb-2 border-bottom pb-2">
                <div class="fw-bold">{{ $titre }}</div>
                <div class="text-muted small">{{ $message }}</div>
                @if($created)
                    <div class="text-end text-muted small">{{ $created }}</div>
                @endif

                @if($lien)
                    <a href="{{ $lien }}" class="btn btn-sm btn-primary mt-2 w-100">Voir</a>
                @endif
            </li>
        @empty
            <li class="text-center text-muted">Aucune notification</li>
        @endforelse

        {{-- action pour marquer toutes comme lues --}}
        <li class="mt-2">
            <form action="{{ route('notifications.markAllRead') }}" method="POST">
                @csrf
                <button class="btn btn-sm btn-outline-secondary w-100" type="submit">Tout marquer lu</button>
            </form>
        </li>
    </ul>
</div>
