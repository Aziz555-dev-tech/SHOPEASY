@extends('layouts.admin')

@section('title', 'Liste des attributions')

@section('content')
<div class="container mt-1">

    <div class="d-flex justify-content-between mb-3">
        {{-- <a href="{{ route('admin.attributions.create') }}" class="btn btn-primary">Nouvelle attribution</a> --}}

        <div>
            <h4 class="mb-2">Liste des attributions</h4>
        </div>

        <div class="btn-group" role="group" aria-label="Affichage">
            <button type="button" class="btn btn-sm btn-outline-primary active" id="btn-list">Liste</button>
            <button type="button" class="btn btn-sm btn-outline-primary" id="btn-cards">Cards</button>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    {{-- Tableau des attributions --}}
    <div id="view-list" class="table-responsive">
        <table class="table table-bordered table-striped table-hover table-sm align-middle">
            <thead class="table-primary">
                <tr>
                    <th class="identifiant">#Id</th>
                    <th>Bien</th>
                    <th>Propriétaire</th>
                    <th>Client</th>
                    <th>Date de liaison</th>
                    <th>Prix</th>
                    <th>Stock</th>
                    <th>Statut du bien</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($attributions as $attribution)
                    <tr>
                        <td class="identifiant">{{ $attribution->id }}</td>
                        <td>{{ $attribution->bien->titre ?? '—' }}</td>
                        <td>{{ $attribution->bien->proprietaire->name." ".$attribution->bien->proprietaire->surname ?? '—' }}</td>
                        <td>{{ $attribution->client->name." ".$attribution->client->surname ?? '—' }}</td>
                        <td>{{ $attribution->created_at->format('d/m/Y H:i') }}</td>

                        <td>{{ number_format($attribution->prix ?? 0, 0, ",", " ") }} <span class="fw-semibold">FCFA</span></td>
                        <td>{{ $attribution->bien->stock }}</td>
                        <td>{{ $attribution->bien->statut }}</td>
                        <td>
                            @if($attribution->status === 'active')
                                <span class="badge bg-success mb-1">Active</span>
                                
                                <!-- Bouton Mettre un terme -->
                                <button class="btn btn-warning btn-sm mb-1" data-bs-toggle="modal" data-bs-target="#terminerModal{{ $attribution->id }}">
                                    Mettre un terme
                                </button>
                            @elseif ($attribution->status === 'à venir')
                                <span class="badge bg-primary mb-1">A venir</span>
                            
                                <!-- Bouton Mettre un terme -->
                                <button class="btn btn-danger btn-sm mb-1" data-bs-toggle="modal" data-bs-target="#annulerModal{{ $attribution->id }}">
                                    Annuler
                                </button>

                            @elseif($attribution->status === 'terminee')
                                <span class="badge bg-secondary mb-1">Terminée</span>
                        
                                <!-- Bouton Relancer -->
                                <button class="btn btn-success btn-sm mb-1" data-bs-toggle="modal" data-bs-target="#relancerModal{{ $attribution->id }}">
                                    Relancer
                                </button>
                            @endif
                        
                            <button class="btn btn-info btn-sm mb-1" data-bs-toggle="modal" data-bs-target="#bienModal{{ $attribution->bien->id }}">
                                👁 Voir
                            </button>

                            {{-- <h6>{{ $attribution->bien->statut }}</h6>
                            <h5>{{ $attribution->status }}</h5> --}}
                        </td>                                               
                    </tr>
                @empty
                    <td colspan="6" class="text-center">
                        <center>
                            <img src="https://cdn-icons-png.flaticon.com/512/4076/4076549.png" alt="Aucune transaction" style="width:200px; height: 200px;">
                            <p>Aucune attribution trouvée.</p>
                        </center>
                    </td>    
                @endforelse
            </tbody>
        </table>
    </div>


    

        <div id="view-cards" class="row g-4" style="display:none;">
            @forelse($attributions as $attribution)
                <div class="col-md-4">
                    <div class="card shadow-sm border-0 h-100 animate__animated animate__fadeInUp">
                        
                        {{-- Galerie réduite du bien attribué --}}
                        <div class="row card-media g-1 p-2">
                            @php
                                $medias = $attribution->bien?->medias ?? collect();
                                $mediasPreview = $medias->take(4);
                            @endphp
        
                            @if($attribution->bien?->image)
                                <img src="{{ asset('storage/'.$attribution->bien->image) }}" 
                                    class="card-img-top rounded-top" 
                                    alt="Image principale du bien">
                            @elseif($mediasPreview->count() > 0)
                                @foreach($mediasPreview as $media)
                                    <div class="col-6">
                                        @if($media->type === 'image')
                                            <img src="{{ asset('storage/' . $media->path) }}" 
                                                class="img-fluid rounded shadow-sm"
                                                style="height:100px; object-fit:cover; width:100%;">
                                        @elseif($media->type === 'video')
                                            <video class="img-fluid rounded shadow-sm" 
                                                style="height:100px; object-fit:cover; width:100%;" muted>
                                                <source src="{{ asset('storage/' . $media->path) }}" type="video/mp4">
                                            </video>
                                        @endif
                                    </div>
                                @endforeach
        
                                {{-- Badge si plus de 4 médias --}}
                                @if($medias->count() > 4)
                                    <div class="col-6 d-flex align-items-center justify-content-center bg-dark text-white rounded" style="height:100px;">
                                        +{{ $medias->count() - 4 }}
                                    </div>
                                @endif
                            @else
                                <img src="https://via.placeholder.com/400x250?text=Aucune+image" 
                                    class="card-img-top rounded-top" 
                                    alt="Aucune image">
                            @endif
                        </div>
        
                        {{-- Contenu de la card --}}
                        <div class="card-body">
                            <h5 class="card-title text-primary">{{ $attribution->bien->titre ?? 'Bien sans titre' }}</h5>
                            <p class="mb-1"><strong>Propriétaire :</strong> {{ $attribution->bien?->proprietaire?->name }} {{ $attribution->bien?->proprietaire?->surname }}</p>
                            <p class="mb-1"><strong>Client :</strong> {{ $attribution->client?->name }} {{ $attribution->client?->surname }}</p>
                            <p class="mb-1"><strong>Date attribution :</strong> {{ $attribution->created_at->format('d/m/Y') }}</p>
                            <p class="mb-1"><strong>Prix :</strong> {{ number_format($attribution->bien->prix ?? 0, 0, ',', ' ') }} <span class="fw-semibold">FCFA</span></p>

                            @if($attribution->status === 'active')
                                <span class="badge bg-success mb-1">Active</span>
                                
                                <!-- Bouton Mettre un terme -->
                                <button class="btn btn-warning btn-sm mb-1" data-bs-toggle="modal" data-bs-target="#terminerModal{{ $attribution->id }}">
                                    Mettre un terme
                                </button>
                            @elseif ($attribution->status === 'à venir')
                                <span class="badge bg-primary mb-1">A venir</span>
                            
                                <!-- Bouton Mettre un terme -->
                                <button class="btn btn-danger btn-sm mb-1" data-bs-toggle="modal" data-bs-target="#annulerModal{{ $attribution->id }}">
                                    Annuler
                                </button>

                            @elseif($attribution->status === 'terminee')
                                <span class="badge bg-secondary mb-1">Terminée</span>
                        
                                <!-- Bouton Relancer -->
                                <button class="btn btn-success btn-sm mb-1" data-bs-toggle="modal" data-bs-target="#relancerModal{{ $attribution->id }}">
                                    Relancer
                                </button>
                            @endif

                            <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#bienModal{{ $attribution->bien->id }}">
                                👁 Voir le bien
                            </button>  
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-muted text-center">Aucune attribution trouvée</p>
            @endforelse
        </div>
        

        
    </div>

