@extends('layouts.client')

@section('title', 'Mes achats')

@section('content')
<div class="container mt-4">
    <h3 class="mb-4 fw-bold" style="color: #005078;">Mes Achats</h3>

    @if($achats->isEmpty())
        <div class="text-center mt-5">
            <img src="https://cdn-icons-png.flaticon.com/512/4076/4076549.png" 
                 alt="Aucun achat" style="width:200px; height:200px;">
            <p class="mt-3 text-muted">Aucun achat pour le moment.</p>
        </div>
    @else
    <div class="container mt-1">
        <h4 class="mb-2">Liste des biens</h4>
    
        <div class="">
            {{-- Toggle view --}}
            <div class="btn-group mb-3 justify-content-end" role="group">
                <button id="btnList" type="button" class="btn btn-outline-primary active">Liste</button>
                <button id="btnCards" type="button" class="btn btn-outline-primary">Cards</button>
            </div>
        </div>
    
        {{-- LISTE --}}
        <div id="view-list">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Bien</th>
                            <th>Montant</th>
                            <th>Date d’achat</th>
                            <th>Mode de paiement</th>
                            <th>Statut</th>
                            <th>Suivie de la livraison</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($achats as $achat)
                            <tr>
                                <td>{{ $achat->id }}</td>
                                <td>{{ $achat->bien->titre ?? '—' }}</td>
                                <td>{{ number_format($achat->prix, 0, ',', ' ') }} FCFA</td>
                                <td>{{ $achat->created_at->format('d/m/Y H:i') }}</td>
                                <td>{{ ucfirst($achat->paiements->first()->mode ?? '') }}</td>
                                <td>
                                    <span class="badge bg-success">Payé</span>  
                                </td>
                                <td>
                                    @if($livraison->livreur)
                                        <a href="{{ route('client.livraisons.tracking', $livraison) }}"
                                            class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-geo-alt"></i> Suivre
                                        </a>
                                    @else
                                        <span class="text-muted">En attente de livreur</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    
    
        {{-- CARDS --}}
        <div id="view-cards" class="row g-4 d-none">
            @foreach($achats as $achat)
            @php
                $bien = $achat->bien;
                $medias = $bien->medias ?? collect();
                $mediasPreview = $medias->take(4);
            @endphp
            
                <div class="col-md-3">
                    <div class="card shadow-lg h-100 animate__animated animate__fade-up-right">
                        
                        {{-- Galerie réduite dans la card --}}
                        <div class="row g-1 p-2">
    
                            @php
                                $medias = $bien->medias ?? collect();
                                // On limite à 4 miniatures dans la card
                                $mediasPreview = $medias->take(4);
                            @endphp
    
                            @if($bien->image)
                                <img src="{{ asset('storage/'.$bien->image) }}" class="card-img-top" alt="Image du bien">
                            @elseif($mediasPreview->count() > 0)
                                @foreach($mediasPreview as $media)
                                    <div class="col-6">
                                        @if($media->type === 'image')
                                            <img src="{{ asset('storage/' . $media->path) }}" 
                                                 class="img-fluid rounded shadow-sm"
                                                 style="height:100px; object-fit:cover; width:100%;">
                                        @elseif($media->type === 'video')
                                            <video class="img-fluid rounded shadow-sm" style="height:100px; object-fit:cover; width:100%;" muted>
                                                <source src="{{ asset('storage/' . $media->path) }}" type="video/mp4">
                                            </video>
                                        @endif
                                    </div>
                                @endforeach
        
                                {{-- Si plus de 4 médias, badge " +X " --}}
                                @if($medias->count() > 4)
                                    <div class="col-6 d-flex align-items-center justify-content-center bg-dark text-white rounded" style="height:100px;">
                                        +{{ $medias->count() - 4 }}
                                    </div>
                                @endif
                            @else
                                {{-- Placeholder si aucun média --}}
                                <img src="https://via.placeholder.com/400x250?text=Aucune+image" 
                                     class="card-img-top rounded-top" 
                                     alt="Image du bien">
                            @endif
                        </div>
        
                        {{-- Contenu de la card --}}
                        <div class="card-body">
                            <h5 class="card-title">{{ $bien->titre }}</h5>
                            <p class="mb-1"><strong>Catégorie: </strong>{{ $bien->categorie?->name }}</p>
                            <p class="mb-1"><strong>Prix :</strong> {{ number_format($bien->prix, 2, ',', ' ') }} FCFA</p>
                            <button class="btn btn-info btn-sm mb-1" data-bs-toggle="modal" data-bs-target="#bienModal{{ $bien->id }}">
                                👁
                            </button>                            
                            <span class="badge bg-success p-1">Payé</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>    
    </div>
    @endif
</div>









<script>
    const btnList = document.getElementById('btnList');
    const btnCards = document.getElementById('btnCards');
    const viewList = document.getElementById('view-list');
    const viewCards = document.getElementById('view-cards');

    btnList.addEventListener('click', () => {
        viewList.classList.remove('d-none');
        viewCards.classList.add('d-none');
        btnList.classList.add('active');
        btnCards.classList.remove('active');
    });

    btnCards.addEventListener('click', () => {
        viewList.classList.add('d-none');
        viewCards.classList.remove('d-none');
        btnCards.classList.add('active');
        btnList.classList.remove('active');
    });
</script>
@endsection







