@extends('layouts.admin')

@section('title', 'Nouvelle attribution')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4"> Nouvelle attribution</h2>

    <form action="{{ route('admin.attributions.store') }}" method="POST">
        @csrf

        {{-- Bien --}}
        <div class="mb-3">
            <label for="bien_id" class="form-label">Bien</label>
            <select name="bien_id" id="bien_id" class="form-control" required>
                <option value="">-- Choisir un bien --</option>
                @foreach($biens as $bien)
                <option 
                    value="{{ $bien->id }}"
                    data-proprietaire="{{ $bien->proprietaire->name }} ({{ $bien->proprietaire->telephone }})"
                    data-proprietaire-id="{{ $bien->proprietaire->id }}"
                    data-prix="{{ $bien->prix }}"
                >
                    {{ $bien->titre }}
                </option>
            
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="prix" class="form-label">Montant à payer</label>
            <input type="number" name="prix" id="prix" class="form-control" readonly required>
        </div>

        {{-- Propriétaire --}}
        <div class="mb-3">
            <label class="form-label">Propriétaire</label>
            <input type="text" id="proprietaire_display" class="form-control" disabled>
            <input type="hidden" name="proprietaire_id" id="proprietaire_id">
        </div>

        {{-- Client --}}
        <div class="mb-3">
            <label for="client_id" class="form-label">Client</label>
            <select name="client_id" id="client_id" class="form-control" required>
                <option value="">-- Choisir un client --</option>
                @foreach($clients as $client)
                    <option value="{{ $client->id }}">
                        {{ $client->name }} {{ $client->surname }} ({{ $client->telephone }})
                    </option>
                @endforeach
            </select>
        </div>        

        <button type="submit" class="btn btn-success">Enregistrer</button>
        <a href="{{ route('admin.attributions.index') }}" class="btn btn-secondary">⬅ Retour</a>
    </form>
</div>

{{-- Script --}}
<script>
    document.addEventListener("DOMContentLoaded", function() {
        let bienSelect = document.getElementById("bien_id");
        let proprietaireDisplay = document.getElementById("proprietaire_display");
        let proprietaireHidden = document.getElementById("proprietaire_id");
        let prix = document.getElementById("prix");

        bienSelect.addEventListener("change", function() {
            let option = bienSelect.options[bienSelect.selectedIndex];

            if (!option.value) {
                proprietaireDisplay.value = "";
                proprietaireHidden.value = "";
                prix.value = "";
                return;
            }

            proprietaireDisplay.value = option.dataset.proprietaire;
            proprietaireHidden.value = option.dataset.proprietaireId;

            prix.value = option.dataset.prix;
        });
    });
</script>
@endsection
