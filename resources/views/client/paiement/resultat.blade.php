@extends('layouts.client')

@section('content')
<div class="container py-5 text-center">

    <div style="width:170px; margin:auto;">
        <lottie-player 
            src="{{ asset('assets/animations/' . ($success ? 'success.json' : 'error.json')) }}"
            background="transparent"
            speed="1"
            style="width:170px; height:170px;"
            autoplay>
        </lottie-player>
    </div>

    <h2 class="mt-3 fw-bold {{ $success ? 'text-success' : 'text-danger' }}">
        {{ $success ? 'Succès du paiement' : 'Échec du paiement' }}
    </h2>

    <p class="mt-2">{{ $message }}</p>

    @if(isset($bien))
        <p class="fw-semibold">Bien : {{ $bien->titre ?? 'N/A' }}</p>
    @endif

    <a href="{{ route('client.dashboard') }}" class="btn btn-primary mt-4">
        Retour au tableau de bord
    </a>
</div>
@endsection
