@extends('layouts.admin')

@section('title', 'Liste des attributions')

@section('content')
<div class="container mt-1">
    
    <div class="d-flex justify-content-between mb-3">
        <div>
            <h4 class="mb-2">Historique des paiements</h4>
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
                    <th>Attribution n*</th>
                    <th>Client</th>
                    <th>Bien</th>
                    <th>Prix du bien</th>
                    <th>Quantité</th>
                    <th>Commission</th>
                    <th>Montant payé</th>
                    <th>Reférences</th>
                    <th>Mode</th>
                    <th>Statut du paiement</th>
                    <th>Date de paiement</th>
                </tr>
            </thead>
            <tbody>
                @forelse($paiements as $p)
                    <tr>
                        <td class="identifiant">{{ $p->id }}</td>
                        <td>{{ $p->attribution->id }}</td>
                        <td>{{ $p->attribution->client->name ?? '—' }}</td>
                        <td>{{ $p->attribution->bien->titre ?? '—' }}</td>
                        <td>{{ $p->attribution->bien->prix ?? '—' }}</td>
                        <td>{{ $p->attribution->stock }}</td>
                        <td>{{ $p->details['commission'] ?? '--' }} FCFA</td>
                        <td>{{ number_format($p->montant ?? 0, 0, ",", " ") }} <span class="fw-semibold">FCFA</span></td>
                        <td>{{ $p->reference }} </td>
                        <td>{{ $p->mode }} </td>
                        <td><span class="bg-success  text-light rounded rounded-2 p-1">{{ $p->status_paiement }}</span> </td>
                        <td>{{ $p->created_at->format('d/m/Y H:i') }}</td>                                             
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
            @forelse($paiements as $p)
                <div class="col-md-4">
                    <div class="card shadow-sm border-0 h-100 animate__animated animate__fadeInUp">
                        
                        {{-- Galerie réduite du bien attribué --}}
                        <div class="row card-media g-1 p-2">
                            @php
                                $medias = $p->attribution->bien?->medias ?? collect();
                                $mediasPreview = $medias->take(4);
                            @endphp
        
                            @if($p->attribution->bien?->image)
                                <img src="{{ asset('storage/'.$p->attribution->bien->image) }}" 
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
                            <p class="mb-1">Attribution n* : {{ $p->attribution->id }} </p>
                            <p class="mb-1">Client : {{ $p->attribution->client->name ?? '—' }}</p>
                            <p class="mb-1">Prix du bien: {{ number_format($p->attribution->bien->prix ?? 0, 0, ",", " ") }} <span class="fw-semibold">FCFA</span></p>
                            <p class="mb-1">Quantité : {{ $p->attribution->stock }}</p>
                            <p class="mb-1">Commission : {{ $p->details['commission'] ?? '--' }} FCFA</p>
                            <p class="mb-1">Montant payé: {{ number_format($p->montant ?? 0, 0, ",", " ") }} <span class="fw-semibold">FCFA</span></p>
                            <p class="mb-1">Mode : {{ $p->mode }}</p>
                            <p class="mb-1">Statut du paiement :  <span class="bg-success  text-light rounded rounded-2 p-1">{{ $p->status_paiement }}</span> </p>
                            <p class="mb-1">Date de paiement : {{ $p->created_at->format('d/m/Y H:i') }}</p>
                            <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#bienModal{{ $p->attribution->bien->id }}">
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
    @foreach($paiements as $p)
        @include('admin.attributions.partials.modals', ['attribution' => $p->attribution])
        @if($p->attribution->bien)
            @include('admin.biens.partials._show_modal', ['bien' => $p->attribution->bien])
        @endif
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

    .card-media img,
    .card-media video {
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