{{-- Script pour basculer entre Liste et Cards --}}
<script>
    const btnList = document.getElementById('btn-list');
    const btnCards = document.getElementById('btn-cards');
    const viewList = document.getElementById('view-list');
    const viewCards = document.getElementById('view-cards');

    btnList.addEventListener('click', () => {
        viewList.style.display = '';
        viewCards.style.display = 'none';
        btnList.classList.add('active');
        btnCards.classList.remove('active');
    });

    btnCards.addEventListener('click', () => {
        viewList.style.display = 'none';
        viewCards.style.display = '';
        btnCards.classList.add('active');
        btnList.classList.remove('active');
    });
</script>
@endsection


    {{-- Toutes les modals ici, une seule fois --}}
    @foreach($attributions as $attribution)
        @include('admin.attributions.partials.modals', ['attribution' => $attribution])
        @if($attribution->bien)
            @include('admin.biens.partials._show_modal', ['bien' => $attribution->bien])
        @endif

        <!-- Modal Annuler Attribution -->
        <div class="modal fade" id="annulerModal{{ $attribution->id }}" tabindex="-1" aria-labelledby="annulerModalLabel{{ $attribution->id }}" aria-hidden="true">
            <div class="modal-dialog">
            <form action="{{ route('admin.attributions.annuler', $attribution->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="annulerModalLabel{{ $attribution->id }}">Annuler l'attribution</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>Voulez-vous vraiment annuler cette attribution avant son début ?</p>
                        <p><strong>Bien :</strong> {{ $attribution->bien->titre }}</p>
                        <p><strong>Locataire :</strong> {{ $attribution->client->name }} {{ $attribution->client->surname }}</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                        <button type="submit" class="btn btn-danger">Annuler l'attribution</button>
                    </div>
                </div>
            </form>
            </div>
        </div>
    @endforeach


<style>

    table {
        min-width: 1200px;
    }


    thead td {
        padding: 10px;
    }

    tbody tr:nth-child(odd) {
        background-color: #f9f9f9;
    }

    tbody tr:nth-child(even) {
        background-color: #e9ecef;
    }

    tbody tr:hover {
        background-color: rgba(0, 80, 120, 0.6);
        color: #000;
        font-weight: bold;
        cursor: pointer;
    }

    .card-media {
        height: 320px;
        overflow: hidden;
    }

    .card-media {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }


    @media (max-width: 768px) {
    table {
        font-size: 14px;
    }
    thead td, tbody td {
        padding: 6px 8px;
    }

    .identifiant {
        display: none;
    }

    .action {
        flex-direction: column;
       flex: 2;
    }
  }
</style>