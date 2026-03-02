

@extends('layouts.app')
<link rel="stylesheet" href="{{ asset('css/styleIndex.css') }}">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

@section('content')
<br><br>
<div class="container py-5 partenaires-section">

    <h2 class="text-center fw-bold mb-5">
        <i class="bi bi-stars text-warning"></i>
        Nos Partenaires
    </h2>

    <div class="row g-4">

        @for($i = 1; $i <= 4; $i++)
        <div class="col-lg-3 col-md-4 col-sm-6">
            <div class="card partenaire-card shadow border-0 text-center p-4">

                <div class="logo-wrapper mx-auto mb-3">
                    <img src="https://picsum.photos/200?random={{$i}}" alt="logo">
                </div>

                <h6 class="fw-bold mb-1 text-light">Partenaire {{$i}}</h6>
                <small class="text-light d-block mb-2">
                    Innovation & Digital
                </small>

                <span class="badge bg-warning text-dark">
                    Partenaire Officiel
                </span>

            </div>
        </div>
        @endfor

    </div>

</div>
<br><br>

@endsection



<style>
    .partenaires-section {
        background: #020617;
        color: white;
        border-radius: 20px;
    }

    .partenaire-card {
        background: #111827;
        border-radius: 20px;
        transition: 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .partenaire-card::before {
        content: "";
        position: absolute;
        inset: 0;
        background: linear-gradient(120deg, transparent, rgba(255,255,255,0.1), transparent);
        opacity: 0;
        transition: 0.3s;
    }

    .partenaire-card:hover::before {
        opacity: 1;
    }

    .partenaire-card:hover {
        transform: translateY(-8px) scale(1.02);
        box-shadow: 0 15px 35px rgba(0,0,0,0.4);
    }

    .logo-wrapper {
        width: 90px;
        height: 90px;
        border-radius: 50%;
        overflow: hidden;
        border: 3px solid #facc15;
        background: white;
    }

    .logo-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .badge {
        font-size: 12px;
        padding: 6px 12px;
    }

</style>