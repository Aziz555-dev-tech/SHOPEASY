@extends('layouts.proprio')

@section('content')
<h4>Localisation de ma boutique</h4>

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

<form method="POST" action="{{ route('proprietaire.boutique.location.update') }}">
    @csrf
    <div class="d-flex gap-2">
        <div class="w-80">
            <input type="text" name="adresse" id="adresse"
            class="form-control mb-2"
            value="{{ $boutique->adresse }}"
            placeholder="Ex : Marché Dantokpa, Cotonou">
        </div>
 
        <div>
            <button type="button" id="searchAddress" title="Retrouver l'adresse" class="bg-primary btn btn-secondary mb-2">
                <i class="bi bi-geo text-light fw-bold"></i>
            </button>
        </div>
    </div>

    <div id="address-error" class="alert alert-danger d-none">
        L'adresse de votre boutique est introuvable.
        Veuillez entrer le nom d'un lieu connu et proche de votre boutique.
    </div>

    <input type="hidden" name="latitude" id="latitude" value="{{ $boutique->latitude }}">
    <input type="hidden" name="longitude" id="longitude" value="{{ $boutique->longitude }}">

    <div id="map" style="height:400px"></div>

    <button class="btn btn-primary mt-3">Enregistrer</button>

</form>
@endsection


@section('scripts')
    <script src="{{ asset('js/boutique-map.js') }}"></script>
@endsection
