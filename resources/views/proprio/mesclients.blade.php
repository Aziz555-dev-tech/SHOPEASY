@extends('layouts.proprio')

@section('title', 'Mes clients')

@section('content')
<div class="container mt-4">
    <h4 class="mb-4">Mes Clients</h4>

    @if($attributions->isEmpty())
        <div class="empty-state text-center">
            <img src="https://cdn-icons-png.flaticon.com/512/4076/4076549.png" 
                 alt="Aucun client pour le moment" 
                 class="empty-img" 
                 style="width:100px; opacity:0.6;">
            <p>Aucun client pour le moment.</p>
        </div>
    @else
        <div class="row g-4">
            @foreach($attributions as $attribution)
                <div class="col-md-6">
                    <div class="card shadow-sm border-0 h-100 animate__animated animate__fadeInUp">
                        <div class="card-header bg-light d-flex justify-content-between card-primary">
                            <h5 class="card-title fw-bold" style="color: #005078 ">
                                {{ $attribution->client->name }} {{ $attribution->client->surname }}
                            </h5>

                            <h4>
                                @if($attribution->client->profil)
                                    <img src="{{ asset('storage/' . $attribution->client->profil) }}" 
                                        alt="Photo de profil" 
                                        class="rounded-circle border" 
                                        style="width: 50px; height: 50px; object-fit: cover;">
                                @else
                                    <img src="{{ asset('assets/images/default_user.png') }}" 
                                        alt="Photo de profil" 
                                        class="rounded-circle border" 
                                        style="width: 50px; height: 50px; object-fit: cover;">
                                @endif
                            </h4>

                        </div>

                        <div class="card-body">
                            <p class="mb-1"><strong>Bien acheté :</strong> {{ $attribution->bien->titre }}</p>
                            <p class="mb-1"><strong>Dates de location :</strong> 
                                {{ $attribution->date_attribution->format('d/m/Y') }}
                            </p>
                            <p class="mb-1"><strong>Montant payé :</strong> 
                                {{ number_format($attribution->bien->prix ?? 0, 2, ',', ' ') }} FCFA
                            </p>

                            <span class="badge bg-success">Payé</span>

                        
                            {{-- Boutons --}}
                            <div class="mt-3">
                                <a href="{{ route('proprietaire.client.contrat', $attribution->id) }}" 
                                    class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-file-earmark-pdf"></i> Reçu en PDF
                                 </a>

                                <button class="btn btn-outline-info rounded rounded-2 btn-sm flex-fill" data-bs-toggle="modal" data-bs-target="#bienModal{{ $attribution->bien->id }}">
                                    <i class="bi bi-eye-fill"></i> Voir
                                </button>
                            </div>
                        </div>
                        
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<style>
    .card-body {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .card-body:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 18px rgba(0,0,0,0.1);
    }
</style>


@endsection


@foreach($biens as $bien)
    @include('admin.biens.partials._show_modal', ['bien' => $bien])
@endforeach