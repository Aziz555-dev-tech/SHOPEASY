
@extends('layouts.proprio')

@section('content')
<div class="container mt-1">
    <h4 class="mb-2">Liste de mes biens sur le marché</h4>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="d-flex justify-content-between mb-3">
        <div>
            <form action="{{ route('proprietaire.biens.create') }}" method="get">
                <button class="btn btn-sm btn-primary">
                    + Ajouter un bien
                </button>
            </form>
        </div>

        @php
            $filtreActif = request('disponibilite');
        @endphp
        <div>
            <form method="GET" class="mb-3">
                <div class="btn-group">
            
                    <a href="{{ route('proprietaire.biens.index') }}"
                       class="btn btn-sm {{ $filtreActif == null ? 'btn-primary' : 'btn-outline-primary' }}">
                        Tous
                    </a>
            
                    <button name="disponibilite" value="disponible"
                        class="btn btn-sm {{ $filtreActif == 'disponible' ? 'btn-success' : 'btn-outline-success' }}">
                        Disponibles
                    </button>
            
                    <button name="disponibilite" value="faible"
                        class="btn btn-sm {{ $filtreActif == 'faible' ? 'btn-warning' : 'btn-outline-warning' }}">
                        Faible stock
                    </button>
            
                    <button name="disponibilite" value="epuise"
                        class="btn btn-sm {{ $filtreActif == 'epuise' ? 'btn-danger' : 'btn-outline-danger' }}">
                        Épuisés
                    </button>
            
                </div>
            </form>
            
        </div>
    
        {{-- Toggle view --}}
        <div class="btn-group btn-sm justify-content-end" role="group">
            <button id="btnList" type="button" class="btn btn-sm btn-outline-primary active">Liste</button>
            <button id="btnCards" type="button" class="btn btn-sm btn-outline-primary">Cards</button>
        </div>
    </div>

    {{-- LISTE --}}
    <div id="view-list">        
        <div class="table-responsive">
            <table class="table table-hover align-middle w-100">
                <thead class="table-primary">
                    <tr>
                        <th class="identifiant">Id</th>
                        <th>Titre</th>
                        <th>Categorie</th>
                        <th>Propriétaire</th>
                        <th>Prix</th>
                        <th>Stock</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($biens as $bien)
                    <tr>
                        <td class="identifiant">{{ $bien->id }}</td>
                        <td>{{ $bien->titre }}</td>
                        <td>{{ $bien->categorie?->name ?? '' }}</td>

                        <td>{{ $bien->proprietaire?->name ?? '—' }} {{ $bien->proprietaire?->surname ?? '' }}</td>

                        <td>{{ number_format($bien->prix, 2, ',', ' ') }} FCFA</td>
                        <td>{{ $bien->stock }}</td>
                        <td class="action">
                            <button class="btn btn-info btn-sm mb-1" title="Voir le bien" data-bs-toggle="modal" data-bs-target="#bienModal{{ $bien->id }}">
                                👁 <span>Voir</span>
                            </button>

                            @if($bien->stock >= 5)
                                <a href="{{ route('proprietaire.biens.edit', $bien->id) }}" class="btn btn-warning btn-sm mb-1" title="Modifier le bien" >
                                    ✏️ <span></span>
                                </a>
                                <form action="{{ route('proprietaire.biens.destroy', $bien->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm mb-1" title="Supprimer le bien" onclick="return confirm('Voulez-vous vraiment supprimer ce bien ?')">🗑 <span></span></button>
                                </form>
                                <span class="badge bg-success p-1">{{ $bien->stock }} disponibles</span>
                            @elseif ($bien->stock >= 1 && $bien->stock <5)                                
                                <a href="{{ route('proprietaire.biens.edit', $bien->id) }}" class="btn btn-warning btn-sm mb-1" title="Modifier le bien" >
                                    ✏️ <span></span>
                                </a>
                                <form action="{{ route('proprietaire.biens.destroy', $bien->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm mb-1" title="Supprimer le bien" onclick="return confirm('Voulez-vous vraiment supprimer ce bien ?')">🗑 <span></span></button>
                                </form>
                                <span class="badge bg-dark text-warning p-1">{{ $bien->stock }} disponible(s)</span>
                            @else
                                <a href="{{ route('proprietaire.biens.edit', $bien->id) }}" class="btn btn-warning btn-sm mb-1" title="Modifier le bien" >
                                    ✏️ <span></span>
                                </a>
                                <form action="{{ route('proprietaire.biens.destroy', $bien->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm mb-1" title="Supprimer le bien" onclick="return confirm('Voulez-vous vraiment supprimer ce bien ?')">🗑 <span></span></button>
                                </form>
                                <span class="badge bg-danger p-1">Épuisé</span>
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
        @foreach($biens as $bien)
            <div class="col-md-3">
                <div class="card shadow-lg h-100 animate__animated animate__fade-up-right">
                    
                    {{-- Galerie réduite dans la card --}}
                    <div class="row card-media g-1 p-2">

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
                        <p class="mb-1"><strong>Propriétaire :</strong> {{ $bien->proprietaire?->name." ".$bien->proprietaire->surname }}</p>
                        <p class="mb-1"><strong>Prix :</strong> {{ number_format($bien->prix, 2, ',', ' ') }} FCFA</p>
                        <button class="btn btn-info btn-sm mb-1" data-bs-toggle="modal" data-bs-target="#bienModal{{ $bien->id }}">
                            👁
                        </button>


                        @if($bien->stock >= 5)
                        <a href="{{ route('proprietaire.biens.edit', $bien->id) }}" class="btn btn-warning btn-sm mb-1" title="Modifier le bien" >
                            ✏️ <span></span>
                        </a>
                        <form action="{{ route('proprietaire.biens.destroy', $bien->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm mb-1" title="Supprimer le bien" onclick="return confirm('Voulez-vous vraiment supprimer ce bien ?')">🗑 <span></span></button>
                        </form>
                        <span class="badge bg-success p-1">{{ $bien->stock }} disponibles</span>
                    @elseif ($bien->stock >= 1 && $bien->stock <5)                                
                        <a href="{{ route('proprietaire.biens.edit', $bien->id) }}" class="btn btn-warning btn-sm mb-1" title="Modifier le bien" >
                            ✏️ <span></span>
                        </a>
                        <form action="{{ route('proprietaire.biens.destroy', $bien->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm mb-1" title="Supprimer le bien" onclick="return confirm('Voulez-vous vraiment supprimer ce bien ?')">🗑 <span></span></button>
                        </form>
                        <span class="badge bg-dark text-warning p-1">{{ $bien->stock }} disponible(s)</span>
                    @else
                        <a href="{{ route('proprietaire.biens.edit', $bien->id) }}" class="btn btn-warning btn-sm mb-1" title="Modifier le bien" >
                            ✏️ <span></span>
                        </a>
                        <form action="{{ route('proprietaire.biens.destroy', $bien->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm mb-1" title="Supprimer le bien" onclick="return confirm('Voulez-vous vraiment supprimer ce bien ?')">🗑 <span></span></button>
                        </form>
                        <span class="badge bg-danger p-1">Épuisé</span>
                    @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>    
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

@foreach($biens as $bien)
    @include('admin.biens.partials._show_modal', ['bien' => $bien])
    @include('admin.biens.partials._edit_modal', ['bien' => $bien, 'proprietaires' => $proprietaires])
@endforeach


<style>
        table {
            min-width: 750px;
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

        .card-media img, .card-media video {
            height: 100%;
            width: 100%;
            object-fit: cover;
        }


        @media (max-width: 768px) {
        table {
            font-size: 14px;
        }
        thead td, tbody td {
            padding: 6px 8px;
        }

        .action button span {
            display: none;
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