@extends('layouts.app')

<link rel="stylesheet" href="{{ asset('css/styleIndex.css') }}">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

@section('content')

<section class="py-5">
    <div class="container">

        {{-- HEADER --}}
        <div class="text-center mb-5">
            <h1 class="fw-bold text-dark">
                <i class="bi bi-shop"></i> À propos de ShopEasy
            </h1>
            <p class="text-muted mt-3">
                ShopEasy est une plateforme moderne dédiée à la découverte de produits
                de qualité, à des prix compétitifs, avec une expérience utilisateur fluide
                et sécurisée.
            </p>
        </div>

        {{-- CARTES INFORMATIONS --}}
        <div class="row g-4">

            {{-- Mission --}}
            <div class="col-md-4">
                <div class="card h-100 bg-black border-warning shadow-lg rounded-4 text-center p-4">
                    <i class="bi bi-bullseye text-warning fs-1 mb-3"></i>
                    <h5 class="fw-bold text-warning">Notre Mission</h5>
                    <p class="text-light">
                        Offrir une plateforme fiable où vendeurs et acheteurs se rencontrent
                        facilement pour échanger des produits de qualité en toute confiance.
                    </p>
                </div>
            </div>

            {{-- Vision --}}
            <div class="col-md-4">
                <div class="card h-100 bg-black border-warning shadow-lg rounded-4 text-center p-4">
                    <i class="bi bi-eye-fill text-warning fs-1 mb-3"></i>
                    <h5 class="fw-bold text-warning">Notre Vision</h5>
                    <p class="text-light">
                        Devenir la référence du commerce digital en Afrique en proposant
                        une expérience rapide, élégante et sécurisée pour tous.
                    </p>
                </div>
            </div>

            {{-- Valeurs --}}
            <div class="col-md-4">
                <div class="card h-100 bg-black border-warning shadow-lg rounded-4 text-center p-4">
                    <i class="bi bi-stars text-warning fs-1 mb-3"></i>
                    <h5 class="fw-bold text-warning">Nos Valeurs</h5>
                    <p class="text-light">
                        Transparence, sécurité, rapidité et satisfaction client sont
                        les piliers qui guident chacune de nos décisions.
                    </p>
                </div>
            </div>

        </div>

        {{-- STATISTIQUES --}}
        {{-- <div class="row text-center mt-3 gap-2">
            <div class="col-md-3 bg-dark p-2" style="border-left: 10px #FFC107">
                <h3 class="text-warning fw-bold">+500</h3>
                <p class="text-muted">Produits Disponibles</p>
            </div>
            <div class="col-md-3 bg-dark p-2" style="border-left: 10px #FFC107">
                <h3 class="text-warning fw-bold">+120</h3>
                <p class="text-muted">Boutiques Actives</p>
            </div>
            <div class="col-md-3 bg-dark p-2" style="border-left: 10px #FFC107">
                <h3 class="text-warning fw-bold">+1 000</h3>
                <p class="text-muted">Clients Satisfaits</p>
            </div>
            <div class="col-md-3 bg-dark p-2" style="border-left: 10px #FFC107">
                <h3 class="text-warning fw-bold">24/7</h3>
                <p class="text-muted">Support Disponible</p>
            </div>
        </div> --}}

        {{-- FOOTER TEXTE --}}
        <div class="text-center mt-5">
            <p class="text-muted">
                ShopEasy combine élégance, performance et sécurité pour offrir
                une expérience d’achat unique. Nous croyons qu’un bon design
                inspire confiance et simplifie chaque interaction.
            </p>
        </div>

    </div>
</section>

@endsection
