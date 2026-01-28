@extends('layouts.client')

@section('content')

<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h2 class="mb-0">Tableau de bord</h2>
        <div class="small text-muted">Bienvenue {{ $client->name }}</div>
    </div>
</div>

<style>
    .stat-card { transition: 0.2s ease; }
    .stat-card:hover { transform: translateY(-3px); box-shadow: 0 8px 15px rgba(0,0,0,0.1); }
    .icon-circle { width: 50px; height: 50px; border-radius: 50%; display:flex; align-items:center; justify-content:center; }
</style>

<div class="row g-4 mb-4">

    {{-- Biens achetés / loués --}}
    <div class="col-md-6">
        <div class="card border-0 shadow-sm stat-card h-100">
            <div class="card-body text-center">
                <div class="icon-circle bg-primary mb-3">
                    <i class="bi bi-house-door text-white fs-4"></i>
                </div>
                <h5 class="card-title">Mes biens achetés</h5>
                <h3 class="fw-bold">{{ $biensAchetes }}</h3>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card border-0 shadow-sm stat-card h-100">
            <div class="card-body text-center">
                <div class="icon-circle bg-success mb-3">
                    <i class="bi bi-credit-card text-white fs-4"></i>
                </div>
                <h5 class="card-title">Mes vendeurs</h5>
                <h3 class="fw-bold">{{ $nombreVendeurs }}</h3>
            </div>
        </div>
    </div>

</div>

<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-light fw-bold">
        Paiements récents
    </div>
    <div class="card-body">

        @if($paiementsRecents->isEmpty())
            <center class="py-4">
                <img src="https://cdn-icons-png.flaticon.com/512/4076/4076549.png" width="90">
                <p class="text-muted mt-2">Aucun paiement effectué récemment</p>
            </center>
        @else
            <ul class="list-group list-group-flush">
                @foreach($paiementsRecents as $p)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <i class="bi bi-cash-coin text-success"></i>
                            {{ number_format($p->montant, 0, ',', ' ') }} FCFA
                            <span class="text-muted">pour</span>
                            <strong>{{ $p->attribution->bien->titre ?? 'Bien supprimé' }}</strong>
                        </div>

                        <span class="badge bg-primary">{{ $p->created_at->format('d/m/Y') }}</span>
                    </li>
                @endforeach
            </ul>
        @endif

    </div>
</div>

<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-light fw-bold">
        Achats récents
    </div>
    <div class="card-body">

        @if($achatsRecents->isEmpty())
            <center class="py-4">
                <img src="https://cdn-icons-png.flaticon.com/512/4076/4076549.png" width="90">
                <p class="text-muted mt-2">Aucun achat enregistré</p>
            </center>
        @else
            <ul class="list-group list-group-flush">
                @foreach($achatsRecents as $a)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <strong>{{ $a->bien->titre ?? 'Bien supprimé' }}</strong>
                        <span class="badge bg-secondary">
                            {{ $a->created_at->format('d/m/Y') }}
                        </span>
                    </li>
                @endforeach
            </ul>
        @endif

    </div>
</div>

@endsection
